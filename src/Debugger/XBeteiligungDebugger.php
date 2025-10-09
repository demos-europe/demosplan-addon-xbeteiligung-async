<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Debugger;


use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use Psr\Log\LoggerInterface;

class XBeteiligungDebugger
{
    public function __construct(
        private readonly LoggerInterface $logger,
    )
    {
    }

    public function createDebugMessageForCreatedXML(
        ProcedureInterface $procedure,
        string $xml,
        string $procedureState): void {
        $this->logger->info(
            'XML created for a ' . $procedureState . ' procedure.',
            [
                'procedure' => $procedure,
                'xml'       => $xml,
            ]
        );
    }
}
