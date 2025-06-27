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
        $this->entityManager->persist($audit);
        $this->entityManager->getUnitOfWork()->computeChangeSet($classMeta, $audit);
    }

    /**
     * Find audit records by procedure ID
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findByProcedureId(string $procedureId): array
    {
        return $this->findBy(['procedureId' => $procedureId], ['createdAt' => 'ASC']);
    }

    /**
     * Find audit records by plan ID
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findByPlanId(string $planId): array
    {
        return $this->findBy(['planId' => $planId], ['createdAt' => 'ASC']);
    }

    /**
     * Find the original incoming message for a procedure
     */
    public function findOriginalIncomingMessage(string $procedureId): ?XBeteiligungMessageAudit
    {
        return $this->createQueryBuilder('a')
            ->where('a.procedureId = :procedureId')
            ->andWhere('a.direction = :direction')
            ->andWhere('a.messageType = :messageType')
            ->setParameter('procedureId', $procedureId)
            ->setParameter('direction', 'incoming')
            ->setParameter('messageType', '401')
            ->orderBy('a.createdAt', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Get complete message history for a procedure
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function getMessageHistory(string $procedureId): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.procedureId = :procedureId')
            ->setParameter('procedureId', $procedureId)
            ->orderBy('a.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Find outgoing messages that are responses to a specific incoming message
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findResponseMessages(string $originalMessageId): array
    {
        return $this->findBy(['responseToMessageId' => $originalMessageId], ['createdAt' => 'ASC']);
    }

    /**
     * Find messages by status
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findByStatus(string $status): array
    {
        return $this->findBy(['status' => $status], ['createdAt' => 'DESC']);
    }

    /**
     * Find pending messages (for processing)
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findPendingMessages(): array
    {
        return $this->findByStatus('pending');
    }

    /**
     * Find failed messages (for error analysis)
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findFailedMessages(): array
    {
        return $this->findByStatus('failed');
    }

    /**
     * Find messages by direction and type
     *
     * @return array<XBeteiligungMessageAudit>
     */
    public function findByDirectionAndType(string $direction, string $messageType): array
    {
        return $this->findBy([
            'direction' => $direction,
            'messageType' => $messageType
        ], ['createdAt' => 'DESC']);
    }

    /**
     * Clean up old audit records (for retention policy)
     */
    public function deleteOlderThan(\DateTime $cutoffDate): int
    {
        return $this->createQueryBuilder('a')
            ->delete()
            ->where('a.createdAt < :cutoffDate')
            ->setParameter('cutoffDate', $cutoffDate)
            ->getQuery()
            ->execute();
    }

    /**
     * Count audit records by status
     *
     * @return array<string, int>
     */
    public function getStatusCounts(): array
    {
        $result = $this->createQueryBuilder('a')
            ->select('a.status, COUNT(a.id) as count')
            ->groupBy('a.status')
            ->getQuery()
            ->getResult();

        $counts = [];
        foreach ($result as $row) {
            $counts[$row['status']] = (int) $row['count'];
        }

        return $counts;
    }
}
