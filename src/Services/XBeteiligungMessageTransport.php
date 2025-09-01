<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Services;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use Exception;
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use Psr\Log\LoggerInterface;

class XBeteiligungMessageTransport
{
    private RpcClient $client;

    public function __construct(
        private readonly XBeteiligungConfiguration $config,
        private readonly CommonHelpers $commonHelpers,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function setClient(RpcClient $client): void
    {
        $this->client = $client;
    }


    /**
     * Create a direct consumer for specific queue
     */
    public function createDirectConsumer(string $queueName): DirectMessageConsumer
    {
        return new DirectMessageConsumer(
            $this->client,
            $queueName,
            $this->logger
        );
    }

    /**
     * Create a direct publisher for outgoing messages
     */
    public function createDirectPublisher(): DirectMessagePublisher
    {
        return new DirectMessagePublisher(
            $this->client,
            $this->logger
        );
    }

    /**
     * Publish message directly to exchange using basic_publish instead of RPC
     *
     * @throws Exception
     */
    public function publishDirectMessage(string $xmlString, string $routingKey): bool
    {
        return $this->createDirectPublisher()->publish($xmlString, $this->config->rabbitMqExchange, $routingKey);
    }
}
