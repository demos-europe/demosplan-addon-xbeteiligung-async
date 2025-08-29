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

use Exception;
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * Direct message publisher using AMQP basic_publish instead of RPC pattern
 */
class DirectMessagePublisher
{
    public function __construct(
        private readonly RpcClient $rpcClient,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * Publish message directly to exchange using basic_publish
     *
     * @throws Exception
     */
    public function publish(string $messageBody, string $exchange, string $routingKey): bool
    {
        $this->logger->info('Publishing message to RabbitMQ', [
            'exchange' => $exchange,
            'routingKey' => $routingKey,
        ]);

        try {
            // Create AMQP message
            $message = new AMQPMessage($messageBody, [
                'content_type' => 'application/xml',
                'delivery_mode' => 2 // Make message persistent
            ]);

            // Publish directly to exchange with routing key
            $this->rpcClient->getChannel()->basic_publish($message, $exchange, $routingKey);

            $this->logger->info('Message published successfully', [
                'exchange' => $exchange,
                'routingKey' => $routingKey
            ]);

            return true;

        } catch (Exception $e) {
            $this->logger->error('Failed to publish message to RabbitMQ', [
                'exchange' => $exchange,
                'routingKey' => $routingKey,
                'amqpMessage' => $message ?? null,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }
}
