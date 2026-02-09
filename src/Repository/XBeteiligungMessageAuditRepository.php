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
use Doctrine\ORM\NonUniqueResultException;

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

    /**
     * Find the latest incoming 402 message that has no response (OK/NOK) linked to it
     *
     * @param array<string> $messageTypes List of 402 message types to search for
     *
     * @throws NonUniqueResultException
     */
    public function findLatestUnrespondedUpdateMessage(
        string $procedureId,
        array $messageTypes,
        string $targetSystem
    ): ?XBeteiligungMessageAudit {
        $qb = $this->createQueryBuilder('incoming');

        $qb->leftJoin(
            XBeteiligungMessageAudit::class,
            'response',
            'WITH',
            'response.responseToMessageId = incoming.id'
        )
        ->where('incoming.procedureId = :procedureId')
        ->andWhere('incoming.messageType IN (:messageTypes)')
        ->andWhere('incoming.direction = :direction')
        ->andWhere('incoming.targetSystem = :targetSystem')
        ->andWhere('response.id IS NULL')
        ->setParameter('procedureId', $procedureId)
        ->setParameter('messageTypes', $messageTypes)
        ->setParameter('direction', 'received')
        ->setParameter('targetSystem', $targetSystem)
        ->orderBy('incoming.createdAt', 'DESC')
        ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}
