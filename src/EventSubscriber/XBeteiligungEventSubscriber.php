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
use DemosEurope\DemosplanAddon\XBeteiligung\Debugger\XBeteiligungDebugger;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Tools\RabbitMQMessageBroker;
use Exception;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
        private readonly ParameterBagInterface                   $parameterBag,
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
        if (false === $this->parameterBag->get('addon_xbeteiligung_async_enable_rabbitmq_communication')) {
            $this->cockpitLogger->info('RabbitMQ communication is disabled');

            return;
        }
        try {
            $this->cache->get('MessageBrokerDelay', function (ItemInterface $item): void {
                $ttl = $this->parameterBag->get('addon_xbeteiligung_async_rabbitmq_communication_delay');
                $this->cockpitLogger->info('Starting XBeteiligung maintenance cycle with delay '.$ttl);
                $item->expiresAfter($ttl);

                // NEW: Process incoming messages from queues first
                if ($this->parameterBag->get('addon_xbeteiligung_async_enable_direct_consumption')) {
                    $this->processIncomingQueueMessages();
                }
                
                // EXISTING: Then try to fetch new messages using existing method
                $this->rabbitMQMessageBroker->processMessages();
            });
        } catch (Exception $e) {
            $this->cockpitLogger->error('Failed to process XBeteiligung messages', [$e]);
        }
    }

    /**
     * Process messages directly from specific queues without request-response pattern
     */
    private function processIncomingQueueMessages(): void
    {
        $procedureType = $this->parameterBag->get('addon_xbeteiligung_async_procedure_message_type');
        
        if (empty($procedureType)) {
            $this->cockpitLogger->warning('No procedure message type configured, skipping direct queue consumption');
            return;
        }

        try {
            // Use existing configuration logic to get the appropriate queue name
            $queueName = $this->getQueueNameForProcedureType($procedureType);
            
            $this->cockpitLogger->info('Processing messages from queue', [
                'queue' => $queueName,
                'procedureType' => $procedureType
            ]);
            
            // Process messages directly from the target queue
            $this->rabbitMQMessageBroker->processQueueMessages($queueName);
            
        } catch (Exception $e) {
            $this->cockpitLogger->error('Direct queue consumption failed', [
                'procedureType' => $procedureType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            // Don't re-throw - let the existing method try as fallback
        }
    }

    /**
     * Get queue name based on procedure message type
     */
    private function getQueueNameForProcedureType(string $procedureType): string
    {
        return match (strtolower($procedureType)) {
            'kommunal' => 'bau.beteiligung',
            'raumordnung' => 'rog.beteiligung',
            'planfeststellung' => 'pfv.beteiligung',
            default => throw new RuntimeException(
                sprintf('Unknown procedure message type "%s"', $procedureType)
            )
        };
    }

    public function handleStatementCreatedEvent(StatementCreatedEventInterface $event): void
    {
        if (false === $this->parameterBag->get('addon_xbeteiligung_async_enable_rabbitmq_communication')) {
            $this->cockpitLogger->info('RabbitMQ communication is disabled');

            return;
        }
        if (!$this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())
            && !$this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())
            && !$this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_pln_create())) {
            $this->cockpitLogger->info('addon_xbeteiligung_async_procedure_message_type is not set.');

            return;
        }
        try {
            $this->rabbitMQMessageBroker->handleStatementCreatedEvent($event);
        } catch (\Exception $e) {
            $this->cockpitLogger->warning('could not send statementCreated message', [$e]);
        }
    }

    /**
     * @throws Exception
     */
    public function newProcedureCreated(PostNewProcedureCreatedEventInterface $event): void
    {
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())) {
            $xml = $this->xBeteiligungService->createProcedureNew401FromObject($event->getProcedure());
            $this->createProcedureMessage($xml, $event->getProcedure(), KommunalInitiieren0401::class);
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())) {
            $xml = $this->xBeteiligungService->createXMLFor301($event->getProcedure());
            $this->createProcedureMessage($xml, $event->getProcedure(), RaumordnungInitiieren0301::class);
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
