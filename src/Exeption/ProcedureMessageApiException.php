<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Exeption;

use RuntimeException;
class ProcedureMessageApiException extends RuntimeException
{
    public static function interactionFailed($url): self
    {
        return new self("Interaction with ProcedureMessage failed for endpoint: {$url}");
    }

}
