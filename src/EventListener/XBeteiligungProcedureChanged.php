<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\EventListener;


use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\SingleDocumentInterface;
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

        foreach ($procedureSettingsToUpdate as $procedureSettings) {
            if ($procedureSettings->getProcedure()->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedureSettings);
            $this->addUniqueRelevantProcedure($changeSet, $procedureSettings->getProcedure());
        }

        foreach ($proceduresToUpdate as $procedure) {
            if ($procedure->getMaster()) {
                continue;
            }
            $changeSet = $this->unitOfWork->getEntityChangeSet($procedure);
            $this->addUniqueRelevantProcedure($changeSet, $procedure);
        }

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

        foreach ($singleDocumentsToInsert as $singleDocument) {
            if (!$singleDocument->getElement()->getEnabled() || $singleDocument->getProcedure()->getMaster()) {
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()->setNewSingleDocument($singleDocument);
            $this->addUniqueRelevantProcedure(['new_single_document' => ''], $singleDocument->getProcedure());
        }

        foreach ($singleDocumentsToDelete as $singleDocument) {
            if (!$singleDocument->getElement()->getEnabled() || $singleDocument->getProcedure()->getMaster()) {
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()
                ->addDeletedSingleDocument($singleDocument->getId());
            $this->addUniqueRelevantProcedure(['delete_single_document' => ''], $singleDocument->getProcedure());
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
                    $this->addUniqueRelevantProcedure(['update_single_document' => ''], $singleDocument->getProcedure());
                }
                continue;
            }
            $this->xBeteiligungService->getPlanningDocumentsLinkCreator()->setUpdatedSingleDocument($singleDocument);
            $this->addUniqueRelevantProcedure(['update_single_document' => ''], $singleDocument->getProcedure());
        }

        foreach ($this->updatedProcedures as $updatedProcedure) {
            $this->onProcedureChanged($updatedProcedure);
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
    private function onProcedureUpdated(ProcedureInterface $procedureAfterUpdate): void
    {
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_update())) {
            $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($procedureAfterUpdate);
            $this->createProcedureUpdatedMessage($xml, $procedureAfterUpdate);
        }
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_update())) {
            $xml = $this->xBeteiligungService->createXMLFor302($procedureAfterUpdate);
            $this->createProcedureUpdatedMessage($xml, $procedureAfterUpdate);
        }
    }

    private function createProcedureUpdatedMessage(string $xml, ProcedureInterface $procedure): void
    {
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage(
            $xml,
            $procedure->getId()
        );
        $this->xBeteiligungService->saveProcedureMessageOnFlush($procedureMessage);
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

    private function addUniqueRelevantProcedure(array $changeSet, ProcedureInterface $updatedProcedure): void
    {
        if (RelevantPropertiesForUpdatedProcedure::propertyHasChanged($changeSet) &&
            !array_key_exists($updatedProcedure->getId(), $this->updatedProcedures)) {
            $this->updatedProcedures[$updatedProcedure->getId()] = $updatedProcedure;
        }
    }
}
