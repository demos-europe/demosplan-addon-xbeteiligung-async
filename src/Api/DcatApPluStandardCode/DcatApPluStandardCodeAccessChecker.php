<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Api\DcatApPluStandardCode;


use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;

class DcatApPluStandardCodeAccessChecker
{
    public function __construct(
        private readonly PermissionEvaluatorInterface $permissionEvaluator) {
    }

    public function isAvailable(): bool
    {
        return $this->permissionEvaluator->isPermissionEnabled(
            'area_customer_procedure_phase_definitions'
        );
    }

    public function getAccessConditions(): array
    {
        return [];
    }
}
