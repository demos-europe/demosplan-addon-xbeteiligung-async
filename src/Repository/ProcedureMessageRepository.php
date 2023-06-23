<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
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
     * @param $ProcedureMessageId
     * @return ProcedureMessage
     */
    public function get($ProcedureMessageId): ProcedureMessage
    {
        return $this->find($ProcedureMessageId);
    }

    /**
     * @param string $procedureMessageID
     * @return string
     * @throws Exception
     */
    public function getProcedureMessage(string $procedureMessageID): string
    {
        /** @var ProcedureMessage $procedureMessage **/
        $procedureMessage = $this->findBy(['id' => $procedureMessageID]);
        $procedureMessage->setRequestCount();
        $this->updateObject($procedureMessage);
        return $procedureMessage->getMessage();
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

}
