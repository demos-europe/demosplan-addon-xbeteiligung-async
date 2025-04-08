<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\EventListener;


use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\SingleDocumentInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\ProcedurePhaseRepositoryInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\RelevantPropertiesForUpdatedProcedure;
use DemosEurope\DemosplanAddon\XBeteiligung\Debugger\XBeteiligungDebugger;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Exception;

#[AsDoctrineListener(Events::onFlush)]
class XBeteiligungProcedureChanged
{
    private UnitOfWork $unitOfWork;
    private array $updatedProcedures = [];

    public function __construct(
        private readonly PermissionEvaluatorInterface  $permissionEvaluator,
        private readonly XBeteiligungDebugger $xBeteiligungDebugger,
        private readonly XBeteiligungService $xBeteiligungService
    )
    {
    }

    /**
     * @throws Exception
     */
    public function onFlush(OnFlushEventArgs $eventArgs): void
    {
        $this->unitOfWork = $eventArgs->getObjectManager()->getUnitOfWork();

        $this->handleProcedureSettingsUpdates();
        $this->handleProcedureUpdates();
        $this->handleElementsUpdates();
        $this->handleSingleDocumentsInsertions();
        $this->handleSingleDocumentsDeletions();
        $this->handleSingleDocumentsUpdates();
        $this->handleProcedurePhasesUpdates($eventArgs);

        foreach ($this->updatedProcedures as $updatedProcedure) {
            $this->onProcedureChanged($updatedProcedure);
        }

        $this->updatedProcedures = [];
    }

    private function handleProcedureSettingsUpdates(): void
    {
        /** @var ProcedureSettingsInterface[] $procedureSettingsToUpdate */
        $procedureSettingsToUpdate = $this->getUpdated(ProcedureSettingsInterface::class);

        foreach ($procedureSettingsToUpdate as $procedureSettings) {
            if ($procedureSettings->getProcedure()->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedureSettings);
            $this->addUniqueRelevantProcedure($changeSet, $procedureSettings->getProcedure());
        }
    }

    private function handleProcedureUpdates(): void
    {
        /** @var ProcedureInterface[] $proceduresToUpdate */
        $proceduresToUpdate = $this->getUpdated(ProcedureInterface::class);

        foreach ($proceduresToUpdate as $procedure) {
            if ($procedure->getMaster()) {
                continue;
            }
            if ($procedure->getDeleted()) {
                $this->updatedProcedures[$procedure->getId()] = $procedure;
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedure);
            $this->addUniqueRelevantProcedure($changeSet, $procedure);
        }
    }

