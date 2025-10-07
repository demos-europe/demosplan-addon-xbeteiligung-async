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

use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\RoutingKeyComponents;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

/**
 * Service for parsing XBeteiligung routing keys
 *
 * Routing Key Format:
 * ${mandant}.{direction}.${dvdv_org}.${ags_code}.*.${dvdv_org}.${ags_code}.${messageIdentifier}
 *
 * Examples:
 * - nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401
 * - bau.beteiligung.bdp.02.05.00200099.bap.02.05.00200099.kommunal.Initiieren.OK.0411
 */
class XBeteiligungRoutingKeyParser
{
    private const MIN_ROUTING_KEY_PARTS = 7; // Minimum parts required (without wildcard)
    private const AGS_PARTS_COUNT = 3;       // AGS has 3 parts (e.g., 02.05.00200099)
    private const FEDERAL_STATE_POSITION = 1; // Position of federal state in AGS parts array

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Parse a routing key into its components
     *
     * @throws InvalidArgumentException If routing key format is invalid
     */
    public function parseRoutingKey(string $routingKey): RoutingKeyComponents
    {
        $this->logger->debug('Parsing routing key', ['routingKey' => $routingKey]);

        $parts = explode('.', $routingKey);

        $amountOfParts = count($parts);
        if ($amountOfParts < self::MIN_ROUTING_KEY_PARTS) {
            throw new InvalidArgumentException(
                'Invalid routing key format. Expected at least '. self::MIN_ROUTING_KEY_PARTS .' parts, got '. $amountOfParts . ": {$routingKey}"
            );
        }


        // Extract fixed positions
        $mandant = $parts[0];
        $direction = $parts[1];
        $dvdvOrg1 = $parts[2];

        // Extract first AGS (positions 3-5: 02.05.00200099)
        $agsCode1 = implode('.', array_slice($parts, 3, self::AGS_PARTS_COUNT));

        // Extract second DVDV org (position 6)
        $dvdvOrg2 = $parts[6];

        // Extract second AGS (positions 7-9: 02.05.00200099)
        $agsCode2 = implode('.', array_slice($parts, 7, self::AGS_PARTS_COUNT));

        // Extract message identifier (remaining parts from position 10 onwards)
        $messageIdentifier = implode('.', array_slice($parts, 10));

        $this->validateDirection($direction, $routingKey);
        $this->validateAgsCode($agsCode1, 'agsCode1', $routingKey);
        $this->validateAgsCode($agsCode2, 'agsCode2', $routingKey);

        $components = new RoutingKeyComponents(
            mandant: $mandant,
            direction: $direction,
            dvdvOrg1: $dvdvOrg1,
            agsCode1: $agsCode1,
            dvdvOrg2: $dvdvOrg2,
            agsCode2: $agsCode2,
            messageIdentifier: $messageIdentifier
        );

        $this->logger->debug('Successfully parsed routing key', [
            'routingKey' => $routingKey,
            'mandant' => $mandant,
            'direction' => $direction,
            'senderAgs' => $components->getSenderAgs(),
            'receiverAgs' => $components->getReceiverAgs(),
            'messageIdentifier' => $messageIdentifier
        ]);

        return $components;
    }

    /**
     * Get sender AGS code for customer mapping
     */
    public function getReceiverAgsFromRoutingKey(string $routingKey): string
    {
        return $this->parseRoutingKey($routingKey)->getReceiverAgs();
    }

    /**
     * Extract federal state code from AGS in routing key for customer mapping
     *
     * @param string $routingKey The routing key
     * @return string Federal state code (2 digits)
     */
    public function extractFederalStateCodeFromRoutingKey(string $routingKey): string
    {
        $senderAgs = $this->getReceiverAgsFromRoutingKey($routingKey);

        // AGS format: "02.05.00200099" -> split and get second element
        $agsParts = explode('.', $senderAgs);

        if (count($agsParts) < 2) {
            throw new InvalidArgumentException(
                "Invalid AGS format in routing key. Expected at least 2 parts, got: {$senderAgs}"
            );
        }

        $federalStateCode = $agsParts[self::FEDERAL_STATE_POSITION];

        $this->logger->debug('Extracted federal state code from routing key', [
            'routingKey' => $routingKey,
            'senderAgs' => $senderAgs,
            'federalStateCode' => $federalStateCode
        ]);

        return $federalStateCode;
    }

    /**
     * Validate direction field
     */
    private function validateDirection(string $direction, string $routingKey): void
    {
        if (!in_array($direction, ['cockpit', 'beteiligung'], true)) {
            throw new InvalidArgumentException(
                "Invalid direction '{$direction}'. Must be 'cockpit' or 'beteiligung': {$routingKey}"
            );
        }
    }

    /**
     * Validate AGS code format (should have 3 parts when split by '.')
     */
    private function validateAgsCode(string $agsCode, string $fieldName, string $routingKey): void
    {
        $agsParts = explode('.', $agsCode);

        if (count($agsParts) !== self::AGS_PARTS_COUNT) {
            throw new InvalidArgumentException(
                "Invalid {$fieldName} format '{$agsCode}'. Expected 3 parts separated by '.': {$routingKey}"
            );
        }

        // Validate that each part is numeric
        foreach ($agsParts as $part) {
            if (!ctype_digit($part)) {
                throw new InvalidArgumentException(
                    "Invalid {$fieldName} format '{$agsCode}'. All parts must be numeric: {$routingKey}"
                );
            }
        }
    }
}
