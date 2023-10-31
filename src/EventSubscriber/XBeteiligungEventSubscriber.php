<?php

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\EventSubscriber;

use DemosEurope\DemosplanAddon\Contracts\Events\PostNewProcedureCreatedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logging\XBeteiligungLogger;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class XBeteiligungEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly XBeteiligungService $xBeteiligungService,
        private readonly XBeteiligungLogger $xBeteiligungLogger
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
        $xml = $this->xBeteiligungService->createProcedureNew401FromObject($event->getProcedure());
        $procedureMessage = $this->xBeteiligungService->createProcedureMessage($xml, $event->getProcedure()->getId());
        $this->xBeteiligungService->saveProcedureMessage($procedureMessage);
        $this->xBeteiligungLogger->createDebugMessageForCreatedXML(
            $event->getProcedure(),
            $xml,
            'created'
        );
    }
}
