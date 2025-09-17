<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Integration;

use DemosEurope\DemosplanAddon\Contracts\Events\AddonMaintenanceEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\EventSubscriber\XBeteiligungEventSubscriber;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageTransport;
use DemosEurope\DemosplanAddon\XBeteiligung\Tools\RabbitMQMessageBroker;
use OldSound\RabbitMqBundle\RabbitMq\RpcClient;
use demosplan\DemosPlanCoreBundle\Tests\Integration\AddonIntegrationTestInterface;
use demosplan\DemosPlanCoreBundle\Tests\Integration\AddonTestResult;
use Doctrine\ORM\EntityManagerInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\NullLogger;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Abstract base class for XBeteiligung integration tests.
 *
 * Contains all the common logic for setting up RabbitMQ connections,
 * running the actual service chain, and database interactions.
 *
 * Concrete implementations must define:
 * - Which test scenarios to run
 * - How to validate the results
 * - The test name/description
 */
abstract class AbstractXBeteiligungIntegrationTestService implements AddonIntegrationTestInterface
{
    protected ?AMQPStreamConnection $connection = null;
    protected ?AMQPChannel $channel = null;
    protected string $exchangeName = 'bau.beteiligung';
    protected string $queueName = 'bau.beteiligung';

    protected ?\DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory $xmlFactory = null;

    public function getAddonName(): string
    {
        return 'XBeteiligung';
    }

    /**
     * Get the specific test name for this integration test.
     */
    abstract public function getTestName(): string;

    /**
     * Get the scenarios to test in this integration test.
     *
     * @return array Array of [scenarioName, isValid] pairs
     * Example: [['quickborn_minimal', true], ['unknown_organization', false]]
     */
    abstract protected function getTestScenarios(): array;

    /**
     * Validate the test results based on the expected behavior.
     *
     * @param int $initialCount Number of procedures before processing
     * @param int $finalCount Number of procedures after processing
     * @param string|null $auditId The audit ID created during processing
     * @return AddonTestResult The test result
     */
    abstract protected function validateTestResult(int $initialCount, int $finalCount, ?string $auditId): AddonTestResult;

    public function setupTestData(ContainerInterface $container): void
    {
        $this->loadXmlFactory();
        $this->setupRabbitMQConnection();
        $this->setupRabbitMQTopology();
        $this->publishTestMessages();

        echo "📤 Published test messages to RabbitMQ\n";
    }

