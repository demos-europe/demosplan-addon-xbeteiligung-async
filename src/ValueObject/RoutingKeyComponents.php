<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;

/**
 * Parsed components from a routing key
 *
 * Template: ${mandant}.{direction}.${dvdv_org}.${ags_code}.*.${dvdv_org}.${ags_code}.${messageIdentifier}
 * Example:  nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401
 */
class RoutingKeyComponents
{
    public function __construct(
        public string $mandant,           // 'nrw', 'bau', etc.
        public string $direction,         // 'cockpit' or 'beteiligung'
        public string $dvdvOrg1,         // 'bap', 'bdp', etc.
        public string $agsCode1,         // '02.05.00200099'
        public string $dvdvOrg2,         // 'bdp', 'bap', etc.
        public string $agsCode2,         // '02.05.00200099'
        public string $messageIdentifier // 'kommunal.initiieren.0401', 'kommunal.Initiieren.OK.0411', etc.
    ) {
    }

    /**
     * Get sender AGS code based on direction
     */
    public function getSenderAgs(): string
    {
        // For incoming messages (cockpit), sender is agsCode1
        // For outgoing messages (beteiligung), sender is agsCode2
        return $this->direction === 'cockpit' ? $this->agsCode1 : $this->agsCode2;
    }

    /**
     * Get receiver AGS code based on direction
     */
    public function getReceiverAgs(): string
    {
        // For incoming messages (cockpit), receiver is agsCode2
        // For outgoing messages (beteiligung), receiver is agsCode1
        return 'cockpit' === $this->direction ? $this->agsCode2 : $this->agsCode1;
    }

    /**
     * Check if this is an incoming message (from cockpit)
     */
    public function isIncoming(): bool
    {
        return 'cockpit' === $this->direction;
    }
}
