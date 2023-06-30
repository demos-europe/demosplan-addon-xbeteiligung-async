<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Repository;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProcedureMessageRepository extends ServiceEntityRepository
{
    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    private LoggerInterface $logger;

    public function __construct(
        EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        LoggerInterface $logger,
        string $entityClass,
        ValidatorInterface $validator
    ){
        $this->entityManager = $entityManager;
        $this->logger = $logger;
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
        $procedureMessage = $this->get($procedureMessageID);
        $procedureMessage->setRequestCount();
        return $procedureMessage->getMessage();
    }

    /**
     * @param $entity
     * @return mixed
     * @throws Exception
     */
    public function updateObject($id)
    {
        try {
            $em = $this->getEntityManager();
            $entity = $this->get($id);
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
