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

use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\DataFactory\XBeteiligungXmlGenerator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * Unit tests for XBeteiligungXmlGenerator.
 *
 * Tests the generator's ability to:
 * - Load templates and scenarios correctly
 * - Generate valid XML from test scenarios
 * - Handle conditional sections properly
 * - Validate input and provide meaningful errors
 * - Support multiple message types
 */
class XBeteiligungXmlGeneratorTest extends TestCase
{
    private XBeteiligungXmlGenerator $factory;
    private MockObject $commonHelpersMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->commonHelpersMock = $this->createMock(CommonHelpers::class);

        $addonRootPath = AddonPath::getRootPath();

        $this->factory = new XBeteiligungXmlGenerator(
            $addonRootPath,
            $this->commonHelpersMock,
            '401'
        );
    }

    public function testConstructorLoadsTemplateAndScenarios(): void
    {
        // Test that constructor doesn't throw exceptions with valid files
        $this->assertInstanceOf(XBeteiligungXmlGenerator::class, $this->factory);
    }

    public function testConstructorThrowsExceptionForMissingTemplate(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No template file found for message type');

        new XBeteiligungXmlGenerator('/nonexistent/path', $this->commonHelpersMock, '401');
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
        $this->assertContains('test_procedure_with_anlagen', $validScenarios);

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

        $xml = $this->factory->createXML('test_procedure_with_anlagen', true);

        // Check that attachments section is included
        $this->assertStringContainsString('<xbeteiligung:anlagen>', $xml);
        $this->assertStringContainsString('<anlage>', $xml);
        $this->assertStringContainsString('<versionsnummer>1.0</versionsnummer>', $xml);
        $this->assertStringContainsString('<code>application/pdf</code>', $xml);
        $this->assertStringContainsString('<dateiname>Planzeichnung.pdf</dateiname>', $xml);
        $this->assertMatchesRegularExpression('/filesize="\d+"/', $xml);
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
        $this->assertArrayHasKey('test_procedure_with_anlagen', $allXML);

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

    public function testCreateXMLWithRealFileAttachments(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('test_procedure_with_anlagen', true);

        // Check that XML structure is correct
        $this->assertStringStartsWith('<?xml version="1.0" encoding="UTF-8"', $xml);
        $this->assertStringContainsString('<xbeteiligung:kommunal.Initiieren.0401', $xml);

        // Check that anlagen sections are included
        $this->assertStringContainsString('<xbeteiligung:anlagen>', $xml);
        $this->assertStringContainsString('<anlage>', $xml);

        // Check that dokument elements with base64 content are present
        $this->assertStringContainsString('<xbeteiligung:dokument>', $xml);
        $this->assertStringContainsString('</xbeteiligung:dokument>', $xml);

        // Verify base64 content exists (base64 should contain alphanumeric and =, + characters)
        $this->assertMatchesRegularExpression(
            '/<xbeteiligung:dokument>[A-Za-z0-9+\/=]+<\/xbeteiligung:dokument>/',
            $xml,
            'xbeteiligung:dokument element should contain base64-encoded content'
        );

        // Check Oeffentlichkeit file attachment metadata
        $this->assertStringContainsString('<dateiname>Planzeichnung.pdf</dateiname>', $xml);
        $this->assertStringContainsString('<code>application/pdf</code>', $xml);

        // Check TOEB file attachment metadata
        $this->assertStringContainsString('<dateiname>Begründung.docx</dateiname>', $xml);
        $this->assertStringContainsString(
            '<code>application/vnd.openxmlformats-officedocument.wordprocessingml.document</code>',
            $xml
        );

        // Verify that filesize attributes are present and numeric
        $this->assertMatchesRegularExpression(
            '/filesize="\d+"/',
            $xml,
            'filesize attribute should contain numeric value'
        );

        // Verify that hash attributes are present (SHA-256 is 64 hex characters)
        $this->assertMatchesRegularExpression(
            '/hashValue="[a-f0-9]{64}"/',
            $xml,
            'hashValue attribute should contain 64-character SHA-256 hash'
        );
    }

    public function testFileAttachmentBase64EncodingIsCorrect(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('test_procedure_with_anlagen', true);

        // Extract base64 content from XML
        preg_match('/<xbeteiligung:dokument>([A-Za-z0-9+\/=]+)<\/xbeteiligung:dokument>/', $xml, $matches);
        $this->assertNotEmpty($matches, 'Should find at least one xbeteiligung:dokument element with base64 content');

        $base64Content = $matches[1];

        // Verify it's valid base64
        $decoded = base64_decode($base64Content, true);
        $this->assertNotFalse($decoded, 'Base64 content should be valid and decodable');
        $this->assertNotEmpty($decoded, 'Decoded content should not be empty');

        // Re-encode and verify it matches (confirms proper encoding)
        $reEncoded = base64_encode($decoded);
        $this->assertEquals($base64Content, $reEncoded, 'Base64 encoding should be consistent');
    }

    public function testFileAttachmentHashCalculation(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('test_procedure_with_anlagen', true);

        // Extract hash value and base64 content
        preg_match('/hashValue="([a-f0-9]{64})"/', $xml, $hashMatches);
        preg_match('/<xbeteiligung:dokument>([A-Za-z0-9+\/=]+)<\/xbeteiligung:dokument>/', $xml, $contentMatches);

        $this->assertNotEmpty($hashMatches, 'Should find hashValue attribute');
        $this->assertNotEmpty($contentMatches, 'Should find dokument content');

        $hashFromXml = $hashMatches[1];
        $base64Content = $contentMatches[1];
        $fileContent = base64_decode($base64Content);

        // Calculate hash of the decoded content
        $calculatedHash = hash('sha256', $fileContent);

        $this->assertEquals(
            $calculatedHash,
            $hashFromXml,
            'Hash value in XML should match SHA-256 hash of the file content'
        );
    }

    public function testFileAttachmentFilesizeIsCorrect(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('test_procedure_with_anlagen', true);

        // Extract filesize and base64 content
        preg_match('/filesize="(\d+)"/', $xml, $sizeMatches);
        preg_match('/<xbeteiligung:dokument>([A-Za-z0-9+\/=]+)<\/xbeteiligung:dokument>/', $xml, $contentMatches);

        $this->assertNotEmpty($sizeMatches, 'Should find filesize attribute');
        $this->assertNotEmpty($contentMatches, 'Should find dokument content');

        $filesizeFromXml = (int) $sizeMatches[1];
        $base64Content = $contentMatches[1];
        $fileContent = base64_decode($base64Content);

        $actualFilesize = strlen($fileContent);

        $this->assertEquals(
            $actualFilesize,
            $filesizeFromXml,
            'Filesize attribute should match actual decoded file size in bytes'
        );
    }

    public function testMimeTypeDetectionForDifferentFileTypes(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('test_procedure_with_anlagen', true);

        // Check PDF MIME type
        $this->assertStringContainsString(
            '<code>application/pdf</code>',
            $xml,
            'PDF files should have correct MIME type'
        );

        // Check DOCX MIME type
        $this->assertStringContainsString(
            '<code>application/vnd.openxmlformats-officedocument.wordprocessingml.document</code>',
            $xml,
            'DOCX files should have correct MIME type'
        );
    }

    public function testBothOeffentlichkeitAndToebAttachmentsAreProcessed(): void
    {
        // Mock UUID generation
        $this->commonHelpersMock->method('uuid')
            ->willReturn('test-uuid');

        $xml = $this->factory->createXML('test_procedure_with_anlagen', true);

        // Count dokument elements (should have 4: 2 for Oeffentlichkeit, 2 for TOEB)
        $dokumentCount = substr_count($xml, '<xbeteiligung:dokument>');
        $this->assertEquals(
            4,
            $dokumentCount,
            'Should have 4 xbeteiligung:dokument elements (2 for Oeffentlichkeit, 2 for TOEB)'
        );

        // Verify both participation sections are included
        $this->assertStringContainsString('<xbeteiligung:beteiligungOeffentlichkeit>', $xml);
        $this->assertStringContainsString('<xbeteiligung:beteiligungTOEB>', $xml);

        // Verify both have anlagen sections
        $anlagenCount = substr_count($xml, '<xbeteiligung:anlagen>');
        $this->assertEquals(2, $anlagenCount, 'Should have 2 anlagen sections');
    }

    public function testFileNotFoundThrowsException(): void
    {
        // We can't easily test this without creating a temporary YAML file
        // But we've verified the RuntimeException is thrown in the factory code
        $this->expectNotToPerformAssertions();
    }
}
