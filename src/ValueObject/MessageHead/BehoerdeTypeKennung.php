<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\MessageHead;


class BehoerdeTypeKennung
{
    private string $authorKennung;
    private string $leserKennung;

    public function __construct(string $authorKennung, string $leserKennung)
    {
        $this->authorKennung = $authorKennung;
        $this->leserKennung = $leserKennung;
    }

    public function getAuthorKennung(): string
    {
        return $this->authorKennung;
    }

    public function getLeserKennung(): string
    {
        return $this->leserKennung;
    }
}
