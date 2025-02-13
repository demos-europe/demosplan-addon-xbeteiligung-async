<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;



use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ValueObject;

class XtaResponseValue extends ValueObject
{
    /** XML string */
    protected string $payload;

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }
}