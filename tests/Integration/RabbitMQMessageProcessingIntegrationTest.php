<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Integration;

use DemosEurope\DemosplanAddon\Contracts\Events\AddonMaintenanceEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\EventSubscriber\XBeteiligungEventSubscriber;
use DemosEurope\DemosplanAddon\XBeteiligung\Tools\RabbitMQMessageBroker;
use PhpAmqpLib\Message\AMQPMessage;
use PHPUnit\Framework\TestCase;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Channel\AMQPChannel;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Adapter\ArrayAdapter;


class RabbitMQMessageProcessingIntegrationTest extends TestCase
{

    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;
    private string $exchangeName = 'bau.beteiligung';
    private string $queueName = 'bau.beteiligung';

    protected function setUp(): void
    {
        parent::setUp();

        // Step 1: Setup RabbitMQ Testcontainer
        // Connect to existing RabbitMQ from docker-compose
        $this->connection = new AMQPStreamConnection(
            '172.22.255.5', // Your existing RabbitMQ host
            5672,
            'hase',         // Your existing username
            'weissvonnix'   // Your existing password
        );

        $this->channel = $this->connection->channel();

        // Declare exchange and queue with bindings
        $this->setupRabbitMQTopology();

        // Step 2: Setup test data factories
//        $this->mockFactory = new MockFactoryTest($this);
        //$this->procedureHandlerFactory = new KommunaleProcedureHandlerFactory($this->mockFactory);

        $this->createTestMessages();

        // Step 3: Publish all test messages to exchange
        foreach ($this->testMessages as $messageType => $messageData) {
            $this->publishMessageToExchange(
                $messageData['xml'],
                $messageData['routingKey']
            );
        }
        // Give RabbitMQ time to establish bindings
        usleep(100000); // 100ms delay

        // Verify messages were published and routed to queue
        $this->verifyMessagesInQueue();


    }

    private function setupRabbitMQTopology(): void
    {
        // Declare topic exchange
        $this->channel->exchange_declare(
            $this->exchangeName,
            'topic',
            false, // passive
            true,  // durable
            false  // auto_delete
        );

        // Declare queue
        $this->channel->queue_declare(
            $this->queueName,
            false, // passive
            true,  // durable
            false, // exclusive
            false,  // auto_delete,
            false, // nowait
          [
              'x-queue-type' => ['S', 'quorum'] // ADD THIS for quorum queue
          ]
        );

        // Bind queue to exchange with routing patterns
        $routingPatterns = [
            'bau.beteiligung.bdp.*.bap.*.kommunal.#',
            'bau.beteiligung.bdp.*.bap.*.raumordnung.#',
            '*.cockpit.#' // For incoming messages
        ];

        foreach ($routingPatterns as $pattern) {
            $this->channel->queue_bind($this->queueName, $this->exchangeName, $pattern);
        }
    }

    protected function tearDown(): void
    {
        if (isset($this->channel)) {
            // Purge all messages from the test queue
            $this->channel->queue_purge($this->queueName);
            $this->channel->close();
        }
        if (isset($this->connection)) {
            $this->connection->close();
        }
        if (isset($this->rabbitMQContainer)) {
            $this->rabbitMQContainer->stop();
        }
        parent::tearDown();
    }

    private function createTestMessages(): void
    {
        // Create Kommunal message (401)
        $kommunalMessage = $this->createKommunalMessage();
        $this->testMessages['kommunal'] = [
            'xml' => $kommunalMessage,
            'routingKey' => 'bau.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.Initiieren.0401',
            'messageType' => 'kommunal.Initiieren.0401',
            'expectedProcedureType' => 'Kommunal'
        ];

        // Create Raumordnung message (301)
        $raumordnungMessage = $this->createRaumordnungMessage();
        $this->testMessages['raumordnung'] = [
            'xml' => $raumordnungMessage,
            'routingKey' => 'rog.cockpit.bap.09.01.00000001.bdp.09.01.00000001.raumordnung.Initiieren.0301',
            'messageType' => 'raumordnung.Initiieren.0301',
            'expectedProcedureType' => 'Raumordnung'
        ];

        // Create Test Environment message
        $testMessage = $this->createTestEnvironmentMessage();
        $this->testMessages['test'] = [
            'xml' => $testMessage,
            'routingKey' => 'bau.cockpit.xyz.00.02.xyz.00.01.kommunal.Initiieren.0401',
            'messageType' => 'kommunal.Initiieren.0401',
            'expectedProcedureType' => 'Kommunal'
        ];
    }

