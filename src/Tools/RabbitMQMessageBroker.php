<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tools;

use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\StatementMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageProcessor;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\IncomingMessageData;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageTransport;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungOutgoingRoutingKeyBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegeben0701;
use Exception;
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

class RabbitMQMessageBroker
{
    public function __construct(
        private readonly XBeteiligungConfiguration $config,
        private readonly XBeteiligungMessageProcessor $messageProcessor,
        private readonly XBeteiligungMessageTransport $messageTransport,
        private readonly XBeteiligungOutgoingRoutingKeyBuilder $outgoingRoutingKeyBuilder,
        private readonly LoggerInterface $logger,
        private readonly StatementCreator $statementCreator,
        private readonly StatementMessageFactory $statementMessageFactory,
        private readonly XBeteiligungAuditService $auditService,
    ) {
    }

    /**
     * @throws AMQPTimeoutException
     * @throws Exception
     */
    private function sendResponseToRabbitMq(string $xmlString, string $messageType, ?string $auditRecordId = null, ?string $incomingRoutingKey = null): bool
    {
        try {
            $routingKey = $this->outgoingRoutingKeyBuilder->buildFromIncomingRoutingKey(
                $incomingRoutingKey,
                $messageType
            );

            // Set outgoing routing key in audit record
            if (null !== $auditRecordId) {
                $this->auditService->setOutgoingRoutingKey($auditRecordId, $routingKey);
            }

            $success = $this->messageTransport->publishDirectMessage($xmlString, $routingKey);

            if ($success) {
                // Mark as sent after successful RabbitMQ communication
                if (null !== $auditRecordId) {
                    $this->auditService->markAsSent($auditRecordId);
                }
            } else {
                throw new Exception('Failed to publish message to RabbitMQ');
            }

            return $success;
        } catch (Exception $e) {
            // Mark as failed only if RabbitMQ send failed (before markAsSent was called)
            if (null !== $auditRecordId) {
                $this->auditService->markAsFailed($auditRecordId, $e->getMessage());
            }
            throw $e;
        }
    }

    /**
     * @throws Exception
     */
    public function handleStatementCreatedEvent(StatementCreatedEventInterface $event): ?StatementCreatedEventInterface
    {
        $statementCreated = $this->statementCreator->getStatementCreatedFromEvent($event);
        if (null === $statementCreated->getPlanId()) {
            $this->logger->error('StatementCreatedEvent has no planId', [$statementCreated]);
            return null;
        }

        $xmlString = $this->statementMessageFactory->createBeteiligung2PlanungStellungnahmeNeu0701($statementCreated);
        $this->logger->info('Send StatementCreated to RabbitMQ', [$xmlString]);

        // Audit statement message (701) with procedure context
        $auditRecord = null;
        if ($this->config->auditEnabled) {
            $auditRecord = $this->auditService->auditSentMessage(
                $xmlString,
                XBeteiligungMessageType::STELLUNGNAHME_NEUABGEGEBEN->value, // Statement message type
                $statementCreated->getProcedureId(),
                $statementCreated->getPlanId(),
                null, // responseToMessageId
                $statementCreated->getPublicId() // statementId
            );
        }

        // Get original incoming routing key from audit for routing key-based outgoing message
        $originalAuditRecord = $this->auditService->findOriginalIncoming401Message($statementCreated->getProcedureId());
        $incomingRoutingKey = $originalAuditRecord?->getRoutingKey();

        if (null === $incomingRoutingKey) {
            $this->logger->warning('No original incoming routing key found for procedure', [
                'procedureId' => $statementCreated->getProcedureId()
            ]);

            return $event;
        }

        $this->sendResponseToRabbitMq(
            $xmlString,
            CommonHelpers::CLASS_TO_MESSAGE_TYPE_MAPPING[AllgemeinStellungnahmeNeuabgegeben0701::class]['name'],
            $auditRecord?->getId(),
            $incomingRoutingKey
        );

        return $event;
    }

    /**
     * Process messages directly from a specific queue without request-response pattern
     *
     * @throws Exception
     */
    public function processMessages(string $queueName, ?int $maxMessages = null): void
    {
        $maxMessages ??= $this->config->maxMessagesPerCycle;

        $this->logger->info('Direct queue consumption started', [
            'queue' => $queueName,
            'maxMessages' => $maxMessages
        ]);

        try {
            // Create direct queue consumer
            $consumer = $this->messageTransport->createDirectConsumer($queueName);

            $processedCount = 0;
            $consumer->consume($maxMessages, function(AMQPMessage $message) use (&$processedCount) {
                $this->processMessage($message, $processedCount);
            });
        } catch (Exception $e) {
            $this->logger->error('Queue consumption failed.', [
                'queue' => $queueName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        $this->logger->info('Queue consumption completed.', [
            'queue' => $queueName,
            'processedMessages' => $processedCount
        ]);
    }

    /**
     * Process a single message from the queue
     *
     * @param AMQPMessage $message The message to process
     * @param int $processedCount Reference to the counter of processed messages
     */
    private function processMessage(AMQPMessage $message, int &$processedCount): void
    {
        try {
            $messageData = new IncomingMessageData($message->getBody(), $message->getRoutingKey());
            $responseData = $this->messageProcessor->processIncomingMessage($messageData);

            if (null !== $responseData) {
                // Publish response message to RabbitMQ using new direct publisher
                $this->sendResponseToRabbitMq(
                    $responseData->getMessageXml(),
                    $responseData->getMessageStringIdentifier(),
                    $responseData->getAuditId(),
                    $message->getRoutingKey()
                );
                $this->logger->debug('Response published successfully', [
                    'messageType' => $responseData->getMessageStringIdentifier()
                ]);
            } else {
                $this->logger->debug('No response required - message processed for audit only');
            }
        } catch (Exception $e) {
            $this->logger->error('Failed to process message.', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        $processedCount++;
        $this->logger->info('Message processed successfully.', [
            'routingKey' => $message->getRoutingKey(),
            'processedCount' => $processedCount
        ]);
    }

    /**
     * @param RpcClient $client
     */
    public function setClient(RpcClient $client): void
    {
        $this->messageTransport->setClient($client);
    }
}
