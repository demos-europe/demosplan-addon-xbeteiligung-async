<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Api\ProcedurePlanId;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\State\ProcessorInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\MessageBagInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use Doctrine\ORM\EntityManagerInterface;
use LogicException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Webmozart\Assert\Assert;

/**
 * Stores the planID in the core property of the procedure. The update message for K3 is sent by
 * the existing procedure change listener once the procedure is saved.
 */
class ProcedurePlanIdProcessor implements ProcessorInterface
{
    public function __construct(
        private readonly ProcedurePlanIdAccessChecker $accessChecker,
        private readonly EntityManagerInterface $entityManager,
        private readonly MessageBagInterface $messageBag,
        private readonly ProcedureServiceInterface $procedureService,
    ) {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ProcedurePlanIdResource
    {
        if (!$operation instanceof Patch) {
            throw new LogicException(sprintf('%s only handles Patch operations, got "%s".', self::class, $operation::class));
        }

        Assert::isInstanceOf($data, ProcedurePlanIdResource::class);

        $procedureId = (string) ($uriVariables['id'] ?? '');
        if (!$this->accessChecker->isAvailable() || !$this->accessChecker->isCurrentProcedure($procedureId)) {
            throw new AccessDeniedHttpException(sprintf('Access denied: insufficient permissions to access %s', $operation->getShortName()));
        }

        $procedure = $this->procedureService->getProcedure($procedureId);
        if (null === $procedure) {
            throw new NotFoundHttpException('The procedure could not be found.');
        }

        $planId = trim((string) $data->xtaPlanId);
        $this->assertPlanIdIsFree($procedure, $planId);

        $procedure->setXtaPlanId($planId);
        $this->procedureService->updateProcedureObject($procedure);

        return ProcedurePlanIdResource::fromProcedure($procedure);
    }

    private function assertPlanIdIsFree(ProcedureInterface $procedure, string $planId): void
    {
        if ('' === $planId) {
            return;
        }

        $proceduresWithSamePlanId = $this->entityManager
            ->getRepository(ProcedureInterface::class)
            ->findBy(['xtaPlanId' => $planId, 'deleted' => false]);

        foreach ($proceduresWithSamePlanId as $otherProcedure) {
            if ($otherProcedure->getId() !== $procedure->getId()) {
                $this->messageBag->add('error', 'error.xbeteiligung.plan.id.already.used');

                throw new UnprocessableEntityHttpException('The planID is already used by another procedure.');
            }
        }
    }
}
