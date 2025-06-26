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

    public function getPayload(): string
    {
        return $this->payload;
    }

    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * Check if the response indicates successful procedure creation
     * Success is determined by the absence of error elements in the XML payload
     */
    public function isSuccessful(): bool
    {
        // Check for error indicators in the XML payload
        if (str_contains($this->payload, '<fehler>') || str_contains($this->payload, 'NOK')) {
            return false;
        }
        
        // Check for success indicators
        if (str_contains($this->payload, '<beteiligungsID>') || str_contains($this->payload, 'OK')) {
            return true;
        }
        
        // Default to false if we can't determine success
        return false;
    }
}