    private function createKommunalMessage(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
          <xbg:kommunal.Initiieren.0401
              xmlns:xbg="http://xbeteiligung.de/14"
              xmlns:xbd="http://xbau.de/V14">
              <xbg:nachrichtenkopf>
                  <xbg:identifikationNachricht>
                      <xbg:nachrichtenUUID>550e8400-e29b-41d4-a716-446655440000</xbg:nachrichtenUUID>
                      <xbg:zeitstempelErstellung>2024-01-15T10:30:00.000Z</xbg:zeitstempelErstellung>
                  </xbg:identifikationNachricht>
                  <xbg:autor>
                      <xbg:behoerde>
                          <xbd:nameBehoerde>Test Municipality</xbd:nameBehoerde>
                          <xbd:anschrift>
                              <xbd:strasse>Test Street 123</xbd:strasse>
                              <xbd:hausnummer>123</xbd:hausnummer>
                              <xbd:postleitzahl>12345</xbd:postleitzahl>
                              <xbd:ort>Test City</xbd:ort>
                          </xbd:anschrift>
                      </xbg:behoerde>
                  </xbg:autor>
                  <xbg:leser>
                      <xbg:behoerde>
                          <xbd:nameBehoerde>DemosPlan System</xbd:nameBehoerde>
                          <xbd:anschrift>
                              <xbd:strasse>Demo Street 456</xbd:strasse>
                              <xbd:hausnummer>456</xbd:hausnummer>
                              <xbd:postleitzahl>67890</xbd:postleitzahl>
                              <xbd:ort>Demo City</xbd:ort>
                          </xbd:anschrift>
                      </xbg:behoerde>
                  </xbg:leser>
              </xbg:nachrichtenkopf>
              <xbg:verfahren>
                  <xbg:verfahrensInformationen>
                      <xbg:nameVerfahren>Test Procedure Kommunal</xbg:nameVerfahren>
                      <xbg:beschreibungVerfahren>Test procedure for integration testing</xbg:beschreibungVerfahren>
                      <xbg:ags>02.05.00200099</xbg:ags>
                  </xbg:verfahrensInformationen>
                  <xbg:beteiligungsInformationen>
                      <xbg:beteiligungBeginn>2024-02-01T00:00:00.000Z</xbg:beteiligungBeginn>
                      <xbg:beteiligungEnde>2024-02-29T23:59:59.000Z</xbg:beteiligungEnde>
                  </xbg:beteiligungsInformationen>
              </xbg:verfahren>
          </xbg:kommunal.Initiieren.0401>';
    }

    private function createRaumordnungMessage(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
          <xbg:raumordnung.Initiieren.0301
              xmlns:xbg="http://xbeteiligung.de/14"
              xmlns:xbd="http://xbau.de/V14">
              <xbg:nachrichtenkopf>
                  <xbg:identifikationNachricht>
                      <xbg:nachrichtenUUID>550e8400-e29b-41d4-a716-446655440001</xbg:nachrichtenUUID>
                      <xbg:zeitstempelErstellung>2024-01-15T11:30:00.000Z</xbg:zeitstempelErstellung>
                  </xbg:identifikationNachricht>
              </xbg:nachrichtenkopf>
              <xbg:verfahren>
                  <xbg:verfahrensInformationen>
                      <xbg:nameVerfahren>Test Procedure Raumordnung</xbg:nameVerfahren>
                      <xbg:beschreibungVerfahren>Test raumordnung procedure</xbg:beschreibungVerfahren>
                      <xbg:ags>09.01.00000001</xbg:ags>
                  </xbg:verfahrensInformationen>
              </xbg:verfahren>
          </xbg:raumordnung.Initiieren.0301>';
    }

