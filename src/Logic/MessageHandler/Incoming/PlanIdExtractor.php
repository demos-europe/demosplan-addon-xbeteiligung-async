<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming;

use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use Exception;
use Psr\Log\LoggerInterface;

class PlanIdExtractor
{
    public function __construct(
        private readonly XBeteiligungIncomingMessageParser $incomingMessageParser,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Extract planId from XML message based on message type.
     */
    public function extractFromMessage(string $messageXml, string $messageType): ?string
    {
        try {
            $messageEnum = XBeteiligungMessageType::tryFrom($messageType);
            $messageCode = $messageEnum?->getMessageCode();

            if (null !== $messageEnum && null !== $messageCode) {
                /** @var KommunalInitiieren0401|KommunalAktualisieren0402|PlanfeststellungInitiieren0201 $xmlObject */
                $xmlObject = $this->incomingMessageParser->getXmlObject($messageXml, $messageCode);
                return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
            }

        } catch (Exception $e) {
            $this->logger->warning('Could not extract planId from message XML', [
                'messageType' => $messageType,
                'error' => $e->getMessage()
            ]);
        }

        return null;
    }
}
