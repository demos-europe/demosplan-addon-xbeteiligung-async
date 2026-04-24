<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeRepository;

/**
 * Resolves a ProcedurePhaseDefinition from an XBeteiligung Verfahrensschritt code,
 * filtered by customer and audience.
 */
class XBeteiligungPhaseDefinitionResolver
{
    public function __construct(
        private readonly XBeteiligungPhaseDefinitionCodeRepository $repository,
    ) {
    }

    /**
     * Returns the ProcedurePhaseDefinition that belongs to the given XBeteiligung code,
     * customer, and audience — or null when no matching mapping is found.
     */
    public function resolveByCodeAndCustomer(
        string $code,
        string $customerId,
        string $audience,
    ): ?ProcedurePhaseDefinitionInterface {
        foreach ($this->repository->findByCode($code) as $mapping) {
            $definition = $mapping->getPhaseDefinition();
            $definitionCustomer = $definition->getCustomer();
            if (null !== $definitionCustomer
                && $definitionCustomer->getId() === $customerId
                && $definition->getAudience() === $audience) {
                return $definition;
            }
        }

        return null;
    }
}
