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
 * Exception thrown when AGS code cannot be found in a 401 message.
 *
 * This exception is thrown when a 401 message (procedure initiation)
 * lacks the required sender AGS code, preventing customer assignment
 * in multi-mandant scenarios.
 */
class AgsCodeNotFoundException extends RuntimeException
{
    public function __construct(string $messageType = '401', ?\Throwable $previous = null)
    {
        $message = "No sender AGS code found in {$messageType} message";
        parent::__construct($message, 0, $previous);
    }
}
