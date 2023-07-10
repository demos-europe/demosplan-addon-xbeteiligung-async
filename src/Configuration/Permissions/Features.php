<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions;

use DemosEurope\DemosplanAddon\Permission\AbstractPermissionMeta;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAsyncAddon;

class Features extends AbstractPermissionMeta
{

    /**
     * needed to retrieve procedure-messages generated when updating creating or deleting a procedure
     */
    public static function feature_read_procedure_message(): self
    {
        return new self('feature_read_procedure_message');
    }

    /**
     * @inheritDoc
     */
    public function getAddonIdentifier(): ?string
    {
        return XBeteiligungAsyncAddon::ADDON_NAME;
    }
}