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
use Psr\Log\LoggerInterface;

/**
 * Direct consumer that reuses existing RabbitMQ connection
 * from old_sound_rabbit_mq service
 */
class DirectMessageConsumer
{
    public function __construct(
        private readonly RpcClient $rpcClient,
        private readonly string $queueName,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Consume messages directly from queue using basic_get
     *
     * @throws Exception
     */
    public function consume(int $maxMessages, callable $messageHandler): void
    {
        $this->logger->info('Starting direct queue consumption', [
            'queue' => $this->queueName,
            'maxMessages' => $maxMessages
        ]);

        // Poll for messages using basic_get
        for ($i = 0; $i < $maxMessages; $i++) {
            $message = $this->rpcClient->getChannel()->basic_get($this->queueName, true);

            if (null === $message) {
                $this->logger->debug('No more messages in queue.');
                break;
            }

            $this->logger->info('Processing message from queue: '.$this->queueName, [
                'routingKey' => $message->getRoutingKey(),
                'messageBody' => $message->getBody(),
                'messageSize' => $message->getBodySize(),
                'messageProperties' => $message->get_properties()
            ]);

            $messageHandler($message);
        }
    }
}
