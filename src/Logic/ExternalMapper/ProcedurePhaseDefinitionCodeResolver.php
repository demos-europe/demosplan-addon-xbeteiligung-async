<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedurePhaseDefinitionServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungPhaseDefinitionCodeMapping;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeMappingRepository;
use Psr\Log\LoggerInterface;

/**
 * Resolves an incoming XBeteiligung Verfahrensschritt code (as sent by Cockpit in 0401/0402
 * messages) to the ProcedurePhaseDefinition a Mandanten-Admin has configured for it.
 *
 * Falls back to the customer's initial ("Konfiguration") phase definition when no code was
 * sent, no mapping exists for the code, or the code ambiguously matches more than one
 * mapping for the given audience/customer.
 */
class ProcedurePhaseDefinitionCodeResolver
{
    public function __construct(
        private readonly XBeteiligungPhaseDefinitionCodeMappingRepository $mappingRepository,
        private readonly ProcedurePhaseDefinitionServiceInterface $phaseDefinitionService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function resolve(
        string $audience,
        ?string $xBeteiligungStandardCode,
        CustomerInterface $customer,
    ): ?ProcedurePhaseDefinitionInterface {
        if (null === $xBeteiligungStandardCode || '' === $xBeteiligungStandardCode) {
            return $this->phaseDefinitionService->findInitialDefinition($audience, $customer);
        }

        $matches = array_values(array_filter(
            $this->mappingRepository->findByXBeteiligungStandardCode($xBeteiligungStandardCode),
            static fn (XBeteiligungPhaseDefinitionCodeMapping $mapping): bool => $audience === $mapping->getPhaseDefinition()->getAudience()
                && $customer->getId() === $mapping->getPhaseDefinition()->getCustomer()?->getId()
        ));

        if (1 === count($matches)) {
            return $matches[0]->getPhaseDefinition();
        }

        if (count($matches) > 1) {
            $this->logger->warning('Ambiguous XBeteiligung phase code mapping', [
                'code' => $xBeteiligungStandardCode,
                'audience' => $audience,
                'customerId' => $customer->getId(),
            ]);
        }

        return $this->phaseDefinitionService->findInitialDefinition($audience, $customer);
    }
}
