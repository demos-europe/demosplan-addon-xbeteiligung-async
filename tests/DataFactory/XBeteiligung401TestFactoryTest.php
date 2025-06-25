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

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Unit tests for XBeteiligung401TestFactory.
 * 
 * Tests the factory's ability to:
 * - Load templates and scenarios correctly
 * - Generate valid XML from test scenarios
 * - Handle conditional sections properly
 * - Validate input and provide meaningful errors
 */
class XBeteiligung401TestFactoryTest extends TestCase
{
    private XBeteiligung401TestFactory $factory;
    private MockObject $commonHelpersMock;
    private string $testAddonPath;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->commonHelpersMock = $this->createMock(CommonHelpers::class);
        $this->testAddonPath = __DIR__ . '/../../'; // Point to addon root
        
        $this->factory = new XBeteiligung401TestFactory(
            $this->testAddonPath,
            $this->commonHelpersMock
        );
    }

    public function testConstructorLoadsTemplateAndScenarios(): void
    {
        // Test that constructor doesn't throw exceptions with valid files
        $this->assertInstanceOf(XBeteiligung401TestFactory::class, $this->factory);
    }

    public function testConstructorThrowsExceptionForMissingTemplate(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Template file not found');
        
        new XBeteiligung401TestFactory('/nonexistent/path', $this->commonHelpersMock);
    }

    public function testGetAvailableScenarios(): void
    {
        $scenarios = $this->factory->getAvailableScenarios();
        
        $this->assertIsArray($scenarios);
        $this->assertArrayHasKey('valid', $scenarios);
        $this->assertArrayHasKey('invalid', $scenarios);
        
        // Check that we have the expected valid scenarios
        $validScenarios = $scenarios['valid'];
        $this->assertContains('quickborn_minimal', $validScenarios);
        $this->assertContains('quickborn_comprehensive', $validScenarios);
        $this->assertContains('buero_flachennutzung', $validScenarios);
        $this->assertContains('quickborn_with_attachments', $validScenarios);
        
        // Check that we have the expected invalid scenarios  
        $invalidScenarios = $scenarios['invalid'];
        $this->assertContains('unknown_organization', $invalidScenarios);
        $this->assertContains('empty_organization', $invalidScenarios);
        $this->assertContains('missing_plan_name', $invalidScenarios);
    }

    public function testGetScenarioInfoForValidScenario(): void
    {
        $info = $this->factory->getScenarioInfo('quickborn_minimal', true);
        
        $this->assertIsArray($info);
        $this->assertEquals('quickborn_minimal', $info['name']);
        $this->assertEquals('Test minimal procedure creation with Stadt Quickborn', $info['description']);
        $this->assertTrue($info['is_valid']);
        $this->assertNull($info['expected_error']);
    }

    public function testGetScenarioInfoForInvalidScenario(): void
    {
        $info = $this->factory->getScenarioInfo('unknown_organization', false);
        
        $this->assertIsArray($info);
        $this->assertEquals('unknown_organization', $info['name']);
        $this->assertFalse($info['is_valid']);
        $this->assertNotNull($info['expected_error']);
        $this->assertStringContainsString('Musterzuständigkeit', $info['expected_error']);
    }

    public function testGetScenarioInfoThrowsExceptionForNonExistentScenario(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Scenario 'nonexistent' not found in valid scenarios");
        
        $this->factory->getScenarioInfo('nonexistent', true);
    }

    public function testCreateXMLForMinimalScenario(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->expects($this->atLeast(3))
            ->method('uuid')
            ->willReturnOnConsecutiveCalls(
                'test-message-uuid',
                'test-vorgangs-uuid', 
                'test-plan-uuid'
            );

        $xml = $this->factory->createXML('quickborn_minimal', true);
        
        // Basic XML structure validation
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"', $xml);
        $this->assertStringContainsString('<xbeteiligung:kommunal.Initiieren.0401', $xml);
        $this->assertStringContainsString('</xbeteiligung:kommunal.Initiieren.0401>', $xml);
        
        // Check organization name is correctly injected
        $this->assertStringContainsString('<name>Stadt Quickborn</name>', $xml);
        
        // Check plan details
        $this->assertStringContainsString(
            '<xbeteiligung:planname>Test Bebauungsplan Minimal</xbeteiligung:planname>',
            $xml
        );
        $this->assertStringContainsString('<code>6_Bebauungsplan</code>', $xml);
        
        // Check UUIDs are injected
        $this->assertStringContainsString('test-message-uuid', $xml);
        $this->assertStringContainsString('ID_test-vorgangs-uuid', $xml);
        $this->assertStringContainsString('ID_test-plan-uuid', $xml);
        
        // Check that optional sections are NOT included for minimal scenario
        $this->assertStringNotContainsString('<xbeteiligung:beteiligungOeffentlichkeit>', $xml);
        $this->assertStringNotContainsString('<xbeteiligung:beteiligungTOEB>', $xml);
    }

    public function testCreateXMLForComprehensiveScenario(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->expects($this->atLeast(3))
            ->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('quickborn_comprehensive', true);
        
        // Check that public participation section IS included
        $this->assertStringContainsString('<xbeteiligung:beteiligungOeffentlichkeit>', $xml);
        $this->assertStringContainsString(
            '<xbeteiligung:beteiligungsID>bet_oeffentlich_001</xbeteiligung:beteiligungsID>',
            $xml
        );
        $this->assertStringContainsString('<xbeteiligung:durchgang>1</xbeteiligung:durchgang>', $xml);
        
        // Check that TOEB section is NOT included
        $this->assertStringNotContainsString('<xbeteiligung:beteiligungTOEB>', $xml);
    }

    public function testCreateXMLForScenarioWithTOEB(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->expects($this->atLeast(3))
            ->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('buero_flachennutzung', true);
        
        // Check organization name
        $this->assertStringContainsString('<name>Büro</name>', $xml);
        
        // Check plan type for Flächennutzungsplan
        $this->assertStringContainsString('<code>3_Flaechennutzungsplan</code>', $xml);
        
        // Check that TOEB section IS included
        $this->assertStringContainsString('<xbeteiligung:beteiligungTOEB>', $xml);
        $this->assertStringContainsString(
            '<xbeteiligung:beteiligungsID>bet_toeb_001</xbeteiligung:beteiligungsID>',
            $xml
        );
        
        // Check that public participation section is NOT included
        $this->assertStringNotContainsString('<xbeteiligung:beteiligungOeffentlichkeit>', $xml);
    }

    public function testCreateXMLForScenarioWithAttachments(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->expects($this->atLeast(3))
            ->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('quickborn_with_attachments', true);
        
        // Check that attachments section is included
        $this->assertStringContainsString('<xbeteiligung:anlagen>', $xml);
        $this->assertStringContainsString('<anlage>', $xml);
        $this->assertStringContainsString('<versionsnummer>1.0</versionsnummer>', $xml);
        $this->assertStringContainsString('<code>application/pdf</code>', $xml);
        $this->assertStringContainsString('<dateiname>bebauungsplan-entwurf.pdf</dateiname>', $xml);
        $this->assertStringContainsString('filesize="3908"', $xml);
    }

    public function testCreateXMLForInvalidScenario(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->expects($this->atLeast(3))
            ->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('unknown_organization', false);
        
        // Should still generate valid XML structure, just with invalid org name
        $this->assertStringContainsString('<name>Musterzuständigkeit</name>', $xml);
        $this->assertStringContainsString('<xbeteiligung:planname>Test Plan Unknown Org</xbeteiligung:planname>', $xml);
    }

    public function testCreateXMLThrowsExceptionForNonExistentScenario(): void
    {
        $this->expectException(InvalidArgumentException::class);
        
        $this->factory->createXML('nonexistent_scenario', true);
    }

    public function testCreateAllXMLForValidScenarios(): void
    {
        // Mock UUID generation for multiple calls
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $allXML = $this->factory->createAllXML(true);
        
        $this->assertIsArray($allXML);
        $this->assertNotEmpty($allXML);
        
        // Check that all valid scenarios are included
        $this->assertArrayHasKey('quickborn_minimal', $allXML);
        $this->assertArrayHasKey('quickborn_comprehensive', $allXML);
        $this->assertArrayHasKey('buero_flachennutzung', $allXML);
        $this->assertArrayHasKey('quickborn_with_attachments', $allXML);
        
        // Check that each returns valid XML
        foreach ($allXML as $scenarioName => $xml) {
            $this->assertStringStartsWith(
                '<?xml',
                $xml,
                "XML for scenario {$scenarioName} should start with XML declaration"
            );
            $this->assertStringContainsString(
                '<xbeteiligung:kommunal.Initiieren.0401',
                $xml,
                "XML for scenario {$scenarioName} should contain root element"
            );
        }
    }

    public function testCreateAllXMLForInvalidScenarios(): void
    {
        // Mock UUID generation for multiple calls
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $allXML = $this->factory->createAllXML(false);
        
        $this->assertIsArray($allXML);
        $this->assertNotEmpty($allXML);
        
        // Check that invalid scenarios are included
        $this->assertArrayHasKey('unknown_organization', $allXML);
        $this->assertArrayHasKey('empty_organization', $allXML);
        
        // Check that each returns XML (even if it will fail validation)
        foreach ($allXML as $scenarioName => $xml) {
            $this->assertStringStartsWith(
                '<?xml',
                $xml,
                "XML for invalid scenario {$scenarioName} should still be structurally valid XML"
            );
        }
    }

    public function testTimestampGeneration(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('quickborn_minimal', true);
        
        // Check that a timestamp is generated in ISO format
        $this->assertMatchesRegularExpression(
            '/<g2g:erstellungszeitpunkt>\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{3}Z<\/g2g:erstellungszeitpunkt>/',
            $xml,
            'XML should contain a properly formatted timestamp'
        );
    }

    public function testDefaultValuesAreMerged(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('quickborn_minimal', true);
        
        // Check that default values from YAML are used
        $this->assertStringContainsString('produkthersteller="DEMOS plan GmbH"', $xml);
        $this->assertStringContainsString('produkt="DiPlan Cockpit"', $xml);
        $this->assertStringContainsString('<behoerde:name>DEMOS plan GmbH</behoerde:name>', $xml);
        $this->assertStringContainsString('<code>4000</code>', $xml); // Default verfahrensschritt_code
    }
}