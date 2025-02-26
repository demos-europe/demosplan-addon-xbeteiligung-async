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
use DemosEurope\DemosplanAddon\Contracts\Events\AddonMaintenanceEventInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\PostNewProcedureCreatedEventInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\XBeteiligung\Debugger\XBeteiligungDebugger;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Tools\GetMessageRabbitMQ;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class XBeteiligungEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly PermissionEvaluatorInterface $permissionEvaluator,
        private readonly XBeteiligungDebugger         $xBeteiligungDebugger,
        private readonly XBeteiligungService          $xBeteiligungService,
        private readonly CacheInterface               $cache,
        private readonly ParameterBagInterface        $parameterBag,
        private readonly LoggerInterface              $cockpitLogger,
        private readonly GetMessageRabbitMQ           $getMessageRabbitMQ,
    ) {
    }

    /**
     * Subscribe on prerender Event to add markup variables.
     */
    public static function getSubscribedEvents(): array
    {
        return [
            PostNewProcedureCreatedEventInterface::class => ['newProcedureCreated'],
            AddonMaintenanceEventInterface::class => ['handleAddonMaintenanceEvent'],
        ];
    }

    public function handleAddonMaintenanceEvent(AddonMaintenanceEventInterface $event): void
    {
        if (false === $this->parameterBag->get('enable_communication')) {
            return;
        }
        try {
            $this->cache->get('MessageBrokerDelay', function (ItemInterface $item): void {
                $ttl = $this->parameterBag->get('communication_delay');
                $this->cockpitLogger->info('Fetch Messages with delay '.$ttl);
                $item->expiresAfter($ttl);

                $this->getMessageRabbitMQ->processMessages();
            });
        } catch (Exception $e) {
            $this->cockpitLogger->warning('failed to get procedure-create messages', [$e]);
        }
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
