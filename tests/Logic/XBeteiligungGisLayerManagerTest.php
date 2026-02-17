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
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\OafExtractor;
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
    private OafExtractor $oafExtractor;
    private ProcedureInterface $procedure;

    private const PROCEDURE_ID = 'test-procedure-id';
    private const WMS_BASE_URL = 'https://example.com/wms';
    private const NO_URL_PROVIDED_MESSAGE = 'No flaechenabgrenzungsUrl provided';
    private const FAILED_TO_PROCESS_MESSAGE = 'Failed to process WMS URL';
    private const NO_LAYERS_FOUND_MESSAGE = 'No LAYERS parameter found';

    protected function setUp(): void
    {
        parent::setUp();

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->gisLayerFactory = $this->createMock(GisLayerFactoryInterface::class);
        $this->gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $this->oafExtractor = $this->createMock(OafExtractor::class);
        $this->procedure = $this->createMock(ProcedureInterface::class);

        $this->procedure->method('getId')->willReturn(self::PROCEDURE_ID);

        // Mock OAF extractor to always return false so WMS processing is used
        $this->oafExtractor->method('validateOafUrl')->willReturn(false);

        $this->sut = new XBeteiligungGisLayerManager(
            $this->entityManager,
            $this->logger,
            $this->gisLayerFactory,
            $this->gisLayerCategoryRepository,
            $this->oafExtractor
        );
    }

    // ============================================================================
    // ACTUAL TEST CASES
    // ============================================================================

    public function testProcessWmsUrlWithNullUrl(): void
    {
        $this->expectLoggerInfo(self::NO_URL_PROVIDED_MESSAGE);
        $this->expectNoGisLayerCreation();

        $this->sut->processUrl(null, $this->procedure);
    }

    public function testProcessWmsUrlWithEmptyUrl(): void
    {
        $this->expectLoggerInfo(self::NO_URL_PROVIDED_MESSAGE);
        $this->expectNoGisLayerCreation();

        $this->sut->processUrl('', $this->procedure);
    }

    public function testProcessWmsUrlWithWhitespaceOnlyUrl(): void
    {
        $this->expectLoggerInfo(self::NO_URL_PROVIDED_MESSAGE);
        $this->expectNoGisLayerCreation();

        $this->sut->processUrl('   ', $this->procedure);
    }

    public function testProcessWmsUrlWithMalformedUrl(): void
    {
        $malformedUrl = 'not-a-valid-url';

        $this->expectLoggerErrorWithException('Invalid WMS URL format - no query parameters');

        $this->sut->processUrl($malformedUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingRequiredParameters(): void
    {
        $urlWithoutLayers = self::WMS_BASE_URL . '?REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->expectLoggerErrorWithException('Missing required WMS parameter: LAYERS');

        $this->sut->processUrl($urlWithoutLayers, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingBboxParameter(): void
    {
        $urlWithoutBbox = self::WMS_BASE_URL . '?LAYERS=testlayer&REQUEST=GetMap&SRS=EPSG:25832';

        $this->expectLoggerErrorWithException('Missing required WMS parameter: BBOX');

        $this->sut->processUrl($urlWithoutBbox, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingRequestParameter(): void
    {
        $urlWithoutRequest = self::WMS_BASE_URL . '?LAYERS=testlayer&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->expectLoggerErrorWithException('Missing required WMS parameter: REQUEST');

        $this->sut->processUrl($urlWithoutRequest, $this->procedure);
    }

    public function testProcessWmsUrlWithMissingSrsAndCrsParameters(): void
    {
        $urlWithoutSrsOrCrs = self::WMS_BASE_URL . '?LAYERS=testlayer&BBOX=1,2,3,4&REQUEST=GetMap';

        $this->expectLoggerErrorWithException('Missing SRS or CRS parameter in WMS URL');

        $this->sut->processUrl($urlWithoutSrsOrCrs, $this->procedure);
    }

    public function testProcessWmsUrlWithValidUrlAndSingleLayer(): void
    {
        $validUrl = 'https://init.xplan.develop.diplanung.de/xplan-wms/services/planwerkwmspre/planname/TestLukas16?request=GetMap&service=WMS&version=1.3.0&format=image/png&transparent=true&exceptions=application/vnd.ogc.se_inimage&crs=epsg:25832&layers=vektordaten&bbox=706456.528060293,707369.340560293,5347155.423893627,5348226.986393627&width=4640699&height=4640858';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $mockGisLayer->expects($this->once())
            ->method('setName')
            ->with('Planzeichnung');

        $mockGisLayer->expects($this->once())
            ->method('setLayers')
            ->with('vektordaten');

        $this->logger->expects($this->any())
            ->method('info');

        $this->logger->expects($this->any())
            ->method('debug');

        $this->sut->processUrl($validUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithValidUrlAndMultipleLayers(): void
    {
        $validUrl = 'https://init.xplan.develop.diplanung.de/xplan-wms/services/planwerkwmspre/planname/TestLukas16?request=GetMap&service=WMS&version=1.3.0&format=image/png&transparent=true&exceptions=application/vnd.ogc.se_inimage&crs=epsg:25832&layers=vektordaten,bp_raster&bbox=706456.528060293,707369.340560293,5347155.423893627,5348226.986393627&width=4640699&height=4640858';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory, 1);

        $mockGisLayer->expects($this->once())
            ->method('setName')
            ->with('Planzeichnung');

        $mockGisLayer->expects($this->once())
            ->method('setLayers')
            ->with('vektordaten,bp_raster');

        $this->logger->expects($this->any())
            ->method('info');

        $this->sut->processUrl($validUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithCaseInsensitiveParameters(): void
    {
        $urlWithLowercaseParams = self::WMS_BASE_URL . '?layers=testlayer&request=GetMap&bbox=1,2,3,4&srs=EPSG:25832&service=wms&version=1.1.0';

        $mockGisLayer = $this->createMockGisLayerWithParameterVerification('testlayer', 'wms', '1.1.0');
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processUrl($urlWithLowercaseParams, $this->procedure);
    }

    public function testProcessWmsUrlWithMixedCaseParameters(): void
    {
        $urlWithMixedCase = self::WMS_BASE_URL . '?Layers=testlayer&Request=GetMap&BBox=1,2,3,4&Srs=EPSG:25832&Service=wms&Version=1.1.0';

        $mockGisLayer = $this->createMockGisLayerWithParameterVerification('testlayer', 'wms', '1.1.0');
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processUrl($urlWithMixedCase, $this->procedure);
    }

    public function testProcessWmsUrlWithEmptyLayersParameter(): void
    {
        $urlWithEmptyLayers = self::WMS_BASE_URL . '?LAYERS=&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->expectLoggerWarning(self::NO_LAYERS_FOUND_MESSAGE);
        $this->expectNoGisLayerCreation();

        $this->sut->processUrl($urlWithEmptyLayers, $this->procedure);
    }

    public function testProcessWmsUrlWithLayersContainingWhitespace(): void
    {
        $urlWithWhitespace = self::WMS_BASE_URL . '?LAYERS= layer1 , layer2 , &REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMock(GisLayerInterface::class);
        $mockRootCategory = $this->createMockRootCategory();

        // Collect the layer string that setLayers() is called with
        $layersString = null;
        $mockGisLayer->method('setLayers')
            ->willReturnCallback(function ($layers) use (&$layersString, $mockGisLayer) {
                $layersString = $layers;
                return $mockGisLayer;
            });

        $mockGisLayer->expects($this->once())
            ->method('setName')
            ->with('Planzeichnung');

        // Setup other methods as usual
        $mockGisLayer->method('setUrl')->willReturnSelf();
        $mockGisLayer->method('setProcedureId')->willReturnSelf();
        $mockGisLayer->method('setType')->willReturnSelf();
        $mockGisLayer->method('setDefaultVisibility')->willReturnSelf();
        $mockGisLayer->method('setServiceType')->willReturnSelf();
        $mockGisLayer->method('setLayerVersion')->willReturnSelf();
        $mockGisLayer->method('getUrl')->willReturn('https://example.com/wms');

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory, 1);

        $this->sut->processUrl($urlWithWhitespace, $this->procedure);

        // Verify that layer names are trimmed correctly and joined with comma
        self::assertSame('layer1,layer2', $layersString, 'Layer names should be trimmed of whitespace and joined with comma');
    }

    public function testProcessWmsUrlWithNoRootCategory(): void
    {
        $validUrl = self::WMS_BASE_URL . '?LAYERS=testlayer&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $this->gisLayerCategoryRepository->expects($this->any())
            ->method('getRootLayerCategory')
            ->with(self::PROCEDURE_ID)
            ->willReturn(null);

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Procedure has no root layer category, cannot add layers');

        $this->sut->processUrl($validUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithInvalidUrlForCleanGeneration(): void
    {
        $invalidUrl = self::WMS_BASE_URL . '?LAYERS=testlayer&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $mockGisLayer->expects($this->once())
            ->method('setUrl')
            ->with(self::WMS_BASE_URL);

        $this->sut->processUrl($invalidUrl, $this->procedure);
    }

    public function testProcessWmsUrlWithoutServiceAndVersionParameters(): void
    {
        $urlWithoutServiceVersion = self::WMS_BASE_URL . '?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $mockGisLayer->expects($this->once())
            ->method('setServiceType')
            ->with('wms');

        $mockGisLayer->expects($this->never())
            ->method('setLayerVersion');

        $this->sut->processUrl($urlWithoutServiceVersion, $this->procedure);
    }

    public function testProcessWmsUrlWithSpecialCharactersInLayers(): void
    {
        $urlWithSpecialChars = self::WMS_BASE_URL . '?LAYERS=layer_with-special.chars&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832';

        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processUrl($urlWithSpecialChars, $this->procedure);
    }

    public function testProcessWmsUrlParameterExtractionAndVerification(): void
    {
        $testUrl = self::WMS_BASE_URL . '?LAYERS=test_layer&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:4326&SERVICE=WMS&VERSION=1.1.0&FORMAT=image/png';

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
        $this->setupStandardMockGisLayerMethods($mockGisLayer);

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory);

        $this->sut->processUrl($testUrl, $this->procedure);

        // Verify all parameters are extracted and set correctly
        self::assertSame('Planzeichnung', $setParameters['name'], 'Layer name should be "Planzeichnung"');
        self::assertSame('test_layer', $setParameters['layers'], 'Layers parameter should be extracted correctly');
        self::assertSame('wms', $setParameters['serviceType'], 'Service type should be extracted correctly');
        self::assertSame('1.1.0', $setParameters['layerVersion'], 'Layer version should be extracted correctly');
        self::assertSame(self::PROCEDURE_ID, $setParameters['procedureId'], 'Procedure ID should be set correctly');
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

        $this->sut->processUrl($invalidUrl, $this->procedure);
    }

    /**
     * @dataProvider validWmsUrlDataProvider
     */
    public function testProcessWmsUrlWithValidUrls(string $url, array $expectedLayers, string $expectedServiceType, ?string $expectedVersion): void
    {
        $mockGisLayer = $this->createMockGisLayer();
        $mockRootCategory = $this->createMockRootCategory();

        $this->setupMocksForValidUrl($mockGisLayer, $mockRootCategory, 1);

        $mockGisLayer->expects($this->once())
            ->method('setName')
            ->with('Planzeichnung');

        $mockGisLayer->expects($this->once())
            ->method('setLayers')
            ->with(implode(',', $expectedLayers));

        $mockGisLayer->expects($this->once())
            ->method('setServiceType')
            ->with($expectedServiceType);

        if (null !== $expectedVersion) {
            $mockGisLayer->expects($this->once())
                ->method('setLayerVersion')
                ->with($expectedVersion);
        }

        $this->sut->processUrl($url, $this->procedure);
    }

    // ============================================================================
    // HELPER METHODS FOR EXPECTATIONS
    // ============================================================================

    private function expectLoggerInfo(string $message): void
    {
        $this->logger->expects($this->once())
            ->method('info')
            ->with(self::stringContains($message));
    }

    private function expectLoggerWarning(string $message): void
    {
        $this->logger->expects($this->once())
            ->method('warning')
            ->with(self::stringContains($message));
    }

    private function expectLoggerErrorWithException(string $exceptionMessage): void
    {
        $this->logger->expects($this->once())
            ->method('error')
            ->with(
                self::stringContains(self::FAILED_TO_PROCESS_MESSAGE),
                self::arrayHasKey('error')
            );

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($exceptionMessage);
    }

    private function expectNoGisLayerCreation(): void
    {
        $this->gisLayerFactory->expects($this->never())->method('createGisLayer');
    }

    // ============================================================================
    // HELPER METHODS FOR MOCK CREATION
    // ============================================================================

    private function createMockGisLayerWithParameterVerification(string $expectedLayersString, string $expectedServiceType, string $expectedVersion): GisLayerInterface
    {
        $mockGisLayer = $this->createMock(GisLayerInterface::class);

        // Verify that parameters are correctly parsed and used
        $mockGisLayer->expects($this->once())
            ->method('setName')
            ->with('Planzeichnung');

        $mockGisLayer->expects($this->once())
            ->method('setLayers')
            ->with($expectedLayersString);

        $mockGisLayer->expects($this->once())
            ->method('setServiceType')
            ->with($expectedServiceType);

        $mockGisLayer->expects($this->once())
            ->method('setLayerVersion')
            ->with($expectedVersion);

        $this->setupStandardMockGisLayerMethods($mockGisLayer);

        return $mockGisLayer;
    }

    private function setupStandardMockGisLayerMethods(GisLayerInterface $mockGisLayer): void
    {
        $mockGisLayer->method('setUrl')->willReturnSelf();
        $mockGisLayer->method('setProcedureId')->willReturnSelf();
        $mockGisLayer->method('setType')->willReturnSelf();
        $mockGisLayer->method('setDefaultVisibility')->willReturnSelf();
        $mockGisLayer->method('getUrl')->willReturn(self::WMS_BASE_URL);
    }

    private function createMockGisLayer(): GisLayerInterface
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
        $mockGisLayer->method('getUrl')->willReturn(self::WMS_BASE_URL);

        return $mockGisLayer;
    }

    private function createMockRootCategory(): GisLayerCategoryInterface
    {
        $mockRootCategory = $this->createMock(GisLayerCategoryInterface::class);
        $mockRootCategory->method('addLayer')->willReturnSelf();

        return $mockRootCategory;
    }

    private function setupMocksForValidUrl($mockGisLayer, $mockRootCategory, int $expectedLayerCount = 1): void
    {
        $this->gisLayerCategoryRepository->expects($this->any())
            ->method('getRootLayerCategory')
            ->with(self::PROCEDURE_ID)
            ->willReturn($mockRootCategory);

        $this->gisLayerFactory->expects($this->exactly($expectedLayerCount))
            ->method('createGisLayer')
            ->willReturn($mockGisLayer);

        $this->entityManager->expects($this->exactly($expectedLayerCount * 2))
            ->method('persist');
    }

    // ============================================================================
    // DATA PROVIDERS
    // ============================================================================

    public static function invalidWmsUrlDataProvider(): array
    {
        return [
            'url without query parameters' => [self::WMS_BASE_URL],
            'url with empty query' => [self::WMS_BASE_URL . '?'],
            'url with only fragment' => [self::WMS_BASE_URL . '#fragment'],
            'completely malformed url' => ['not-a-url-at-all'],
            'url with special characters' => [self::WMS_BASE_URL . '?LAYERS=test<>layer'],
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
                self::WMS_BASE_URL . '?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&SERVICE=WMS&VERSION=1.1.0',
                ['layer1'],
                'wms',
                '1.1.0'
            ],
            'WMS 1.3.0 with CRS' => [
                self::WMS_BASE_URL . '?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&CRS=EPSG:4326&SERVICE=WMS&VERSION=1.3.0',
                ['layer1'],
                'wms',
                '1.3.0'
            ],
            'multiple layers' => [
                self::WMS_BASE_URL . '?LAYERS=layer1,layer2,layer3&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&SERVICE=WMS&VERSION=1.1.0',
                ['layer1', 'layer2', 'layer3'],
                'wms',
                '1.1.0'
            ],
            'without service parameter defaults to wms' => [
                self::WMS_BASE_URL . '?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&VERSION=1.1.0',
                ['layer1'],
                'wms',
                '1.1.0'
            ],
            'without version parameter' => [
                self::WMS_BASE_URL . '?LAYERS=layer1&REQUEST=GetMap&BBOX=1,2,3,4&SRS=EPSG:25832&SERVICE=WMS',
                ['layer1'],
                'wms',
                null
            ],
        ];
    }
}
