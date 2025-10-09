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

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ProcedureMessageRepository extends ServiceEntityRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        ManagerRegistry $registry,
        private LoggerInterface $logger,
        string $entityClass,
        private ValidatorInterface $validator
    ){
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
     * @param array<string, mixed> $criteria
     * @return array<ProcedureMessage>
     */
    public function findIdsBy(array $criteria): array
    {
        return array_map(
            static fn (ProcedureMessage $procedureMessage) => $procedureMessage->getId(), $this->findBy($criteria));
    }

    public function save(ProcedureMessage $procedureMessage): void
    {
        $this->getEntityManager()->persist($procedureMessage);
        $this->getEntityManager()->flush($procedureMessage);
    }

    public function saveOnFlush(ProcedureMessage $procedureMessage): void
    {
        $classMeta = $this->getEntityManager()->getClassMetadata(ProcedureMessage::class);
        $this->entityManager->persist($procedureMessage);
        $this->entityManager->getUnitOfWork()->computeChangeSet($classMeta, $procedureMessage);
    }

    /**
     * @param string $procedureMessageID
     * @return string
     * @throws Exception
     */
    public function getXmlContent(string $procedureMessageID): string
    {
        /** @var ProcedureMessage $procedureMessage **/
        $procedureMessage = $this->get($procedureMessageID);
        $procedureMessage->increaseRequestCountByOne();
        return $procedureMessage->getMessage();
    }

    /**
     * @param $entity
     * @return mixed
     * @throws Exception
     */
    public function updateObject($id): ProcedureMessage
    {
        try {
            $em = $this->getEntityManager();
            $entity = $this->get($id);
            if (!is_null($entity->getId())) {
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
