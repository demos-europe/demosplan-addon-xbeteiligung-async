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
        // Setup HTTP client mock
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

        // Use reflection to call private method
        $reflection = new ReflectionClass($this->sut);
        $method = $reflection->getMethod('resolveProjectionInfo');
        $method->setAccessible(true);

        $result = $method->invoke($this->sut, $url);

        $this->assertEquals($expectedValue, $result['value'], "Expected value mismatch for: $testName");
        $this->assertEquals($expectedLabel, $result['label'], "Expected label mismatch for: $testName");
    }

    public function resolveProjectionInfoDataProvider(): array
    {
        return [
            'Invalid URL - No collections pattern' => [
                'testName' => 'Invalid URL format',
                'url' => 'https://example.com/api/layers',
                'httpResponse' => null,
                'httpException' => null,
                'expectedValue' => '+proj=utm +zone=32 +ellps=GRS80 +units=m +no_defs',
                'expectedLabel' => 'EPSG:25832'
            ],

            'Invalid URL - Empty collection name' => [
                'testName' => 'Empty collection name',
                'url' => 'https://example.com/collections/',
                'httpResponse' => null,
                'httpException' => null,
                'expectedValue' => '+proj=utm +zone=32 +ellps=GRS80 +units=m +no_defs',
                'expectedLabel' => 'EPSG:25832'
            ],

            'Valid URL but HTTP request fails' => [
                'testName' => 'HTTP transport exception',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => null,
                'httpException' => 'Connection timeout',
                'expectedValue' => '+proj=utm +zone=32 +ellps=GRS80 +units=m +no_defs',
                'expectedLabel' => 'EPSG:25832'
            ],

            'Valid URL but invalid JSON response' => [
                'testName' => 'Malformed JSON',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => 'invalid-json-{{{',
                'httpException' => null,
                'expectedValue' => '+proj=utm +zone=32 +ellps=GRS80 +units=m +no_defs',
                'expectedLabel' => 'EPSG:25832'
            ],

            'Valid URL with no storageCrs in response' => [
                'testName' => 'Missing storageCrs field',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => '{"title": "Test Collection", "description": "A test collection"}',
                'httpException' => null,
                'expectedValue' => '+proj=utm +zone=32 +ellps=GRS80 +units=m +no_defs',
                'expectedLabel' => 'EPSG:25832'
            ],

            'Valid URL with EPSG URI format CRS' => [
                'testName' => 'EPSG URI format',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => '{"storageCrs": "http://www.opengis.net/def/crs/EPSG/0/25832"}',
                'httpException' => null,
                'expectedValue' => 'EPSG:25832',
                'expectedLabel' => 'http://www.opengis.net/def/crs/EPSG/0/25832'
            ],

            'Valid URL with CRS84 format' => [
                'testName' => 'CRS84 format',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => '{"storageCrs": "http://www.opengis.net/def/crs/OGC/1.3/CRS84"}',
                'httpException' => null,
                'expectedValue' => 'EPSG:4326',
                'expectedLabel' => 'http://www.opengis.net/def/crs/OGC/1.3/CRS84'
            ],

            'Valid URL with already formatted EPSG' => [
                'testName' => 'Pre-formatted EPSG',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => '{"storageCrs": "EPSG:3857"}',
                'httpException' => null,
                'expectedValue' => 'EPSG:3857',
                'expectedLabel' => 'EPSG:3857'
            ],

            'Valid URL with lowercase EPSG' => [
                'testName' => 'Lowercase EPSG',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => '{"storageCrs": "epsg:4326"}',
                'httpException' => null,
                'expectedValue' => 'EPSG:4326',
                'expectedLabel' => 'epsg:4326'
            ],

            'Valid URL with unknown CRS format' => [
                'testName' => 'Unknown CRS format',
                'url' => 'https://example.com/collections/test-layer/items',
                'httpResponse' => '{"storageCrs": "CUSTOM:12345"}',
                'httpException' => null,
                'expectedValue' => 'CUSTOM:12345',
                'expectedLabel' => 'CUSTOM:12345'
            ],

            'Valid URL with WGS84 EPSG URI' => [
                'testName' => 'WGS84 EPSG URI',
                'url' => 'https://example.com/collections/boundary/items',
                'httpResponse' => '{"storageCrs": "http://www.opengis.net/def/crs/EPSG/0/4326"}',
                'httpException' => null,
                'expectedValue' => 'EPSG:4326',
                'expectedLabel' => 'http://www.opengis.net/def/crs/EPSG/0/4326'
            ],

            'Valid URL with Web Mercator' => [
                'testName' => 'Web Mercator EPSG URI',
                'url' => 'https://example.com/collections/tiles/items',
                'httpResponse' => '{"storageCrs": "http://www.opengis.net/def/crs/EPSG/0/3857"}',
                'httpException' => null,
                'expectedValue' => 'EPSG:3857',
                'expectedLabel' => 'http://www.opengis.net/def/crs/EPSG/0/3857'
            ]
        ];
    }
}
