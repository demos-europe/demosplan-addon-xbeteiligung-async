<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Exeption;

use RuntimeException;

/**
 * Exception thrown when a required audit record cannot be found.
 * 
 * This exception is thrown when AGS code extraction requires an original
 * 401 message audit record but no such record exists for the given procedure.
 */
class AuditRecordNotFoundException extends RuntimeException
{
    public function __construct(string $procedureId, string $messageType = '401', ?\Throwable $previous = null)
    {
        $message = "No {$messageType} audit record found for procedure: {$procedureId}";
        parent::__construct($message, 0, $previous);
    }
}