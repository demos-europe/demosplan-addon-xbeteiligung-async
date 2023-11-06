<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\EventListener;


use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ParagraphInterface;
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

        /** @var array<int, ProcedureInterface> $proceduresToUpdate */
        $proceduresToUpdate = $this->getUpdated(ProcedureInterface::class);
        /** @var array<int, ProcedureSettingsInterface> $procedureSettingsToUpdate */
        $procedureSettingsToUpdate = $this->getUpdated(ProcedureSettingsInterface::class);
        /** @var array<int, ElementsInterface> $elementsToUpdate */
        $elementsToUpdate = $this->getUpdated(ElementsInterface::class);
//        /** @var array<int, ParagraphInterface> $paragraphsToUpdate */
//        $paragraphsToDelete = $this->getDeleted(ParagraphInterface::class);
//        /** @var array<int, ParagraphInterface> $paragraphsToInsert */
//        $paragraphsToInsert = $this->getInsertions(ParagraphInterface::class);

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

//        if (0 < count($paragraphsToInsert)) {
//            // when first paragraph then update relevant
//        }
//          if (0 < count($paragraphsToDelete)) {
//              // when procedure has no more paragraphs then update relevant
//          }
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
    private function getInsertions(mixed $type): array
    {
        return array_filter(
            $this->unitOfWork->getScheduledEntityInsertions(),
            static fn ($entity): bool => $entity instanceof $type
        );
    }

    private function getDeleted(mixed $type): array
    {
        return array_filter(
            $this->unitOfWork->getScheduledEntityDeletions(),
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
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage($xml, $procedure->getId());
        $this->xBeteiligungService->saveProcedureMessageOnFlush($procedureMessage);
        $this->xBeteiligungLogger->createDebugMessageForCreatedXML($procedure, $xml, 'soft deleted');
    }

    /**
     * @throws Exception
     */
    private function procedureUpdated(array $changeSet, ProcedureInterface $procedureAfterUpdate): void
    {
        if (RelevantPropertiesForUpdatedProcedure::propertyHasChanged($changeSet)) {
            $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($procedureAfterUpdate);
            $procedureMessage = $this->xBeteiligungService->createProcedureMessage(
                $xml,
                $procedureAfterUpdate->getId()
            );
            $this->xBeteiligungService->saveProcedureMessageOnFlush($procedureMessage);
            $this->xBeteiligungLogger->createDebugMessageForCreatedXML(
                $procedureAfterUpdate,
                $xml,
                'updated'
            );
        }
    }
}