    private function handleElementsUpdates(): void
    {
        /** @var ElementsInterface[] $elementsToUpdate */
        $elementsToUpdate = $this->getUpdated(ElementsInterface::class);

        foreach ($elementsToUpdate as $elements) {
            if ($elements->getProcedure()->getMaster()) {
                continue;
            }
            if (!$elements->getEnabled()) {
                $changeSet = $this->unitOfWork->getEntityChangeSet($elements);
                if (isset($changeSet['enabled']) && false === $changeSet['enabled'][1]) {
                    $this->addUniqueRelevantProcedure($changeSet, $elements->getProcedure());
                }
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($elements);
            $this->addUniqueRelevantProcedure($changeSet, $elements->getProcedure());
        }
    }

    private function handleSingleDocumentsInsertions(): void
    {
        /** @var SingleDocumentInterface[] $singleDocumentsToInsert */
        $singleDocumentsToInsert = $this->getInsertions(SingleDocumentInterface::class);

        foreach ($singleDocumentsToInsert as $singleDocument) {
            $procedure = $singleDocument->getProcedure();
            if ($procedure->getMaster() || !$singleDocument->getElement()->getEnabled()) {
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()
                ->addNewSingleDocument($procedure->getId(), $singleDocument);
            $this->addUniqueRelevantProcedure(['new_single_document' => ''], $singleDocument->getProcedure());
        }
    }

    private function handleSingleDocumentsDeletions(): void
    {
        /** @var SingleDocumentInterface[] $singleDocumentsToDelete */
        $singleDocumentsToDelete = $this->getDeleted(SingleDocumentInterface::class);

        foreach ($singleDocumentsToDelete as $singleDocument) {
            if (!$singleDocument->getElement()->getEnabled() || $singleDocument->getProcedure()->getMaster()) {
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()
                ->addDeletedSingleDocument($singleDocument->getProcedure()->getId(), $singleDocument->getId());
            $this->addUniqueRelevantProcedure(['delete_single_document' => ''], $singleDocument->getProcedure());
        }
    }

    private function handleSingleDocumentsUpdates(): void
    {
        /** @var SingleDocumentInterface[] $singleDocumentsToUpdate */
        $singleDocumentsToUpdate = $this->getUpdated(SingleDocumentInterface::class);

        foreach ($singleDocumentsToUpdate as $singleDocument) {
            if (!$singleDocument->getElement()->getEnabled() || $singleDocument->getProcedure()->getMaster()) {
                continue;
            }
            if (!$singleDocument->getVisible()) {
                $changeSet = $this->unitOfWork->getEntityChangeSet($singleDocument);
                if (isset($changeSet['visible']) && false === $changeSet['visible'][1]) {
                    $this->xBeteiligungService->getPlanningDocumentsLinkCreator()
                        ->addUpdatedSingleDocument($singleDocument->getProcedure()->getId(), $singleDocument);
                    $this->addUniqueRelevantProcedure(['update_single_document' => ''], $singleDocument->getProcedure());
                }
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()
                ->addUpdatedSingleDocument($singleDocument->getProcedure()->getId(), $singleDocument);
            $this->addUniqueRelevantProcedure(['update_single_document' => ''], $singleDocument->getProcedure());
        }
    }

    private function handleProcedurePhasesUpdates(OnFlushEventArgs $eventArgs): void
    {
        /** @var ProcedurePhaseInterface[] $procedurePhasesToUpdate */
        $procedurePhasesToUpdate = $this->getUpdated(ProcedurePhaseInterface::class);

        foreach ($procedurePhasesToUpdate as $procedurePhase) {
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedurePhase);
            /** @var ProcedurePhaseRepositoryInterface $procedurePhaseRepository */
            $procedurePhaseRepository = $eventArgs->getObjectManager()->getRepository(
                ProcedurePhaseInterface::class
            );
            $procedure = $procedurePhaseRepository->getProcedureByInstitutionPhaseId($procedurePhase->getId());
            if (null === $procedure) {
                $procedure = $procedurePhaseRepository->getProcedureByPublicParticipationPhaseId(
                    $procedurePhase->getId()
                );
            }
            if (null !== $procedure && !$procedure->getMaster()) {
                $this->addUniqueRelevantProcedure($changeSet, $procedure);
            }
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
    public function onProcedureChanged(ProcedureInterface $procedure): void
    {
        $procedure->getDeleted()
            ? $this->onProcedureSoftDeleted($procedure)
            : $this->onProcedureUpdated($procedure);
    }

    /** The procedure still exists, only a delte flag is set.
     * @throws Exception
     */
    private function onProcedureSoftDeleted(ProcedureInterface $procedure): void
    {
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_delete())) {
            $xml = $this->xBeteiligungService->createProcedureDeleted409FromObject($procedure->getId());
            $this->createProcedureDeleteMessage($xml, $procedure, KommunalLoeschen0409::class);
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_delete())) {
            $xml = $this->xBeteiligungService->createXMLFor309($procedure->getId());
            $this->createProcedureDeleteMessage($xml, $procedure, RaumordnungLoeschen0309::class);
        }
    }

    /**
     * @throws Exception
     */
    private function onProcedureUpdated(ProcedureInterface $procedureAfterUpdate): void
    {
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_update())) {
            $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($procedureAfterUpdate);
            $this->createProcedureUpdatedMessage(
                $xml,
                $procedureAfterUpdate,
                KommunalAktualisieren0402::class
            );
        }
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_update())) {
            $xml = $this->xBeteiligungService->createXMLFor302($procedureAfterUpdate);
            $this->createProcedureUpdatedMessage(
                $xml,
                $procedureAfterUpdate,
                RaumordnungAktualisieren0302::class
            );
        }
    }

    private function createProcedureUpdatedMessage(
        string $xml,
        ProcedureInterface $procedure,
        string $messageClass): void {
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage(
            $xml,
            $procedure->getId(),
            $messageClass
        );
        $this->xBeteiligungService->saveProcedureMessageOnFlush($procedureMessage);
        $this->xBeteiligungDebugger->createDebugMessageForCreatedXML(
            $procedure,
            $xml,
            'updated'
        );
    }

    private function createProcedureDeleteMessage(
        string $xml,
        ProcedureInterface $procedure,
        string $messageClass): void {
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage(
            $xml,
            $procedure->getId(),
            $messageClass
        );
        $this->xBeteiligungService->saveProcedureMessageOnFlush($procedureMessage);
        $this->xBeteiligungDebugger->createDebugMessageForCreatedXML($procedure, $xml, 'soft deleted');
    }

    private function addUniqueRelevantProcedure(array $changeSet, ProcedureInterface $updatedProcedure): void
    {
        if (RelevantPropertiesForUpdatedProcedure::propertyHasChanged($changeSet) &&
            !array_key_exists($updatedProcedure->getId(), $this->updatedProcedures)) {
            $this->updatedProcedures[$updatedProcedure->getId()] = $updatedProcedure;
        }
    }
}
