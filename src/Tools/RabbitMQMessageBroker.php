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

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\Exception\JsonException;
use DemosEurope\DemosplanAddon\Utilities\Json;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\StatementMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class RabbitMQMessageBroker
{
    protected RpcClient $client;
    private const RABBIT_MQ_QUEUE_NAME = 'addon_xbeteiligung_async_rabbitMqQueueName';
    private const RABBIT_MQ_REQUEST_ID_GET = 'addon_xbeteiligung_async_rabbitMqRequestIdGet';
    private const RABBIT_MQ_REQUEST_ID_SEND = 'addon_xbeteiligung_async_rabbitMqRequestIdSend';

    public function __construct(
        private readonly GlobalConfigInterface $globalConfig,
        private readonly LoggerInterface $logger,
        private readonly ParameterBagInterface $parameterBag,
        private readonly StatementCreator $statementCreator,
        private readonly StatementMessageFactory $statementMessageFactory,
        private readonly XBeteiligungService $xBeteiligungService,
        private readonly XBeteiligungAuditService $auditService,
    ) {
    }

    /**
     * @throws JsonException
     * @throws ParameterNotFoundException
     */
    public function processMessages(): void
    {
        $routingKey = $this->globalConfig->getProjectPrefix();
        if ($this->globalConfig->isMessageQueueRoutingDisabled()) {
            $routingKey = '';
        }
        $this->client->addRequest(
            '',
            $this->parameterBag->get(self::RABBIT_MQ_QUEUE_NAME),
            self::RABBIT_MQ_REQUEST_ID_GET,
            $routingKey,
            300
        );
        $replies = $this->client->getReplies();
        $result = Json::decodeToArray($replies[self::RABBIT_MQ_REQUEST_ID_GET]);
        $this->logger->info('Got response from RabbitMQ', [$result]);
        foreach ($result as $message) {
            $this->logger->info('Process message', [$message]);

            // Audit will be handled in XBeteiligungService where parsed objects are available
            $auditEnabled = $this->parameterBag->get(XBeteiligungService::AUDIT_ENABLE_PARAMETER);

            try {
                $responseObject = $this->xBeteiligungService->determineMessageContextAndDelegateAction($message, $auditEnabled);

                // Audit outgoing response message (OK/NOK)
                $auditRecordId = null;
                if ($auditEnabled) {
                    $responsePayload = $responseObject->getPayload();
                    $responseMessageType = $this->determineResponseMessageType($responsePayload);

                    // Find the original audit record for the incoming 401 message to link the response
                    $originalAuditRecord = null;
                    if (null !== $responseObject->getProcedureId()) {
                        $originalAuditRecord = $this->auditService->findOriginalIncoming401Message(
                            $responseObject->getProcedureId()
                        );
                    }

                    $auditRecord = $this->auditService->auditSentMessage(
                        $responsePayload,
                        $responseMessageType,
                        $responseObject->getProcedureId(),
                        $originalAuditRecord?->getPlanId(), // planId from original incoming message
                        $originalAuditRecord?->getId() // responseToMessageId - link to original audit record
                    );
                    $auditRecordId = $auditRecord->getId();
                }

                $this->sendRabbitMq($responseObject->getPayload(), 300, $auditRecordId);
            } catch (InvalidArgumentException $e) {
                $this->logger->error('Message payload not supported', [$e]);
            } catch (SchemaException $e) {
                $this->logger->error('Incoming cockpit Message could not be parsed', [$e]);
            } catch (Exception $e) {
                $this->logger->error(
                    'XBeteiligung Plugin - Could not execute
                    (new procedure)401/411/421/301/311/321/201/211/221 |
                    (delete procedure)409/419/429/309/319/329/209/219/229 |: ', [$e, $e->getTraceAsString()]
                );
            }
        }
    }

    /**
     * @throws AMQPTimeoutException
     * @throws Exception
     */
    protected function sendRabbitMq(string $xmlString, int $expiration = 300, ?string $auditRecordId = null): bool
    {
        $routingKey = $this->globalConfig->getProjectPrefix();
        if ($this->globalConfig->isMessageQueueRoutingDisabled()) {
            $routingKey = '';
        }
        $this->logger->info('Send Response to RabbitMQ', [$xmlString]);

        try {
            $this->client->addRequest(
                $xmlString,
                $this->parameterBag->get(self::RABBIT_MQ_QUEUE_NAME),
                self::RABBIT_MQ_REQUEST_ID_SEND,
                $routingKey,
                $expiration
            );
            $replies = $this->client->getReplies();

            // Mark as sent after successful RabbitMQ communication
            if (null !== $auditRecordId) {
                $this->auditService->markAsSent($auditRecordId);
            }

            $this->logger->info('Replies from RabbitMQ', [$replies]);
        } catch (\Exception $e) {
            // Mark as failed only if RabbitMQ send failed (before markAsSent was called)
            if (null !== $auditRecordId) {
                $this->auditService->markAsFailed($auditRecordId, $e->getMessage());
            }
            throw $e;
        }

        return Json::decodeToMatchingType($replies[self::RABBIT_MQ_REQUEST_ID_SEND]);
    }

    /**
     * @throws Exception
     */
    public function handleStatementCreatedEvent(StatementCreatedEventInterface $event): ?StatementCreatedEventInterface
    {
        $statementCreated = $this->statementCreator->getStatementCreatedFromEvent($event);
        if ($statementCreated->getPlanId() === null) {
            $this->logger->error('StatementCreatedEvent has no planId', [$statementCreated]);
            return null;
        }
        // this technically returns a response which is currently unused

        $xmlString = $this->statementMessageFactory->createBeteiligung2PlanungStellungnahmeNeu0701($statementCreated);
        $this->logger->info('Send StatementCreated to RabbitMQ', [$xmlString]);

        // Audit statement message (701) with procedure context
        $auditRecord = null;
        if ($this->parameterBag->get(XBeteiligungService::AUDIT_ENABLE_PARAMETER)) {
            $auditRecord = $this->auditService->auditSentMessage(
                $xmlString,
                XBeteiligungService::NEW_STATEMENT_MESSAGE_IDENTIFIER, // Statement message type
                $statementCreated->getProcedureId(),
                $statementCreated->getPlanId(),
                null, // responseToMessageId
                $statementCreated->getPublicId() // statementId
            );
        }

        $this->sendRabbitMq($xmlString, 300, $auditRecord?->getId());

        return $event;
    }

    /**
     * @param RpcClient $client
     */
    public function setClient(RpcClient $client): void
    {
        $this->client = $client;
    }

    /**
     * Determine message type from XML response content
     */
    private function determineResponseMessageType(string $xmlContent): string
    {
        // Check for OK responses
        if (str_contains($xmlContent, XBeteiligungService::NEW_KOMMUNAL_OK_MESSAGE_IDENTIFIER)) {
            return XBeteiligungService::NEW_KOMMUNAL_OK_MESSAGE_IDENTIFIER;
        }
        if (str_contains($xmlContent, XBeteiligungService::NEW_KOMMUNAL_NOK_MESSAGE_IDENTIFIER)) {
            return XBeteiligungService::NEW_KOMMUNAL_NOK_MESSAGE_IDENTIFIER;
        }

        // Default fallback
        return XBeteiligungService::UNKNOWN_RESPONSE_MESSAGE_TYPE;
    }
}
