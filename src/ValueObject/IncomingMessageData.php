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

/**
 * Data Transfer Object for incoming message data from RabbitMQ
 */
readonly class IncomingMessageData
{
    public function __construct(
        public string $body,
        public ?string $routingKey = null
    ) {
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function getRoutingKey(): ?string
    {
        return $this->routingKey;
    }
}
