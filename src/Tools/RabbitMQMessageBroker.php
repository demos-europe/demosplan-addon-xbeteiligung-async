<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tools;

use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\StatementMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageProcessor;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageTransport;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungRoutingService;
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
        private readonly XBeteiligungRoutingService $routingService,
        private readonly LoggerInterface $logger,
        private readonly StatementCreator $statementCreator,
        private readonly StatementMessageFactory $statementMessageFactory,
        private readonly XBeteiligungAuditService $auditService,
    ) {
    }

    /**
     * @throws Exception
     */
    public function processMessages(): void
    {
        $routingKey = $this->routingService->buildIncomingRoutingKey();

        // Receive messages from RabbitMQ
        $messages = $this->messageTransport->receiveMessages($routingKey);

        // Process messages and get response data
        $responseDataArray = $this->messageProcessor->processIncomingMessages($messages);

        // Send responses back to RabbitMQ
        foreach ($responseDataArray as $responseData) {
            $this->sendResponseToRabbitMq(
                $responseData['payload'],
                $responseData['messageTypeCode'],
                $responseData['procedureId'],
                $responseData['auditRecordId']
            );
        }
    }

    /**
     * @throws AMQPTimeoutException
     * @throws Exception
     */
    private function sendResponseToRabbitMq(string $xmlString, string $messageType, ?string $procedureId = null, ?string $auditRecordId = null): mixed
    {
        try {
            $routingKey = $this->routingService->buildOutgoingRoutingKey($messageType, $procedureId);
            $result = $this->messageTransport->sendMessage($xmlString, $routingKey);

            // Mark as sent after successful RabbitMQ communication
            if (null !== $auditRecordId) {
                $this->auditService->markAsSent($auditRecordId);
            }

            return $result;
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
                XBeteiligungService::NEW_STATEMENT_MESSAGE_IDENTIFIER, // Statement message type
                $statementCreated->getProcedureId(),
                $statementCreated->getPlanId(),
                null, // responseToMessageId
                $statementCreated->getPublicId() // statementId
            );
        }

        $this->sendResponseToRabbitMq(
            $xmlString,
            CommonHelpers::CLASS_TO_MESSAGE_TYPE_MAPPING[AllgemeinStellungnahmeNeuabgegeben0701::class]['name'],
            $statementCreated->getPlanId(), // Pass procedure ID for AGS lookup
            $auditRecord?->getId()
        );

        return $event;
    }

    /**
     * Process messages directly from a specific queue without request-response pattern
     */
    public function processQueueMessages(string $queueName, int $maxMessages = null): void
    {
        $maxMessages ??= $this->config->maxMessagesPerCycle;

        try {
            $this->logger->info('Direct queue consumption started', [
                'queue' => $queueName,
                'maxMessages' => $maxMessages
            ]);

            // Create direct queue consumer
            $consumer = $this->messageTransport->createDirectConsumer($queueName);

            $processedCount = 0;
            $consumer->consume($maxMessages, function(AMQPMessage $message) use (&$processedCount, $consumer) {
                try {
                    $this->logger->info('Processing message from queue', [
                        'messageId' => $message->has('message_id') ? $message->get('message_id') : null,
                        'routingKey' => $message->getRoutingKey(),
                        'body' => $message->getBody(),
                    ]);

                    // Process the message using existing logic
                    $responseData = $this->messageProcessor->processIncomingMessage($message->getBody());

                    // Send response back if this was a request that expects a reply
                    if ($message->has('reply_to')) {
                        $consumer->sendDirectReply($message, $responseData);
                    }

                    $processedCount++;
                    $this->logger->info('Message processed successfully', [
                        'messageId' => $message->has('message_id') ? $message->get('message_id') : null,
                        'processedCount' => $processedCount
                    ]);

                    return true; // ACK the message

                } catch (Exception $e) {
                    $this->logger->error('Failed to process queue message', [
                        'error' => $e->getMessage(),
                        'messageId' => $message->has('message_id') ? $message->get('message_id') : null,
                        'trace' => $e->getTraceAsString()
                    ]);

                    return false; // NACK the message
                }
            });

            $this->logger->info('Queue consumption completed', [
                'queue' => $queueName,
                'processedMessages' => $processedCount
            ]);

        } catch (Exception $e) {
            $this->logger->error('Queue consumption failed', [
                'queue' => $queueName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * @param RpcClient $client
     */
    public function setClient(RpcClient $client): void
    {
        $this->messageTransport->setClient($client);
    }

}
