<?php

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
    private string $procedureMessageTyp;
    public function __construct(GlobalConfigInterface $globalConfig, ParameterBagInterface $parameterBag)
    {
        $this->restrictedAccess = $globalConfig->hasProcedureUserRestrictedAccess();
        $this->procedureMessageTyp = $parameterBag->get('procedure_message_type');
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

        if (ProcedureMessageTyp::KOMMUNAL->value === $this->procedureMessageTyp)
        {
            $permissionCollection->configurePermissionInstance(
                Features::feature_create_procedure_message_0401(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_create_procedure_message_0402(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_create_procedure_message_0409(),
                PermissionConditionBuilder::start()->enableAlways()
            );
        }

        if (ProcedureMessageTyp::RAUMORDNUNG->value === $this->procedureMessageTyp)
        {
            $permissionCollection->configurePermissionInstance(
                Features::feature_create_procedure_message_0301(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_create_procedure_message_0302(),
                PermissionConditionBuilder::start()->enableAlways()
            );

            $permissionCollection->configurePermissionInstance(
                Features::feature_create_procedure_message_0309(),
                PermissionConditionBuilder::start()->enableAlways()
            );
        }
    }
}
