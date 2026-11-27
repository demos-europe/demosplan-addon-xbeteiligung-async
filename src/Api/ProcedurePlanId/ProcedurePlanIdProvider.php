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
use ApiPlatform\State\ProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Webmozart\Assert\Assert;

class ProcedurePlanIdProvider implements ProviderInterface
{
    public function __construct(
        private readonly ProcedurePlanIdAccessChecker $accessChecker,
        private readonly ProcedureServiceInterface $procedureService,
    ) {
    }

    /**
     * Returning null lets API Platform answer 404.
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): ?ProcedurePlanIdResource
    {
        Assert::same($operation->getClass(), ProcedurePlanIdResource::class);

        $procedureId = (string) ($uriVariables['id'] ?? '');
        if (!$this->accessChecker->isAvailable() || !$this->accessChecker->isCurrentProcedure($procedureId)) {
            throw new AccessDeniedHttpException(sprintf('Access denied: insufficient permissions to access %s', $operation->getShortName()));
        }

        $procedure = $this->procedureService->getProcedure($procedureId);

        return null === $procedure ? null : ProcedurePlanIdResource::fromProcedure($procedure);
    }
}
