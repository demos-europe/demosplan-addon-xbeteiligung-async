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
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\RoutingKeyComponents;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Service for building outgoing routing keys based on incoming routing keys
 *
 * Outgoing Template: ${beteiligung_variant}.beteiligung.${dvdv_org}.${ags_code}.${dvdv_org}.${ags_code}.${messageIdentifier}
 * Example: bau.beteiligung.bdp.02.05.00200099.bap.02.05.00200099.kommunal.Initiieren.OK.0411
 */
class XBeteiligungOutgoingRoutingKeyBuilder
{
    private const OUTGOING_DIRECTION = 'beteiligung';

    public function __construct(
        private readonly XBeteiligungConfiguration $config,
        private readonly XBeteiligungRoutingKeyParser $routingKeyParser,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Build outgoing routing key from incoming routing key
     *
     * @param string $incomingRoutingKey The original incoming routing key
     * @param string $outgoingMessageIdentifier The message identifier for the response (e.g., 'kommunal.Initiieren.OK.0411')
     * @return string The outgoing routing key
     * @throws InvalidArgumentException
     */
    public function buildFromIncomingRoutingKey(
        string $incomingRoutingKey,
        string $outgoingMessageIdentifier
    ): string {
        $this->logger->debug('Building outgoing routing key from incoming', [
            'incomingRoutingKey' => $incomingRoutingKey,
            'outgoingMessageIdentifier' => $outgoingMessageIdentifier
        ]);

        // Parse the incoming routing key
        $incomingComponents = $this->routingKeyParser->parseRoutingKey($incomingRoutingKey);

        if (!$incomingComponents->isIncoming()) {
            throw new InvalidArgumentException(
                "Expected incoming routing key (direction=cockpit), got: {$incomingComponents->direction}"
            );
        }

        // Build outgoing routing key by flipping the direction and organizations
        $outgoingKey = $this->buildOutgoingKey(
            $incomingComponents,
            $outgoingMessageIdentifier
        );

        $this->logger->info('Built outgoing routing key', [
            'incomingRoutingKey' => $incomingRoutingKey,
            'outgoingRoutingKey' => $outgoingKey,
            'outgoingMessageIdentifier' => $outgoingMessageIdentifier
        ]);

        return $outgoingKey;
    }

    /**
     * Build the outgoing routing key from parsed incoming components
     */
    private function buildOutgoingKey(
        RoutingKeyComponents $incomingComponents,
        string $outgoingMessageIdentifier
    ): string {
        $beteiligungVariant = $this->config->getProjectTypePrefix();

        // In incoming: cockpit -> demosplan (agsCode1 -> agsCode2)
        // In outgoing: demosplan -> cockpit (flip: agsCode2 -> agsCode1)
        $senderAgs = $incomingComponents->agsCode2;    // demosplan AGS (was receiver)
        $receiverAgs = $incomingComponents->agsCode1;  // cockpit AGS (was sender)

        $senderDvdvOrg = $this->config->xoevAddressPrefixKommunal;  // demosplan DVDV
        $receiverDvdvOrg = $this->config->xoevAddressPrefixCockpit; // cockpit DVDV

        return implode('.', [
            $beteiligungVariant,        // e.g., 'bau'
            self::OUTGOING_DIRECTION,   // direction
            $senderDvdvOrg,            // sender DVDV org
            $senderAgs,                // sender AGS
            $receiverDvdvOrg,          // receiver DVDV org
            $receiverAgs,              // receiver AGS
            $outgoingMessageIdentifier // message identifier
        ]);
    }
}
