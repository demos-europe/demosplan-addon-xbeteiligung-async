<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionInitializerInterface;
use DemosEurope\DemosplanAddon\Permission\ResolvablePermissionCollectionInterface;

class PermissionInitializer implements PermissionInitializerInterface
{
    private bool $restrictedAccess;

    public function __construct(GlobalConfigInterface $globalConfig)
    {
        $this->restrictedAccess = $globalConfig->hasProcedureUserRestrictedAccess();
    }

    public function configurePermissions(ResolvablePermissionCollectionInterface $permissionCollection): void
    {

    }
}