    public function runIntegrationTest(ContainerInterface $container): AddonTestResult
    {
        // Get REAL services from Symfony container - NO MOCKS!
        $realEventSubscriber = $container->get(XBeteiligungEventSubscriber::class);
        $entityManager = $container->get(EntityManagerInterface::class);

        // Configure the RabbitMQ transport to use the same connection as our test
        $this->configureRabbitMQTransport($container);

        // Count procedures before processing
        $initialCount = $this->getProcedureCount($entityManager);
        echo "📊 Initial procedure count: {$initialCount}\n";

        // Check queue and process messages
        $messageCount = $this->channel->queue_declare($this->queueName, true, true, false, false)[1];
        echo "📊 Messages in queue before processing: {$messageCount}\n";

        // Initialize variables
        $auditId = null;

        try {
            // Start database transaction for the entire process
            $entityManager->getConnection()->beginTransaction();
            echo "🔄 Started database transaction\n";

            // Create maintenance event to trigger message processing (like production)
            $maintenanceEvent = new class implements AddonMaintenanceEventInterface {
                public function getAddonName(): string { return 'XBeteiligung'; }
            };

            echo "🚀 Calling REAL XBeteiligung event processing...\n";
            echo "🎯 Calling XBeteiligungEventSubscriber::handleAddonMaintenanceEvent()\n";

            // This is the REAL production flow - event subscriber processes all messages
            $realEventSubscriber->handleAddonMaintenanceEvent($maintenanceEvent);

            echo "✅ Event subscriber processing completed\n";

            // Check if messages were processed from the queue
            $messageCountAfterEvent = $this->channel->queue_declare($this->queueName, true, true, false, false)[1];
            echo "📊 Messages in queue after event processing: {$messageCountAfterEvent}\n";

            // Look for audit entries created during event processing
            echo "🔍 Searching for audit entries created during event processing...\n";
            $auditId = $this->findLatestAuditEntry($entityManager);

            if ($auditId) {
                echo "✅ Found audit entry from event processing: {$auditId}\n";
            }

            // Commit transaction
            $entityManager->flush();
            $entityManager->getConnection()->commit();
            echo "💾 Flushed and committed database changes\n";

        } catch (\Exception $e) {
            // Roll back on error
            if ($entityManager->getConnection()->isTransactionActive()) {
                $entityManager->getConnection()->rollBack();
            }

            return new AddonTestResult(
                false,
                "Exception during event processing: " . $e->getMessage(),
                ['exception' => $e->getMessage(), 'file' => $e->getFile(), 'line' => $e->getLine()]
            );
        }

        // Count procedures after processing
        $finalCount = $this->getProcedureCount($entityManager);
        echo "📊 Final procedure count: {$finalCount}\n";

        $proceduresCreated = $finalCount - $initialCount;
        echo "✨ Procedures created by REAL services: {$proceduresCreated}\n";

        // Verify audit entry was created if we have an audit ID
        if ($auditId && !$this->verifyAuditEntry($entityManager, $auditId)) {
            return new AddonTestResult(
                false,
                "Expected audit entry to be created for message processing",
                ['audit_id' => $auditId],
                $auditId,
                $initialCount,
                $finalCount
            );
        }

        echo "📝 Audit entry exists: " . ($auditId ? 'YES' : 'NO') . "\n";

        // Let concrete implementation validate the specific results
        return $this->validateTestResult($initialCount, $finalCount, $auditId);
    }

    public function cleanupTestData(ContainerInterface $container): void
    {
        if (isset($this->channel)) {
            $this->channel->queue_purge($this->queueName);
            $this->channel->close();
        }
        if (isset($this->connection)) {
            $this->connection->close();
        }
        echo "🧹 Cleaned up RabbitMQ connections\n";
    }

    /**
     * Load the XML factory for dynamic test data generation.
     */
    protected function loadXmlFactory(): void
    {
        // Manually require the factory file (following the same pattern as integration test loading)
        $factoryFile = __DIR__ . '/../DataFactory/XBeteiligung401TestFactory.php';

        if (!class_exists('DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory')) {
            if (!file_exists($factoryFile)) {
                throw new RuntimeException("Factory file not found: {$factoryFile}");
            }

            echo "📁 Manually requiring XBeteiligung401TestFactory\n";
            require_once $factoryFile;

            if (!class_exists('DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory')) {
                echo "❌ Available classes after require:\n";
                $classes = get_declared_classes();
                foreach ($classes as $class) {
                    if (strpos($class, 'XBeteiligung') !== false) {
                        echo "  - {$class}\n";
                    }
                }
                throw new RuntimeException("Factory class not available after requiring file");
            }
            echo "✅ Factory class loaded successfully\n";
        }

        // Initialize XML factory for dynamic test data generation
        $commonHelpers = new CommonHelpers(new NullLogger());
        $this->xmlFactory = new \DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory(
            __DIR__ . '/../..',  // Point to the addon root directory
            $commonHelpers
        );
    }

    /**
     * Setup RabbitMQ connection.
     */
    protected function setupRabbitMQConnection(): void
    {
        $this->connection = new AMQPStreamConnection(
            '172.22.255.5', // RabbitMQ host from docker-compose
            5672,
            'hase',        // RabbitMQ username
            'weissvonnix'  // RabbitMQ password
        );
        $this->channel = $this->connection->channel();
    }

