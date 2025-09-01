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
    protected string $messageXml;

    protected string $messageStringIdentifier;

    /** Procedure ID created during message processing */
    protected ?string $procedureId = null;

    protected ?string $auditId = null;

    public function getMessageXml(): string
    {
        return $this->messageXml;
    }

    public function setMessageXml(string $messageXml): void
    {
        $this->messageXml = $messageXml;
    }

    public function getMessageStringIdentifier(): string
    {
        return $this->messageStringIdentifier;
    }

    public function setMessageStringIdentifier(string $messageStringIdentifier): void
    {
        $this->messageStringIdentifier = $messageStringIdentifier;
    }

    public function getProcedureId(): ?string
    {
        return $this->procedureId;
    }

    public function setProcedureId(?string $procedureId): void
    {
        $this->procedureId = $procedureId;
    }

    public function getAuditId(): ?string
    {
        return $this->auditId;
    }

    public function setAuditId(?string $auditId): void
    {
        $this->auditId = $auditId;
    }
}
