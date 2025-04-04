<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;

use DemosEurope\DemosplanAddon\Contracts\ValueObject\ValueObjectInterface;

class ValueObject implements ValueObjectInterface
{
    /**
     * Setters can only be used when ValueObject is not locked
     * Getters can only be used when ValueObject is locked.
     */
    private bool $locked = false;

    /**
     * ValueObject needs to be locked in order to read values.
     *
     * @return $this
     */
    public function lock(): ValueObjectInterface
    {
        $this->locked = true;

        return $this;
    }
}
