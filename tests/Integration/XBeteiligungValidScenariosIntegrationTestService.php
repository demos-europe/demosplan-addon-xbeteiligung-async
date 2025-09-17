<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Integration;

use demosplan\DemosPlanCoreBundle\Tests\Integration\AddonTestResult;

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
     * Get valid test scenarios that should result in procedure creation.
     *
     * @return array Array of [scenarioName, isValid] pairs
     */
    protected function getTestScenarios(): array
    {
        return [
            // Start with the simplest valid scenario first
            ['quickborn_minimal', true],

            // Can add more scenarios later:
            // ['quickborn_comprehensive', true],
            // ['buero_flachennutzung', true],
            // ['quickborn_with_attachments', true],
        ];
    }

    /**
     * Validate that valid scenarios successfully created procedures.
     *
     * For valid scenarios, we expect:
     * - At least one procedure to be created
     * - Audit entries to be logged
     * - No validation errors
     */
    protected function validateTestResult(int $initialCount, int $finalCount, ?string $auditId): AddonTestResult
    {
        $proceduresCreated = $finalCount - $initialCount;

        // For valid scenarios, we EXPECT procedures to be created
        if ($proceduresCreated <= 0) {
            return new AddonTestResult(
                false,
                "Expected procedures to be created for valid scenarios, but {$proceduresCreated} were created",
                [
                    'initial_count' => $initialCount,
                    'final_count' => $finalCount,
                    'procedures_created' => $proceduresCreated,
                    'audit_id' => $auditId,
                    'expected_behavior' => 'procedure_creation'
                ],
                $auditId,
                $initialCount,
                $finalCount
            );
        }

        // Verify audit entry exists (should always be present for processed messages)
        if (!$auditId) {
            return new AddonTestResult(
                false,
                "No audit entry was created during message processing",
                [
                    'initial_count' => $initialCount,
                    'final_count' => $finalCount,
                    'procedures_created' => $proceduresCreated,
                    'expected_audit' => true,
                    'actual_audit' => false
                ],
                null,
                $initialCount,
                $finalCount
            );
        }

        // Success! Valid scenarios worked as expected
        $scenarios = $this->getTestScenarios();
        $scenarioCount = count($scenarios);

        return new AddonTestResult(
            true,
            "Valid scenarios successfully processed: {$proceduresCreated} procedures created",
            [
                'procedures_created' => $proceduresCreated,
                'scenarios_tested' => $scenarioCount,
                'audit_entry_verified' => 'YES',
                'validation_passed' => 'YES',
                'test_type' => 'valid_scenarios'
            ],
            $auditId,
            $initialCount,
            $finalCount
        );
    }
}