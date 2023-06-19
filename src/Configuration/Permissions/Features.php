<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions;

use DemosEurope\DemosplanAddon\Permission\AbstractPermissionMeta;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAddon;

class Features extends AbstractPermissionMeta
{
    public function getAddonIdentifier(): ?string
    {
        return XBeteiligungAddon::ADDON_NAME;
    }
}
