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
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Exception;

#[AsDoctrineListener(Events::onFlush)]
class XBeteiligungProcedureChanged
{
    private UnitOfWork $unitOfWork;
    private ?string $currentProcedureMessage = null;

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

        /** @var array<int, ProcedureInterface> $proceduresToUpdate */
        $proceduresToUpdate = $this->getUpdated(ProcedureInterface::class);
        /** @var array<int, ProcedureSettingsInterface> $procedureSettingsToUpdate */
        $procedureSettingsToUpdate = $this->getUpdated(ProcedureSettingsInterface::class);
        /** @var array<int, ElementsInterface> $elementsToUpdate */
        $elementsToUpdate = $this->getUpdated(ElementsInterface::class);
        /** @var array<int, SingleDocumentInterface> $singleDocumentsToInsert */
        $singleDocumentsToInsert = $this->getInsertions(SingleDocumentInterface::class);
        /** @var array<int, SingleDocumentInterface> $singleDocumentsToDelete */
        $singleDocumentsToDelete = $this->getDeleted(SingleDocumentInterface::class);
        /** @var array<int, SingleDocumentInterface> $singleDocumentsToUpdate */
        $singleDocumentsToUpdate = $this->getUpdated(SingleDocumentInterface::class);
        /** @var array<int, ProcedurePhaseInterface> $procedurePhasesToUpdate */
        $procedurePhasesToUpdate = $this->getUpdated(ProcedurePhaseInterface::class);


        foreach ($procedureSettingsToUpdate as $procedureSettings) {
            if ($procedureSettings->getProcedure()->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedureSettings);
            $this->onProcedureChanged($procedureSettings->getProcedure(), $changeSet);
        }

        foreach ($proceduresToUpdate as $procedure) {
            if ($procedure->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedure);
            $this->onProcedureChanged($procedure, $changeSet);
        }

        foreach ($elementsToUpdate as $elements) {
            if (!$elements->getEnabled() && !$elements->getProcedure()->getMaster()) {
                $changeSet = $this->unitOfWork->getEntityChangeSet($elements);
                if (isset($changeSet['enabled']) && false === $changeSet['enabled'][1]) {
                    $this->onProcedureChanged($elements->getProcedure(), $changeSet);
                }
                continue;
            }
            if ($elements->getProcedure()->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($elements);
            $this->onProcedureChanged($elements->getProcedure(), $changeSet);
        }

        foreach ($singleDocumentsToInsert as $singleDocument) {
            if (!$singleDocument->getElement()->getEnabled() || $singleDocument->getProcedure()->getMaster()) {
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()->setNewSingleDocument($singleDocument);
            $this->onProcedureChanged($singleDocument->getProcedure(), ['new_single_document' => '']);
        }

        foreach ($singleDocumentsToDelete as $singleDocument) {
            if (!$singleDocument->getElement()->getEnabled() || $singleDocument->getProcedure()->getMaster()) {
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()
                ->addDeletedSingleDocument($singleDocument->getId());
            $this->onProcedureChanged($singleDocument->getProcedure(), ['delete_single_document' => '']);
        }

        foreach ($singleDocumentsToUpdate as $singleDocument) {
            if (!$singleDocument->getElement()->getEnabled() || $singleDocument->getProcedure()->getMaster()) {
                continue;
            }
            if (!$singleDocument->getVisible()) {
                $changeSet = $this->unitOfWork->getEntityChangeSet($singleDocument);
                if (isset($changeSet['visible']) && false === $changeSet['visible'][1]) {
                    $this->xBeteiligungService->getPlanningDocumentsLinkCreator()
                        ->setUpdatedSingleDocument($singleDocument);
                    $this->onProcedureChanged($singleDocument->getProcedure(), ['update_single_document' => '']);
                }
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()->setUpdatedSingleDocument($singleDocument);
            $this->onProcedureChanged($singleDocument->getProcedure(), ['update_single_document' => '']);
        }

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
            if (null === $procedure) {
                continue;
            }

            if (!$procedure->getMaster()) {
                $this->onProcedureChanged($procedure, $changeSet);
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
    public function onProcedureChanged(ProcedureInterface $procedure, array $changeSet): void
    {
        $procedure->getDeleted()
            ? $this->onProcedureSoftDeleted($procedure)
            : $this->onProcedureUpdated($changeSet, $procedure);
    }

    /** The procedure still exists, only a delte flag is set.
     * @throws Exception
     */
    private function onProcedureSoftDeleted(ProcedureInterface $procedure): void
    {
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_delete())) {
            $xml = $this->xBeteiligungService->createProcedureDeleted409FromObject($procedure->getId());
            $this->createProcedureDeleteMessage($xml, $procedure);
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_delete())) {
            $xml = $this->xBeteiligungService->createXMLFor309($procedure->getId());
            $this->createProcedureDeleteMessage($xml, $procedure);
        }
    }

    /**
     * @throws Exception
     */
    private function onProcedureUpdated(array $changeSet, ProcedureInterface $procedureAfterUpdate): void
    {
        if (RelevantPropertiesForUpdatedProcedure::propertyHasChanged($changeSet)
        ) {
            if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_update())) {
                $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($procedureAfterUpdate);
                $this->createProcedureUpdatedMessage($xml, $procedureAfterUpdate);
            }
            if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_update())) {
                $xml = $this->xBeteiligungService->createXMLFor302($procedureAfterUpdate);
                $this->createProcedureUpdatedMessage($xml, $procedureAfterUpdate);
            }
        }
    }

    private function createProcedureUpdatedMessage(string $xml, ProcedureInterface $procedure): void
    {
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage(
            $xml,
            $procedure->getId()
        );
        $this->deleteCurrentProcedureMessageIfExist();
        $this->xBeteiligungService->saveProcedureMessageOnFlush($procedureMessage);
        $this->currentProcedureMessage = $procedureMessage->getId();
        $this->xBeteiligungDebugger->createDebugMessageForCreatedXML(
            $procedure,
            $xml,
            'updated'
        );
    }

    private function createProcedureDeleteMessage(string $xml, ProcedureInterface $procedure): void
    {
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage($xml, $procedure->getId());
        $this->xBeteiligungService->saveProcedureMessageOnFlush($procedureMessage);
        $this->xBeteiligungDebugger->createDebugMessageForCreatedXML($procedure, $xml, 'soft deleted');
    }

    private function deleteCurrentProcedureMessageIfExist(): void {
        if (null !== $this->currentProcedureMessage) {
            $this->xBeteiligungService->deleteProcedureMessageOnFlush($this->currentProcedureMessage);
        }
    }
}
