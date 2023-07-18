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
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\RelevantPropertiesForUpdatedProcedure;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class XBeteiligungEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly LoggerInterface $logger,
        private readonly XBeteiligungService $xBeteiligungService,
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
        $procedureAfterUpdate = $event->getProcedureAfterUpdate();
        $procedureAfterUpdate->getDeleted()
            ? $this->procedureDeleted($procedureAfterUpdate)
            : $this->procedureUpdated($event->getModifiedValues(), $procedureAfterUpdate);
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
    private function procedureUpdated(array $modifiedValues, ProcedureInterface $procedureAfterUpdate): void
    {
        if (self::relevantPropertyHasChanged($modifiedValues)) {
            $xml = $this->xBeteiligungService->createProcedureUpdate402FromObject($procedureAfterUpdate);
            $this->createDebugMessageForCreatedXML($procedureAfterUpdate, $xml, 'updated');
        }
    }

    private static function relevantPropertyHasChanged(array $modifiedValues): bool
    {
        foreach (RelevantPropertiesForUpdatedProcedure::cases() as $case) {
            if (array_key_exists($case->value, $modifiedValues)) {
                return true;
            }
            foreach ($modifiedValues as $modifiedValue) {
                if (is_array($modifiedValue)
                    && !array_key_exists('new', $modifiedValue)
                    && !array_key_exists('old', $modifiedValue)
                ) {
                    return self::relevantPropertyHasChanged($modifiedValue);
                }
            }
        }

        return false;
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
