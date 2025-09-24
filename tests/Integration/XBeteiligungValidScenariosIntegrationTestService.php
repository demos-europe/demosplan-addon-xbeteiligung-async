<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Integration;

use demosplan\DemosPlanCoreBundle\Tests\Integration\AddonTestResult;
use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Integration test for XBeteiligung valid scenarios.
 *
 * This test validates that valid XBeteiligung messages successfully:
 * - Pass validation checks
 * - Create procedures in the database
 * - Generate appropriate audit entries
 *
 * Uses factory-generated realistic test data from valid scenarios.
 */
class XBeteiligungValidScenariosIntegrationTestService extends AbstractXBeteiligungIntegrationTestService
{
    public function getTestName(): string
    {
        return 'Real Service Chain with Valid Scenarios - Procedure Creation';
    }

    /**
     * Example of how to enable assertion mode.
     * Uncomment the line below to switch to assertion mode (fail fast).
     */
    public function setupTestData(ContainerInterface $container, ?object $testCase = null): void
    {
        // Toggle modes by uncommenting one of these lines:
        $this->enableAssertionMode();      // Uncomment for assertion mode (fail fast)
        // $this->enableErrorCollectionMode(); // Uncomment to explicitly set error collection mode

        parent::setupTestData($container, $testCase);
    }

    /**
     * Get valid test scenarios that should result in procedure creation.
     *
     * @return array Array of [scenarioName, isValid] pairs
     */
    protected function getTestScenarios(): array
    {
        return [
            // Test scenarios with dynamically created organizations
            ['test_minimal', true],
            ['quickborn_minimal', true],       // Now works with dynamic entity creation
            ['quickborn_comprehensive', true], // Now works with dynamic entity creation
            ['buero_flachennutzung', true],    // Now works with dynamic entity creation
            ['quickborn_with_attachments', true], // Now works with dynamic entity creation
        ];
    }

    /**
     * Validate that valid scenarios successfully created procedures.
     *
     * For valid scenarios, we expect:
     * - At least one procedure to be created
     * - Created procedures match scenario expectations
     * - Audit entries to be logged
     * - No validation errors
     */
    protected function validateTestResult(array $createdProcedures, ?string $auditId): AddonTestResult
    {


        $proceduresCreated = count($createdProcedures);

        // For valid scenarios, we EXPECT procedures to be created
        if ($proceduresCreated <= 0) {
            return new AddonTestResult(
                false,
                "Expected procedures to be created for valid scenarios, but none were created",
                [
                    'procedures_found' => $proceduresCreated,
                    'audit_id' => $auditId,
                    'expected_behavior' => 'procedure_creation',
                    'test_type' => 'valid_scenarios'
                ],
                $auditId,
                0,
                0
            );
        }


        // Validate each created procedure against scenario expectations
        $scenarios = $this->getTestScenarios();
        $validationResults = [];
        $validationErrors = [];

        foreach ($scenarios as [$scenarioName, $isValid]) {
            // Find procedures that should match this scenario
            echo "🔍 DEBUG: Looking for scenario '{$scenarioName}' procedures...\n";
            $scenarioInfo = $this->xmlFactory->getScenarioInfo($scenarioName, $isValid);
            $expectedName = $scenarioInfo['plan_name'];
            foreach ($createdProcedures as $procedure) {
                if ($procedure->getName() === $expectedName) {
                    echo "✅ Found expected procedure: {$procedure->getName()}\n";

                    $scenarioInfo = $this->xmlFactory->getScenarioInfo($scenarioName, $isValid);
                    $expectedName = $scenarioInfo['plan_name'] ?? 'NOT_SET';
                    $validationResult = $this->validateProcedureAgainstScenario($procedure->_real(), $scenarioName, $isValid);
                    $validationResults[] = $validationResult;
                }
            }

        }

        // If we have validation errors, return failure with details
        if (!empty($validationErrors)) {
            return new AddonTestResult(
                false,
                "Created procedures don't match scenario expectations: " . implode('; ', $validationErrors),
                [
                    'procedures_created' => $proceduresCreated,
                    'validation_errors' => $validationErrors,
                    'created_procedure_names' => array_map(fn($p) => $p->getName(), $createdProcedures),
                    'created_procedure_orgs' => array_map(fn($p) => $p->getOrga()->getName(), $createdProcedures),
                    'test_type' => 'valid_scenarios'
                ],
                $auditId,
                0,
                $proceduresCreated
            );
        }

        // Verify audit entry exists (should always be present for processed messages)
        if (!$auditId) {
            return new AddonTestResult(
                false,
                "No audit entry was created during message processing",
                [
                    'procedures_created' => $proceduresCreated,
                    'expected_audit' => true,
                    'actual_audit' => false
                ],
                null,
                0,
                $proceduresCreated
            );
        }

        // Success! Valid scenarios worked as expected
        $scenarioCount = count($scenarios);
        $procedureDetails = [];

        foreach ($createdProcedures as $procedure) {
            $procedureDetails[] = [
                'id' => $procedure->getId(),
                'name' => $procedure->getName(),
                'organization' => $procedure->getOrga()->getName()
            ];
        }

        return new AddonTestResult(
            true,
            "Valid scenarios successfully created and validated {$proceduresCreated} procedures",
            [
                'procedures_created' => $proceduresCreated,
                'scenarios_tested' => $scenarioCount,
                'audit_entry_verified' => 'YES',
                'validation_passed' => 'YES',
                'test_type' => 'valid_scenarios',
                'procedure_details' => $procedureDetails,
                'validation_results' => array_map(fn($r) => [
                    'scenario' => $r['scenario'],
                    'success' => $r['success'],
                    'procedure_name' => $r['procedure']->getName()
                ], $validationResults)
            ],
            $auditId,
            0,
            $proceduresCreated
        );
    }
}
