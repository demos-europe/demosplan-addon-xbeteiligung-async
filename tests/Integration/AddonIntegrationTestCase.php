<?php

declare(strict_types=1);

namespace Tests\Core\Integration;

use demosplan\DemosPlanCoreBundle\Tests\Integration\AddonIntegrationTestInterface;
use Exception;
use Tests\Base\FunctionalTestCase;

class AddonIntegrationTestCase extends FunctionalTestCase
{
    /**
     * Get all addon integration test services using the existing addon registry
     * @return AddonIntegrationTestInterface[]
     */
    private function getAddonTests(): array
    {
        $addonTests = [];

        echo "🔍 Discovering addon integration tests using project structure\n";

        try {
            $container = self::getContainer();

            // Use Symfony's kernel to get project directory (committed pattern)
            $projectDir = $container->getParameter('kernel.project_dir');

            echo "📁 Project directory: {$projectDir}\n";

            // Look for addon directories using committed patterns
            $addonBasePaths = [
                $projectDir . '/addonDev',  // Development addons
                $projectDir . '/addons',    // Production addons
            ];

            foreach ($addonBasePaths as $addonBasePath) {
                if (!is_dir($addonBasePath)) {
                    echo "⏭️ Skipping non-existent path: {$addonBasePath}\n";
                    continue;
                }

                echo "🔍 Scanning addon path: {$addonBasePath}\n";
                $addonDirs = glob($addonBasePath . '/demosplan-addon-*');

                foreach ($addonDirs as $addonDir) {
                    $addonName = basename($addonDir);
                    echo "🔍 Found addon: {$addonName} at {$addonDir}\n";

                    $integrationTestFiles = glob($addonDir . '/tests/Integration/*IntegrationTestService.php');
                    echo "   Found " . count($integrationTestFiles) . " integration test files\n";

                    foreach ($integrationTestFiles as $testFile) {
                        $testService = $this->loadAddonIntegrationTest($testFile);
                        if ($testService) {
                            $addonTests[] = $testService;
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            echo "❌ Error discovering addons: " . $e->getMessage() . "\n";
        }

        echo "✅ Found " . count($addonTests) . " addon integration test services\n";
        return $addonTests;
    }

    /**
     * Load and instantiate an addon integration test service from a file
     */
    private function loadAddonIntegrationTest(string $classFile): ?AddonIntegrationTestInterface
    {
        $fileName = basename($classFile, '.php');
        echo "🔍 Loading integration test: {$fileName}\n";
        echo "📁 Class file path: {$classFile}\n";

        if (!file_exists($classFile)) {
            echo "❌ Class file does not exist\n";
            return null;
        }

        echo "✅ Class file exists, requiring it\n";
        require_once $classFile;

        // Extract namespace and class name from file
        $className = $this->extractClassNameFromFile($classFile);

        if (!$className) {
            echo "❌ Could not determine class name from file\n";
            return null;
        }

        if (!class_exists($className)) {
            echo "❌ Class not available after requiring file: {$className}\n";
            return null;
        }

        echo "✅ Class is available: {$className}\n";

        try {
            $service = new $className();
            echo "✅ Service instantiated: " . get_class($service) . "\n";

            if ($service instanceof AddonIntegrationTestInterface) {
                echo "✅ Service implements AddonIntegrationTestInterface\n";
                return $service;
            } else {
                echo "❌ Service does not implement AddonIntegrationTestInterface\n";
                return null;
            }
        } catch (\Exception $e) {
            echo "❌ Could not instantiate service: " . $e->getMessage() . "\n";
            return null;
        }
    }

    /**
     * Extract the fully qualified class name from a PHP file
     */
    private function extractClassNameFromFile(string $filePath): ?string
    {
        $content = file_get_contents($filePath);
        if (!$content) {
            return null;
        }

        // Extract namespace
        if (preg_match('/namespace\s+([^;]+);/', $content, $namespaceMatches)) {
            $namespace = trim($namespaceMatches[1]);
        } else {
            return null;
        }

        // Extract class name
        if (preg_match('/class\s+(\w+)/', $content, $classMatches)) {
            $className = trim($classMatches[1]);
        } else {
            return null;
        }

        return $namespace . '\\' . $className;
    }

    /**
     * Test all registered addon integrations
     */
    public function testAddonIntegrations(): void
    {
        $container = self::getContainer();
        $addonTests = $this->getAddonTests();

        $testsRun = 0;
        $testsPassed = 0;
        $testsFailed = 0;

        foreach ($addonTests as $addonTest) {
            $testsRun++;
            echo "**** Running {$addonTest->getAddonName()} - {$addonTest->getTestName()} **** \n ";

            try {
                // Setup test data
                $addonTest->setupTestData($container);

                // Run the integration test
                $result = $addonTest->runIntegrationTest($container);

                // Cleanup test data
                $addonTest->cleanupTestData($container);

                if ($result->isSuccess()) {
                    $testsPassed++;

                    $this->debugSuccessTestResult($result);

                    // Assert success
                    $this->assertTrue($result->isSuccess(), $result->getMessage());
                } else {
                    $testsFailed++;
                    echo "❌ FAILED: {$result->getMessage()}\n";
                    $this->fail($result->getMessage());
                }
            } catch (Exception $e) {
                $testsFailed++;
                echo "💥 EXCEPTION: {$e->getMessage()}\n";

                // Always cleanup on exception
                try {
                    $addonTest->cleanupTestData($container);
                } catch (Exception $cleanupException) {
                    echo "⚠️ CLEANUP ERROR: {$cleanupException->getMessage()}\n";
                }

                throw $e;
            }
        }

        $this->debugTestSummary($testsRun, $testsPassed, $testsFailed);

        // Final assertion
        $this->assertEquals(0, $testsFailed, "Some addon integration tests failed");
        $this->assertGreaterThan(0, $testsRun, "No addon integration tests were discovered");
    }

    private function debugSuccessTestResult($result){
        echo "✅ SUCCESS: {$result->getMessage()}\n";

        // Display result details
        foreach ($result->getDetails() as $key => $value) {
            echo "   {$key}: {$value}\n";
        }
    }

    private function debugTestSummary($testsRun, $testsPassed, $testsFailed) {
        echo "\n" . str_repeat("=", 80) . "\n";
        echo "📊 ADDON INTEGRATION TEST SUMMARY\n";
        echo str_repeat("=", 80) . "\n";
        echo "Tests Run: {$testsRun}\n";
        echo "Tests Passed: {$testsPassed}\n";
        echo "Tests Failed: {$testsFailed}\n";
        echo str_repeat("=", 80) . "\n";

    }

    /**
     * Test a specific addon by name (helper method, not a test)
     */
    private function runSpecificAddon(string $addonName): void
    {
        $container = self::getContainer();
        $addonTests = $this->getAddonTests();

        $addonTest = null;
        foreach ($addonTests as $test) {
            if ($test->getAddonName() === $addonName) {
                $addonTest = $test;
                break;
            }
        }
        $this->assertNotNull($addonTest, "Addon test '{$addonName}' not found");

        echo "\n🧪 Running specific addon test: {$addonName}\n";

        $addonTest->setupTestData($container);
        $result = $addonTest->runIntegrationTest($container);
        $addonTest->cleanupTestData($container);

        $this->assertTrue($result->isSuccess(), $result->getMessage());
    }
}
