<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Exeption;

use LogicException;
class ProcedureMessageException extends LogicException
{
    public static function missingParameter(string $string): self
    {
        return new self(sprintf('Missing configuration parameter `%s`', $string));
    }

}
