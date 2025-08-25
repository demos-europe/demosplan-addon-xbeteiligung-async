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
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerInterface;

/**
 * Direct consumer that reuses existing RabbitMQ connection
 * from old_sound_rabbit_mq service
 */
class DirectMessageConsumer
{
    private ?object $channel = null;

    public function __construct(
        private readonly RpcClient $rpcClient,
        private readonly string $queueName,
        private readonly XBeteiligungConfiguration $config,
        private readonly LoggerInterface $logger,
    ) {}
    
    /**
     * Consume messages directly from queue using basic_get
     */
    public function consume(int $maxMessages, callable $messageHandler): void
    {
        $this->logger->info('Starting direct queue consumption', [
            'queue' => $this->queueName,
            'maxMessages' => $maxMessages
        ]);
        
        $processedCount = 0;
        
        try {
            $this->establishChannel();
            
            // Poll for messages using basic_get (non-blocking)
            for ($i = 0; $i < $maxMessages; $i++) {
                $message = $this->channel->basic_get($this->queueName);
                
                if (null === $message) {
                    $this->logger->debug('No more messages in queue');
                    break;
                }
                
                try {
                    $this->logger->info('Processing message from queue', [
                        'messageTag' => $message->getDeliveryTag(),
                        'routingKey' => $message->getRoutingKey(),
                        'bodyLength' => strlen($message->getBody()),
                        'correlationId' => $message->get('correlation_id'),
                        'replyTo' => $message->get('reply_to')
                    ]);
                    
                    // Call the message handler
                    $success = $messageHandler($message);
                    
                    if ($success) {
                        // ACK the message
                        $this->channel->basic_ack($message->getDeliveryTag());
                        $this->logger->debug('Message ACKed');
                        $processedCount++;
                    } else {
                        // NACK the message (requeue it)
                        $this->channel->basic_nack($message->getDeliveryTag(), false, true);
                        $this->logger->debug('Message NACKed and requeued');
                    }
                    
                } catch (\Exception $e) {
                    $this->logger->error('Message processing failed', [
                        'error' => $e->getMessage(),
                        'messageTag' => $message->getDeliveryTag() ?? 'unknown',
                        'trace' => $e->getTraceAsString()
                    ]);
                    
                    // NACK on error (requeue for retry)
                    if ($message->getDeliveryTag()) {
                        $this->channel->basic_nack($message->getDeliveryTag(), false, true);
                    }
                }
            }
            
        } catch (\Exception $e) {
            $this->logger->error('Direct queue consumption failed', [
                'queue' => $this->queueName,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        } finally {
            $this->closeChannel();
        }
        
        $this->logger->info('Direct consumption finished', [
            'processedMessages' => $processedCount
        ]);
    }

    /**
     * Send reply directly to specified queue
     */
    public function sendDirectReply(AMQPMessage $originalMessage, array $responseData): void
    {
        try {
            $replyTo = $originalMessage->get('reply_to');
            $correlationId = $originalMessage->get('correlation_id');
            
            if (empty($replyTo)) {
                $this->logger->debug('No reply_to queue specified, skipping reply');
                return;
            }
            
            $responseJson = json_encode($responseData);
            
            // Create reply message
            $replyMessage = new AMQPMessage($responseJson, [
                'correlation_id' => $correlationId,
                'content_type' => 'application/json'
            ]);
            
            // Publish reply directly to the reply queue
            $this->channel->basic_publish($replyMessage, '', $replyTo);
            
            $this->logger->info('Reply sent successfully', [
                'replyTo' => $replyTo,
                'correlationId' => $correlationId,
                'responseSize' => strlen($responseJson)
            ]);
            
        } catch (\Exception $e) {
            $this->logger->error('Failed to send reply', [
                'error' => $e->getMessage(),
                'replyTo' => $originalMessage->get('reply_to') ?? 'unknown'
            ]);
            throw $e;
        }
    }

    /**
     * Get channel from existing RPC client connection
     */
    private function establishChannel(): void
    {
        try {
            // Access the connection from the existing RpcClient
            $reflection = new \ReflectionClass($this->rpcClient);
            $connectionProperty = $reflection->getProperty('connection');
            $connectionProperty->setAccessible(true);
            $connection = $connectionProperty->getValue($this->rpcClient);
            
            if (null === $connection) {
                throw new \RuntimeException('RpcClient connection is not established');
            }
            
            // Create a new channel from the existing connection
            $this->channel = $connection->channel();
            
            // Declare queue to ensure it exists (idempotent operation)
            $this->channel->queue_declare($this->queueName, false, true, false, false);
            
            $this->logger->debug('Channel established for direct consumption', [
                'queue' => $this->queueName
            ]);
            
        } catch (\Exception $e) {
            $this->logger->error('Failed to establish channel', [
                'queue' => $this->queueName,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Close the channel (but keep connection alive for RpcClient)
     */
    private function closeChannel(): void
    {
        try {
            if ($this->channel) {
                $this->channel->close();
                $this->channel = null;
                $this->logger->debug('Channel closed');
            }
            
        } catch (\Exception $e) {
            $this->logger->warning('Error closing channel', [
                'error' => $e->getMessage()
            ]);
        }
    }

    public function __destruct()
    {
        $this->closeChannel();
    }
}