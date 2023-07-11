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
use DemosEurope\DemosplanAddon\Contracts\Events\PostProcedureUpdatedEventInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\PostProcedureDeletedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class XBeteiligungEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly XBeteiligungService $xBeteiligungService
    ) {
    }

    /**
     * Subscribe on prerender Event to add markup variables.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PostNewProcedureCreatedEventInterface::class => 'newProcedureCreated',
            PostProcedureUpdatedEventInterface::class => 'procedureUpdated',
            PostProcedureDeletedEventInterface::class => 'procedureDeleted',
        ];
    }

    public function newProcedureCreated(PostNewProcedureCreatedEventInterface $event): void
    {
       $xml = $this->xBeteiligungService->createProcedureNew401FromObject($event->getProcedure());
    }

    public function procedureUpdated(PostProcedureUpdatedEventInterface $event): void
    {
       $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($event->getProcedure());
    }

    public function procedureDeleted(PostProcedureDeletedEventInterface $event): void
    {
        $xml = $this->xBeteiligungService->createProcedureDeleted409FromObject($event->getProcedureId());
    }

}
