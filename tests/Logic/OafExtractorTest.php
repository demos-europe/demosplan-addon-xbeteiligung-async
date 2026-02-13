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

use DemosEurope\DemosplanAddon\Contracts\Factory\GisLayerFactoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\OafExtractor;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionClass;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class OafExtractorTest extends TestCase
{
    private OafExtractor $sut;
    private HttpClientInterface $httpClient;
    private LoggerInterface $logger;
    private ReflectionClass $reflection;

    private const DEFAULT_PROJECTION_VALUE = '+proj=utm +zone=32 +ellps=GRS80 +units=m +no_defs';
    private const DEFAULT_PROJECTION_LABEL = 'EPSG:25832';

    protected function setUp(): void
    {
        parent::setUp();

        $this->httpClient = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $gisLayerFactory = $this->createMock(GisLayerFactoryInterface::class);
        $gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);

        $this->sut = new OafExtractor(
            $this->httpClient,
            $this->logger,
            $gisLayerFactory,
            $gisLayerCategoryRepository,
            $entityManager
        );

        $this->reflection = new ReflectionClass($this->sut);
    }

    #[DataProvider('resolveProjectionInfoDataProvider')]
    public function testResolveProjectionInfo_DifferentScenarios(
        string $testName,
        string $url,
        ?string $httpResponse,
        ?string $httpException,
        string $expectedValue,
        string $expectedLabel
    ): void {
        $this->setupHttpClientMock($httpResponse, $httpException);

        $result = $this->invokeResolveProjectionInfo($url);

        $this->assertEquals($expectedValue, $result['value'], "Expected value mismatch for: $testName");
        $this->assertEquals($expectedLabel, $result['label'], "Expected label mismatch for: $testName");
    }

    public static function resolveProjectionInfoDataProvider(): array
    {
        return [
            // Default cases (all return same defaults)
            'Invalid URL - No collections pattern' => [
                'Invalid URL format',
                'https://example.com/api/layers',
                null, null,
                self::DEFAULT_PROJECTION_VALUE, self::DEFAULT_PROJECTION_LABEL
            ],
            'Invalid URL - Empty collection name' => [
                'Empty collection name',
                'https://example.com/collections/',
                null, null,
                self::DEFAULT_PROJECTION_VALUE, self::DEFAULT_PROJECTION_LABEL
            ],
            'Valid URL but HTTP request fails' => [
                'HTTP transport exception',
                'https://example.com/collections/test-layer/items',
                null, 'Connection timeout',
                self::DEFAULT_PROJECTION_VALUE, self::DEFAULT_PROJECTION_LABEL
            ],
            'Valid URL but invalid JSON response' => [
                'Malformed JSON',
                'https://example.com/collections/test-layer/items',
                'invalid-json-{{{', null,
                self::DEFAULT_PROJECTION_VALUE, self::DEFAULT_PROJECTION_LABEL
            ],
            'Valid URL with no storageCrs in response' => [
                'Missing storageCrs field',
                'https://example.com/collections/test-layer/items',
                '{"title": "Test Collection", "description": "A test collection"}', null,
                self::DEFAULT_PROJECTION_VALUE, self::DEFAULT_PROJECTION_LABEL
            ],

            // CRS processing cases
            'Valid URL with EPSG URI format CRS' => [
                'EPSG URI format',
                'https://example.com/collections/test-layer/items',
                '{"storageCrs": "http://www.opengis.net/def/crs/EPSG/0/25832"}', null,
                'EPSG:25832', 'http://www.opengis.net/def/crs/EPSG/0/25832'
            ],
            'Valid URL with CRS84 format' => [
                'CRS84 format',
                'https://example.com/collections/test-layer/items',
                '{"storageCrs": "http://www.opengis.net/def/crs/OGC/1.3/CRS84"}', null,
                'EPSG:4326', 'http://www.opengis.net/def/crs/OGC/1.3/CRS84'
            ],
            'Valid URL with already formatted EPSG' => [
                'Pre-formatted EPSG',
                'https://example.com/collections/test-layer/items',
                '{"storageCrs": "EPSG:3857"}', null,
                'EPSG:3857', 'EPSG:3857'
            ],
            'Valid URL with lowercase EPSG' => [
                'Lowercase EPSG',
                'https://example.com/collections/test-layer/items',
                '{"storageCrs": "epsg:4326"}', null,
                'EPSG:4326', 'epsg:4326'
            ],
            'Valid URL with unknown CRS format' => [
                'Unknown CRS format',
                'https://example.com/collections/test-layer/items',
                '{"storageCrs": "CUSTOM:12345"}', null,
                'CUSTOM:12345', 'CUSTOM:12345'
            ],
            'Valid URL with WGS84 EPSG URI' => [
                'WGS84 EPSG URI',
                'https://example.com/collections/boundary/items',
                '{"storageCrs": "http://www.opengis.net/def/crs/EPSG/0/4326"}', null,
                'EPSG:4326', 'http://www.opengis.net/def/crs/EPSG/0/4326'
            ],
            'Valid URL with Web Mercator' => [
                'Web Mercator EPSG URI',
                'https://example.com/collections/tiles/items',
                '{"storageCrs": "http://www.opengis.net/def/crs/EPSG/0/3857"}', null,
                'EPSG:3857', 'http://www.opengis.net/def/crs/EPSG/0/3857'
            ]
        ];
    }

    private function setupHttpClientMock(?string $httpResponse, ?string $httpException): void
    {
        if ($httpException) {
            $this->httpClient
                ->method('request')
                ->willThrowException(new Exception($httpException));
        } elseif ($httpResponse !== null) {
            $response = $this->createMock(ResponseInterface::class);
            $response->method('getContent')->willReturn($httpResponse);
            $this->httpClient
                ->method('request')
                ->willReturn($response);
        }
    }

    private function invokeResolveProjectionInfo(string $url): array
    {
        $method = $this->reflection->getMethod('resolveProjectionInfo');
        $method->setAccessible(true);

        return $method->invoke($this->sut, $url);
    }
}
