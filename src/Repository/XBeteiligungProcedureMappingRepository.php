<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedureMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class XBeteiligungProcedureMappingRepository extends ServiceEntityRepository
{

    public function get(string $id): ?XBeteiligungProcedureMapping
    {
        return $this->find($id);
    }

    public function save(XBeteiligungProcedureMapping $mapping): void
    {
        $this->getEntityManager()->persist($mapping);
        $this->getEntityManager()->flush($mapping);
    }

}
