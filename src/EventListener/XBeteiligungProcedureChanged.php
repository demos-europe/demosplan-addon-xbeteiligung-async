<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\EventListener;


use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\RelevantPropertiesForUpdatedProcedure;
use DemosEurope\DemosplanAddon\XBeteiligung\Logging\XBeteiligungLogger;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Exception;

#[AsDoctrineListener(Events::onFlush)]
class XBeteiligungProcedureChanged
{
    private UnitOfWork $unitOfWork;

    public function __construct(
        private readonly XBeteiligungService $xBeteiligungService,
        private readonly XBeteiligungLogger $xBeteiligungLogger
    )
    {
    }

    /**
     * @throws Exception
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $this->unitOfWork = $eventArgs->getObjectManager()->getUnitOfWork();

        // we have to check if the procedure is a master template or not

        /** @var array<int, ProcedureInterface> $proceduresToUpdate */
        $proceduresToUpdate = $this->getUpdated(ProcedureInterface::class);
        /** @var array<int, ProcedureSettingsInterface> $procedureSettingsToUpdate */
        $procedureSettingsToUpdate = $this->getUpdated(ProcedureSettingsInterface::class);
        /** @var array<int, ElementsInterface> $elementsToUpdate */
        $elementsToUpdate = $this->getUpdated(ElementsInterface::class);

        foreach ($procedureSettingsToUpdate as $procedureSettings) {
            if ($procedureSettings->getProcedure()->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedureSettings);
            $this->procedureChanged($procedureSettings->getProcedure(), $changeSet);
        }

        foreach ($proceduresToUpdate as $procedure) {
            if ($procedure->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedure);
            $this->procedureChanged($procedure, $changeSet);
        }

        foreach ($elementsToUpdate as $elements) {
            if (!$elements->getEnabled() && $elements->getProcedure()->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($elements);
            $this->procedureChanged($elements->getProcedure(), $changeSet);
        }
    }

    /**
     * @return array<int, mixed>
     */
    private function getUpdated(mixed $type): array
    {
        return array_filter(
            $this->unitOfWork->getScheduledEntityUpdates(),
            static fn ($entity): bool => $entity instanceof $type
        );
    }

    /**
     * @throws Exception
     */
    public function procedureChanged(ProcedureInterface $procedure, array $changeSet): void
    {
        $procedure->getDeleted()
            ? $this->procedureDeleted($procedure)
            : $this->procedureUpdated($changeSet, $procedure);
    }

    /**
     * @throws Exception
     */
    private function procedureDeleted(ProcedureInterface $procedure): void
    {
        $xml = $this->xBeteiligungService->createProcedureDeleted409FromObject($procedure->getId());
        $this->xBeteiligungService->saveProcedureMessage($xml, $procedure->getId());
        $this->xBeteiligungLogger->createDebugMessageForCreatedXML($procedure, $xml, 'soft deleted');
    }

    /**
     * @throws Exception
     */
    private function procedureUpdated(array $changeSet, ProcedureInterface $procedureAfterUpdate): void
    {
        if (RelevantPropertiesForUpdatedProcedure::propertyHasChanged($changeSet)) {
            $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($procedureAfterUpdate);
            $this->xBeteiligungService->saveProcedureMessage($xml, $procedureAfterUpdate->getId());
            $this->xBeteiligungLogger->createDebugMessageForCreatedXML(
                $procedureAfterUpdate,
                $xml,
                'updated'
            );
        }
    }
}
