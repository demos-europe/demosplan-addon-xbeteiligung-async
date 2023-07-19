<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Exeption;

use LogicException;
class ProcedureMessageException extends LogicException
{
    public static function missingParameter(string $string): self
    {
        return new self(sprintf('Missing configuration parameter `%s`', $string));
    }

}
