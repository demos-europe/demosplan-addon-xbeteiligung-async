<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Services;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
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
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Process a single incoming message and return response data.
     *
     * @param string $message string containing xml message
     *
     * @return ResponseValue Response data for sending back to RabbitMQ
     * @throws SchemaException
     */
    public function processIncomingMessage(string $message): ?ResponseValue
    {
        try {
            $this->logger->debug('Process single message', [$message]);

            $responseObject = $this->xBeteiligungService->processXmlMessage(
                $message,
                $this->config->auditEnabled
            );

            // If no response is needed (e.g., for 711/721 acknowledgments), return null
            if (null === $responseObject) {
                $this->logger->debug('No response required for this message type');
                return null;
            }

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
                    $originalAuditRecord?->getId() // responseToMessageId - link to original audit record
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
