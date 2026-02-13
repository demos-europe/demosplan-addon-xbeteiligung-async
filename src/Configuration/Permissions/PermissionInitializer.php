<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionConditionBuilder;
use DemosEurope\DemosplanAddon\Permission\PermissionInitializerInterface;
use DemosEurope\DemosplanAddon\Permission\ResolvablePermissionCollectionInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ProcedureMessageTyp;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class PermissionInitializer implements PermissionInitializerInterface
{
    private bool $restrictedAccess;
    private string $procedureMessageType;
    public function __construct(GlobalConfigInterface $globalConfig, ParameterBagInterface $parameterBag)
    {
        $this->restrictedAccess = $globalConfig->hasProcedureUserRestrictedAccess();
        $this->procedureMessageType = $parameterBag->get('addon_xbeteiligung_async_procedure_message_type');
    }

    /**
     * @inheritDoc
     */
    public function configurePermissions(ResolvablePermissionCollectionInterface $permissionCollection): void
    {
        $permissionCollection->configurePermissionInstance(
            Features::feature_read_procedure_message(),
            PermissionConditionBuilder::start()
                ->enableAlways()
        );

        if (ProcedureMessageTyp::KOMMUNAL->value === $this->procedureMessageType)
        {
            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_kom_create(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_kom_update(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_kom_delete(),
                PermissionConditionBuilder::start()->enableAlways()
            );
        }

        if (ProcedureMessageTyp::RAUMORDNUNG->value === $this->procedureMessageType)
        {
            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_rog_create(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_rog_update(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_rog_delete(),
                PermissionConditionBuilder::start()->enableAlways()
            );
        }

        if (ProcedureMessageTyp::PLANFESTSTELLUNG->value === $this->procedureMessageType)
        {
            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_pln_create(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_pln_update(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_procedure_message_pln_delete(),
                PermissionConditionBuilder::start()->enableAlways()
            );
        }
    }
}
