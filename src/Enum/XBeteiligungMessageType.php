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
    case KOMMUNAL_OK = 'kommunal.Initiieren.OK.0411';
    case KOMMUNAL_NOK = 'kommunal.Initiieren.NOK.0421';
    case RAUMORDNUNG_INITIIEREN = 'raumordnung.Initiieren.0301';
    case RAUMORDNUNG_AKTUALISIEREN = 'raumordnung.Aktualisieren.0302';
    case RAUMORDNUNG_LOESCHEN = 'raumordnung.Loeschen.0309';
    case PLANFESTSTELLUNG_INITIIEREN = 'planfeststellung.Initiieren.0201';
    case PLANFESTSTELLUNG_AKTUALISIEREN = 'planfeststellung.Aktualisieren.0202';
    case PLANFESTSTELLUNG_LOESCHEN = 'planfeststellung.Loeschen.0209';
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
}
