<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Factory\GisLayerFactoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OafExtractor
{
    private const OAF_SERVICE_TYPE = 'OAF';
    private const LAYER_TYPE_OVERLAY = 'overlay';
    private const LOG_PREFIX = 'XBeteiligung OAF Extractor: ';
    private const LAYER_NAME = 'Planzeichnung';
    private const COLLECTIONS_PATTERN =  '/collections/';
    private const STORAGE_CRS =  'storageCrs';
    private const DEFAULT_PROJECTION_VALUE = '+proj=utm +zone=32 +ellps=GRS80 +units=m +no_defs';
    private const DEFAULT_PROJECTION_LABEL = 'EPSG:25832';

    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly LoggerInterface $logger,
        private readonly GisLayerFactoryInterface $gisLayerFactory,
        private readonly GisLayerCategoryRepositoryInterface $gisLayerCategoryRepository,
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws Exception
     */
    public function processOafUrl(string $flaechenabgrenzungsUrl, ProcedureInterface $procedure): void
    {
        $this->logger->info(self::LOG_PREFIX . 'Processing OAF URL for GIS layers', ['url' => $flaechenabgrenzungsUrl]);
        $this->validateOafUrl($flaechenabgrenzungsUrl);

        $rootCategory = $this->gisLayerCategoryRepository->getRootLayerCategory($procedure->getId());
        if (null === $rootCategory) {
            throw new InvalidArgumentException('Procedure has no root layer category, cannot add layers');
        }

        try {
            $projectionInfo = $this->resolveProjectionInfo($flaechenabgrenzungsUrl);
            $projectionValue = $projectionInfo['value'];
            $projectionLabel = $projectionInfo['label'];

            $gisLayer = $this->createOafGisLayer($flaechenabgrenzungsUrl, $projectionValue, $projectionLabel, $procedure);

            $rootCategory->addLayer($gisLayer);
            $this->entityManager->persist($gisLayer);
            $this->entityManager->persist($rootCategory);

            $this->logger->info(self::LOG_PREFIX . 'Created OAF layer ', [
                'flaechenabgrenzungsUrl' => $flaechenabgrenzungsUrl
            ]);

        } catch (Exception $e) {
            $this->logger->error(self::LOG_PREFIX . 'Failed to process OAF URL for GIS layers', [
                'url' => $flaechenabgrenzungsUrl,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    private function resolveProjectionInfo(string $flaechenabgrenzungsUrl): array
    {
        $collectionUrl = $this->getCollectionUrl($flaechenabgrenzungsUrl);

        if (!$collectionUrl) {
            $this->logger->warning(self::LOG_PREFIX . 'Failed to extract collection URL, using defaults');
            return [
                'value' => self::DEFAULT_PROJECTION_VALUE,
                'label' => self::DEFAULT_PROJECTION_LABEL
            ];
        }

        $storageCrs = $this->fetchStorageCrs($collectionUrl);

        if (!$storageCrs) {
            $this->logger->info(self::LOG_PREFIX . 'No CRS found in collection, using defaults');
            return [
                'value' => self::DEFAULT_PROJECTION_VALUE,
                'label' => self::DEFAULT_PROJECTION_LABEL
            ];
        }

        return [
            'value' => $this->parseStorageCrsToEpsg($storageCrs),
            'label' => $storageCrs
        ];
    }


    public function validateOafUrl(string $url): bool
    {
        $lowerUrl = strtolower($url);
        $collectionsIndex = strpos($lowerUrl, self::COLLECTIONS_PATTERN);

        if ($collectionsIndex === false) {
            return false;
        }

        $afterCollections = substr($url, $collectionsIndex + strlen(self::COLLECTIONS_PATTERN));
        $hasNoCollectionName = trim($afterCollections) === '' ||
            $afterCollections === '/' ||
            preg_match('/^\/+$/', $afterCollections);

        return !$hasNoCollectionName;
    }

    private function fetchStorageCrs(string $collectionUrl): string
    {
        try {
            $response = $this->httpClient->request('GET', $collectionUrl, [
                'timeout' => 30,
                'headers' => [
                    'Accept' => 'application/json',
                    ],
                ]);
        } catch (Exception $e) {
            $this->logger->warning(self::LOG_PREFIX . 'Failed to fetch collectionUrl.', ['collectionUrl' => $collectionUrl, 'error' => $e->getMessage()]);
            return '';
        }


        $responseBody = $response->getContent();
        $capabilitiesData = json_decode($responseBody, true);

        $storageCrs = $capabilitiesData[self::STORAGE_CRS] ?? null;

        if (null === $storageCrs) {
            $this->logger->warning(self::LOG_PREFIX . self::STORAGE_CRS . 'not found in OAF response', ['collectionUrl' => $collectionUrl]);
            return '';
        }

        $this->logger->info(self::LOG_PREFIX . 'Retrieved OAF capabilities', [
            'collectionUrl' => $collectionUrl,
            'storageCrs' => $storageCrs
        ]);

        return $storageCrs;
    }

    private function createOafGisLayer(string $originalUrl, string $projectionValue, string $projectionLabel, ProcedureInterface $procedure): GisLayerInterface
    {
        $gisLayer = $this->gisLayerFactory->createGisLayer();

        $gisLayer->setName(self::LAYER_NAME);
        $gisLayer->setUrl($this->buildCleanLayerUrl($originalUrl));
        $gisLayer->setProcedureId($procedure->getId());
        $gisLayer->setType(self::LAYER_TYPE_OVERLAY);
        $gisLayer->setDefaultVisibility(true);
        $gisLayer->setServiceType(self::OAF_SERVICE_TYPE);
        $gisLayer->setProjectionValue($projectionValue);
        $gisLayer->setProjectionLabel($projectionLabel);

        return $gisLayer;
    }

    private function getCollectionUrl(string $url): string
    {
        $collectionsIndex = strpos($url, self::COLLECTIONS_PATTERN);

        if (false === $collectionsIndex) {
            $this->logger->warning(self::LOG_PREFIX . 'OAF URL does not contain /collections/ pattern', [
                'url' => $url
            ]);
            return '';
        }

        $collectionNameStart = $collectionsIndex + strlen(self::COLLECTIONS_PATTERN);
        $nextSlashIndex = strpos($url, '/', $collectionNameStart);

        if (false === $nextSlashIndex) {
            $this->logger->warning(self::LOG_PREFIX . ' No slash after collection name', [
                'url' => $url
            ]);
            return '';
        }

        return substr($url, 0, $nextSlashIndex);
    }

    private function parseStorageCrsToEpsg(string $storageCrs): string
    {
        // Handle EPSG URIs
        if (preg_match('/\/EPSG\/\d+\/(\d+)$/i', $storageCrs, $matches)) {
            return 'EPSG:' . $matches[1];
        }

        // Handle CRS84 (maps to WGS84)
        if (stripos($storageCrs, 'CRS84') !== false) {
            return 'EPSG:4326';
        }

        // Handle already formatted EPSG
        if (preg_match('/^EPSG:(\d+)$/i', $storageCrs)) {
            return strtoupper($storageCrs);
        }

        return $storageCrs;
    }

    private function buildCleanLayerUrl(string $url): string
    {
        $scheme = parse_url($url, PHP_URL_SCHEME);
        $host = parse_url($url, PHP_URL_HOST);
        $path = parse_url($url, PHP_URL_PATH);

        if (false === $scheme || false === $host || false === $path) {
            $this->logger->warning(self::LOG_PREFIX . 'Unable to parse OAF URL for clean URL generation, using original URL', [
                'url' => $url
            ]);
            return $url;
        }

        return $scheme . '://' . $host . $path;
    }
}

