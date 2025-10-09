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

class MapData extends ValueObject
{
    public function __construct(
        private string $territory,
        private string $bbox,
        private string $mapExtent,
        private string $flaechenabgrenzungsUrl
    ) {
        $this->lock();
    }

    public function getTerritory(): string {
        return $this->territory;
    }

    public function getBbox(): string {
        return $this->bbox;
    }

    public function getMapExtent(): string {
        return $this->mapExtent;
    }

    public function getFlaechenabgrenzungsUrl(): string {
        return $this->flaechenabgrenzungsUrl;
    }
}
