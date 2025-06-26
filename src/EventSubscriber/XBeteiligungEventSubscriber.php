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
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungProcedureContextService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedureAgsRepository;
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
        private readonly XBeteiligungProcedureContextService     $procedureContextService,
        private readonly XBeteiligungProcedureAgsRepository      $procedureAgsRepository,
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
                $this->cockpitLogger->info('Fetch RabbitMQ Messages with delay '.$ttl);
                $item->expiresAfter($ttl);

                $this->rabbitMQMessageBroker->processMessages();
            });
        } catch (Exception $e) {
            $this->cockpitLogger->warning('failed to get procedure-create messages', [$e]);
        }
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
        // First, try to store AGS codes if they exist in context
        $this->handleAgsCodesStorage($event);

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())) {
            $xml = $this->xBeteiligungService->createProcedureNew401FromObject($event->getProcedure());
            $this->createProcedureMessage($xml, $event->getProcedure(), KommunalInitiieren0401::class);
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())) {
            $xml = $this->xBeteiligungService->createXMLFor301($event->getProcedure());
            $this->createProcedureMessage($xml, $event->getProcedure(), RaumordnungInitiieren0301::class);
        }
    }

    /**
     * Handle AGS codes storage with transaction safety
     * If AGS storage fails, add critical concern to trigger transaction rollback
     */
    private function handleAgsCodesStorage(PostNewProcedureCreatedEventInterface $event): void
    {
        $procedure = $event->getProcedure();
        $procedureId = $procedure->getId();
        $planId = $procedure->getXtaPlanId();

        // Only process procedures created from 401 messages (have xtaPlanId)
        if ('' === $planId) {
            $this->cockpitLogger->debug('Procedure has no xtaPlanId, skipping AGS storage', [
                'procedureId' => $procedureId
            ]);

            return;
        }

        // Check if we have AGS codes for this planId in context
        $agsCodes = $this->procedureContextService->retrieveAndClearAgsContext($planId);

        if (null === $agsCodes) {
            // CRITICAL: No AGS codes in context for 401 procedure - this should never happen
            $this->cockpitLogger->error('CRITICAL: No AGS codes found in context for 401 procedure', [
                'procedureId' => $procedureId,
                'planId' => $planId,
                'message' => 'Procedure created from 401 XML but AGS codes missing from context'
            ]);

            // Add critical concern to trigger transaction rollback
            $event->addCriticalConcern(
                'ags_context_missing',
                'AGS codes missing from context for 401 procedure with planId: ' . $planId,
                new RuntimeException('AGS codes not found in context for 401 procedure')
            );
            return;
        }

        // Try to store AGS codes using the actual procedure ID - if this fails, abort the entire procedure creation
        try {
            $this->procedureAgsRepository->saveAgsCodesForProcedure(
                $procedureId,  // Use actual procedure ID for storage
                $agsCodes['autor'],
                $agsCodes['leser']
            );

            $this->cockpitLogger->info('Successfully stored AGS codes for procedure in EventSubscriber', [
                'procedureId' => $procedureId,
                'planId' => $planId,
                'autorAgs' => $agsCodes['autor'],
                'leserAgs' => $agsCodes['leser']
            ]);
        } catch (Exception $e) {
            // CRITICAL: AGS storage failed - this should trigger transaction rollback
            $this->cockpitLogger->error('CRITICAL: Failed to store AGS codes for procedure', [
                'procedureId' => $procedureId,
                'planId' => $planId,
                'autorAgs' => $agsCodes['autor'] ?? 'null',
                'leserAgs' => $agsCodes['leser'] ?? 'null',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Add critical concern to trigger transaction rollback
            $event->addCriticalConcern(
                'ags_storage_failed',
                'Failed to store AGS codes for procedure: ' . $e->getMessage(),
                $e
            );
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
