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

use RuntimeException;

/**
 * Exception thrown when AGS code extraction from XML fails.
 *
 * This exception is thrown when the XML parsing or AGS code extraction
 * process fails, typically due to malformed XML or missing AGS elements.
 */
class AgsExtractionException extends RuntimeException
{
    public function __construct(string $procedureId, ?\Throwable $previous = null)
    {
        $message = "Failed to extract AGS codes from audit XML for procedure {$procedureId}";
        if ($previous) {
            $message .= ": " . $previous->getMessage();
        }
        parent::__construct($message, 0, $previous);
    }
}
