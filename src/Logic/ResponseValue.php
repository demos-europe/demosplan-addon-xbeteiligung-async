<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;



use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ValueObject;

class ResponseValue extends ValueObject
{
    /** XML string */
    protected string $payload;

    /** Procedure ID created during message processing */
    protected ?string $procedureId = null;

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    public function getProcedureId(): ?string
    {
        return $this->procedureId;
    }

    public function setProcedureId(?string $procedureId): void
    {
        $this->procedureId = $procedureId;
    }
}
