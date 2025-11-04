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

use Exception;

class XsdValidationException extends Exception
{
    public function __construct(string $message, array $validationErrors = [])
    {
        $errorDetails = '';
        if (!empty($validationErrors)) {
            $errorDetails = ' Validation errors: ' . implode('; ', array_map(
                fn($error) => "Line {$error->line}: {$error->message}",
                $validationErrors
            ));
        }

        parent::__construct($message . $errorDetails);
    }
}