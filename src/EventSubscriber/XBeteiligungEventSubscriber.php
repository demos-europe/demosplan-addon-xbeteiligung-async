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
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\XBeteiligung\Debugger\XBeteiligungDebugger;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class XBeteiligungEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly XBeteiligungService $xBeteiligungService,
        private readonly XBeteiligungDebugger $xBeteiligungDebugger,
        private readonly PermissionEvaluatorInterface $permissionEvaluator
    ) {
    }

    /**
     * Subscribe on prerender Event to add markup variables.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PostNewProcedureCreatedEventInterface::class => 'newProcedureCreated',
        ];
    }

    /**
     * @throws Exception
     */
    public function newProcedureCreated(PostNewProcedureCreatedEventInterface $event): void
    {
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())) {
            $xml = $this->xBeteiligungService->createProcedureNew401FromObject($event->getProcedure());
            $this->createProcedureMessage($xml, $event->getProcedure());
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())) {
            $xml = $this->xBeteiligungService->createXMLFor301($event->getProcedure());
            $this->createProcedureMessage($xml, $event->getProcedure());
        }
    }

    private function createProcedureMessage(string $xml, ProcedureInterface $procedure): void
    {
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage($xml, $procedure->getId());
        $this->xBeteiligungService->saveProcedureMessage($procedureMessage);
        $this->xBeteiligungDebugger->createDebugMessageForCreatedXML(
            $procedure,
            $xml,
            'created'
        );
    }
}