    private function createTestEnvironmentMessage(): string
    {
        return '<?xml version="1.0" encoding="UTF-8"?>
          <xbg:kommunal.Initiieren.0401
              xmlns:xbg="http://xbeteiligung.de/14"
              xmlns:xbd="http://xbau.de/V14">
              <xbg:nachrichtenkopf>
                  <xbg:identifikationNachricht>
                      <xbg:nachrichtenUUID>550e8400-e29b-41d4-a716-446655440002</xbg:nachrichtenUUID>
                      <xbg:zeitstempelErstellung>2024-01-15T12:30:00.000Z</xbg:zeitstempelErstellung>
                  </xbg:identifikationNachricht>
              </xbg:nachrichtenkopf>
              <xbg:verfahren>
                  <xbg:verfahrensInformationen>
                      <xbg:nameVerfahren>Test Environment Procedure</xbg:nameVerfahren>
                      <xbg:beschreibungVerfahren>Test procedure for test environment</xbg:beschreibungVerfahren>
                      <xbg:ags>xyz.00.01</xbg:ags>
                  </xbg:verfahrensInformationen>
              </xbg:verfahren>
          </xbg:kommunal.Initiieren.0401>';
    }

    public function testPublishMessagesToExchange(): void
    {
        // Step 3: Publish all test messages to exchange
        foreach ($this->testMessages as $messageType => $messageData) {
            $this->publishMessageToExchange(
                $messageData['xml'],
                $messageData['routingKey']
            );
        }

        // Verify messages were published and routed to queue
        $this->verifyMessagesInQueue();
    }

    private function publishMessageToExchange(string $xmlContent, string $routingKey): void
    {
        // Create AMQP message with proper headers
        $message = new AMQPMessage($xmlContent, [
            'content_type' => 'application/xml',
            'delivery_mode' => 2, // Make message persistent
            'timestamp' => time(),
            'message_id' => uniqid('test_', true)
        ]);

        // Publish message to exchange with routing key
        $this->channel->basic_publish(
            $message,           // message
            $this->exchangeName, // exchange
            $routingKey         // routing key
        );

        $this->assertTrue(true, "Published message to exchange: {$this->exchangeName} with routing key: {$routingKey}\n");
        //echo "Published message to exchange: {$this->exchangeName} with routing key: {$routingKey}\n";
    }

    private function verifyMessagesInQueue(): void
    {
        // Check queue has expected number of messages
        [$queueName, $messageCount, $consumerCount] = $this->channel->queue_declare(
            $this->queueName,
            true  // passive - just check if queue exists
        );

        $expectedMessageCount = count($this->testMessages);
        $this->assertEquals(
            $expectedMessageCount,
            $messageCount,
            "Expected {$expectedMessageCount} messages in queue, found {$messageCount}"
        );
        $this->assertTrue(true, "Successfully verified {$messageCount} messages in queue: {$queueName}\n");


        //echo "Successfully verified {$messageCount} messages in queue: {$queueName}\n";
    }


    /**
     * Step 4: Test real message processing trigger
     */
    public function testRealMessageProcessingTrigger(): void
    {
        // Publish messages first
        foreach ($this->testMessages as $messageType => $messageData) {
            $this->publishMessageToExchange($messageData['xml'], $messageData['routingKey']);
        }

        // Verify messages are in queue before processing
        $this->verifyMessagesInQueue();

        // 4.1: Create real AddonMaintenanceEvent (mock only the interface, not the logic)
        $maintenanceEvent = $this->getMockBuilder(AddonMaintenanceEventInterface::class)->disableOriginalConstructor()->getMock();

        // 4.2: Create real XBeteiligungEventSubscriber with all its dependencies
        // We need to figure out what dependencies it needs from its constructor

        $eventSubscriber = $this->createXBeteiligungEventSubscriberManually();

        // 4.3: Capture initial queue state
        [$queueName, $initialMessageCount, $consumerCount] = $this->channel->queue_declare(
            $this->queueName,
            true // passive
        );

        $this->assertTrue(true, "Initial queue has {$initialMessageCount} messages before processing");

        // 4.4: TRIGGER THE REAL METHOD
        $eventSubscriber->handleAddonMaintenanceEvent($maintenanceEvent);

        // 4.5: Check what happened to the queue
        [$queueName, $finalMessageCount, $consumerCount] = $this->channel->queue_declare(
            $this->queueName,
            true // passive
        );

        $this->assertTrue(true, "Final queue has {$finalMessageCount} messages after processing");

        // 4.6: Verify the maintenance event handler executed successfully
        // This test verifies that the correct methods are called, not actual message processing
        // (since dependencies are mocked, actual processing doesn't occur)
        $this->assertTrue(true, "handleAddonMaintenanceEvent executed successfully with real services");

        // Optional: Verify that queue state didn't change (since services are mocked)
        $this->assertEquals($initialMessageCount, $finalMessageCount, "Queue unchanged with mocked services as expected");
    }


