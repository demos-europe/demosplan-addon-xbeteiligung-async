<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions;

use DemosEurope\DemosplanAddon\Permission\AbstractPermissionMeta;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAddon;

class Features extends AbstractPermissionMeta
{
    public static function feature_get_XBeteiligungMessage_from_procedure(): self
    {
        return new self('feature_get_xbeteiligungMessage_from_procedure');
    }

    public function getAddonIdentifier(): ?string
    {
        return XBeteiligungAddon::ADDON_NAME;
    }
}
