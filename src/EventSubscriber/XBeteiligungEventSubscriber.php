<?php

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\EventSubscriber;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\PostNewProcedureCreatedEventInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\PostProcedureUpdatedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\RelevantPropertiesForUpdatedProcedure;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

 class XBeteiligungEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly XBeteiligungService $xBeteiligungService,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Subscribe on prerender Event to add markup variables.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PostNewProcedureCreatedEventInterface::class => 'newProcedureCreated',
            PostProcedureUpdatedEventInterface::class => 'procedureChanged',
        ];
    }

    /**
     * @throws Exception
     */
    public function newProcedureCreated(PostNewProcedureCreatedEventInterface $event): void
    {
       $xml = $this->xBeteiligungService->createProcedureNew401FromObject($event->getProcedure());
       $this->createDebugMessageForCreatedXML($event->getProcedure(), $xml, 'created');
    }

    /**
     * @throws Exception
     */
    public function procedureChanged(PostProcedureUpdatedEventInterface $event): void
    {
        $procedure = $event->getProcedure();
        $procedure->getDeleted() ? $this->procedureDeleted($procedure) : $this->procedureUpdated($procedure);
    }

    /**
     * @throws Exception
     */
    private function procedureDeleted(ProcedureInterface $procedure): void
    {
        $xml = $this->xBeteiligungService->createProcedureDeleted409FromObject($procedure->getId());
        $this->createDebugMessageForCreatedXML($procedure, $xml, 'soft deleted');
    }

    /**
     * @throws Exception
     */
    private function procedureUpdated(ProcedureInterface $procedure): void
    {
        $unitOfWork = $this->entityManager->getUnitOfWork();
        $unitOfWork->computeChangeSet(
            $this->entityManager->getClassMetadata(get_class($procedure)),
            $procedure
        );
        $procedureChanges = [];
        if ($unitOfWork->isEntityScheduled($procedure)) {
            $procedureChanges = $unitOfWork->getEntityChangeSet($procedure);
        }

        foreach (RelevantPropertiesForUpdatedProcedure::cases() as $case) {
            if (in_array($case, $procedureChanges, true)) {

            }
        }

        $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($procedure);
        $this->createDebugMessageForCreatedXML($procedure, $xml, 'updated');
    }

    private function createDebugMessageForCreatedXML(
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
