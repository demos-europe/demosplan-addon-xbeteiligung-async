<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tools;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Exception\JsonException;
use DemosEurope\DemosplanAddon\Utilities\Json;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use Psr\Log\LoggerInterface;

class GetMessageRabbitMQ
{
    protected RpcClient $client;

    public function __construct(
        private readonly GlobalConfigInterface $globalConfig,
        private readonly LoggerInterface $logger,
        private readonly string $rabbitMqQueueName,
        private readonly XBeteiligungService $xBeteiligungService,
    ) {
    }

    /**
     * @throws JsonException
     */
    public function getMessages(): void
    {
        $routingKey = $this->globalConfig->getProjectPrefix();
        if ($this->globalConfig->isMessageQueueRoutingDisabled()) {
            $routingKey = '';
        }
        $this->client->addRequest('', $this->rabbitMqQueueName, 'XBeteiligung_xtaGet', $routingKey, 300);
        $replies = $this->client->getReplies();
        $result = Json::decodeToArray($replies['XBeteiligung_xtaGet']);
        $this->logger->info('Got response from XTA-Service', [$result]);
        foreach ($result as $message) {
            $this->logger->info('Process message', [$message]);
            try {
                $xtaResponseObject = $this->xBeteiligungService->determineMessageContextAndDelegateAction($message);
                $this->sendWithXtaToBroker($xtaResponseObject->getPayload());
            } catch (InvalidArgumentException $e) {
                $this->logger->error('Message payload not supported', [$e]);
            } catch (SchemaException $e) {
                $this->logger->error('Incoming cockpit Message could not be parsed', [$e]);
            } catch (Exception $e) {
                $this->logger->error(
                    'XBeteiligung Plugin - Could not execute
                    (new procedure)401/411/421/301/311/321/201/211/221 |
                    (delete procedure)409/419/429/309/319/329/209/219/229 |: ', [$e, $e->getTraceAsString()]
                );
            }
        }
    }

    /**
     * Send the the message that a procedure was created over the rabbit to the XTA java client to be send to the
     * configured message broker.
     *
     * @throws AMQPTimeoutException
     * @throws Exception
     */
    protected function sendWithXtaToBroker(string $xmlString, int $expiration = 300): bool
    {
        $routingKey = $this->globalConfig->getProjectPrefix();
        if ($this->globalConfig->isMessageQueueRoutingDisabled()) {
            $routingKey = '';
        }
        $this->logger->info('Send Response to XTA', [$xmlString]);
        $this->client->addRequest(
            $xmlString,
            'xtaSendDemosPlan',
            'XBeteiligung_xtaSend',
            $routingKey,
            $expiration
        );
        $replies = $this->client->getReplies();

        $this->logger->info('Replies from XTA', [$replies]);

        return Json::decodeToMatchingType($replies['XBeteiligung_xtaSend']);
    }

    /**
     * @param RpcClient $client
     */
    public function setClient(RpcClient $client): void
    {
        $this->client = $client;
    }

}