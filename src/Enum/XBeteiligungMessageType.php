<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Enum;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenNOK0721;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenOK0711;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\GenericProcedureMessageHandler;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\GenericStatementResponseHandler;
use InvalidArgumentException;

/**
 * XBeteiligung message type identifiers.
 *
 * This enum contains all supported message types for the XBeteiligung standard.
 *
 * Message type format: {domain}.{action}.{version}
 * - domain: kommunal, raumordnung, planfeststellung, allgemein
 * - action: Initiieren, Aktualisieren, Loeschen, stellungnahme
 * - version: 4-digit version number (e.g. 0401, 0302)
 */
enum XBeteiligungMessageType: string
{
    case KOMMUNAL_INITIIEREN = 'kommunal.Initiieren.0401';
    case KOMMUNAL_AKTUALISIEREN = 'kommunal.Aktualisieren.0402';
    case KOMMUNAL_LOESCHEN = 'kommunal.Loeschen.0409';
    case KOMMUNAL_INITIIEREN_OK = 'kommunal.Initiieren.OK.0411';
    case KOMMUNAL_INITIIEREN_NOK = 'kommunal.Initiieren.NOK.0421';
    case RAUMORDNUNG_INITIIEREN = 'raumordnung.Initiieren.0301';
    case RAUMORDNUNG_AKTUALISIEREN = 'raumordnung.Aktualisieren.0302';
    case RAUMORDNUNG_LOESCHEN = 'raumordnung.Loeschen.0309';
    case PLANFESTSTELLUNG_INITIIEREN = 'planfeststellung.Initiieren.0201';
    case PLANFESTSTELLUNG_AKTUALISIEREN = 'planfeststellung.Aktualisieren.0202';
    case PLANFESTSTELLUNG_LOESCHEN = 'planfeststellung.Loeschen.0209';
    case PLANFESTSTELLUNG_INITIIEREN_OK = 'planfeststellung.Initiieren.OK.0211';
    case PLANFESTSTELLUNG_INITIIEREN_NOK = 'planfeststellung.Initiieren.NOK.0221';
    case STELLUNGNAHME_NEUABGEGEBEN = 'allgemein.stellungnahme.Neuabgegeben.0701';
    case STELLUNGNAHME_OK = 'allgemein.stellungnahme.Neuabgegeben.OK.0711';
    case STELLUNGNAHME_NOK = 'allgemein.stellungnahme.Neuabgegeben.NOK.0721';
    case UNKNOWN = 'unknown';
    case UNKNOWN_RESPONSE = 'unknown.response';

    /**
     * Determine message type from XML content.
     *
     * Scans the XML content for known message type identifiers and returns
     * the matching enum case's value as string.
     *
     * @param string $xmlContent XML message content to analyze
     * @return string The detected message type string or 'unknown' if no match found
     */
    public static function fromXmlContent(string $xmlContent): string
    {
        foreach (self::cases() as $messageType) {
            if (str_contains($xmlContent, $messageType->value)) {
                return $messageType->value;
            }
        }

        return self::UNKNOWN->value;
    }

    /**
     * Get SOAP class for message type deserialization.
     *
     * @return string SOAP schema class name for XML deserialization
     */
    public function getSoapClass(): string
    {
        return match ($this) {
            self::KOMMUNAL_INITIIEREN => KommunalInitiieren0401::class,
            self::KOMMUNAL_AKTUALISIEREN => KommunalAktualisieren0402::class,
            self::KOMMUNAL_LOESCHEN => KommunalLoeschen0409::class,
            self::RAUMORDNUNG_INITIIEREN => RaumordnungInitiieren0301::class,
            self::RAUMORDNUNG_AKTUALISIEREN => RaumordnungAktualisieren0302::class,
            self::RAUMORDNUNG_LOESCHEN => RaumordnungLoeschen0309::class,
            self::PLANFESTSTELLUNG_INITIIEREN => PlanfeststellungInitiieren0201::class,
            self::PLANFESTSTELLUNG_AKTUALISIEREN => PlanfeststellungAktualisieren0202::class,
            self::PLANFESTSTELLUNG_LOESCHEN => PlanfeststellungLoeschen0209::class,
            self::STELLUNGNAHME_OK => AllgemeinStellungnahmeNeuabgegebenOK0711::class,
            self::STELLUNGNAHME_NOK => AllgemeinStellungnahmeNeuabgegebenNOK0721::class,
            default => throw new InvalidArgumentException('No SOAP class defined for message type: ' . $this->value),
        };
    }

    /**
     * Create message type from short message code.
     *
     * Maps short codes (401, 402, etc.) used in message parsing to enum cases.
     *
     * @param string $code Short message code (e.g., '401', '402')
     * @return self|null Enum case or null if code not found
     */
    public static function fromCode(string $code): ?self
    {
        return match ($code) {
            '401' => self::KOMMUNAL_INITIIEREN,
            '402' => self::KOMMUNAL_AKTUALISIEREN,
            '409' => self::KOMMUNAL_LOESCHEN,
            '301' => self::RAUMORDNUNG_INITIIEREN,
            '302' => self::RAUMORDNUNG_AKTUALISIEREN,
            '309' => self::RAUMORDNUNG_LOESCHEN,
            '201', '0201' => self::PLANFESTSTELLUNG_INITIIEREN, // Support both formats
            '202' => self::PLANFESTSTELLUNG_AKTUALISIEREN,
            '209' => self::PLANFESTSTELLUNG_LOESCHEN,
            '711' => self::STELLUNGNAHME_OK,
            '721' => self::STELLUNGNAHME_NOK,
            default => null,
        };
    }

    /**
     * Get the message code used for XML parsing.
     *
     * @return string|null The message code (e.g., '401', '402') or null if not applicable
     */
    public function getMessageCode(): ?string
    {
        return match ($this) {
            self::KOMMUNAL_INITIIEREN => '401',
            self::KOMMUNAL_AKTUALISIEREN => '402',
            self::KOMMUNAL_LOESCHEN => '409',
            self::RAUMORDNUNG_INITIIEREN => '301',
            self::RAUMORDNUNG_AKTUALISIEREN => '302',
            self::RAUMORDNUNG_LOESCHEN => '309',
            self::PLANFESTSTELLUNG_INITIIEREN => '0201',
            self::PLANFESTSTELLUNG_AKTUALISIEREN => '202',
            self::PLANFESTSTELLUNG_LOESCHEN => '209',
            self::STELLUNGNAHME_OK => '711',
            self::STELLUNGNAHME_NOK => '721',
            default => null,
        };
    }

    /**
     * Get the handler class responsible for processing this message type.
     *
     * @return string The fully qualified class name of the handler
     * @throws InvalidArgumentException If no handler class is defined for this message type
     */
    public function getHandlerClass(): string
    {
        return match ($this) {
            self::KOMMUNAL_INITIIEREN,
            self::KOMMUNAL_AKTUALISIEREN,
            self::PLANFESTSTELLUNG_INITIIEREN => GenericProcedureMessageHandler::class,

            self::STELLUNGNAHME_OK,
            self::STELLUNGNAHME_NOK => GenericStatementResponseHandler::class,

            default => throw new InvalidArgumentException('No handler class defined for message type: ' . $this->value),
        };
    }
}