    /**
     * Setup RabbitMQ topology (exchanges, queues, bindings).
     */
    protected function setupRabbitMQTopology(): void
    {
        $this->channel->exchange_declare($this->exchangeName, 'topic', false, true, false);
        $this->channel->queue_declare($this->queueName, false, true, false, false, false, [
            'x-queue-type' => ['S', 'quorum']
        ]);

        $routingPatterns = [
            'bau.beteiligung.bdp.*.bap.*.kommunal.#',
            'bau.beteiligung.bdp.*.bap.*.raumordnung.#',
            '*.cockpit.#'
        ];

        foreach ($routingPatterns as $pattern) {
            $this->channel->queue_bind($this->queueName, $this->exchangeName, $pattern);
        }
    }

    /**
     * Publish test messages based on configured scenarios.
     */
    protected function publishTestMessages(): void
    {
        $scenarios = $this->getTestScenarios();

        foreach ($scenarios as [$scenarioName, $isValid]) {
            echo "📤 Publishing test scenario: {$scenarioName} (valid: " . ($isValid ? 'YES' : 'NO') . ")\n";

            $xml = $this->xmlFactory->createXML($scenarioName, $isValid);

            // Get scenario info for debugging
            $scenarioInfo = $this->xmlFactory->getScenarioInfo($scenarioName, $isValid);
            echo "   Description: {$scenarioInfo['description']}\n";
            if (!$isValid && isset($scenarioInfo['expected_error'])) {
                echo "   Expected error: {$scenarioInfo['expected_error']}\n";
            }

            $this->publishMessage($xml, $scenarioName);
        }

        usleep(100000); // 100ms delay after all messages
    }

    /**
     * Publish a single message to RabbitMQ.
     */
    protected function publishMessage(string $xml, string $debugLabel): void
    {
        $message1 = new AMQPMessage($xml, ['delivery_mode' => 2]);
        $this->channel->basic_publish(
            $message1,
            $this->exchangeName,
            'bau.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.Initiieren.0401'
        );

        $message2 = new AMQPMessage($xml, ['delivery_mode' => 2]);
        $this->channel->basic_publish(
            $message2,
            $this->exchangeName,
            'bau.cockpit.xyz.00.02.xyz.00.01.kommunal.Initiieren.0401'
        );
    }

    /**
     * Configure the RabbitMQ transport service to use the same connection as our test.
     */
    protected function configureRabbitMQTransport(ContainerInterface $container): void
    {
        echo "🔧 Configuring RabbitMQ transport to use test connection...\n";

        // Get the transport service and message broker
        $transport = $container->get(XBeteiligungMessageTransport::class);
        $messageBroker = $container->get(RabbitMQMessageBroker::class);

        // Create a mock RpcClient that uses our test connection
        $rpcClient = new class($this->connection, $this->channel) extends RpcClient {
            private AMQPStreamConnection $testConnection;
            private AMQPChannel $testChannel;

            public function __construct(AMQPStreamConnection $connection, AMQPChannel $channel)
            {
                $this->testConnection = $connection;
                $this->testChannel = $channel;
                // Don't call parent constructor to avoid creating another connection
            }

            public function getChannel(): AMQPChannel
            {
                return $this->testChannel;
            }

            public function addRequest($msgBody, $server, $requestId = null, $routingKey = '', $expiration = 0): void
            {
                // Implementation not needed for our test
            }

            public function getReplies(): array
            {
                // Implementation not needed for our test
                return [];
            }

            protected function getReply($msgBody, $requestId)
            {
                // Implementation not needed for our test
                return null;
            }
        };

        // Configure the transport to use our test RPC client
        $transport->setClient($rpcClient);
        $messageBroker->setClient($rpcClient);

        echo "✅ RabbitMQ transport configured with test connection\n";
    }

