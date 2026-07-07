<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\EventSubscriber;

use DemosEurope\DemosplanAddon\Contracts\Events\ProcedurePhaseDefinitionMarkedAsDeletedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeMappingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Cleans up XBeteiligungPhaseDefinitionCodeMapping mappings when a ProcedurePhaseDefinition
 * is soft-deleted, ensuring no orphaned code mappings remain.
 */
class XBeteiligungPhaseDefinitionCodeMappingSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly XBeteiligungPhaseDefinitionCodeMappingRepository $repository,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProcedurePhaseDefinitionMarkedAsDeletedEventInterface::class => 'onPhaseDefinitionMarkedAsDeleted',
        ];
    }

    public function onPhaseDefinitionMarkedAsDeleted(ProcedurePhaseDefinitionMarkedAsDeletedEventInterface $event): void
    {
        $mapping = $this->repository->findOneByPhaseDefinition($event->getPhaseDefinition());
        if (null !== $mapping) {
            $this->entityManager->remove($mapping);
        }
    }
}
