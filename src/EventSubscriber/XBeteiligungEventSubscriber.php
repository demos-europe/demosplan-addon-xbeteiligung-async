<?php

declare(strict_types=1);

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
use DemosEurope\DemosplanAddon\Contracts\Events\ManualOriginalStatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\PostNewProcedureCreatedEventInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Debugger\XBeteiligungDebugger;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Tools\RabbitMQMessageBroker;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class XBeteiligungEventSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly PermissionEvaluatorInterface            $permissionEvaluator,
        private readonly XBeteiligungDebugger                    $xBeteiligungDebugger,
        private readonly XBeteiligungService                     $xBeteiligungService,
        private readonly CacheInterface                          $cache,
        private readonly XBeteiligungConfiguration               $config,
        private readonly LoggerInterface                         $cockpitLogger,
        private readonly RabbitMQMessageBroker                   $rabbitMQMessageBroker,
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
            StatementCreatedEventInterface::class => ['handleStatementCreatedEvent'],
            ManualOriginalStatementCreatedEventInterface::class => ['handleStatementCreatedEvent'],
        ];
    }

    public function handleAddonMaintenanceEvent(AddonMaintenanceEventInterface $event): void
    {
        if (false === $this->config->rabbitMqEnabled) {
            $this->cockpitLogger->info('RabbitMQ communication is disabled');

            return;
        }
        try {
            $this->cache->get('MessageBrokerDelay', function (ItemInterface $item): void {
                $ttl = $this->config->communicationDelay;
                $this->cockpitLogger->info('Starting XBeteiligung maintenance cycle with delay '.$ttl);
                $item->expiresAfter($ttl);

                $queueName = $this->config->getQueueName();
                $this->cockpitLogger->info('Processing messages from queue.', [
                    'queue' => $queueName,
                ]);
                $this->rabbitMQMessageBroker->processMessages($queueName);
            });
        } catch (Exception $e) {
            $this->cockpitLogger->error('Failed to process XBeteiligung messages', [$e]);
        }
    }

    public function handleStatementCreatedEvent(StatementCreatedEventInterface $event): void
    {
        try {
            if (false === $this->config->rabbitMqEnabled) {
                $this->cockpitLogger->info('RabbitMQ communication is disabled');

                return;
            }
            if (!$this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())
                && !$this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())
                && !$this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_pln_create())) {
                $this->cockpitLogger->warning('XBeteiligung statement processing skipped: No procedure message type permissions enabled. Check parameter "addon_xbeteiligung_async_procedure_message_type" - should be one of: "Kommunal", "Raumordnung", or "Planfeststellung".');

                return;
            }
            
            $this->rabbitMQMessageBroker->handleStatementCreatedEvent($event);
        } catch (Exception $exception) {
            $this->cockpitLogger->error('XBeteiligung: Error in handleStatementCreatedEvent', [
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);
            throw $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function newProcedureCreated(PostNewProcedureCreatedEventInterface $event): void
    {
        try {
            if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())) {
                $xml = $this->xBeteiligungService->createProcedureNew401FromObject($event->getProcedure());
                $this->createProcedureMessage($xml, $event->getProcedure(), KommunalInitiieren0401::class);
            }

            if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())) {
                $xml = $this->xBeteiligungService->createXMLFor301($event->getProcedure());
                $this->createProcedureMessage($xml, $event->getProcedure(), RaumordnungInitiieren0301::class);
            }
        } catch (Exception $exception) {
            $this->cockpitLogger->error('XBeteiligung: Error in newProcedureCreated event handler', [
                'procedureId' => $event->getProcedure()->getId(),
                'error' => $exception->getMessage(),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => $exception->getTraceAsString()
            ]);
            throw $exception;
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_pln_create())) {
            $xml = $this->xBeteiligungService->createXMLFor201($event->getProcedure());
            $this->createProcedureMessage($xml, $event->getProcedure(), PlanfeststellungInitiieren0201::class);
        }
    }


    private function createProcedureMessage(string $xml, ProcedureInterface $procedure, string $messageClass): void
    {
        $procedureMessage =
            $this->xBeteiligungService->createProcedureMessage($xml, $procedure->getId(), $messageClass);
        $this->xBeteiligungService->saveProcedureMessage($procedureMessage);
        $this->xBeteiligungDebugger->createDebugMessageForCreatedXML(
            $procedure,
            $xml,
            'created'
        );
    }
}
