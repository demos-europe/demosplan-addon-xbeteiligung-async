<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions;

use DemosEurope\DemosplanAddon\Permission\AbstractPermissionMeta;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAddon;

class Features extends AbstractPermissionMeta
{
    public static function feature_create_procedure_from_XBeteiligungMessage(): self
    {
        return new self('feature_create_procedure_from_XBeteiligungMessage');
    }

    public function getAddonIdentifier(): ?string
    {
        return XBeteiligungAddon::ADDON_NAME;
    }
}
