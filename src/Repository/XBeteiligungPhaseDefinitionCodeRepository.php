<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\Logic\ApiRequest\FluentRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungPhaseDefinitionCode;

/**
 * @template-extends FluentRepository<XBeteiligungPhaseDefinitionCode>
 */
class XBeteiligungPhaseDefinitionCodeRepository extends FluentRepository
{
    /**
     * Returns all code mappings for the given XBeteiligung Verfahrensschritt code.
     * The caller filters by customer/audience via the linked ProcedurePhaseDefinition.
     *
     * @return XBeteiligungPhaseDefinitionCode[]
     */
    public function findByCode(string $code): array
    {
        return $this->findBy(['code' => $code]);
    }

    public function findOneByPhaseDefinition(ProcedurePhaseDefinitionInterface $phaseDefinition): ?XBeteiligungPhaseDefinitionCode
    {
        return $this->findOneBy(['phaseDefinition' => $phaseDefinition]);
    }

    public function save(XBeteiligungPhaseDefinitionCode $entity): void
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush($entity);
    }
}
