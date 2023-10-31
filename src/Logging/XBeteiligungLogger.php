<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\Logging;


use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use Psr\Log\LoggerInterface;

class XBeteiligungLogger
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
        $this->logger->debug(
            'XML created for a ' . $procedureState . ' procedure.',
            [
                'procedure' => $procedure,
                'xml'       => $xml,
            ]
        );
    }
}
