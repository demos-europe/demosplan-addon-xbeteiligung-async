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

use DemosEurope\DemosplanAddon\Contracts\CurrentContextProviderInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;

/**
 * Access rules for the planID of a procedure, shared by the provider and the processor so that
 * reads and writes cannot drift apart.
 */
class ProcedurePlanIdAccessChecker
{
    public function __construct(
        private readonly CurrentContextProviderInterface $currentContextProvider,
        private readonly PermissionEvaluatorInterface $permissionEvaluator,
    ) {
    }

    /**
     * Same permission as the procedure settings page the field is shown on.
     */
    public function isAvailable(): bool
    {
        return $this->permissionEvaluator->isPermissionEnabled('area_admin_preferences');
    }

    /**
     * Only the procedure the user currently works in may be read or changed.
     */
    public function isCurrentProcedure(string $procedureId): bool
    {
        $currentProcedure = $this->currentContextProvider->getCurrentProcedure();

        return null !== $currentProcedure && $currentProcedure->getId() === $procedureId;
    }
}
