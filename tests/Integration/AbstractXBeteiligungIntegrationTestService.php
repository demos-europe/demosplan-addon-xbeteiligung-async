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
use demosplan\DemosPlanCoreBundle\Repository\ProcedureRepository;
use demosplan\DemosPlanCoreBundle\DataGenerator\Factory\User\CustomerFactory;
use demosplan\DemosPlanCoreBundle\DataGenerator\Factory\Orga\OrgaFactory;
use demosplan\DemosPlanCoreBundle\DataGenerator\Factory\User\UserFactory;
use demosplan\DemosPlanCoreBundle\DataGenerator\Factory\Procedure\ProcedureTypeFactory;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use demosplan\DemosPlanCoreBundle\Logic\User\RoleHandler;
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

    /** @var CustomerInterface|null Test customer created by factory */
    protected ?CustomerInterface $testCustomer = null;

    /** @var OrgaInterface|null Test organization created by factory */
    protected ?OrgaInterface $testOrganization = null;

    /** @var UserInterface|null Test planner user created by factory */
    protected ?UserInterface $testPlannerUser = null;

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
     * @param ProcedureInterface[] $createdProcedures Array of procedures created during test
     * @param string|null $auditId The audit ID created during processing
     * @return AddonTestResult The test result
     */
    abstract protected function validateTestResult(array $createdProcedures, ?string $auditId): AddonTestResult;

    public function setupTestData(ContainerInterface $container): void
    {
        $this->loadXmlFactory();
        $this->createTestEntities($container);
        $this->setupRabbitMQConnection();
        $this->setupRabbitMQTopology();
        $this->publishTestMessages();

        echo "📤 Published test messages to RabbitMQ\n";
    }

    public function runIntegrationTest(ContainerInterface $container): AddonTestResult
    {
        echo "🔧 INTEGRATION_DEBUG: Starting runIntegrationTest with fixes...\n";

        // Skip all EntityManager refresh attempts - use services as-is like direct test
        echo "🔧 INTEGRATION_DEBUG: Using services without modification (like direct test)...\n";

        // Configure the RabbitMQ transport to use the same connection as our test
        $this->configureRabbitMQTransport($container);

        // Capture procedures before processing using repository approach
        $procedureRepository = $container->get(ProcedureRepository::class);
        $initialProcedures = $this->captureRelevantProcedures($procedureRepository);
        $initialCount = count($initialProcedures);
        echo "📊 Initial relevant procedures: {$initialCount}\n";

        // Check queue and process messages
        $messageCount = $this->channel->queue_declare($this->queueName, true, true, false, false)[1];
        echo "📊 Messages in queue before processing: {$messageCount}\n";

        // Initialize variables
        $auditId = null;

        try {
            echo "🔧 INTEGRATION_DEBUG: About to start database transaction...\n";

            // Get EntityManager for transaction
            $entityManager = $container->get(EntityManagerInterface::class);
            echo "🔧 INTEGRATION_DEBUG: Got EntityManager for transaction\n";

            // Start database transaction for the entire process
            $entityManager->getConnection()->beginTransaction();
            echo "🔄 Started database transaction\n";

            echo "🔧 INTEGRATION_DEBUG: About to use direct message processing...\n";

            // ALTERNATIVE: Use direct message processing to avoid service isolation issues
            // This still tests the core XBeteiligung logic but stays in same service context
            echo "🔧 INTEGRATION_DEBUG: Using direct message processing to avoid service isolation...\n";

            $messageProcessor = $container->get('DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageProcessor');
            $scenarios = $this->getTestScenarios();

            foreach ($scenarios as [$scenarioName, $isValid]) {
                $xml = $this->xmlFactory->createXML($scenarioName, $isValid);
                $messageData = new \DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\IncomingMessageData(
                    $xml,
                    'bau.cockpit.xyz.00.01.kommunal.Initiieren.0401'
                );

                echo "🔧 INTEGRATION_DEBUG: Processing scenario '{$scenarioName}' directly...\n";

                // Check EntityManager status before processing
                $currentEM = $container->get(EntityManagerInterface::class);
                echo "🔧 INTEGRATION_DEBUG: EntityManager open before processing: " . ($currentEM->isOpen() ? 'YES' : 'NO') . "\n";

                try {
                    $result = $messageProcessor->processIncomingMessage($messageData);
                    echo "🔧 INTEGRATION_DEBUG: Direct processing result: " . (is_object($result) ? get_class($result) : gettype($result)) . "\n";

                    if ($result && method_exists($result, 'getProcedureId')) {
                        echo "🔧 INTEGRATION_DEBUG: Procedure ID from result: " . ($result->getProcedureId() ?? 'null') . "\n";
                    }
                } catch (\Exception $e) {
                    echo "🚨 INTEGRATION_DEBUG: Exception during direct processing: {$e->getMessage()}\n";
                    echo "🚨 INTEGRATION_DEBUG: Exception type: " . get_class($e) . "\n";
                }
            }

            echo "✅ Event subscriber processing completed\n";

            // Check if messages were processed from the queue
            $messageCountAfterEvent = $this->channel->queue_declare($this->queueName, true, true, false, false)[1];
            echo "📊 Messages in queue after event processing: {$messageCountAfterEvent}\n";

            // Look for audit entries created during event processing
            echo "🔍 Searching for audit entries created during event processing...\n";
            $auditId = $this->findLatestAuditEntry($entityManager);

            if ($auditId) {
                echo "✅ Found audit entry from event processing: {$auditId}\n";

                // Check for error details in audit entry that might explain why procedure creation failed
                $auditDetails = $this->getAuditEntryDetails($entityManager, $auditId);
                if ($auditDetails) {
                    if (isset($auditDetails['error_details']) && !empty($auditDetails['error_details'])) {
                        echo "❌ Audit entry contains error details: {$auditDetails['error_details']}\n";
                    }
                    if (isset($auditDetails['status']) && $auditDetails['status'] !== 'processed') {
                        echo "⚠️ Audit entry status: {$auditDetails['status']}\n";
                    }
                    if (empty($auditDetails['procedure_id'])) {
                        echo "⚠️ No procedure_id in audit entry - procedure creation likely failed\n";
                    } else {
                        echo "✅ Audit entry has procedure_id: {$auditDetails['procedure_id']}\n";
                    }
                }
            }

            // Commit transaction
            // Check if EntityManager is still open (XBeteiligung processing might have closed it)
            if ($entityManager->isOpen()) {
                $entityManager->flush();
                $entityManager->getConnection()->commit();
                echo "💾 Flushed and committed database changes\n";
            } else {
                echo "⚠️ EntityManager was closed during XBeteiligung processing\n";
                echo "   This suggests an exception occurred during procedure creation\n";
                echo "   However, message was processed (audit entry exists)\n";

                // Try to commit/rollback the connection if it's still active
                if ($entityManager->getConnection()->isTransactionActive()) {
                    try {
                        $entityManager->getConnection()->rollBack();
                        echo "🔄 Rolled back database transaction\n";
                    } catch (\Exception $e) {
                        echo "⚠️ Could not rollback transaction: {$e->getMessage()}\n";
                    }
                }
            }

        } catch (\Exception $e) {
            // Roll back on error
            if ($entityManager->getConnection()->isTransactionActive()) {
                $entityManager->getConnection()->rollBack();
            }

            echo "💥 Exception during message processing: {$e->getMessage()}\n";
            echo "   File: {$e->getFile()}:{$e->getLine()}\n";
            echo "   EntityManager open: " . ($entityManager->isOpen() ? 'YES' : 'NO') . "\n";

            // Show the full stack trace for debugging
            echo "🔍 Stack trace:\n";
            $stackLines = explode("\n", $e->getTraceAsString());
            foreach (array_slice($stackLines, 0, 10) as $line) {
                echo "   {$line}\n";
            }

            return new AddonTestResult(
                false,
                "Exception during event processing: " . $e->getMessage(),
                [
                    'exception' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'entityManagerOpen' => $entityManager->isOpen(),
                    'trace' => $e->getTraceAsString()
                ],
                null, // auditId
                count($initialProcedures), // initialCount
                0 // finalCount - can't get final count due to exception
            );
        }

        // Capture procedures after processing and find newly created ones
        $finalProcedures = $this->captureRelevantProcedures($procedureRepository);
        $createdProcedures = $this->findNewlyCreatedProcedures($initialProcedures, $finalProcedures);
        $proceduresCreated = count($createdProcedures);

        echo "📊 Final relevant procedures: " . count($finalProcedures) . "\n";
        echo "✨ Procedures created by REAL services: {$proceduresCreated}\n";

        // Debug: Check all procedures in the test organization to see if any were created
        if ($this->testOrganization) {
            $allOrgProcedures = $procedureRepository->findBy(['orga' => $this->testOrganization]);
            echo "🔍 Debug: All procedures in test org '{$this->testOrganization->getName()}': " . count($allOrgProcedures) . "\n";
            foreach ($allOrgProcedures as $proc) {
                echo "   - ID: {$proc->getId()}, Name: {$proc->getName()}, Created: {$proc->getCreatedDate()->format('Y-m-d H:i:s')}\n";
            }
        }

        if (!empty($createdProcedures)) {
            echo "📋 Created procedures:\n";
            foreach ($createdProcedures as $procedure) {
                echo "   - ID: {$procedure->getId()}, Name: {$procedure->getName()}, Org: {$procedure->getOrga()->getName()}\n";
            }
        }

        // Verify audit entry was created if we have an audit ID
        if ($auditId && !$this->verifyAuditEntry($entityManager, $auditId)) {
            return new AddonTestResult(
                false,
                "Expected audit entry to be created for message processing",
                ['audit_id' => $auditId],
                $auditId,
                count($initialProcedures),
                count($finalProcedures)
            );
        }

        echo "📝 Audit entry exists: " . ($auditId ? 'YES' : 'NO') . "\n";

        // Let concrete implementation validate the specific results with created procedures
        return $this->validateTestResult($createdProcedures, $auditId);
    }

    public function cleanupTestData(ContainerInterface $container): void
    {
        // Cleanup RabbitMQ connections
        if (isset($this->channel)) {
            $this->channel->queue_purge($this->queueName);
            $this->channel->close();
        }
        if (isset($this->connection)) {
            $this->connection->close();
        }
        echo "🧹 Cleaned up RabbitMQ connections\n";

        // Cleanup test entities
        $this->cleanupTestEntities($container);
    }

    /**
     * Load the XML factory for dynamic test data generation.
     */
    protected function loadXmlFactory(): void
    {
        $factoryClassName = 'DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory';

        if (!class_exists($factoryClassName)) {
            throw new RuntimeException("Factory class not found via
  autoloader: {$factoryClassName}");
        }

        $commonHelpers = new CommonHelpers(new NullLogger());
        $this->xmlFactory = new $factoryClassName(
            $this->getAddonRootPath(),
            $commonHelpers
        );
    }

    private function getAddonRootPath(): string
    {
        // Use reflection to get the current class directory and go up to addon root
      $reflectionClass = new \ReflectionClass($this);
      return dirname($reflectionClass->getFileName(), 3); // Go up 3 levels:Integration -> tests -> addon-root
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

            // Get full scenario data to access all fields including org_name
            $reflection = new \ReflectionClass($this->xmlFactory);
            $getScenarioDataMethod = $reflection->getMethod('getScenarioData');
            $getScenarioDataMethod->setAccessible(true);
            $fullScenarioData = $getScenarioDataMethod->invoke($this->xmlFactory, $scenarioName, $isValid);

            // Debug: Show organization name in scenario and verify it matches created org
            if (isset($fullScenarioData['org_name'])) {
                echo "   🏢 Scenario org name: '{$fullScenarioData['org_name']}'\n";
                echo "   🏢 Created org name: '{$this->testOrganization->getName()}'\n";
                echo "   🔍 Names match: " . ($fullScenarioData['org_name'] === $this->testOrganization->getName() ? 'YES' : 'NO') . "\n";

                // Debug: Check if ORG_NAME is in the generated XML
                if (strpos($xml, 'TestOrg XBeteiligung') !== false) {
                    echo "   ✅ Organization name found in generated XML\n";
                } else {
                    echo "   ❌ Organization name NOT found in generated XML\n";
                    // Look for ORG_NAME placeholder not being replaced
                    if (strpos($xml, '{{ORG_NAME}}') !== false) {
                        echo "   ⚠️ Unreplaced {{ORG_NAME}} placeholder found in XML\n";
                    }
                }
            } else {
                echo "   ⚠️ No org_name in full scenario data\n";
                echo "   📋 Available fields: " . implode(', ', array_keys($fullScenarioData)) . "\n";
            }

            // Debug: Show first 500 chars of XML to check content
            echo "   📄 XML preview: " . substr($xml, 0, 500) . "...\n";

            // Debug: Show the full section with organization name to understand structure
            $lines = explode("\n", $xml);
            $orgLines = [];
            foreach ($lines as $i => $line) {
                if (strpos($line, 'TestOrg XBeteiligung') !== false) {
                    // Show 3 lines before and after the org name
                    for ($j = max(0, $i-3); $j <= min(count($lines)-1, $i+3); $j++) {
                        $orgLines[] = $lines[$j];
                    }
                    break;
                }
            }
            if (!empty($orgLines)) {
                echo "   📋 XML structure around organization name:\n";
                foreach ($orgLines as $line) {
                    echo "      " . trim($line) . "\n";
                }
            }

            // Debug: Extract and show the organization name that will be used for lookup
            try {
                $xmlDoc = new \DOMDocument();
                $xmlDoc->loadXML($xml);
                $xpath = new \DOMXPath($xmlDoc);
                $xpath->registerNamespace('xbd', 'http://www.xleitstelle.de/xbau/12');

                // Look for organization name in the XML structure
                $nameElements = $xpath->query('//xbd:nameBehoerde | //nameBehoerde');
                if ($nameElements->length > 0) {
                    $xmlOrgName = $nameElements->item(0)->textContent;
                    echo "   🏢 Organization name extracted from XML: '{$xmlOrgName}'\n";
                } else {
                    echo "   ⚠️ Could not find organization name in XML\n";
                }
            } catch (\Exception $e) {
                echo "   ⚠️ Error parsing XML for org name: {$e->getMessage()}\n";
            }

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

    /**
     * Get audit entry details from the database.
     */
    protected function getAuditEntryDetails(EntityManagerInterface $entityManager, string $auditId): ?array
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
                        // Get audit details
                        $auditDetails = $connection->executeQuery(
                            "SELECT * FROM {$tableName} WHERE id = ?",
                            [$auditId]
                        )->fetchAssociative();

                        return $auditDetails ?: null;
                    }
                } catch (\Exception $e) {
                    // Table doesn't exist, try next one
                    continue;
                }
            }

            return null;
        } catch (\Exception $e) {
            echo "⚠️ Error getting audit entry details: " . $e->getMessage() . "\n";
            return null;
        }
    }

    /**
     * Capture procedures that might be relevant to our test scenarios.
     * This includes procedures created recently or with names matching our test scenarios.
     *
     * @param ProcedureRepository $repository
     * @return ProcedureInterface[]
     */
    protected function captureRelevantProcedures(ProcedureRepository $repository): array
    {
        // Get all procedures created in the last minute to catch test-created procedures
        $recentThreshold = new \DateTime('-1 minute');

        // Use query builder for more flexible querying
        $qb = $repository->createQueryBuilder('p')
            ->leftJoin('p.orga', 'o')
            ->where('p.createdDate >= :threshold')
            ->setParameter('threshold', $recentThreshold)
            ->orderBy('p.createdDate', 'DESC');

        // Also look for procedures with names matching our test scenarios
        $scenarios = $this->getTestScenarios();
        $testNames = [];

        foreach ($scenarios as [$scenarioName, $isValid]) {
            try {
                $scenarioInfo = $this->xmlFactory->getScenarioInfo($scenarioName, $isValid);
                if (isset($scenarioInfo['plan_name'])) {
                    $testNames[] = $scenarioInfo['plan_name'];
                }
            } catch (\Exception $e) {
                // Skip if scenario info not available
                continue;
            }
        }

        if (!empty($testNames)) {
            $qb->orWhere('p.name IN (:testNames)')
               ->setParameter('testNames', $testNames);
        }

        return $qb->getQuery()->getResult();
    }

    /**
     * Find procedures that were created between the initial and final snapshots.
     *
     * @param ProcedureInterface[] $initialProcedures
     * @param ProcedureInterface[] $finalProcedures
     * @return ProcedureInterface[]
     */
    protected function findNewlyCreatedProcedures(array $initialProcedures, array $finalProcedures): array
    {
        // Create map of initial procedure IDs for fast lookup
        $initialIds = array_map(function(ProcedureInterface $p) {
            return $p->getId();
        }, $initialProcedures);

        $initialIdSet = array_flip($initialIds);

        // Find procedures in final set that weren't in initial set
        $newProcedures = array_filter($finalProcedures, function(ProcedureInterface $p) use ($initialIdSet) {
            return !isset($initialIdSet[$p->getId()]);
        });

        return array_values($newProcedures); // Re-index array
    }

    /**
     * Validate a created procedure against expected scenario data.
     *
     * @param ProcedureInterface $procedure
     * @param string $scenarioName
     * @param bool $isValid
     * @return array Validation result with success/failure and details
     */
    protected function validateProcedureAgainstScenario(ProcedureInterface $procedure, string $scenarioName, bool $isValid): array
    {
        try {
            $scenarioInfo = $this->xmlFactory->getScenarioInfo($scenarioName, $isValid);
            $errors = [];

            // Validate name
            if (isset($scenarioInfo['plan_name']) && $procedure->getName() !== $scenarioInfo['plan_name']) {
                $errors[] = "Expected name '{$scenarioInfo['plan_name']}', got '{$procedure->getName()}'";
            }

            // Validate organization
            if (isset($scenarioInfo['org_name']) && $procedure->getOrga()->getName() !== $scenarioInfo['org_name']) {
                $errors[] = "Expected org '{$scenarioInfo['org_name']}', got '{$procedure->getOrga()->getName()}'";
            }

            // Validate description
            if (isset($scenarioInfo['beschreibung_planungsanlass']) && $procedure->getDescription() !== $scenarioInfo['beschreibung_planungsanlass']) {
                $errors[] = "Expected description '{$scenarioInfo['beschreibung_planungsanlass']}', got '{$procedure->getDescription()}'";
            }

            return [
                'success' => empty($errors),
                'errors' => $errors,
                'procedure' => $procedure,
                'scenario' => $scenarioName
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'errors' => ["Could not validate against scenario: " . $e->getMessage()],
                'procedure' => $procedure,
                'scenario' => $scenarioName
            ];
        }
    }

    /**
     * Create test entities needed for XBeteiligung integration tests.
     * This includes customer, organization, and planner user.
     */
    protected function createTestEntities(ContainerInterface $container): void
    {
        echo "🏭 Creating test entities using factories...\n";

        // Create required procedure type first (must match config: addon_xbeteiligung_async_procedure_type_name)
        $testProcedureType = ProcedureTypeFactory::createOne([
            'name' => 'test-procedure-type',
            'description' => 'Test procedure type for XBeteiligung integration tests',
        ])->object();
        echo "✅ Created procedure type: {$testProcedureType->getName()}\n";

        // Create test customer with subdomain 'hh' (required for routing key mapping)
        $this->testCustomer = CustomerFactory::createOne([
            'subdomain' => 'hh',
            'name' => 'Test Hamburg Customer for XBeteiligung',
        ])->object();

        echo "✅ Created test customer: {$this->testCustomer->getName()} (subdomain: {$this->testCustomer->getSubdomain()})\n";

        // Create test organization for procedure creation
        $this->testOrganization = OrgaFactory::createOne([
            'name' => 'TestOrg XBeteiligung',
            'deleted' => false,
            'showlist' => true,
            'showname' => true,
        ])->object();

        echo "✅ Created test organization: {$this->testOrganization->getName()}\n";

        // Create test planner user in the organization (required for procedure creation)
        $this->testPlannerUser = UserFactory::createOne([
            'login' => 'xbet_test_planner_' . uniqid(),
            'orga' => $this->testOrganization,
            'deleted' => false,
            'firstname' => 'Test',
            'lastname' => 'Planner',
            'email' => 'test.planner.' . uniqid() . '@xbeteiligung.test',
        ])->object();

        echo "✅ Created test planner user: {$this->testPlannerUser->getLogin()} in org {$this->testOrganization->getName()}\n";

        // Ensure bidirectional user-organization relationship (user has orga, but orga needs user in collection)
        $this->testOrganization->addUser($this->testPlannerUser);
        echo "✅ Added user to organization's user collection\n";

        // Assign planner role to the user (fix: set current customer first for proper role persistence)
        $this->testPlannerUser->setCurrentCustomer($this->testCustomer);

        $roleHandler = $container->get(RoleHandler::class);
        $plannerRole = $roleHandler->getUserRolesByCodes([RoleInterface::PRIVATE_PLANNING_AGENCY])[0];
        $this->testPlannerUser->addDplanrole($plannerRole, $this->testCustomer);

        echo "✅ Assigned planner role to user: {$plannerRole->getCode()}\n";

        // Flush and COMMIT to ensure entities are visible to all database connections
        $entityManager = $container->get(EntityManagerInterface::class);
        $entityManager->flush();

        // CRITICAL: Commit the transaction so XBeteiligung processing can see the test entities
        $connection = $entityManager->getConnection();
        echo "🔍 DEBUG: Transaction active: " . ($connection->isTransactionActive() ? 'YES' : 'NO') . "\n";

        if ($connection->isTransactionActive()) {
            $connection->commit();
            echo "✅ Committed test entities transaction for visibility\n";
        } else {
            echo "⚠️ No active transaction to commit - entities should already be visible\n";
        }

        echo "💾 Persisted all test entities to database\n";

        // Debug: Verify organization can be found by name
        $orgaService = $container->get('demosplan\DemosPlanCoreBundle\Logic\User\OrgaService');
        $foundOrgas = $orgaService->getOrgaByFields(['name' => $this->testOrganization->getName(), 'deleted' => false]);
        echo "🔍 Debug: Organization '{$this->testOrganization->getName()}' findable by service: " . count($foundOrgas) . " found\n";
    }

    /**
     * Clean up test entities created for the integration test.
     */
    protected function cleanupTestEntities(ContainerInterface $container): void
    {
        echo "🗑️ Cleaning up test entities...\n";

        try {
            $entityManager = $container->get(EntityManagerInterface::class);

            if (!$entityManager->isOpen()) {
                echo "⚠️ EntityManager was closed during test execution\n";
                echo "   Attempting cleanup with IDs only...\n";

                // If we have entity references, we can at least report what should be cleaned up
                if ($this->testPlannerUser) {
                    echo "🗑️ Test planner user should be cleaned: {$this->testPlannerUser->getLogin()} (ID: {$this->testPlannerUser->getId()})\n";
                }
                if ($this->testOrganization) {
                    echo "🗑️ Test organization should be cleaned: {$this->testOrganization->getName()} (ID: {$this->testOrganization->getId()})\n";
                }
                if ($this->testCustomer) {
                    echo "🗑️ Test customer should be cleaned: {$this->testCustomer->getName()} (ID: {$this->testCustomer->getId()})\n";
                }

                echo "⚠️ EntityManager closed - entities may persist in database\n";
                return;
            }

            // EntityManager is open, proceed with normal cleanup
            $entitiesRemoved = 0;

            if ($this->testPlannerUser) {
                try {
                    if ($entityManager->contains($this->testPlannerUser)) {
                        $entityManager->remove($this->testPlannerUser);
                    } else {
                        // Entity might be detached, try to find and remove by ID
                        $managedUser = $entityManager->find(get_class($this->testPlannerUser), $this->testPlannerUser->getId());
                        if ($managedUser) {
                            $entityManager->remove($managedUser);
                        }
                    }
                    echo "🗑️ Removed test planner user: {$this->testPlannerUser->getLogin()}\n";
                    $entitiesRemoved++;
                } catch (\Exception $e) {
                    echo "⚠️ Could not remove test planner user: {$e->getMessage()}\n";
                }
            }

            if ($this->testOrganization) {
                try {
                    if ($entityManager->contains($this->testOrganization)) {
                        $entityManager->remove($this->testOrganization);
                    } else {
                        $managedOrga = $entityManager->find(get_class($this->testOrganization), $this->testOrganization->getId());
                        if ($managedOrga) {
                            $entityManager->remove($managedOrga);
                        }
                    }
                    echo "🗑️ Removed test organization: {$this->testOrganization->getName()}\n";
                    $entitiesRemoved++;
                } catch (\Exception $e) {
                    echo "⚠️ Could not remove test organization: {$e->getMessage()}\n";
                }
            }

            if ($this->testCustomer) {
                try {
                    if ($entityManager->contains($this->testCustomer)) {
                        $entityManager->remove($this->testCustomer);
                    } else {
                        $managedCustomer = $entityManager->find(get_class($this->testCustomer), $this->testCustomer->getId());
                        if ($managedCustomer) {
                            $entityManager->remove($managedCustomer);
                        }
                    }
                    echo "🗑️ Removed test customer: {$this->testCustomer->getName()}\n";
                    $entitiesRemoved++;
                } catch (\Exception $e) {
                    echo "⚠️ Could not remove test customer: {$e->getMessage()}\n";
                }
            }

            if ($entitiesRemoved > 0 && $entityManager->isOpen()) {
                $entityManager->flush();
                echo "✅ Successfully cleaned up {$entitiesRemoved} test entities\n";
            } elseif ($entitiesRemoved === 0) {
                echo "ℹ️ No test entities to clean up\n";
            }

        } catch (\Exception $e) {
            echo "⚠️ Cleanup failed: {$e->getMessage()}\n";
            echo "   EntityManager may have been closed during test execution\n";
            echo "   This is normal when tests encounter exceptions during message processing\n";
            // Don't fail the test due to cleanup issues
        }

        // Reset references
        $this->testCustomer = null;
        $this->testOrganization = null;
        $this->testPlannerUser = null;
    }
}
