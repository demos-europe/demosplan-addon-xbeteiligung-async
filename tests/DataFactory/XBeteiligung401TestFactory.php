<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory;

use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\Yaml\Yaml;

/**
 * Factory for generating dynamic XBeteiligung 401 test XML messages.
 *
 * This class provides a dynamic approach to XML test generation by:
 * - Loading XML templates with placeholders
 * - Reading test scenario configurations from YAML
 * - Generating XML with injected test data
 * - Supporting both valid and invalid test scenarios
 */
class XBeteiligung401TestFactory
{
    private const TEMPLATE_FILE = 'tests/fixtures/xbeteiligung/templates/kommunal-initiieren-0401-template.xml';
    private const SCENARIOS_BASE_DIR = 'tests/fixtures/xbeteiligung/test-data/401';
    private const DEFAULTS_FILE = 'defaults.yml';
    private const GEOJSON_DIR = 'geojson';
    private const VALID_SCENARIOS_DIR = 'scenarios/valid';
    private const INVALID_SCENARIOS_DIR = 'scenarios/invalid';

    private string $templateContent;
    private array $scenariosConfig;
    private array $defaults;

    public function __construct(
        private readonly string $addonRootPath,
        private readonly CommonHelpers $commonHelpers
    ) {
        $this->loadTemplate();
        $this->loadScenarios();
    }

    /**
     * Generate XML for a specific test scenario.
     *
     * @param string $scenarioName Name of the scenario from YAML config
     * @param bool $isValid Whether to use valid or invalid scenarios
     * @return string Generated XML content
     * @throws InvalidArgumentException If scenario not found
     */
    public function createXML(string $scenarioName, bool $isValid = true): string
    {
        $scenarioData = $this->getScenarioData($scenarioName, $isValid);
        $parameters = $this->buildParameters($scenarioData);

        return $this->processTemplate($parameters);
    }

    /**
     * Get all available test scenarios.
     *
     * @return array Array with 'valid' and 'invalid' scenario lists
     */
    public function getAvailableScenarios(): array
    {
        return [
            'valid' => array_keys($this->scenariosConfig['valid_scenarios'] ?? []),
            'invalid' => array_keys($this->scenariosConfig['invalid_scenarios'] ?? [])
        ];
    }

    /**
     * Get scenario data including description and expected behavior.
     *
     * @param string $scenarioName Name of the scenario
     * @param bool $isValid Whether to look in valid or invalid scenarios
     * @return array Scenario configuration data
     */
    public function getScenarioInfo(string $scenarioName, bool $isValid = true): array
    {
        $scenario = $this->getScenarioData($scenarioName, $isValid);

        return [
            'name' => $scenarioName,
            'description' => $scenario['description'] ?? 'No description available',
            'expected_error' => $scenario['expected_error'] ?? null,
            'expected_error_type' => $scenario['expected_error_type'] ?? null,
            'is_valid' => $isValid
        ];
    }

    /**
     * Generate XML for all scenarios of a given type.
     *
     * @param bool $isValid Whether to generate valid or invalid scenarios
     * @return array Array of scenario_name => xml_content
     */
    public function createAllXML(bool $isValid = true): array
    {
        $scenarios = $isValid
            ? $this->scenariosConfig['valid_scenarios'] ?? []
            : $this->scenariosConfig['invalid_scenarios'] ?? [];

        $results = [];
        foreach (array_keys($scenarios) as $scenarioName) {
            $results[$scenarioName] = $this->createXML($scenarioName, $isValid);
        }

        return $results;
    }

    /**
     * Load the XML template from file.
     */
    private function loadTemplate(): void
    {
        $templatePath = $this->addonRootPath . '/' . self::TEMPLATE_FILE;

        if (!file_exists($templatePath)) {
            throw new RuntimeException("Template file not found: {$templatePath}");
        }

        $this->templateContent = file_get_contents($templatePath);

        if (false === $this->templateContent) {
            throw new RuntimeException("Failed to read template file: {$templatePath}");
        }
    }

    /**
     * Load scenario configurations from individual YAML files and shared defaults.
     */
    private function loadScenarios(): void
    {
        $baseDir = $this->addonRootPath . '/' . self::SCENARIOS_BASE_DIR;

        if (!is_dir($baseDir)) {
            throw new RuntimeException("Scenarios base directory not found: {$baseDir}");
        }

        // Load defaults
        $this->loadDefaults($baseDir);

        // Load individual scenario files
        $this->scenariosConfig = [
            'valid_scenarios' => $this->loadScenariosFromDirectory($baseDir . '/' . self::VALID_SCENARIOS_DIR),
            'invalid_scenarios' => $this->loadScenariosFromDirectory($baseDir . '/' . self::INVALID_SCENARIOS_DIR)
        ];
    }

    /**
     * Load shared defaults from defaults.yml file.
     */
    private function loadDefaults(string $baseDir): void
    {
        $defaultsPath = $baseDir . '/' . self::DEFAULTS_FILE;

        if (!file_exists($defaultsPath)) {
            throw new RuntimeException("Defaults file not found: {$defaultsPath}");
        }

        $this->defaults = Yaml::parseFile($defaultsPath);

        // Load GeoJSON for defaults if referenced
        $this->defaults = $this->loadGeoJsonForScenario($this->defaults, $baseDir);
    }

    /**
     * Load all scenario files from a directory.
     */
    private function loadScenariosFromDirectory(string $directory): array
    {
        if (!is_dir($directory)) {
            return [];
        }

        $scenarios = [];
        $files = glob($directory . '/*.yml');

        foreach ($files as $file) {
            $scenarioName = pathinfo($file, PATHINFO_FILENAME);
            $scenarioData = Yaml::parseFile($file);

            // Load GeoJSON if referenced
            $scenarioData = $this->loadGeoJsonForScenario($scenarioData, dirname($directory, 2));

            $scenarios[$scenarioName] = $scenarioData;
        }

        return $scenarios;
    }

