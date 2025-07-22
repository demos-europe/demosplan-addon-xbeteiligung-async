<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungMessageAudit;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class XBeteiligungMessageAuditRepository extends ServiceEntityRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        string $entityClass
    ) {
        parent::__construct($registry, $entityClass);
    }

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