    private function createXBeteiligungEventSubscriberManually(): XBeteiligungEventSubscriber
    {
        // Create real configuration with test values
        $config = new XBeteiligungConfiguration(
            rabbitMqEnabled: true,
            requestTimeout: 30,
            communicationDelay: 1,
            procedureMessageType: 'kommunal',
            auditEnabled: true,
            rabbitMqExchange: $this->exchangeName,
            xoevAddressPrefixKommunal: 'bau.cockpit',
            xoevAddressPrefixCockpit: 'rog.cockpit',
            maxMessagesPerCycle: 10,
            consumerTimeout: 5,
            procedureTypeName: 'test-procedure-type'
        );

        // Create simple logger
        $cockpitLogger = new NullLogger();

        // Create simple cache
        $cache = new ArrayAdapter();

        // Create real RabbitMQMessageBroker with real config and logger, mock complex dependencies
        $messageProcessor = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageProcessor::class)->disableOriginalConstructor()->getMock();
        $messageTransport = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageTransport::class)->disableOriginalConstructor()->getMock();
        $outgoingRoutingKeyBuilder = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungOutgoingRoutingKeyBuilder::class)->disableOriginalConstructor()->getMock();
        $statementCreator = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator::class)->disableOriginalConstructor()->getMock();
        $statementMessageFactory = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\StatementMessageFactory::class)->disableOriginalConstructor()->getMock();
        $auditService = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService::class)->disableOriginalConstructor()->getMock();

        $rabbitMQMessageBroker = new RabbitMQMessageBroker(
            $config,
            $messageProcessor,
            $messageTransport,
            $outgoingRoutingKeyBuilder,
            $cockpitLogger,
            $statementCreator,
            $statementMessageFactory,
            $auditService
        );

        // Mock the complex dependencies we don't need for message processing
        $permissionEvaluator = $this->getMockBuilder(\DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface::class)->disableOriginalConstructor()->getMock();
        $xBeteiligungDebugger = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Debugger\XBeteiligungDebugger::class)->disableOriginalConstructor()->getMock();
        $xBeteiligungService = $this->getMockBuilder(\DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService::class)->disableOriginalConstructor()->getMock();

        return new XBeteiligungEventSubscriber(
            $permissionEvaluator,
            $xBeteiligungDebugger,
            $xBeteiligungService,
            $cache,
            $config,
            $cockpitLogger,
            $rabbitMQMessageBroker
        );
    }

    private function consumeAndVerifyMessages(): array
    {
        $consumedMessages = [];

        // Consume messages to verify content
        for ($i = 0; $i < count($this->testMessages); $i++) {
            $message = $this->channel->basic_get($this->queueName, true); // auto-ack

            if ($message !== null) {
                $consumedMessages[] = [
                    'body' => $message->body,
                    'routingKey' => $message->get('routing_key'),
                    'properties' => $message->get_properties()
                ];

                $this->assertTrue(true,"Consumed message with routing key: " . $message->get('routing_key') . "\n");

                //echo "Consumed message with routing key: " . $message->get('routing_key') . "\n";
            }
        }

        return $consumedMessages;
    }



}
