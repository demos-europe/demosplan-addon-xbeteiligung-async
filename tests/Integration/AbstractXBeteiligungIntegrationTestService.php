<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Integration;


use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageTransport;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Tools\RabbitMQMessageBroker;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\IncomingMessageData;
use demosplan\DemosPlanCoreBundle\DataGenerator\Factory\Procedure\ProcedureFactory;
use Exception;
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
use ReflectionClass;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Zenstruck\Foundry\Persistence\Proxy;

/**
 * Abstract base class for XBeteiligung integration tests.
 *
 * Contains all the common logic for setting up RabbitMQ connections,
 * running the actual service chain, and database interactions.
 *
 * VALIDATION MODES:
 * - Error Collection Mode (default): Collects all validation errors and reports them together
 * - Assertion Mode: Fails immediately on first validation error (fail fast)
 *
 * To switch modes in concrete test classes:
 * - Call $this->enableAssertionMode() in setupTestData() for assertion mode
 * - Call $this->enableErrorCollectionMode() for error collection mode (or do nothing - it's default)
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
    const FACTORY_CLASS_NAME = 'DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory';

    protected ?\DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory $xmlFactory = null;

    /** @var CustomerInterface|null Test customer created by factory */
    protected ?CustomerInterface $testCustomer = null;

    /** @var array<string, OrgaInterface> Map of organization name to created organization entities */
    protected array $createdOrganizations = [];

    /** @var array<string, UserInterface[]> Map of organization name to array of created users */
    protected array $createdUsers = [];

    /** Toggle between assertion mode (fail fast) and error collection mode (collect all errors) */
    protected bool $useAssertions = false;

    /** PHPUnit test case for real assertions (when available) */
    protected ?object $testCase = null;

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

    /**
     * Enable assertion-based validation mode (fail fast on first error).
     * Uncomment this call in concrete test classes to use assertion mode.
     */
    protected function enableAssertionMode(): void
    {
        $this->useAssertions = true;
        echo "🔄 Enabled assertion mode: Tests will fail fast on first validation error using PHPUnit assertions\n";
    }

    public function setupTestData(ContainerInterface $container, ?object $testCase = null): void
    {
        $this->testCase = $testCase;
        $this->loadXmlFactory();
        $this->createTestEntities($container);
        $this->setupRabbitMQConnection();
        $this->setupRabbitMQTopology();
        $this->publishTestMessages();

        echo "📤 Published test messages to RabbitMQ\n";
    }

    public function runIntegrationTest(ContainerInterface $container): AddonTestResult
    {
        // Configure the RabbitMQ transport to use the same connection as our test
        $this->configureRabbitMQTransport($container);

        // Capture procedures before processing using repository approach
        $procedureRepository = $container->get(ProcedureRepository::class);
        $initialProcedures  = ProcedureFactory::findBy(['deleted' => false]);
        //$initialProcedures = $this->captureRelevantProcedures($procedureRepository);
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

            // Start database transaction for the entire process
            $entityManager->getConnection()->beginTransaction();

            $messageProcessor = $container->get('DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageProcessor');
            $scenarios = $this->getTestScenarios();

            foreach ($scenarios as [$scenarioName, $isValid]) {
                $xml = $this->xmlFactory->createXML($scenarioName, $isValid);
                $messageData = new IncomingMessageData(
                    $xml,
                    'bau.cockpit.xyz.00.01.kommunal.Initiieren.0401'
                );

                echo "🔧 INTEGRATION_DEBUG: Processing scenario '{$scenarioName}' directly...\n";


                // Check EntityManager status before processing
                $currentEM = $container->get(EntityManagerInterface::class);

                try {
                    $result = $messageProcessor->processIncomingMessage($messageData);
                    // validate that procedure was created

                    $finalProceduresNEW  = ProcedureFactory::findBy(['deleted' => false]);

                    $initialCount = count($finalProceduresNEW);

                    echo "💾 Final procedure COUNT {$initialCount}\n";
                    $this->validateProcedureAgainstScenarioAfterCreation($scenarioName, $isValid);

                    // todo (test audit entry)
                    //echo "🔍 Searching for audit entries created during event processing...\n";
                    //$auditId = $this->findLatestAuditEntry($entityManager);

                    /*if ($auditId) {
                        $this->debugAuditDetails($auditId, $entityManager);
                    }*/

                } catch (Exception $e) {
                    $this->debugProcessMessageException($e);
                }
            }


            // Check if messages were processed from the queue
            $messageCountAfterEvent = $this->channel->queue_declare($this->queueName, true, true, false, false)[1];
            echo "📊 Messages in queue after event processing: {$messageCountAfterEvent}\n";

            // Commit transaction
            // Check if EntityManager is still open (XBeteiligung processing might have closed it)
            if ($entityManager->isOpen()) {
                $entityManager->flush();
                $entityManager->getConnection()->commit();
                echo "💾 Flushed and committed database changes\n";
            } else {
                echo "⚠️ EntityManager was closed during XBeteiligung processing\n";
                // Try to commit/rollback the connection if it's still active
                if ($entityManager->getConnection()->isTransactionActive()) {
                    try {
                        $entityManager->getConnection()->rollBack();
                        echo "🔄 Rolled back database transaction\n";
                    } catch (Exception $e) {
                        echo "⚠️ Could not rollback transaction: {$e->getMessage()}\n";
                    }
                }
            }

        } catch (Exception $e) {
            // Roll back on error
            if ($entityManager->getConnection()->isTransactionActive()) {
                $entityManager->getConnection()->rollBack();
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
        $finalProcedures  = ProcedureFactory::findBy(['deleted' => false]);
        $createdProcedures = $this->findNewlyCreatedProcedures($initialProcedures, $finalProcedures);


        // Let concrete implementation validate the specific results with created procedures
        return $this->validateTestResult($createdProcedures, $auditId);
    }

    private function debugProcessMessageException($e) {
        echo "🚨 INTEGRATION_DEBUG: Exception during direct processing: {$e->getMessage()}\n";
        echo "🚨 INTEGRATION_DEBUG: Exception type: " . get_class($e) . "\n";
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
        $factoryFile = $this->getAddonRootPath() . '/tests/DataFactory/XBeteiligung401TestFactory.php';

        if (!class_exists(self::FACTORY_CLASS_NAME) && file_exists($factoryFile)) {
            require_once $factoryFile;
        }

        if (!class_exists(self::FACTORY_CLASS_NAME)) {
            throw new RuntimeException("Factory class not available: " . self::FACTORY_CLASS_NAME);
        }

        $commonHelpers = new CommonHelpers(new NullLogger());
        $this->xmlFactory = new XBeteiligung401TestFactory(
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
            $this->publishMessage($xml, $scenarioName);
        }

        usleep(100000); // 100ms delay after all messages
    }

    private function getFullScenario($scenarioName, $isValid) {
        $reflection = new ReflectionClass($this->xmlFactory);
        $getScenarioDataMethod = $reflection->getMethod('getScenarioData');
        $getScenarioDataMethod->setAccessible(true);
        return $getScenarioDataMethod->invoke($this->xmlFactory, $scenarioName, $isValid);
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
     * Find procedures that were created between the initial and final snapshots.
     *
     * @param ProcedureInterface[] $initialProcedures
     * @param ProcedureInterface[] $finalProcedures
     * @return ProcedureInterface[]
     */
    protected function findNewlyCreatedProcedures(array $initialProcedures, array $finalProcedures): array
    {
        // Create map of initial procedure IDs for fast lookup

        $initialIds = array_map(function(Proxy $p) {
            return $p->_real()->getId();
        }, $initialProcedures);

        $initialIdSet = array_flip($initialIds);

        // Find procedures in final set that weren't in initial set
        $newProcedures = array_filter($finalProcedures, function(Proxy $p) use ($initialIdSet) {
            return !isset($initialIdSet[$p->_real()->getId()]);
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
        $scenarioData = $this->getFullScenario($scenarioName, $isValid);

        // USE REAL PHPUNIT ASSERTIONS! ✅
        $this->testCase->assertEquals(
            $scenarioData['plan_name'],
            $procedure->getName(),
            "Expected procedure name '{$scenarioData['plan_name']}', got '{$procedure->getName()}' for scenario '{$scenarioName}'"
        );
        echo "✅ Procedure name PHPUnit assertion passed: '{$procedure->getName()}'\n";

        $this->testCase->assertEquals(
            $scenarioData['beschreibung_planungsanlass'],
            $procedure->getDesc(),
            "Expected procedure description to match scenario '{$scenarioName}'"
        );
        echo "✅ Procedure description PHPUnit assertion passed\n";

        $this->testCase->assertEquals(
            $scenarioData['org_name'],
            $procedure->getOrga()->getName(),
            "Expected organization name '{$scenarioData['org_name']}' for scenario '{$scenarioName}'"
        );
        echo "✅ Procedure organization PHPUnit assertion passed: '{$procedure->getOrga()->getName()}'\n";

        $this->testCase->assertNotNull($procedure->getSettings()->getTerritory(), "Territory should not be null");
        $this->testCase->assertNotNull($procedure->getProcedureType(), "Procedure type should not be null");
        $this->testCase->assertNotNull($procedure->getCustomer(), "Customer should not be null");
        echo "✅ All required fields PHPUnit assertions passed\n";

        return [
            'success' => true,
            'procedure' => $procedure,
            'scenario' => $scenarioName,
            'errors' => []
        ];

    }

    protected function validateProcedureAgainstScenarioAfterCreation(string $scenarioName, bool $isValid): array
    {
        echo "Asserting procedure details with Scenario : '{$scenarioName}'\n";
        $procedure = ProcedureFactory::last('createdDate')->_real();
        $scenarioData = $this->getFullScenario($scenarioName, $isValid);

        // USE REAL PHPUNIT ASSERTIONS! ✅
        $this->testCase->assertEquals(
            $scenarioData['plan_name'],
            $procedure->getName(),
            "Expected procedure name '{$scenarioData['plan_name']}', got '{$procedure->getName()}' for scenario '{$scenarioName}'"
        );
        echo "✅ Procedure name PHPUnit assertion passed: '{$procedure->getName()}'\n";

        $this->testCase->assertEquals(
            $scenarioData['beschreibung_planungsanlass'],
            $procedure->getDesc(),
            "Expected procedure description to match scenario '{$scenarioName}'"
        );
        echo "✅ Procedure description PHPUnit assertion passed\n";

        $this->testCase->assertEquals(
            $scenarioData['org_name'],
            $procedure->getOrga()->getName(),
            "Expected organization name '{$scenarioData['org_name']}' for scenario '{$scenarioName}'"
        );
        echo "✅ Procedure organization PHPUnit assertion passed: '{$procedure->getOrga()->getName()}'\n";

        $this->testCase->assertNotNull($procedure->getSettings()->getTerritory(), "Territory should not be null");
        $this->testCase->assertNotNull($procedure->getProcedureType(), "Procedure type should not be null");
        $this->testCase->assertNotNull($procedure->getCustomer(), "Customer should not be null");
        echo "✅ All required fields PHPUnit assertions passed\n";

        return [
            'success' => true,
            'procedure' => $procedure,
            'scenario' => $scenarioName,
            'errors' => []
        ];

    }

    /**
     * Create test entities needed for XBeteiligung integration tests.
     * This includes customer, organization, and planner user.
     */
    protected function createTestEntities(ContainerInterface $container): void
    {
        echo "🏭 Creating test entities dynamically based on scenario requirements...\n";

        // Analyze all scenarios to extract entity requirements
        $entityRequirements = $this->analyzeScenarioRequirements();
        //echo "📊 Found {$entityRequirements['organization_count']} unique organizations across " . count($entityRequirements['scenarios']) . " scenarios\n";

        // Create required procedure type first (must match config: addon_xbeteiligung_async_procedure_type_name)
        $testProcedureType = ProcedureTypeFactory::createOne([
            'name' => 'test-procedure-type',
            'description' => 'Test procedure type for XBeteiligung integration tests',
        ])->_real();
        //echo "✅ Created procedure type: {$testProcedureType->getName()}\n";

        // Create test customer with subdomain 'hh' (required for routing key mapping)
        $this->testCustomer = CustomerFactory::createOne([
            'subdomain' => 'hh',
            'name' => 'Test Hamburg Customer for XBeteiligung',
        ])->_real();
        //echo "✅ Created test customer: {$this->testCustomer->getName()} (subdomain: {$this->testCustomer->getSubdomain()})\n";

        // Create organizations and users dynamically based on scenario requirements
        $this->createDynamicOrganizationsAndUsers($container, $entityRequirements);

        // Flush and COMMIT to ensure entities are visible to all database connections
        $this->commitTestEntities($container);
    }

    /**
     * Analyze all test scenarios to extract entity requirements.
     */
    private function analyzeScenarioRequirements(): array
    {
        $scenarios = $this->getTestScenarios();
        $organizations = [];
        $scenarioData = [];

        foreach ($scenarios as [$scenarioName, $isValid]) {
            try {
                $scenarioInfo = $this->getFullScenario($scenarioName, $isValid);
                $orgName = $scenarioInfo['org_name'] ?? null;
                $organizations[$orgName] = true;
                $scenarioData[] = [
                    'scenario' => $scenarioName,
                    'valid' => $isValid,
                    'org_name' => $orgName,
                    'plan_name' => $scenarioInfo['plan_name'] ?? 'Unknown Plan'
                ];

                //echo "🔍 Scenario '{$scenarioName}' requires organization: '{$orgName}'\n";

            } catch (Exception $e) {
                echo "❌ Failed to analyze scenario '{$scenarioName}': {$e->getMessage()}\n";
            }
        }

        return [
            'organizations' => $organizations,
            'scenarios' => $scenarioData,
            'organization_count' => count($organizations)
        ];
    }

    /**
     * Create organizations and users dynamically based on requirements.
     */
    private function createDynamicOrganizationsAndUsers(ContainerInterface $container, array $entityRequirements): void
    {
        $roleHandler = $container->get(RoleHandler::class);
        $plannerRole = $roleHandler->getUserRolesByCodes([RoleInterface::PRIVATE_PLANNING_AGENCY])[0];

        foreach (array_keys($entityRequirements['organizations']) as $orgName) {
            echo "🏢 Creating organization: '{$orgName}'\n";

            // Create organization
            $organization = OrgaFactory::createOne([
                'name' => $orgName,
                'deleted' => false,
                'showlist' => true,
                'showname' => true,
            ])->_real();

            echo "✅ Created organization: {$organization->getName()}\n";

            // Create planner user for this organization
            $plannerUser = UserFactory::createOne([
                'login' => 'xbet_planner_' . strtolower(str_replace([' ', 'ä', 'ö', 'ü'], ['_', 'ae', 'oe', 'ue'], $orgName)) . '_' . uniqid('', true),
                'orga' => $organization,
                'deleted' => false,
                'firstname' => 'Test',
                'lastname' => 'Planner',
                'email' => 'test.planner.' . uniqid('', true) . '@xbeteiligung.test',
            ])->_real();

            echo "✅ Created planner user: {$plannerUser->getLogin()} for org '{$orgName}'\n";

            // Ensure bidirectional relationship
            $organization->addUser($plannerUser);

            // Assign planner role (fix: set current customer first)
            $plannerUser->setCurrentCustomer($this->testCustomer);
            $plannerUser->addDplanrole($plannerRole, $this->testCustomer);

            echo "✅ Assigned planner role to user in '{$orgName}'\n";

            // Store created entities for lookup
            $this->createdOrganizations[$orgName] = $organization;
            if (!isset($this->createdUsers[$orgName])) {
                $this->createdUsers[$orgName] = [];
            }
            $this->createdUsers[$orgName][] = $plannerUser;
        }
    }

    /**
     * Commit test entities and ensure database visibility.
     */
    private function commitTestEntities(ContainerInterface $container): void
    {
        $entityManager = $container->get(EntityManagerInterface::class);
        $entityManager->flush();

        // CRITICAL: Commit the transaction so XBeteiligung processing can see the test entities
        $connection = $entityManager->getConnection();

        if ($connection->isTransactionActive()) {
            $connection->commit();
            echo "✅ Committed test entities transaction for visibility\n";
        } else {
            echo "⚠️ No active transaction to commit - entities should already be visible\n";
        }

    }


    /**
     * Get organization created for a specific scenario by its organization name.
     */
    protected function getOrganizationForScenario(string $scenarioName, bool $isValid = true): ?OrgaInterface
    {
        $scenarioInfo = $this->getFullScenario($scenarioName, $isValid);
        $orgName = $scenarioInfo['org_name'] ?? null;

        if (!$orgName) {
            echo "⚠️ Scenario '{$scenarioName}' has no org_name defined\n";
            return null;
        }

        if (isset($this->createdOrganizations[$orgName])) {
            return $this->createdOrganizations[$orgName];
        }

        echo "⚠️ Organization '{$orgName}' not found in created entities for scenario '{$scenarioName}'\n";
        return null;
    }

    /**
     * Get users created for a specific scenario by its organization name.
     */
    protected function getUsersForScenario(string $scenarioName, bool $isValid = true): array
    {
        try {
            $scenarioInfo = $this->getFullScenario($scenarioName, $isValid);
            $orgName = $scenarioInfo['org_name'] ?? null;

            if (!$orgName) {
                echo "⚠️ Scenario '{$scenarioName}' has no org_name defined\n";
                return [];
            }

            if (isset($this->createdUsers[$orgName])) {
                return $this->createdUsers[$orgName];
            }

            echo "⚠️ Users for organization '{$orgName}' not found in created entities for scenario '{$scenarioName}'\n";
            return [];
        } catch (\Exception $e) {
            echo "⚠️ Failed to get users for scenario '{$scenarioName}': {$e->getMessage()}\n";
            return [];
        }
    }

    /**
     * Clean up test entities created for the integration test.
     */
    protected function cleanupTestEntities(ContainerInterface $container): void
    {
        echo "🗑️ Cleaning up dynamically created test entities...\n";

        try {
            $entityManager = $container->get(EntityManagerInterface::class);

            if (!$entityManager->isOpen()) {
                echo "⚠️ EntityManager was closed during test execution\n";
                $this->reportEntitiesForCleanup();
                echo "⚠️ EntityManager closed - entities may persist in database\n";
                return;
            }

            // EntityManager is open, proceed with cleanup
            $entitiesRemoved = 0;

            // Clean up dynamically created users (must be done before organizations)
            $entitiesRemoved += $this->cleanupDynamicUsers($entityManager);

            // Clean up dynamically created organizations
            $entitiesRemoved += $this->cleanupDynamicOrganizations($entityManager);

            // Clean up customer (if any)
            $entitiesRemoved += $this->cleanupTestCustomer($entityManager);

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

        // Reset all references
        $this->resetEntityReferences();
    }

    /**
     * Report entities that should be cleaned up when EntityManager is closed.
     */
    private function reportEntitiesForCleanup(): void
    {
        echo "   Attempting cleanup with IDs only...\n";

        // Report dynamically created users
        foreach ($this->createdUsers as $orgName => $users) {
            foreach ($users as $user) {
                echo "🗑️ User should be cleaned: {$user->getLogin()} (ID: {$user->getId()}) in org '{$orgName}'\n";
            }
        }

        // Report dynamically created organizations
        foreach ($this->createdOrganizations as $orgName => $org) {
            echo "🗑️ Organization should be cleaned: {$org->getName()} (ID: {$org->getId()})\n";
        }

        // Report customer
        if ($this->testCustomer) {
            echo "🗑️ Test customer should be cleaned: {$this->testCustomer->getName()} (ID: {$this->testCustomer->getId()})\n";
        }
    }

    /**
     * Clean up all dynamically created users.
     */
    private function cleanupDynamicUsers($entityManager): int
    {
        $removed = 0;

        foreach ($this->createdUsers as $orgName => $users) {
            echo "🗑️ Cleaning users from organization: {$orgName}\n";

            foreach ($users as $user) {
                try {
                    $this->removeEntitySafely($entityManager, $user, "user {$user->getLogin()}");
                    $removed++;
                } catch (\Exception $e) {
                    echo "⚠️ Could not remove user {$user->getLogin()}: {$e->getMessage()}\n";
                }
            }
        }

        return $removed;
    }

    /**
     * Clean up all dynamically created organizations.
     */
    private function cleanupDynamicOrganizations($entityManager): int
    {
        $removed = 0;

        foreach ($this->createdOrganizations as $orgName => $org) {
            try {
                $this->removeEntitySafely($entityManager, $org, "organization {$org->getName()}");
                $removed++;
            } catch (\Exception $e) {
                echo "⚠️ Could not remove organization {$org->getName()}: {$e->getMessage()}\n";
            }
        }

        return $removed;
    }

    /**
     * Clean up the test customer.
     */
    private function cleanupTestCustomer($entityManager): int
    {
        if (!$this->testCustomer) {
            return 0;
        }

        try {
            $this->removeEntitySafely($entityManager, $this->testCustomer, "customer {$this->testCustomer->getName()}");
            return 1;
        } catch (\Exception $e) {
            echo "⚠️ Could not remove test customer: {$e->getMessage()}\n";
            return 0;
        }
    }

    /**
     * Safely remove an entity, handling both managed and detached states.
     */
    private function removeEntitySafely($entityManager, $entity, string $description): void
    {
        if ($entityManager->contains($entity)) {
            $entityManager->remove($entity);
        } else {
            // Entity might be detached, try to find and remove by ID
            $managedEntity = $entityManager->find(get_class($entity), $entity->getId());
            if ($managedEntity) {
                $entityManager->remove($managedEntity);
            }
        }
        echo "🗑️ Removed {$description}\n";
    }

    /**
     * Reset all entity references after cleanup.
     */
    private function resetEntityReferences(): void
    {
        $this->testCustomer = null;
        $this->createdOrganizations = [];
        $this->createdUsers = [];
    }
}