    /**
     * Get the current count of procedures in the database.
     */
    protected function getProcedureCount(EntityManagerInterface $entityManager): int
    {
        try {
            $connection = $entityManager->getConnection();
            $result = $connection->executeQuery('SELECT COUNT(*) as count FROM _procedure')->fetchAssociative();

            // Debug: Show recent procedures and check for very recent ones
            echo "🔍 Debug: Recent procedures in database:\n";
            $recent = $connection->executeQuery(
                'SELECT _p_id, _p_name, _o_name, _p_created_date FROM _procedure ORDER BY _p_created_date DESC LIMIT 5'
            )->fetchAllAssociative();

            $veryRecent = $connection->executeQuery(
                "SELECT _p_id, _p_name, _o_name, _p_created_date FROM _procedure WHERE _p_created_date >= datetime('now', '-10 seconds')"
            )->fetchAllAssociative();
            echo "🕒 Procedures created in last 10 seconds: " . count($veryRecent) . "\n";

            foreach ($recent as $proc) {
                echo "   ID: {$proc['_p_id']}, Name: {$proc['_p_name']}, Org: {$proc['_o_name']}, Created: {$proc['_p_created_date']}\n";
            }

            return (int) ($result['count'] ?? 0);
        } catch (\Exception $e) {
            echo "⚠️ Error getting procedure count: " . $e->getMessage() . "\n";
            return 0;
        }
    }

    /**
     * Find the latest audit entry created during event processing.
     */
    protected function findLatestAuditEntry(EntityManagerInterface $entityManager): ?string
    {
        try {
            $connection = $entityManager->getConnection();

            // Try common audit table names
            $auditTables = [
                'xbeteiligung_async_message_audit',
                'message_audit',
                'xbeteiligung_audit',
                'addon_xbeteiligung_async_audit'
            ];

            foreach ($auditTables as $tableName) {
                try {
                    // Look for the most recent audit entry (created in the last minute)
                    $result = $connection->executeQuery(
                        "SELECT id, message_type FROM {$tableName} WHERE created_at >= datetime('now', '-1 minute') ORDER BY created_at DESC LIMIT 1"
                    )->fetchAssociative();

                    if ($result && !empty($result['id'])) {
                        echo "🔍 Found recent audit entry in table: {$tableName}\n";
                        echo "   ID: {$result['id']}\n";
                        echo "   Message Type: {$result['message_type']}\n";
                        return $result['id'];
                    }
                } catch (\Exception $e) {
                    // Table doesn't exist, try next one
                    continue;
                }
            }

            return null;
        } catch (\Exception $e) {
            echo "⚠️ Error finding latest audit entry: " . $e->getMessage() . "\n";
            return null;
        }
    }

    /**
     * Verify that an audit entry exists in the database.
     */
    protected function verifyAuditEntry(EntityManagerInterface $entityManager, string $auditId): bool
    {
        try {
            $connection = $entityManager->getConnection();

            // Try common audit table names
            $auditTables = [
                'xbeteiligung_async_message_audit',
                'message_audit',
                'xbeteiligung_audit',
                'addon_xbeteiligung_async_audit'
            ];

            foreach ($auditTables as $tableName) {
                try {
                    $result = $connection->executeQuery(
                        "SELECT COUNT(*) as count FROM {$tableName} WHERE id = ?",
                        [$auditId]
                    )->fetchAssociative();

                    if ($result && $result['count'] > 0) {
                        echo "🔍 Found audit entry in table: {$tableName}\n";

                        // Get audit details
                        $auditDetails = $connection->executeQuery(
                            "SELECT * FROM {$tableName} WHERE id = ?",
                            [$auditId]
                        )->fetchAssociative();

                        echo "📋 Audit entry details:\n";
                        foreach ($auditDetails as $key => $value) {
                            $displayValue = is_string($value) ? substr($value, 0, 100) : $value;
                            if (is_string($value) && strlen($value) > 100) {
                                $displayValue .= "...";
                            }
                            echo "   {$key}: {$displayValue}\n";
                        }

                        return true;
                    }
                } catch (\Exception $e) {
                    // Table doesn't exist, try next one
                    continue;
                }
            }

            echo "⚠️ Audit entry not found in any expected table\n";
            return false;
        } catch (\Exception $e) {
            echo "⚠️ Error verifying audit entry: " . $e->getMessage() . "\n";
            return false;
        }
    }
}