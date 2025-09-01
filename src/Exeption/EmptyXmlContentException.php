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
 * Exception thrown when audit record contains empty XML content.
 *
 * This exception is thrown when an audit record exists but contains
 * no XML message content, preventing AGS code extraction.
 */
class EmptyXmlContentException extends RuntimeException
{
    public function __construct(string $procedureId, ?\Throwable $previous = null)
    {
        $message = "Empty XML content in audit record for procedure: {$procedureId}";
        parent::__construct($message, 0, $previous);
    }
}
