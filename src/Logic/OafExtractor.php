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
    private const LOG_PREFIX = 'XBeteiligung OAF Handler: ';
    private const LAYER_NAME = 'Planzeichnung';

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
    public function processOafUrl(string $url, ProcedureInterface $procedure): void
    {
        $this->validateOafUrl($url);

        $rootCategory = $this->gisLayerCategoryRepository->getRootLayerCategory($procedure->getId());
        if (null === $rootCategory) {
            throw new InvalidArgumentException('Procedure has no root layer category, cannot add layers');
        }

        try {
            $collectionUrl = $this->getCollectionUrl($url);
            $storageCrs = $this->fetchStorageCrs($collectionUrl);

            $gisLayer = $this->createOafGisLayer($url, $storageCrs, $procedure);

            $rootCategory->addLayer($gisLayer);
            $this->entityManager->persist($gisLayer);
            $this->entityManager->persist($rootCategory);

        } catch (Exception $e) {
            $this->logger->error(self::LOG_PREFIX . 'Failed to process OAF URL for GIS layers', [
                'url' => $url,
                'error' => $e->getMessage(),
                'exception' => $e,
            ]);
            throw $e;
        }
    }

    public function validateOafUrl(string $url): bool
    {
        $collectionsPattern = '/collections/';
        $lowerUrl = strtolower($url);
        $collectionsIndex = strpos($lowerUrl, $collectionsPattern);

        if ($collectionsIndex === false) {
            return false;
        }

        $afterCollections = substr($url, $collectionsIndex + strlen($collectionsPattern));
        $hasNoCollectionName = trim($afterCollections) === '' ||
            $afterCollections === '/' ||
            preg_match('/^\/+$/', $afterCollections);

        return !$hasNoCollectionName;
    }

    private function fetchStorageCrs(string $collectionUrl): string
    {
        $response = $this->httpClient->request('GET', $collectionUrl, [
            'timeout' => 30,
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $responseBody = $response->getContent();
        $capabilitiesData = json_decode($responseBody, true);

        $storageCrs = $capabilitiesData['storageCrs'] ?? null;

        if (null === $storageCrs) {
            throw new InvalidArgumentException(self::LOG_PREFIX . 'storageCrs not found in OAF response');
        }

        $this->logger->info(self::LOG_PREFIX . 'Retrieved OAF capabilities', [
            'collectionUrl' => $collectionUrl,
            'storageCrs' => $storageCrs
        ]);

        return $storageCrs;
    }

    private function createOafGisLayer(string $originalUrl, string $storageCrs, ProcedureInterface $procedure): GisLayerInterface
    {
        $gisLayer = $this->gisLayerFactory->createGisLayer();

        $gisLayer->setName(self::LAYER_NAME);
        $gisLayer->setUrl($this->buildCleanLayerUrl($originalUrl));
        $gisLayer->setProcedureId($procedure->getId());
        $gisLayer->setType(self::LAYER_TYPE_OVERLAY);
        $gisLayer->setDefaultVisibility(true);
        $gisLayer->setServiceType(self::OAF_SERVICE_TYPE);
        $gisLayer->setLayerVersion('1.3.0');
        $gisLayer->setProjectionValue($storageCrs);
        $gisLayer->setProjectionLabel($this->parseStorageCrsToEpsg($storageCrs));

        return $gisLayer;
    }

    private function getCollectionUrl(string $url): string
    {
        $collectionsPattern = '/collections/';
        $collectionsIndex = strpos($url, $collectionsPattern);

        if (false === $collectionsIndex) {
            throw new InvalidArgumentException(self::LOG_PREFIX . 'OAF URL does not contain /collections/ pattern');
        }

        $collectionNameStart = $collectionsIndex + strlen($collectionsPattern);
        $nextSlashIndex = strpos($url, '/', $collectionNameStart);

        if ($nextSlashIndex === false) {
            throw new InvalidArgumentException(self::LOG_PREFIX . ' No slash after collection name');
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

