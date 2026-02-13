<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming;


use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;

interface IncomingMessageHandlerInterface
{
    /**
     * Handle an incoming XBeteiligung message.
     *
     * @param string $messageXml The raw XML message content
     * @param bool $auditEnabled Whether audit logging is enabled
     * @param string|null $routingKey The RabbitMQ routing key for the message
     * @return ResponseValue|null Response to send back, or null if no response required
     */
    public function handleIncomingMessage(string $messageXml, bool $auditEnabled, ?string $routingKey): ?ResponseValue;
}
