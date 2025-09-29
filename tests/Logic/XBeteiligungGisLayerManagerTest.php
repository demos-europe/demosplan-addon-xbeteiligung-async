<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Factory\GisLayerFactoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungGisLayerManager;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class XBeteiligungGisLayerManagerTest extends TestCase
{
    private XBeteiligungGisLayerManager $sut;
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;
    private GisLayerFactoryInterface $gisLayerFactory;
    private GisLayerCategoryRepositoryInterface $gisLayerCategoryRepository;
    private ProcedureInterface $procedure;

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->gisLayerFactory = $this->createMock(GisLayerFactoryInterface::class);
        $this->gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $this->procedure = $this->createMock(ProcedureInterface::class);

        $this->procedure->method('getId')->willReturn('test-procedure-id');

        $this->sut = new XBeteiligungGisLayerManager(
            $this->entityManager,
            $this->logger,
            $this->gisLayerFactory,
            $this->gisLayerCategoryRepository
        );
    }

    // ============================================================================
    // ACTUAL TEST CASES
    // ============================================================================

    public function testProcessWmsUrlWithNullUrl(): void
    {
        $this->logger->expects($this->once())
            ->method('info')
            ->with(self::stringContains('No flaechenabgrenzungsUrl provided'));

        $this->gisLayerFactory->expects($this->never())->method('createGisLayer');

        $this->sut->processWmsUrl(null, $this->procedure);
    }

    public function testProcessWmsUrlWithEmptyUrl(): void
    {
        $this->logger->expects($this->once())
            ->method('info')
            ->with(self::stringContains('No flaechenabgrenzungsUrl provided'));

        $this->gisLayerFactory->expects($this->never())->method('createGisLayer');

        $this->sut->processWmsUrl('', $this->procedure);
    }

    public function testProcessWmsUrlWithWhitespaceOnlyUrl(): void
    {
        $this->logger->expects($this->once())
            ->method('info')
            ->with(self::stringContains('No flaechenabgrenzungsUrl provided'));

        $this->gisLayerFactory->expects($this->never())->method('createGisLayer');

        $this->sut->processWmsUrl('   ', $this->procedure);
    }

    public function testProcessWmsUrlWithMalformedUrl(): void
    {
        $malformedUrl = 'not-a-valid-url';

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                self::stringContains('Failed to process WMS URL'),
                self::arrayHasKey('error')
            );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid WMS URL format - no query parameters');

        $this->sut->processWmsUrl($malformedUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingRequiredParameters(): void
    {
        $urlWithoutLayers = 'https://example.com/wms?REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                self::stringContains('Failed to process WMS URL'),
                self::arrayHasKey('error')
            );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required WMS parameter: LAYERS');

        $this->sut->processWmsUrl($urlWithoutLayers, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingBboxParameter(): void
    {
        $urlWithoutBbox = 'https://example.com/wms?LAYERS=testlayer&REQUEST=GetMap&SRS=EPSG:25832';

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                self::stringContains('Failed to process WMS URL'),
                self::arrayHasKey('error')
            );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required WMS parameter: BBOX');

        $this->sut->processWmsUrl($urlWithoutBbox, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingRequestParameter(): void
    {
        $urlWithoutRequest = 'https://example.com/wms?LAYERS=testlayer&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                self::stringContains('Failed to process WMS URL'),
                self::arrayHasKey('error')
            );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing required WMS parameter: REQUEST');

        $this->sut->processWmsUrl($urlWithoutRequest, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingSrsAndCrsParameters(): void
    {
        $urlWithoutSrsOrCrs = 'https://example.com/wms?LAYERS=testlayer&BBOX=1,2,3,4&REQUEST=GetMap';

        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                self::stringContains('Failed to process WMS URL'),
                self::arrayHasKey('error')
            );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Missing SRS or CRS parameter in WMS URL');

        $this->sut->processWmsUrl($urlWithoutSrsOrCrs, $this->procedure);
    }

    public function testProcessWmsUrlWithValidUrlAndSingleLayer(): void
    {
        $validUrl = 'https://init.xplan.develop.diplanung.de/xplan-wms/services/planwerkwmspre/planname/TestLukas16?request=GetMap&service=WMS&version=1.3.0&format=image/png&transparent=true&exceptions=application/vnd.ogc.se_inimage&crs=epsg:25832&layers=vektordaten&bbox=706456.528060293,707369.340560293,5347155.423893627,5348226.986393627&width=4640699&height=4640858';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->logger->expects($this->exactly(3))
            ->method('info')
            ->with(
                static::logicalOr(
                self::stringContains('Processing WMS URL'),
                self::stringContains('Extracted layers from WMS URL'),
                self::stringContains('Created GIS layer')
            ));

        $this->logger->expects($this->atLeastOnce())
            ->method('debug')
            ->with(
                static::logicalOr(
                self::stringContains('WMS URL validation successful'),
                self::stringContains('Built clean layer URL')
            ));

        $this->sut->processWmsUrl($validUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithValidUrlAndMultipleLayers(): void
    {
        $validUrl = 'https://init.xplan.develop.diplanung.de/xplan-wms/services/planwerkwmspre/planname/TestLukas16?request=GetMap&service=WMS&version=1.3.0&format=image/png&transparent=true&exceptions=application/vnd.ogc.se_inimage&crs=epsg:25832&layers=vektordaten,bp_raster&bbox=706456.528060293,707369.340560293,5347155.423893627,5348226.986393627&width=4640699&height=4640858';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory, 2);

        $this->logger->expects($this->exactly(4))
            ->method('info')
            ->with(
                static::logicalOr(
                self::stringContains('Processing WMS URL'),
                self::stringContains('Extracted layers from WMS URL'),
                self::stringContains('Created GIS layer')
            ));

        $this->sut->processWmsUrl($validUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithCaseInsensitiveParameters(): void
    {
        $urlWithLowercaseParams = 'https://example.com/wms?layers=testlayer&request=GetMap&bbox=1,2,3,4&srs=EPSG:25832&service=wms&version=1.1.0';

        $mockGisLayer = $this->createMock(GisLayerInterface::class);
        $mockRootCategory = $this->createMockRootCategory();

        // Verify that lowercase parameters are correctly parsed and used
        $mockGisLayer->expects($this->once())
            ->method('setName')
            ->with('testlayer');

        $mockGisLayer->expects($this->once())
            ->method('setLayers')
            ->with('testlayer');

        $mockGisLayer->expects($this->once())
            ->method('setServiceType')
            ->with('wms');

        $mockGisLayer->expects($this->once())
            ->method('setLayerVersion')
            ->with('1.1.0');

        // Setup other required methods
        $mockGisLayer->method('setUrl')->willReturnSelf();
        $mockGisLayer->method('setProcedureId')->willReturnSelf();
        $mockGisLayer->method('setType')->willReturnSelf();
        $mockGisLayer->method('setDefaultVisibility')->willReturnSelf();
        $mockGisLayer->method('getUrl')->willReturn('https://example.com/wms');

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processWmsUrl($urlWithLowercaseParams, $this->procedure);
    }

    public function testProcessWmsUrlWithMixedCaseParameters(): void
    {
        $urlWithMixedCase = 'https://example.com/wms?Layers=testlayer&Request=GetMap&BBox=1,2,3,4&Srs=EPSG:25832&Service=wms&Version=1.1.0';

        $mockGisLayer = $this->createMock(GisLayerInterface::class);
        $mockRootCategory = $this->createMockRootCategory();

        // Verify that mixed case parameters are correctly parsed and used
        $mockGisLayer->expects($this->once())
            ->method('setName')
            ->with('testlayer');

        $mockGisLayer->expects($this->once())
            ->method('setLayers')
            ->with('testlayer');

        $mockGisLayer->expects($this->once())
            ->method('setServiceType')
            ->with('wms');

        $mockGisLayer->expects($this->once())
            ->method('setLayerVersion')
            ->with('1.1.0');

        // Setup other required methods
        $mockGisLayer->method('setUrl')->willReturnSelf();
        $mockGisLayer->method('setProcedureId')->willReturnSelf();
        $mockGisLayer->method('setType')->willReturnSelf();
        $mockGisLayer->method('setDefaultVisibility')->willReturnSelf();
        $mockGisLayer->method('getUrl')->willReturn('https://example.com/wms');

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processWmsUrl($urlWithMixedCase, $this->procedure);
    }

    public function testProcessWmsUrlWithEmptyLayersParameter(): void
    {
        $urlWithEmptyLayers = 'https://example.com/wms?LAYERS=&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->logger->expects($this->once())
            ->method('warning')
            ->with(self::stringContains('No LAYERS parameter found'));

        $this->gisLayerFactory->expects($this->never())->method('createGisLayer');

        $this->sut->processWmsUrl($urlWithEmptyLayers, $this->procedure);
    }

    public function testProcessWmsUrlWithLayersContainingWhitespace(): void
    {
        $urlWithWhitespace = 'https://example.com/wms?LAYERS= layer1 , layer2 , &REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMock(GisLayerInterface::class);
        $mockRootCategory = $this->createMockRootCategory();

        // Collect the layer names that setName() is called with
        $layerNames = [];
        $mockGisLayer->method('setName')
            ->willReturnCallback(function ($name) use (&$layerNames, $mockGisLayer) {
                $layerNames[] = $name;
                return $mockGisLayer;
            });

        // Setup other methods as usual
        $mockGisLayer->method('setUrl')->willReturnSelf();
        $mockGisLayer->method('setLayers')->willReturnSelf();
        $mockGisLayer->method('setProcedureId')->willReturnSelf();
        $mockGisLayer->method('setType')->willReturnSelf();
        $mockGisLayer->method('setDefaultVisibility')->willReturnSelf();
        $mockGisLayer->method('setServiceType')->willReturnSelf();
        $mockGisLayer->method('setLayerVersion')->willReturnSelf();
        $mockGisLayer->method('getUrl')->willReturn('https://example.com/wms');

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory, 2);

        $this->sut->processWmsUrl($urlWithWhitespace, $this->procedure);

        // Verify that layer names are trimmed correctly
        self::assertSame(['layer1', 'layer2'], $layerNames, 'Layer names should be trimmed of whitespace');
    }

    public function testProcessWmsUrlWithNoRootCategory(): void
    {
        $validUrl = 'https://example.com/wms?LAYERS=testlayer&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->gisLayerCategoryRepository->expects($this->once())
            ->method('getRootLayerCategory')
            ->with('test-procedure-id')
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Procedure has no root layer category, cannot add layers');

        $this->sut->processWmsUrl($validUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithInvalidUrlForCleanGeneration(): void
    {
        $invalidUrl = 'https://example.com/wms?LAYERS=testlayer&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $mockGisLayer->expects($this->once())
            ->method('setUrl')
            ->with('https://example.com/wms');

        $this->sut->processWmsUrl($invalidUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithoutServiceAndVersionParameters(): void
    {
        $urlWithoutServiceVersion = 'https://example.com/wms?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $mockGisLayer->expects($this->once())
            ->method('setServiceType')
            ->with('wms');

        $mockGisLayer->expects($this->never())
            ->method('setLayerVersion');

        $this->sut->processWmsUrl($urlWithoutServiceVersion, $this->procedure);
    }

    public function testProcessWmsUrlWithSpecialCharactersInLayers(): void
    {
        $urlWithSpecialChars = 'https://example.com/wms?LAYERS=layer_with-special.chars&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processWmsUrl($urlWithSpecialChars, $this->procedure);
    }

    public function testProcessWmsUrlParameterExtractionAndVerification(): void
    {
        $testUrl = 'https://example.com/wms?LAYERS=test_layer&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:4326&SERVICE=WMS&VERSION=1.1.0&FORMAT=image/png';

        $mockGisLayer = $this->createMock(GisLayerInterface::class);
        $mockRootCategory = $this->createMockRootCategory();

        // Collect all the parameter values that are set
        $setParameters = [];

        $mockGisLayer->method('setName')
            ->willReturnCallback(function ($value) use (&$setParameters, $mockGisLayer) {
                $setParameters['name'] = $value;
                return $mockGisLayer;
            });

        $mockGisLayer->method('setLayers')
            ->willReturnCallback(function ($value) use (&$setParameters, $mockGisLayer) {
                $setParameters['layers'] = $value;
                return $mockGisLayer;
            });

        $mockGisLayer->method('setServiceType')
            ->willReturnCallback(function ($value) use (&$setParameters, $mockGisLayer) {
                $setParameters['serviceType'] = $value;
                return $mockGisLayer;
            });

        $mockGisLayer->method('setLayerVersion')
            ->willReturnCallback(function ($value) use (&$setParameters, $mockGisLayer) {
                $setParameters['layerVersion'] = $value;
                return $mockGisLayer;
            });

        $mockGisLayer->method('setProcedureId')
            ->willReturnCallback(function ($value) use (&$setParameters, $mockGisLayer) {
                $setParameters['procedureId'] = $value;
                return $mockGisLayer;
            });

        // Setup other methods
        $mockGisLayer->method('setUrl')->willReturnSelf();
        $mockGisLayer->method('setType')->willReturnSelf();
        $mockGisLayer->method('setDefaultVisibility')->willReturnSelf();
        $mockGisLayer->method('getUrl')->willReturn('https://example.com/wms');

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processWmsUrl($testUrl, $this->procedure);

        // Verify all parameters are extracted and set correctly
        self::assertSame('test_layer', $setParameters['name'], 'Layer name should be extracted correctly');
        self::assertSame('test_layer', $setParameters['layers'], 'Layers parameter should be extracted correctly');
        self::assertSame('wms', $setParameters['serviceType'], 'Service type should be extracted correctly');
        self::assertSame('1.1.0', $setParameters['layerVersion'], 'Layer version should be extracted correctly');
        self::assertSame('test-procedure-id', $setParameters['procedureId'], 'Procedure ID should be set correctly');
    }

    /**
     * @dataProvider invalidWmsUrlDataProvider
     */
    public function testProcessWmsUrlWithVariousInvalidUrls(string $invalidUrl): void
    {
        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                self::stringContains('Failed to process WMS URL'),
                self::callback(fn (array $context) => isset($context['url'], $context['error']) && $context['url'] === $invalidUrl
                )
            );

        $this->expectException(Exception::class);

        $this->sut->processWmsUrl($invalidUrl, $this->procedure);
    }

    /**
     * @dataProvider validWmsUrlDataProvider
     */
    public function testProcessWmsUrlWithValidUrls(string $url, array $expectedLayers, string $expectedServiceType, ?string $expectedVersion): void
    {
        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory, count($expectedLayers));

        $mockGisLayer->expects($this->exactly(count($expectedLayers)))
            ->method('setServiceType')
            ->with($expectedServiceType);

        if (null !== $expectedVersion) {
            $mockGisLayer->expects($this->exactly(count($expectedLayers)))
                ->method('setLayerVersion')
                ->with($expectedVersion);
        }

        $this->sut->processWmsUrl($url, $this->procedure);
    }

    // ============================================================================
    // HELPER METHODS AND DATA PROVIDERS
    // ============================================================================

    private function createMockGisLayer()
    {
        $mockGisLayer = $this->createMock(GisLayerInterface::class);
        $mockGisLayer->method('setName')->willReturnSelf();
        $mockGisLayer->method('setUrl')->willReturnSelf();
        $mockGisLayer->method('setLayers')->willReturnSelf();
        $mockGisLayer->method('setProcedureId')->willReturnSelf();
        $mockGisLayer->method('setType')->willReturnSelf();
        $mockGisLayer->method('setDefaultVisibility')->willReturnSelf();
        $mockGisLayer->method('setServiceType')->willReturnSelf();
        $mockGisLayer->method('setLayerVersion')->willReturnSelf();
        $mockGisLayer->method('getUrl')->willReturn('https://example.com/wms');

        return $mockGisLayer;
    }

    private function createMockRootCategory()
    {
        $mockRootCategory = $this->createMock(GisLayerCategoryInterface::class);
        $mockRootCategory->method('addLayer')->willReturnSelf();

        return $mockRootCategory;
    }

    private function setupMocksForValidUrl($mockGisLayer, $mockRootCategory, int $expectedLayerCount = 1): void
    {
        $this->gisLayerCategoryRepository->expects($this->exactly($expectedLayerCount))
            ->method('getRootLayerCategory')
            ->with('test-procedure-id')
            ->willReturn($mockRootCategory);

        $this->gisLayerFactory->expects($this->exactly($expectedLayerCount))
            ->method('createGisLayer')
            ->willReturn($mockGisLayer);

        $this->entityManager->expects($this->exactly($expectedLayerCount * 2))
            ->method('persist');
    }

    public static function invalidWmsUrlDataProvider(): array
    {
        return [
            'url without query parameters' => ['https://example.com/wms'],
            'url with empty query' => ['https://example.com/wms?'],
            'url with only fragment' => ['https://example.com/wms#fragment'],
            'completely malformed url' => ['not-a-url-at-all'],
            'url with special characters' => ['https://example.com/wms?LAYERS=test<>layer'],
        ];
    }

    public static function validWmsUrlDataProvider(): array
    {
        return [
            'real URL from testxml with single layer' => [
                'https://init.xplan.develop.diplanung.de/xplan-wms/services/planwerkwmspre/planname/TestLukas16?request=GetMap&service=WMS&version=1.3.0&format=image/png&transparent=true&exceptions=application/vnd.ogc.se_inimage&crs=epsg:25832&layers=vektordaten&bbox=706456.528060293,707369.340560293,5347155.423893627,5348226.986393627&width=4640699&height=4640858',
                ['vektordaten'],
                'wms',
                '1.3.0'
            ],
            'real URL from testxml with multiple layers' => [
                'https://init.xplan.develop.diplanung.de/xplan-wms/services/planwerkwmspre/planname/TestLukas16?request=GetMap&service=WMS&version=1.3.0&format=image/png&transparent=true&exceptions=application/vnd.ogc.se_inimage&crs=epsg:25832&layers=vektordaten,bp_raster&bbox=706456.528060293,707369.340560293,5347155.423893627,5348226.986393627&width=4640699&height=4640858',
                ['vektordaten', 'bp_raster'],
                'wms',
                '1.3.0'
            ],
            'basic WMS 1.1.0 with SRS' => [
                'https://example.com/wms?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&SERVICE=WMS&VERSION=1.1.0',
                ['layer1'],
                'wms',
                '1.1.0'
            ],
            'WMS 1.3.0 with CRS' => [
                'https://example.com/wms?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&CRS=EPSG:4326&SERVICE=WMS&VERSION=1.3.0',
                ['layer1'],
                'wms',
                '1.3.0'
            ],
            'multiple layers' => [
                'https://example.com/wms?LAYERS=layer1,layer2,layer3&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&SERVICE=WMS&VERSION=1.1.0',
                ['layer1', 'layer2', 'layer3'],
                'wms',
                '1.1.0'
            ],
            'without service parameter defaults to wms' => [
                'https://example.com/wms?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&VERSION=1.1.0',
                ['layer1'],
                'wms',
                '1.1.0'
            ],
            'without version parameter' => [
                'https://example.com/wms?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&SERVICE=WMS',
                ['layer1'],
                'wms',
                null
            ],
        ];
    }
}
