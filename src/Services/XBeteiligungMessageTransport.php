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

use DemosEurope\DemosplanAddon\Utilities\Json;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use Exception;
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use PhpAmqpLib\Exception\AMQPTimeoutException;
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
     * Send message to RabbitMQ.
     *
     * @throws AMQPTimeoutException
     * @throws Exception
     */
    public function sendMessage(string $xmlString, string $routingKey): mixed
    {
        $requestId = $this->commonHelpers->uuid();

        $this->logger->info('Send Response to RabbitMQ', [
            'xmlString' => $xmlString,
            'server' => $this->config->rabbitMqExchange,
            'requestId' => $requestId,
            'routingKey' => $routingKey,
            'expiration' => $this->config->requestTimeout,
        ]);

        try {
            $this->client->addRequest(
                $xmlString,
                $this->config->rabbitMqExchange,
                $requestId,
                $routingKey,
                $this->config->requestTimeout
            );
            $replies = $this->client->getReplies();

            $this->logger->info('Replies from RabbitMQ', [$replies]);

            return Json::decodeToMatchingType($replies[$requestId]);
        } catch (Exception $e) {
            $this->logger->error('XBeteiligung Addon - Could not send message to RabbitMQ', [
                $e,
                $e->getMessage(),
                $e->getTraceAsString()
            ]);
            throw $e;
        }
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
}
