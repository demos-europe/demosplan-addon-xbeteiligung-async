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
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\StatementMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAgsService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegeben0701;
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
    private const XOEV_ORGANISATION_SENDER = 'bdp';    // Beteiligung system (XöV-DvdvOrganisationskategorie)
    private const XOEV_ORGANISATION_RECEIVER = 'bap';  // Cockpit system (Behördenanwendung Planung)

    public function __construct(
        private readonly GlobalConfigInterface $globalConfig,
        private readonly LoggerInterface $logger,
        private readonly ParameterBagInterface $parameterBag,
        private readonly StatementCreator $statementCreator,
        private readonly StatementMessageFactory $statementMessageFactory,
        private readonly XBeteiligungService $xBeteiligungService,
        private readonly XBeteiligungAgsService $agsService,
        private readonly XBeteiligungAuditService $auditService,
    ) {
    }

    /**
     * @throws JsonException
     * @throws ParameterNotFoundException
     */
    public function processMessages(): void
    {
        $routingKey = $this->buildIncomingRoutingKey();
        if ($this->globalConfig->isMessageQueueRoutingDisabled()) {
            $routingKey = '';
        }
        $this->client->addRequest(
            '',
            $this->parameterBag->get(self::RABBIT_MQ_QUEUE_NAME),
            $this->parameterBag->get(self::RABBIT_MQ_REQUEST_ID_GET),
            $routingKey,
            300
        );
        $replies = $this->client->getReplies();
        $result = Json::decodeToArray($replies[$this->parameterBag->get(self::RABBIT_MQ_REQUEST_ID_GET)]);
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

                $this->sendRabbitMq($responseObject->getPayload(), $message['messageTypeCode'], $responseObject->getProcedureId(), 300, $auditRecordId);
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
    protected function sendRabbitMq(string $xmlString, string $messageType, ?string $procedureId = null, int $expiration = 300, ?string $auditRecordId = null): bool
    {
        $routingKey = $this->buildOutgoingRoutingKey($messageType, $procedureId);
        if ($this->globalConfig->isMessageQueueRoutingDisabled()) {
            $routingKey = '';
        }
        $this->logger->info('Send Response to RabbitMQ', [
            'xmlString' => $xmlString,
            'routingKey' => $routingKey
        ]);

        try {
            $this->client->addRequest(
                $xmlString,
                $this->parameterBag->get(self::RABBIT_MQ_QUEUE_NAME),
                $this->parameterBag->get(self::RABBIT_MQ_REQUEST_ID_SEND),
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

        return Json::decodeToMatchingType($replies[$this->parameterBag->get(self::RABBIT_MQ_REQUEST_ID_SEND)]);
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

        $this->sendRabbitMq(
            $xmlString,
            CommonHelpers::CLASS_TO_MESSAGE_TYPE_MAPPING[AllgemeinStellungnahmeNeuabgegeben0701::class]['name'],
            $statementCreated->getPlanId(), // Pass procedure ID for AGS lookup
            300,
            $auditRecord?->getId()
        );

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
     * @throws Exception
     */
    private function buildOutgoingRoutingKey(string $messageType, ?string $procedureId): string
    {
        try {
            // Get project type from configuration and map to routing prefix
            $projectType = $this->getProjectType();

            // Get AGS codes from audit XML for the procedure
            $agsData = null;
            if (null !== $procedureId) {
                $agsData = $this->agsService->getAgsCodesForRouting($procedureId);
            }

            if (null === $agsData) {
                $this->logger->error('Cannot send message: No AGS codes found for procedure', [
                    'procedureId' => $procedureId,
                    'messageType' => $messageType,
                    'reason' => 'Missing AGS codes from audit XML'
                ]);

                throw new InvalidArgumentException(
                    \sprintf('Cannot build routing key: No AGS codes found for procedure %s', $procedureId ?? 'null')
                );
            }

            // Build XBeteiligung routing key format
            // Format: {project_type}.beteiligung.{sender_organisation}.{sender_ags}.{receiver_organisation}.{receiver_ags}.{message_type}
            $routingKey = implode('.', [
                $projectType,
                'beteiligung',
                self::XOEV_ORGANISATION_SENDER,
                $agsData['sender'],
                self::XOEV_ORGANISATION_RECEIVER,
                $agsData['receiver'],
                $messageType
            ]);

            $this->logger->info('Built XBeteiligung outgoing routing key', [
                'routingKey' => $routingKey,
                'procedureId' => $procedureId,
                'projectType' => $projectType,
                'senderOrganisation' => self::XOEV_ORGANISATION_SENDER,
                'receiverOrganisation' => self::XOEV_ORGANISATION_RECEIVER,
                'senderAgs' => $agsData['sender'],
                'receiverAgs' => $agsData['receiver']
            ]);

            return $routingKey;

        } catch (Exception $e) {
            $this->logger->error('Cannot send message: Failed to build dynamic routing key', [
                'procedureId' => $procedureId,
                'messageType' => $messageType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw the exception - do not send message if routing key cannot be built
            throw $e;
        }
    }

    private function buildIncomingRoutingKey(): string
    {
        return '*.cockpit.#';
    }

    private function getProjectType(): string
    {
        $procedureMessageType = $this->parameterBag->get('addon_xbeteiligung_async_procedure_message_type');

        if ('' === $procedureMessageType) {
            throw new InvalidArgumentException('Parameter addon_xbeteiligung_async_procedure_message_type is not configured');
        }

        return $this->mapProcedureTypeToRoutingPrefix($procedureMessageType);
    }

    private function mapProcedureTypeToRoutingPrefix(string $procedureType): string
    {
        return match (strtolower($procedureType)) {
            'kommunal' => 'bau',           // Bauleitplanung
            'raumordnung' => 'rog',        // Raumordnung
            'planfeststellung' => 'pfv',   // Planfeststellung
            default => throw new InvalidArgumentException(
                sprintf('Unknown procedure message type "%s". Valid values: Kommunal, Raumordnung, Planfeststellung', $procedureType)
            )
        };
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
