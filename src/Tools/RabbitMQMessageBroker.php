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
     * @param RpcClient $client
     */
    public function setClient(RpcClient $client): void
    {
        $this->messageTransport->setClient($client);
    }

}
