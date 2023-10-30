<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\EventListener;


use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;

#[AsDoctrineListener(Events::onFlush)]
class XBeteiligungProcedureChanged
{
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $unitOfWork = $eventArgs->getObjectManager()->getUnitOfWork();

        $proceduresToUpdate = array_filter(
            $unitOfWork->getScheduledEntityUpdates(),
            static fn ($entity): bool => $entity instanceof ProcedureInterface
        );

        foreach ($proceduresToUpdate as $procedure) {
            $changeSet = $unitOfWork->getEntityChangeSet($procedure);
        }
    }
}
