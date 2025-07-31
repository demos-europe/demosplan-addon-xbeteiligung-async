<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use RuntimeException;

/**
 * Service dedicated to AGS (Amtlicher Gemeindeschlüssel) code extraction and management
 * for XBeteiligung routing purposes.
 */
class XBeteiligungAgsService
{
    private const LOG_PREFIX = 'XBeteiligung AGS Service: ';

    public function __construct(
        private readonly XBeteiligungAuditService $auditService,
        private readonly XBeteiligungIncomingMessageParser $incomingMessageParser,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Extract AGS codes from stored audit XML for routing key generation
     *
     * @throws RuntimeException if no audit record found or AGS extraction fails
     */
    public function getAgsCodesForRouting(string $procedureId): array
    {
        $this->logger->info(self::LOG_PREFIX . 'Extracting AGS codes for routing', [
            'procedureId' => $procedureId
        ]);

        // Find the original 401 message audit record
        $auditRecord = $this->auditService->findOriginalIncoming401Message($procedureId);

        if (!$auditRecord) {
            $errorMsg = "No 401 audit record found for procedure: {$procedureId}";
            $this->logger->error(self::LOG_PREFIX . $errorMsg);
            throw new RuntimeException($errorMsg);
        }

        $xmlContent = $auditRecord->getMessageContent();
        if ('' === $xmlContent) {
            $errorMsg = "Empty XML content in audit record for procedure: {$procedureId}";
            $this->logger->error(self::LOG_PREFIX . $errorMsg);
            throw new RuntimeException($errorMsg);
        }

        try {
            $agsCodes = $this->extractAgsFromXmlContent($xmlContent);

            $this->logger->info(self::LOG_PREFIX . 'Successfully extracted AGS codes from audit XML', [
                'procedureId' => $procedureId,
                'senderAgs' => $agsCodes['sender'],
                'receiverAgs' => $agsCodes['receiver']
            ]);

            return $agsCodes;
        } catch (Exception $e) {
            $errorMsg = "Failed to extract AGS codes from audit XML for procedure {$procedureId}: " . $e->getMessage();
            $this->logger->error(self::LOG_PREFIX . $errorMsg);
            throw new RuntimeException($errorMsg, 0, $e);
        }
    }

    /**
     * Extract AGS codes from XML content using the incoming message parser
     *
     * @throws Exception if XML parsing or AGS extraction fails
     */
    public function extractAgsFromXmlContent(string $xmlContent): array
    {
        // Parse XML using the incoming message parser
        /** @var KommunalInitiieren0401 $xmlObject */
        $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '401');

        // Extract AGS codes using local logic
        $agsCodes = $this->extractAgsCodesFromXmlObject($xmlObject);

        // Validate AGS codes using local logic
        $this->validateAgsCodesForRouting($agsCodes);

        return $agsCodes;
    }

    /**
     * Extract AGS codes from XML message object
     */
    public function extractAgsCodesFromXmlObject(NachrichtG2GTypeType $xmlObject): array
    {
        $autorAgs = null;
        $leserAgs = null;

        // Get the message header containing autor and leser
        $messageHead = $xmlObject->getNachrichtenkopfG2g();

        if (null !== $messageHead) {
            // Extract autor AGS code
            $autor = $messageHead->getAutor();
            if (null !== $autor) {
                $autorAgs = $autor->getKennung();
            }

            // Extract leser AGS code
            $leser = $messageHead->getLeser();
            if (null !== $leser) {
                $leserAgs = $leser->getKennung();
            }
        }

        return [
            'sender' => $autorAgs,
            'receiver' => $leserAgs
        ];
    }

    /**
     * Validate extracted AGS codes
     */
    public function validateAgsCodesForRouting(array $agsCodes): void
    {
        if (empty($agsCodes['sender'])) {
            throw new InvalidArgumentException('Missing sender AGS code in XML message');
        }

        if (empty($agsCodes['receiver'])) {
            throw new InvalidArgumentException('Missing receiver AGS code in XML message');
        }
    }
}
