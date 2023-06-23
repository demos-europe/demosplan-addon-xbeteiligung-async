<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use InvalidArgumentException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProcedureMessageRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        string $entityClass,
        ValidatorInterface $validator
    ){
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param ProcedureMessage $ProcedureMessage
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function deleteObject(ProcedureMessage $ProcedureMessage): void
    {
        $this->getEntityManager()->remove($ProcedureMessage);
        $this->getEntityManager()->flush();
    }

    /**
     * @param $ProcedureMessageId
     * @return ProcedureMessage
     */
    public function get($ProcedureMessageId): ProcedureMessage
    {
        return $this->find($ProcedureMessageId);
    }

    public function getAll()
    {
        return $this->createQueryBuilder('ProcedureMessage')
            ->andWhere('ProcedureMessage.deleted = :parameter')
            ->setParameter('parameter', false)
            ->getQuery()
            ->getResult();
    }

    /**
     * Create a ProcedureMessage with necessary properties
     * and validate it.
     *
     * @throws InvalidArgumentException
     */
    public function createProcedureMessage(ProcedureInterface $procedure): ProcedureMessage
    {

        $procedureMessage = new ProcedureMessage($procedure);

        $this->validator->validate($procedureMessage);

        return $procedureMessage;
    }

    /**
     * @param $entity
     * @return mixed
     * @throws Exception
     */
    public function updateObject($entity)
    {
        try {
            $em = $this->getEntityManager();

            if (!is_null($entity->getPId())) {
                $em->persist($entity);
                $em->flush();
            }

            return $entity;
        } catch (Exception $e) {
            $this->logger->warning('update SingleMessage failed Reason: ', [$e]);
            throw $e;
        }
    }

    /**
     * @param $entityId
     * @return mixed
     * @throws Exception
     */
    public function delete($entityId)
    {
        try {
            return $this->createQueryBuilder('ProcedureMessage')
                ->andWhere('ProcedureMessage.id', 'id')
                ->set('ProcedureMessage.deleted' , ':parameter')
                ->setParameter('id', $entityId)
                ->setParameter('parameter', true)
                ->getQuery()
                ->getResult();
        } catch (Exception $e) {
            $this->logger->warning('delete SingleMessage failed Reason: ', [$e]);
            throw $e;
        }
    }

    public function persist(ProcedureMessage $ProcedureMessage)
    {
        $this->entityManager->persist($ProcedureMessage);
    }

    public function persistEntities(array $entities): void
    {
        foreach ($entities as $entity) {
            $this->getEntityManager()->persist($entity);
        }
    }

    public function flushEverything(): void
    {
        $this->getEntityManager()->flush();
    }

}