    /**
     * Load GeoJSON data for a scenario if it references a GeoJSON file.
     */
    private function loadGeoJsonForScenario(array $scenarioData, string $baseDir): array
    {
        if (isset($scenarioData['geltungsbereich_geojson_file'])) {
            $geoJsonPath = $baseDir . '/' . $scenarioData['geltungsbereich_geojson_file'];

            if (file_exists($geoJsonPath)) {
                $geoJsonContent = file_get_contents($geoJsonPath);
                if ($geoJsonContent !== false) {
                    // Replace the file reference with the actual GeoJSON content
                    $scenarioData['geltungsbereich_json'] = $geoJsonContent;
                    unset($scenarioData['geltungsbereich_geojson_file']);
                }
            }
        }

        return $scenarioData;
    }

    /**
     * Get scenario data by name and type.
     */
    private function getScenarioData(string $scenarioName, bool $isValid): array
    {
        $scenarioKey = $isValid ? 'valid_scenarios' : 'invalid_scenarios';
        $scenarios = $this->scenariosConfig[$scenarioKey] ?? [];

        if (!isset($scenarios[$scenarioName])) {
            $type = $isValid ? 'valid' : 'invalid';
            throw new InvalidArgumentException("Scenario '{$scenarioName}' not found in {$type} scenarios");
        }

        return $scenarios[$scenarioName];
    }

    /**
     * Build complete parameter set by merging defaults with scenario data.
     */
    private function buildParameters(array $scenarioData): array
    {
        // Start with defaults
        $parameters = $this->defaults;

        // Merge scenario-specific data
        $parameters = array_merge($parameters, $scenarioData);

        // Generate dynamic values
        $parameters = $this->addDynamicParameters($parameters);

        // Convert to template placeholder format
        return $this->convertToPlaceholders($parameters);
    }

    /**
     * Add dynamic parameters like UUIDs and timestamps.
     */
    private function addDynamicParameters(array $parameters): array
    {
        // Generate unique IDs if not provided
        $parameters['nachrichten_uuid'] = $parameters['nachrichten_uuid'] ?? $this->commonHelpers->uuid();
        $parameters['vorgangs_id'] = $parameters['vorgangs_id'] ?? 'ID_' . $this->commonHelpers->uuid();
        $parameters['plan_id'] = $parameters['plan_id'] ?? 'ID_' . $this->commonHelpers->uuid();

        // Generate timestamp if not provided
        $parameters['erstellungszeitpunkt'] = $parameters['erstellungszeitpunkt'] ??
            (new DateTime())->format('Y-m-d\TH:i:s.v\Z');

        return $parameters;
    }

    /**
     * Convert parameter keys to template placeholder format (UPPER_CASE).
     */
    private function convertToPlaceholders(array $parameters): array
    {
        $placeholders = [];

        foreach ($parameters as $key => $value) {
            // Convert snake_case to UPPER_CASE for template placeholders
            $placeholderKey = strtoupper($key);
            $placeholders[$placeholderKey] = $value;
        }

        return $placeholders;
    }

    /**
     * Process template by replacing placeholders and handling conditional sections.
     */
    private function processTemplate(array $parameters): string
    {
        $xml = $this->templateContent;

        // Handle conditional sections first
        $xml = $this->processConditionalSections($xml, $parameters);

        // Replace simple placeholders
        $xml = $this->replacePlaceholders($xml, $parameters);

        return $xml;
    }

    /**
     * Process conditional sections like {{#INCLUDE_BETEILIGUNG_OEFFENTLICHKEIT}}.
     */
    private function processConditionalSections(string $xml, array $parameters): string
    {
        // Process beteiligungOeffentlichkeit section
        $xml = $this->processConditionalSection(
            $xml,
            'INCLUDE_BETEILIGUNG_OEFFENTLICHKEIT',
            $parameters['INCLUDE_BETEILIGUNG_OEFFENTLICHKEIT'] ?? false
        );

        // Process beteiligungTOEB section
        $xml = $this->processConditionalSection(
            $xml,
            'INCLUDE_BETEILIGUNG_TOEB',
            $parameters['INCLUDE_BETEILIGUNG_TOEB'] ?? false
        );

        // Process anlagen sections
        $xml = $this->processConditionalSection(
            $xml,
            'INCLUDE_ANLAGEN_OEFFENTLICHKEIT',
            $parameters['INCLUDE_ANLAGEN_OEFFENTLICHKEIT'] ?? false
        );

        $xml = $this->processConditionalSection(
            $xml,
            'INCLUDE_ANLAGEN_TOEB',
            $parameters['INCLUDE_ANLAGEN_TOEB'] ?? false
        );

        return $xml;
    }

    /**
     * Process a single conditional section.
     */
    private function processConditionalSection(string $xml, string $sectionName, bool $include): string
    {
        $startTag = "{{#$sectionName}}";
        $endTag = "{{/$sectionName}}";

        $pattern = '/\s*' . preg_quote($startTag, '/') . '(.*?)' . preg_quote($endTag, '/') . '\s*/s';

        if ($include) {
            // Keep content, remove conditional tags
            $xml = preg_replace($pattern, '$1', $xml);
        } else {
            // Remove entire section
            $xml = preg_replace($pattern, '', $xml);
        }

        return $xml;
    }

    /**
     * Replace simple {{PLACEHOLDER}} patterns with actual values.
     */
    private function replacePlaceholders(string $xml, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $placeholder = '{{' . $key . '}}';
            $xml = str_replace($placeholder, (string) $value, $xml);
        }

        return $xml;
    }
}
