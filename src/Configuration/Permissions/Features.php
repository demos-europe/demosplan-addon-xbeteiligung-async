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

    public static function feature_create_procedure_message_0301(): self
    {
        return new self('feature_create_procedure_message_0301');
    }

    public static function feature_create_procedure_message_0302(): self
    {
        return new self('feature_create_procedure_message_0302');
    }

    public static function feature_create_procedure_message_0309(): self
    {
        return new self('feature_create_procedure_message_0309');
    }

    public static function feature_create_procedure_message_0401(): self
    {
        return new self('feature_create_procedure_message_0401');
    }

    public static function feature_create_procedure_message_0402(): self
    {
        return new self('feature_create_procedure_message_0402');
    }

    public static function feature_create_procedure_message_0409(): self
    {
        return new self('feature_create_procedure_message_0409');
    }

    /**
     * @inheritDoc
     */
    public function getAddonIdentifier(): ?string
    {
        return XBeteiligungAsyncAddon::ADDON_NAME;
    }
}
