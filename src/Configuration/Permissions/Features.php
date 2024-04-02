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

    public static function feature_procedure_message_rog_create(): self
    {
        return new self('feature_procedure_message_rog_create');
    }

    public static function feature_procedure_message_rog_update(): self
    {
        return new self('feature_procedure_message_rog_update');
    }

    public static function feature_procedure_message_rog_delete(): self
    {
        return new self('feature_procedure_message_rog_delete');
    }

    public static function feature_procedure_message_kom_create(): self
    {
        return new self('feature_procedure_message_kom_create');
    }

    public static function feature_procedure_message_kom_update(): self
    {
        return new self('feature_procedure_message_kom_update');
    }

    public static function feature_procedure_message_kom_delete(): self
    {
        return new self('feature_procedure_message_kom_delete');
    }

    /**
     * @inheritDoc
     */
    public function getAddonIdentifier(): ?string
    {
        return XBeteiligungAsyncAddon::ADDON_NAME;
    }
}
