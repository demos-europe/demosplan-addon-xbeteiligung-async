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

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungMessageAudit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class XBeteiligungMessageAuditRepository extends ServiceEntityRepository
{
    public function get(string $auditId): ?XBeteiligungMessageAudit
    {
        return $this->find($auditId);
    }

    public function save(XBeteiligungMessageAudit $audit): void
    {
        $this->getEntityManager()->persist($audit);
        $this->getEntityManager()->flush($audit);
    }

    public function saveOnFlush(XBeteiligungMessageAudit $audit): void
    {
        $classMeta = $this->getEntityManager()->getClassMetadata(XBeteiligungMessageAudit::class);
        $this->getEntityManager()->persist($audit);
        $this->getEntityManager()->getUnitOfWork()->computeChangeSet($classMeta, $audit);
    }


    /**
     * Find audit records by procedure ID and target system
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findByProcedureIdAndTargetSystem(string $procedureId, string $targetSystem): array
    {
        return $this->findBy([
            'procedureId' => $procedureId,
            'targetSystem' => $targetSystem
        ], ['createdAt' => 'ASC']);
    }
}
