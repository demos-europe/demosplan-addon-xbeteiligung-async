<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligingService;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\MapProjectionConverterInterface;
use proj4php\Proj;
use proj4php\Proj4php;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungPhaseDefinitionCode;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Tests specifically for BPlan layer handling in XBeteiligung messages.
 *
 * This test class verifies the fix for ADO-45461 where geodata was not correctly
 * transmitted to K3 because the wrong GIS layer type was used.
 *
 * BUG: Original code looked for layers with type='base' (basemap layers)
 * FIX: New code looks for layers with isBplan()=true (procedure boundary layers)
 */
class XBeteiligungServiceBPlanLayerTest extends TestCase
{
    protected XBeteiligungService $sut;
    protected MockObject $gisLayerCategoryRepository;
    protected MockObject $logger;
    protected MockObject $mapProjectionConverter;
    protected MockObject $phaseDefinitionCodeRepository;

    // WGS84 polygon returned by the mock converter (Hamburg area, within Germany's bounds)
    protected const WGS84_POLYGON = '{"type":"Polygon","coordinates":[[[10.02,53.55],[10.04,53.55],[10.04,53.56],[10.02,53.56],[10.02,53.55]]]}';
    protected const TERRITORY_DATA = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[10.083502,53.475728],[10.083429,53.475761],[10.083502,53.475728]]]},"properties":null}]}';

    // Realistic territory format from the DB: 1-feature FeatureCollection in EPSG:3857 (Web Mercator)
    // Coordinates around Hamburg (≈ 10.02°E, 51.5°N in WGS84)
    protected const TERRITORY_EPSG3857 = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[1116296.9705734858,6634813.663749559],[1117905.9884860306,6634187.8624979565],[1117301.031359643,6636161.866445964],[1115603.7905328334,6635901.465925163],[1116296.9705734858,6634813.663749559]]]},"properties":null}]}';

    // Legacy territory format from older DB records: bare Polygon directly (no FeatureCollection wrapper), also in EPSG:3857
    // Coordinates from diplanbau_develop_2025_10_08 snapshot (≈ 10.0°E, 53.5°N in WGS84, Hamburg area)
    protected const TERRITORY_LEGACY_POLYGON_EPSG3857 = '{"type":"Polygon","coordinates":[[[1111896.0216485453,7083012.661495186],[1113000.0,7084000.0],[1112000.0,7085000.0],[1111896.0216485453,7083012.661495186]]]}';

    protected function setUp(): void
    {
        parent::setUp();

        $this->gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $codeMapping = $this->createMock(XBeteiligungPhaseDefinitionCode::class);
        $codeMapping->method('getCode')->willReturn('1000');
        $this->phaseDefinitionCodeRepository = $this->createMock(XBeteiligungPhaseDefinitionCodeRepository::class);
        $this->phaseDefinitionCodeRepository->method('findOneByPhaseDefinition')->willReturn($codeMapping);

        $proj4 = new Proj4php();
        $this->mapProjectionConverter = $this->createMock(MapProjectionConverterInterface::class);
        $this->mapProjectionConverter->method('getProjection')
            ->willReturnCallback(fn(string $name) => new Proj($name, $proj4));
        $this->mapProjectionConverter->method('convertGeoJsonPolygon')
            ->willReturn(json_decode('{"type":"FeatureCollection","features":[{"type":"Feature","geometry":' . self::WGS84_POLYGON . ',"properties":null}]}'));

        $globalConfigMock = $this->createMock(GlobalConfigInterface::class);
        $globalConfigMock->method('getMapDefaultProjection')
            ->willReturn([
                'label' => 'EPSG:3857',
                'value' => '+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext  +no_defs'
            ]);

        $reusableMessageBlocks = new ReusableMessageBlocks(
            new CommonHelpers($this->createMock(LoggerInterface::class))
        );

        $this->sut = new XBeteiligungService(
            $this->gisLayerCategoryRepository,
            $globalConfigMock,
            $this->logger,
            $this->mapProjectionConverter,
            $this->createMock(ParameterBagInterface::class),
            $this->createMock(PlanningDocumentsLinkCreator::class),
            $this->createMock(ProcedureMessageRepository::class),
            $this->createMock(ProcedureNewsServiceInterface::class),
            $this->createMock(RouterInterface::class),
            $this->createMock(XBeteiligungIncomingMessageParser::class),
            $this->createMock(CommonHelpers::class),
            $reusableMessageBlocks,
            $this->createMock(XBeteiligungAuditService::class),
            $this->phaseDefinitionCodeRepository,
        );
    }

    /**
     * TEST 1: Verify that BPlan layer is used (not base layer)
     *
     * This test would have FAILED with the old buggy code.
     * The old code looked for type='base', this test verifies it looks for isBplan()=true.
     */
    public function testGenerateMessageUsesBPlanLayerNotBaseLayer(): void
    {
        $procedure = $this->createTestProcedureWithLayers([
            $this->createBaseLayer(),  // Base layer (basemap)
            $this->createBPlanLayer(), // BPlan layer (procedure boundary) ← Should use this one
        ]);

        $xml = $this->sut->createProcedureNew401FromObject($procedure);

        // Verify the XML contains the BPlan layer URL, not the basemap URL
        self::assertStringContainsString(
            'https://example.com/wms/bplan',
            $xml,
            'XML should contain BPlan layer URL'
        );

        self::assertStringNotContainsString(
            'basemapde',
            $xml,
            'XML should NOT contain basemap URL'
        );
    }

    /**
     * TEST 2: Verify behavior when only base layer exists (no BPlan layer)
     *
     * This simulates the bug scenario: procedure has only basemap, no BPlan layer.
     * Expected: flaechenabgrenzungUrl should be missing (null returned from method).
     */
    public function testGenerateMessageWithoutBPlanLayerLogsWarning(): void
    {
        $procedure = $this->createTestProcedureWithLayers([
            $this->createBaseLayer(), // Only base layer, no BPlan layer
        ]);

        // Expect warning to be logged when no BPlan layer found
        $this->logger->expects($this->once())
            ->method('warning')
            ->with(
                $this->stringContains('No enabled BPlan layer found'),
                $this->arrayHasKey('procedureId')
            );

        $xml = $this->sut->createProcedureNew401FromObject($procedure);

        // flaechenabgrenzungUrl will be missing in XML when no BPlan layer exists
        // We can't easily verify it's missing without parsing XML, but the warning proves it
        self::assertNotEmpty($xml);
    }

    /**
     * TEST 3: Verify that disabled BPlan layer is not used
     *
     * Even if a BPlan layer exists, it must be enabled to be used.
     */
    public function testGenerateMessageIgnoresDisabledBPlanLayer(): void
    {
        // Create a disabled BPlan layer (don't reuse createBPlanLayer to avoid mock conflicts)
        $disabledBPlanLayer = $this->createMock(GisLayerInterface::class);
        $disabledBPlanLayer->method('getName')->willReturn('Disabled Plangebiet');
        $disabledBPlanLayer->method('isEnabled')->willReturn(false); // DISABLED
        $disabledBPlanLayer->method('isBplan')->willReturn(true);
        $disabledBPlanLayer->method('getUrl')->willReturn('https://should-not-be-used.com');

        $procedure = $this->createTestProcedureWithLayers([
            $disabledBPlanLayer,
            $this->createBaseLayer(),
        ]);

        // Expect warning because BPlan layer is disabled
        $this->logger->expects($this->once())
            ->method('warning')
            ->with($this->stringContains('No enabled BPlan layer found'));

        $xml = $this->sut->createProcedureNew401FromObject($procedure);
        self::assertNotEmpty($xml);

        // Verify disabled layer URL is NOT in the XML
        self::assertStringNotContainsString(
            'should-not-be-used.com',
            $xml,
            'Disabled BPlan layer should not be used'
        );
    }

    /**
     * TEST 4: Verify multiple layers with BPlan - uses first enabled one
     *
     * If multiple BPlan layers exist, should use the first enabled one.
     */
    public function testGenerateMessageWithMultipleBPlanLayersUsesFirstEnabled(): void
    {
        // Create first BPlan layer with unique URL
        $bplanLayer1 = $this->createMock(GisLayerInterface::class);
        $bplanLayer1->method('getName')->willReturn('Plangebiet First');
        $bplanLayer1->method('getUrl')->willReturn('https://example.com/wms/bplan-first');
        $bplanLayer1->method('getLayerVersion')->willReturn('1.3.0');
        $bplanLayer1->method('getLayers')->willReturn('first_boundary');
        $bplanLayer1->method('getType')->willReturn('overlay');
        $bplanLayer1->method('isEnabled')->willReturn(true);
        $bplanLayer1->method('isBplan')->willReturn(true);
        $bplanLayer1->method('getProjectionLabel')->willReturn('EPSG:3857');

        // Create second BPlan layer with different URL
        $bplanLayer2 = $this->createMock(GisLayerInterface::class);
        $bplanLayer2->method('getName')->willReturn('Plangebiet Second');
        $bplanLayer2->method('getUrl')->willReturn('https://example.com/wms/bplan-second');
        $bplanLayer2->method('getLayerVersion')->willReturn('1.3.0');
        $bplanLayer2->method('getLayers')->willReturn('second_boundary');
        $bplanLayer2->method('getType')->willReturn('overlay');
        $bplanLayer2->method('isEnabled')->willReturn(true);
        $bplanLayer2->method('isBplan')->willReturn(true);
        $bplanLayer2->method('getProjectionLabel')->willReturn('EPSG:3857');

        $procedure = $this->createTestProcedureWithLayers([
            $bplanLayer1, // Should use this one (first)
            $bplanLayer2,
        ]);

        $xml = $this->sut->createProcedureNew401FromObject($procedure);

        // Should use the first BPlan layer
        self::assertStringContainsString(
            'bplan-first',
            $xml,
            'XML should contain first BPlan layer URL'
        );

        self::assertStringNotContainsString(
            'bplan-second',
            $xml,
            'XML should NOT contain second BPlan layer URL'
        );
    }

    /**
     * TEST 5: Verify Geltungsbereich is included when territory data exists
     *
     * The geltungsbereich element should contain the territory data.
     */
    public function testGenerateMessageIncludesGeltungsbereichFromTerritoryData(): void
    {
        $procedure = $this->createTestProcedureWithLayers([
            $this->createBPlanLayer(),
        ]);

        $xml = $this->sut->createProcedureNew401FromObject($procedure);

        // Verify geltungsbereich element is present with polygon coordinates (with namespace)
        self::assertStringContainsString(
            'geltungsbereich>',
            $xml,
            'XML should contain geltungsbereich element'
        );

        self::assertStringContainsString(
            '"type":"Polygon"',
            $xml,
            'Geltungsbereich should contain Polygon geometry'
        );

        self::assertStringContainsString(
            '"coordinates"',
            $xml,
            'Geltungsbereich should contain coordinates'
        );
    }

    /**
     * TEST 6: Verify warning when territory data is missing
     *
     * If procedure has no territory data, geltungsbereich will be missing.
     */
    public function testGenerateMessageLogsWarningWhenTerritoryDataMissing(): void
    {
        $procedure = $this->createTestProcedureWithLayers([
            $this->createBPlanLayer(),
        ], null); // No territory data

        // Expect warning about missing territory data
        $this->logger->expects($this->once())
            ->method('warning')
            ->with(
                $this->stringContains('no territory data'),
                $this->arrayHasKey('procedureId')
            );

        $xml = $this->sut->createProcedureNew401FromObject($procedure);
        self::assertNotEmpty($xml);
    }

    /**
     * TEST 7: Integration test - verify complete message with BPlan layer and territory
     *
     * This is the "happy path" - everything configured correctly.
     */
    public function testGenerateCompleteMessageWithBPlanLayerAndTerritory(): void
    {
        $procedure = $this->createTestProcedureWithLayers([
            $this->createBaseLayer(),  // Basemap (should be ignored)
            $this->createBPlanLayer(), // BPlan layer (should be used)
        ]);

        $xml = $this->sut->createProcedureNew401FromObject($procedure);

        // Verify it's valid XML
        self::assertNotEmpty($xml);
        self::assertStringContainsString('<?xml', $xml);

        // Verify BPlan layer URL is used
        self::assertStringContainsString('https://example.com/wms/bplan', $xml);
        self::assertStringNotContainsString('basemapde', $xml);

        // Verify geltungsbereich is included (without < to handle namespace prefix)
        self::assertStringContainsString('geltungsbereich>', $xml);
        self::assertStringContainsString('"type":"Polygon"', $xml);

        // Note: Full XML schema validation is not performed here because this test uses
        // simplified mocks. The base XBeteiligungServiceTest already validates full XML
        // generation with complete procedure mocks. This test focuses specifically on
        // verifying that BPlan layers (not base layers) are used for flaechenabgrenzungUrl.
    }

    /**
     * TEST 8: Verify Geltungsbereich is converted from EPSG:3857 to WGS84 for outgoing messages
     *
     * Territory is stored in the database as EPSG:3857 (Web Mercator) coordinates.
     * The outgoing XBeteiligung message must contain WGS84/EPSG:4326 coordinates per GeoJSON RFC 7946.
     */
    public function testGeltungsbereichIsConvertedFromEpsg3857ToWgs84(): void
    {
        $procedure = $this->createTestProcedureWithLayers(
            [$this->createBPlanLayer()],
            self::TERRITORY_EPSG3857
        );

        $xml = $this->sut->createProcedureNew401FromObject($procedure);

        self::assertStringContainsString('geltungsbereich>', $xml, 'XML should contain geltungsbereich element');
        $this->assertGeltungsbereichIsWgs84($xml);
    }

    /**
     * Legacy territory format (bare Polygon without FeatureCollection wrapper) is also stored in EPSG:3857.
     * Verified against the diplanbau_develop_2025_10_08 DB snapshot.
     */
    public function testLegacyPolygonTerritoryIsConvertedFromEpsg3857ToWgs84(): void
    {
        $procedure = $this->createTestProcedureWithLayers(
            [$this->createBPlanLayer()],
            self::TERRITORY_LEGACY_POLYGON_EPSG3857
        );

        $xml = $this->sut->createProcedureNew401FromObject($procedure);

        $this->assertGeltungsbereichIsWgs84($xml);
    }

    private function assertGeltungsbereichIsWgs84(string $xml): void
    {
        preg_match('/<[^>]*geltungsbereich[^>]*>(.*?)<\/[^>]*geltungsbereich>/', $xml, $matches);
        self::assertNotEmpty($matches[1], 'Geltungsbereich should have content');

        $geltungsbereich = json_decode(html_entity_decode($matches[1]), true);
        self::assertNotNull($geltungsbereich, 'Geltungsbereich should be valid JSON');
        self::assertSame('Polygon', $geltungsbereich['type']);

        foreach ($geltungsbereich['coordinates'][0] as $coord) {
            self::assertGreaterThan(-180, $coord[0], 'Longitude must be > -180 (WGS84)');
            self::assertLessThan(180, $coord[0], 'Longitude must be < 180 (WGS84)');
            self::assertGreaterThan(-90, $coord[1], 'Latitude must be > -90 (WGS84)');
            self::assertLessThan(90, $coord[1], 'Latitude must be < 90 (WGS84)');
        }
    }

    /**
     * Helper: Create a base layer (basemap)
     */
    private function createBaseLayer(): MockObject
    {
        $layer = $this->createMock(GisLayerInterface::class);
        $layer->method('getName')->willReturn('BaseMap DE');
        $layer->method('getUrl')->willReturn('https://sgx.geodatenzentrum.de/wms_basemapde');
        $layer->method('getLayerVersion')->willReturn('1.3.0');
        $layer->method('getLayers')->willReturn('de_basemapde_web_raster_farbe');
        $layer->method('getType')->willReturn('base');
        $layer->method('isEnabled')->willReturn(true);
        $layer->method('isBplan')->willReturn(false); // Not a BPlan layer
        $layer->method('getProjectionLabel')->willReturn('EPSG:3857');

        return $layer;
    }

    /**
     * Helper: Create a BPlan layer (procedure boundary)
     */
    private function createBPlanLayer(): MockObject
    {
        $layer = $this->createMock(GisLayerInterface::class);
        $layer->method('getName')->willReturn('Plangebiet');
        $layer->method('getUrl')->willReturn('https://example.com/wms/bplan');
        $layer->method('getLayerVersion')->willReturn('1.3.0');
        $layer->method('getLayers')->willReturn('bplan_boundary');
        $layer->method('getType')->willReturn('overlay');
        $layer->method('isEnabled')->willReturn(true);
        $layer->method('isBplan')->willReturn(true); // This is a BPlan layer
        $layer->method('getProjectionLabel')->willReturn('EPSG:3857');

        return $layer;
    }

    /**
     * Helper: Create a test procedure with specified GIS layers
     */
    private function createTestProcedureWithLayers(
        array $gisLayers,
        ?string $territory = self::TERRITORY_DATA
    ): MockObject {
        // Setup GIS layer category
        $gisLayerCategory = $this->createMock(GisLayerCategoryInterface::class);
        $gisLayerCategory->method('getGisLayers')->willReturn(new ArrayCollection($gisLayers));
        $this->gisLayerCategoryRepository->method('getRootLayerCategory')->willReturn($gisLayerCategory);

        // Setup procedure settings
        $procedureSettings = $this->createMock(ProcedureSettingsInterface::class);
        $procedureSettings->method('getTerritory')->willReturn($territory);
        $procedureSettings->method('getMapExtent')->willReturn('904640.92,7067292.96,1195347.64,7350657.51');

        // Setup procedure phase
        $phaseDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $phaseDefinition->method('getName')->willReturn('configuration');
        $phase = $this->createMock(ProcedurePhaseInterface::class);
        $phase->method('getStartDate')->willReturn(new \DateTime('2025-01-01'));
        $phase->method('getEndDate')->willReturn(new \DateTime('2025-02-01'));
        $phase->method('getIteration')->willReturn(1);
        $phase->method('getPhaseDefinition')->willReturn($phaseDefinition);

        // Setup organization
        $orga = $this->createMock(OrgaInterface::class);
        $orga->method('getName')->willReturn('Test Organization');

        // Setup procedure
        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('test-procedure-id');
        $procedure->method('getXtaPlanId')->willReturn('test-procedure-id');
        $procedure->method('getName')->willReturn('Test Procedure');
        $procedure->method('getDesc')->willReturn('Test Description');
        $procedure->method('getSettings')->willReturn($procedureSettings);
        $procedure->method('getOrga')->willReturn($orga);
        $procedure->method('getStartDate')->willReturn(new \DateTime('2025-01-01'));
        $procedure->method('getEndDate')->willReturn(new \DateTime('2025-02-01'));
        $procedure->method('getPhaseObject')->willReturn($phase);
        $procedure->method('getPublicParticipationPhaseObject')->willReturn($phase);

        return $procedure;
    }
}
