<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Services;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\IncomingMessageData;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class XBeteiligungMessageProcessor
{
    public function __construct(
        private readonly XBeteiligungConfiguration $config,
        private readonly XBeteiligungService $xBeteiligungService,
        private readonly XBeteiligungAuditService $auditService,
        private readonly XBeteiligungOutgoingRoutingKeyBuilder $outgoingRoutingKeyBuilder,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Process a single incoming message and return response data.
     *
     * @param IncomingMessageData $messageData Message data containing xml content and routing key
     *
     * @return ResponseValue|null Response data for sending back to RabbitMQ
     * @throws SchemaException
     */
    public function processIncomingMessage(IncomingMessageData $messageData): ?ResponseValue
    {
        try {
            $this->logger->debug('Process single message', [$messageData->getBody()]);

            $responseObject = $this->xBeteiligungService->processXmlMessage(
                $messageData->getBody(),
                $this->config->auditEnabled,
                $messageData->getRoutingKey()
            );

            // If no response is needed (e.g., for 711/721 acknowledgments), return null
            if (null === $responseObject) {
                $this->logger->debug('No response required for this message type');
                return null;
            }
            $outgoingRoutingKey = $this->outgoingRoutingKeyBuilder->buildFromIncomingRoutingKey(
                $messageData->getRoutingKey(),
                $responseObject->getMessageStringIdentifier()
            );
            // Audit outgoing response message (OK/NOK)
            if ($this->config->auditEnabled) {
                // Find the original audit record for the incoming 401 message to link the response
                $originalAuditRecord = null;
                if (null !== $responseObject->getProcedureId()) {
                    $originalAuditRecord = $this->auditService->findOriginalIncoming401Message(
                        $responseObject->getProcedureId()
                    );
                }

                $auditRecord = $this->auditService->auditSentMessage(
                    $responseObject->getMessageXml(),
                    $responseObject->getMessageStringIdentifier(),
                    $responseObject->getProcedureId(),
                    $originalAuditRecord?->getPlanId(), // planId from original incoming message
                    $originalAuditRecord?->getId(), // responseToMessageId - link to original audit record,
                    null, // statementId - not applicable for procedure messages
                    $outgoingRoutingKey
                );

                $responseObject->setAuditId($auditRecord->getId());
            }

            return $responseObject;

        } catch (InvalidArgumentException $e) {
            $this->logger->error('Message payload not supported', [$e]);
            throw $e;
        } catch (SchemaException $e) {
            $this->logger->error('Incoming cockpit Message could not be parsed', [$e]);
            throw $e;
        } catch (Exception $e) {
            $this->logger->error(
                'XBeteiligung Plugin - Could not execute
                (new procedure)401/411/421/301/311/321/201/211/221 |
                (delete procedure)409/419/429/309/319/329/209/219/229 |: ',
                [$e, $e->getTraceAsString()]
            );
            throw $e;
        }
    }
}
