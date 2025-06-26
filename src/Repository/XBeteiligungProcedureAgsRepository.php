<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedureAgs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for XBeteiligungProcedureAgs entity
 */
class XBeteiligungProcedureAgsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, XBeteiligungProcedureAgs::class);
    }

    /**
     * Find AGS codes by procedure ID
     */
    public function findByProcedureId(string $procedureId): ?XBeteiligungProcedureAgs
    {
        return $this->findOneBy(['procedureId' => $procedureId]);
    }

    /**
     * Get AGS codes formatted for routing key generation
     */
    public function getAgsCodesForRouting(string $procedureId): ?array
    {
        $procedureAgs = $this->findByProcedureId($procedureId);
        
        if ($procedureAgs === null) {
            return null;
        }
        
        return [
            'autor' => $procedureAgs->getAutorAgsCode(),
            'leser' => $procedureAgs->getLeserAgsCode()
        ];
    }

    /**
     * Save or update AGS codes for a procedure
     */
    public function saveAgsCodesForProcedure(
        string $procedureId,
        string $autorAgsCode,
        string $leserAgsCode
    ): XBeteiligungProcedureAgs {
        $existingAgs = $this->findByProcedureId($procedureId);
        
        if ($existingAgs !== null) {
            // Update existing record
            $existingAgs->setAutorAgsCode($autorAgsCode);
            $existingAgs->setLeserAgsCode($leserAgsCode);
            $this->getEntityManager()->flush();
            return $existingAgs;
        }
        
        // Create new record
        $procedureAgs = new XBeteiligungProcedureAgs(
            $procedureId,
            $autorAgsCode,
            $leserAgsCode
        );
        
        $this->getEntityManager()->persist($procedureAgs);
        $this->getEntityManager()->flush();
        
        return $procedureAgs;
    }

    /**
     * Check if AGS codes exist for a procedure
     */
    public function hasAgsCodesForProcedure(string $procedureId): bool
    {
        return $this->findByProcedureId($procedureId) !== null;
    }
}