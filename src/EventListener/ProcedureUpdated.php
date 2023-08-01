<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\EventListener;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Psr\Log\LoggerInterface;

class ProcedureUpdated
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly XBeteiligungService $xBeteiligungService,
    ) {
    }
    // the entity listener methods receive two arguments:
    // the entity instance and the lifecycle event
    public function postUpdate(ProcedureInterface $procedure, LifecycleEventArgs $event): void
    {
        // ... do something to notify the changes
        $updatedProcedure = $procedure;
        $testGetObject = $event->getObject();
        $testGetObjectManager = $event->getObjectManager();
        $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($updatedProcedure);
        dd($xml);
    }
}
