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

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungFileMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class XBeteiligungFileMappingRepository extends ServiceEntityRepository
{
    /**
     * Find file mapping by XML file ID and procedure
     */
    public function findByXmlFileIdAndProcedure(string $xmlFileId, string $procedureId): ?XBeteiligungFileMapping
    {
        return $this->findOneBy([
            'xmlFileId' => $xmlFileId,
            'procedureId' => $procedureId,
        ]);
    }

    /**
     * Save file mapping (create or update)
     */
    public function save(XBeteiligungFileMapping $mapping): XBeteiligungFileMapping
    {
        $this->getEntityManager()->persist($mapping);
        $this->getEntityManager()->flush($mapping);

        return $mapping;
    }

    /**
     * Delete all mappings for a procedure
     *
     * @return int Number of deleted records
     */
    public function deleteByProcedureId(string $procedureId): int
    {
        $qb = $this->createQueryBuilder('fm')
            ->delete()
            ->where('fm.procedureId = :procedureId')
            ->setParameter('procedureId', $procedureId);

        return $qb->getQuery()->execute();
    }
}
