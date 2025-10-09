<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Exception;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\FehlerType;
use Exception;

class XBeteiligungProcedureException extends Exception
{
    /**
     * @var FehlerType[]
     */
    private array $errorTypes = [];

    /**
     * @param FehlerType[] $errorTypes
     */
    public function __construct(array $errorTypes, string $message = '', int $code = 0, ?Exception $previous = null)
    {
        $this->errorTypes = $errorTypes;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return FehlerType[]
     */
    public function getErrorTypes(): array
    {
        return $this->errorTypes;
    }
}
