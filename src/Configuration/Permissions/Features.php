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
     * Needed to generate a procedure message of type 0301.
     */
    public static function feature_procedure_message_rog_create(): self
    {
        return new self('feature_procedure_message_rog_create');
    }

    /**
     * Needed to generate a procedure message of type 0302.
     */
    public static function feature_procedure_message_rog_update(): self
    {
        return new self('feature_procedure_message_rog_update');
    }

    /**
     * Needed to generate a procedure message of type 0309.
     */
    public static function feature_procedure_message_rog_delete(): self
    {
        return new self('feature_procedure_message_rog_delete');
    }

    /**
     * Needed to generate a procedure message of type 0401.
     */
    public static function feature_procedure_message_kom_create(): self
    {
        return new self('feature_procedure_message_kom_create');
    }

    /**
     * Needed to generate a procedure message of type 0402.
     */
    public static function feature_procedure_message_kom_update(): self
    {
        return new self('feature_procedure_message_kom_update');
    }

    /**
     * Needed to generate a procedure message of type 0409.
     */
    public static function feature_procedure_message_kom_delete(): self
    {
        return new self('feature_procedure_message_kom_delete');
    }

    /**
     * Needed to generate a procedure message of type 0201.
     */
    public static function feature_procedure_message_pln_create(): self
    {
        return new self('feature_procedure_message_pln_create');
    }

    /**
     * Needed to generate a procedure message of type 0202.
     */
    public static function feature_procedure_message_pln_update(): self
    {
        return new self('feature_procedure_message_pln_update');
    }

    /**
     * Needed to generate a procedure message of type 0209.
     */
    public static function feature_procedure_message_pln_delete(): self
    {
        return new self('feature_procedure_message_pln_delete');
    }

    /**
     * @inheritDoc
     */
    public function getAddonIdentifier(): ?string
    {
        return XBeteiligungAsyncAddon::ADDON_NAME;
    }
}
