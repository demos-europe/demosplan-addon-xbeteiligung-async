<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Integration;

use DemosEurope\DemosplanAddon\Contracts\Events\AddonMaintenanceEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\EventSubscriber\XBeteiligungEventSubscriber;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungMessageTransport;
use DemosEurope\DemosplanAddon\XBeteiligung\Tools\RabbitMQMessageBroker;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\IncomingMessageData;
use demosplan\DemosPlanCoreBundle\Logic\User\OrgaService;
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
    const FACTORY_CLASS_NAME = 'DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory';

    protected ?\DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory $xmlFactory = null;

    /** @var CustomerInterface|null Test customer created by factory */
    protected ?CustomerInterface $testCustomer = null;

    /** @var array<string, OrgaInterface> Map of organization name to created organization entities */
    protected array $createdOrganizations = [];

    /** @var array<string, UserInterface[]> Map of organization name to array of created users */
    protected array $createdUsers = [];

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
                echo "🔧 INTEGRATION_DEBUG: EntityManager open before processing: " . ($currentEM->isOpen() ? 'YES' : 'NO') . "\n";

                try {
                    $result = $messageProcessor->processIncomingMessage($messageData);
                    $this->debugProcessMessage($result);
                } catch (Exception $e) {
                    $this->debugProcessMessageException($e);
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

    private function debugProcessMessage($result) {
        echo "🔧 INTEGRATION_DEBUG: Direct processing result: " . (is_object($result) ? get_class($result) : gettype($result)) . "\n";

        if ($result && method_exists($result, 'getProcedureId')) {
            echo "🔧 INTEGRATION_DEBUG: Procedure ID from result: " . ($result->getProcedureId() ?? 'null') . "\n";
        }

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
        /*$factoryClassName = self::FACTORY_CLASS_NAME;

        if (!class_exists($factoryClassName)) {
            throw new RuntimeException("Factory class not found via autoloader: {$factoryClassName}");
        }

        $commonHelpers = new CommonHelpers(new NullLogger());
        $this->xmlFactory = new $factoryClassName(
            $this->getAddonRootPath(),
            $commonHelpers
        );*/


        // Manually require the factory file (following the same pattern as this integration test loading)
        $factoryFile = __DIR__ .
            '/../DataFactory/XBeteiligung401TestFactory.php';

        if (!class_exists('DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory')) {
            if (!file_exists($factoryFile)) {
                throw new RuntimeException("Factory file not found:
  {$factoryFile}");
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

            $this->debugPublishingMessage($xml, $scenarioName, $isValid);

            $this->publishMessage($xml, $scenarioName);
        }

        usleep(100000); // 100ms delay after all messages
    }
    private function debugPublishingMessage($xml, $scenarioName, $isValid) {
        // Get scenario info for debugging
        $scenarioInfo = $this->xmlFactory->getScenarioInfo($scenarioName, $isValid);
        echo "   Description: {$scenarioInfo['description']}\n";

        // Get full scenario data to access all fields including org_name
        $fullScenarioData = $this->getFullScenario($scenarioName, $isValid);

        // Debug: Show organization name in scenario and verify it matches created org
        //$this->debugOrga($fullScenarioData, $xml);

        // Debug: Show first 500 chars of XML to check content
        $this->debugFirstChar($xml);

        // Debug: Show the full section with organization name to understand structure
        //$this->debugFullOrgaSecion($xml);

        // Debug: Extract and show the organization name that will be used for lookup
        $this->debugXml($xml);

        if (!$isValid && isset($scenarioInfo['expected_error'])) {
            echo "   Expected error: {$scenarioInfo['expected_error']}\n";
        }
    }

    private function getFullScenario($scenarioName, $isValid) {
        $reflection = new ReflectionClass($this->xmlFactory);
        $getScenarioDataMethod = $reflection->getMethod('getScenarioData');
        $getScenarioDataMethod->setAccessible(true);
        return $getScenarioDataMethod->invoke($this->xmlFactory, $scenarioName, $isValid);
    }

    private function debugFirstChar($xml) {
        echo "   📄 XML preview: " . substr($xml, 0, 500) . "...\n";
    }

    private function debugFullOrgaSecion($xml) {
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
    }

    private function debugOrga( $fullScenarioData, $xml): void {
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
    }

    private function debugXml($xml): void {
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
            // Get full scenario data to access all fields including org_name
            $scenarioData = $this->getFullScenario($scenarioName, $isValid);
            $errors = [];

            echo "🔍 DEBUG: Validating procedure '{$procedure->getName()}' against scenario '{$scenarioName}'\n";

            // Validate name
            if (isset($scenarioData['plan_name']) && $procedure->getName() !== $scenarioData['plan_name']) {
                $errors[] = "Expected name '{$scenarioData['plan_name']}', got '{$procedure->getName()}'";
            } else {
                echo "✅ Procedure name matches: '{$procedure->getName()}'\n";
            }

            // Validate organization using the dynamically created organization
            $expectedOrg = $this->getOrganizationForScenario($scenarioName, $isValid);
            if ($expectedOrg) {
                if ($procedure->getOrga()->getId() !== $expectedOrg->getId()) {
                    $errors[] = "Expected org '{$expectedOrg->getName()}' (ID: {$expectedOrg->getId()}), got '{$procedure->getOrga()->getName()}' (ID: {$procedure->getOrga()->getId()})";
                } else {
                    echo "✅ Procedure organization matches: '{$procedure->getOrga()->getName()}' (ID: {$procedure->getOrga()->getId()})\n";
                }
            }

            // Validate description (using arbeitstitel as fallback)
            $expectedDescription = $scenarioData['beschreibung_planungsanlass'] ?? $scenarioData['arbeitstitel'] ?? null;
            if ($expectedDescription && method_exists($procedure, 'getDescription') && $procedure->getDescription() !== $expectedDescription) {
                $errors[] = "Expected description '{$expectedDescription}', got '{$procedure->getDescription()}'";
            }

            if (empty($errors)) {
                echo "✅ All validations passed for scenario '{$scenarioName}'\n";
            } else {
                echo "❌ Validation errors for scenario '{$scenarioName}': " . implode('; ', $errors) . "\n";
            }

            return [
                'success' => empty($errors),
                'errors' => $errors,
                'procedure' => $procedure,
                'scenario' => $scenarioName
            ];
        } catch (\Exception $e) {
            echo "⚠️ Exception during validation for scenario '{$scenarioName}': {$e->getMessage()}\n";
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
        echo "🏭 Creating test entities dynamically based on scenario requirements...\n";

        // Analyze all scenarios to extract entity requirements
        $entityRequirements = $this->analyzeScenarioRequirements();
        echo "📊 Found {$entityRequirements['organization_count']} unique organizations across " . count($entityRequirements['scenarios']) . " scenarios\n";

        // Create required procedure type first (must match config: addon_xbeteiligung_async_procedure_type_name)
        $testProcedureType = ProcedureTypeFactory::createOne([
            'name' => 'test-procedure-type',
            'description' => 'Test procedure type for XBeteiligung integration tests',
        ])->_real();
        echo "✅ Created procedure type: {$testProcedureType->getName()}\n";

        // Create test customer with subdomain 'hh' (required for routing key mapping)
        $this->testCustomer = CustomerFactory::createOne([
            'subdomain' => 'hh',
            'name' => 'Test Hamburg Customer for XBeteiligung',
        ])->_real();
        echo "✅ Created test customer: {$this->testCustomer->getName()} (subdomain: {$this->testCustomer->getSubdomain()})\n";

        // Create organizations and users dynamically based on scenario requirements
        $this->createDynamicOrganizationsAndUsers($container, $entityRequirements);

        // Flush and COMMIT to ensure entities are visible to all database connections
        $this->commitTestEntities($container);

        // Debug: Verify organizations can be found by name
        $this->debugOrganizationLookup($container, $entityRequirements);
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

                echo "🔍 Scenario '{$scenarioName}' requires organization: '{$orgName}'\n";

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
        echo "🔍 DEBUG: Transaction active: " . ($connection->isTransactionActive() ? 'YES' : 'NO') . "\n";

        if ($connection->isTransactionActive()) {
            $connection->commit();
            echo "✅ Committed test entities transaction for visibility\n";
        } else {
            echo "⚠️ No active transaction to commit - entities should already be visible\n";
        }

        echo "💾 Persisted all test entities to database\n";
    }

    /**
     * Debug organization lookup to verify entities are accessible.
     */
    private function debugOrganizationLookup(ContainerInterface $container, array $entityRequirements): void
    {
        $orgaService = $container->get(OrgaService::class);

        foreach (array_keys($entityRequirements['organizations']) as $orgName) {
            $foundOrgas = $orgaService->getOrgaByFields(['name' => $orgName, 'deleted' => false]);
            echo "🔍 Debug: Organization '{$orgName}' findable by service: " . count($foundOrgas) . " found\n";
        }
    }

    /**
     * Get organization created for a specific scenario by its organization name.
     */
    protected function getOrganizationForScenario(string $scenarioName, bool $isValid = true): ?OrgaInterface
    {
        try {
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
        } catch (\Exception $e) {
            echo "⚠️ Failed to get organization for scenario '{$scenarioName}': {$e->getMessage()}\n";
            return null;
        }
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
