<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedurePhaseMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class XBeteiligungProcedurePhaseMappingRepository extends ServiceEntityRepository
{

    public function get(string $id): ?XBeteiligungProcedurePhaseMapping
    {
        return $this->find($id);
    }

    public function save(XBeteiligungProcedurePhaseMapping $mapping): void
    {
        $this->getEntityManager()->persist($mapping);
        $this->getEntityManager()->flush($mapping);
    }

}

