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

class XBeteiligungGisLayerManager
{
    private const WMS_PARAM_LAYERS = 'LAYERS';
    private const WMS_PARAM_BBOX = 'BBOX';
    private const WMS_PARAM_REQUEST = 'REQUEST';
    private const WMS_PARAM_SRS = 'SRS';
    private const WMS_PARAM_CRS = 'CRS';
    private const WMS_PARAM_SERVICE = 'SERVICE';
    private const WMS_PARAM_VERSION = 'VERSION';

    private const LAYER_TYPE_OVERLAY = 'overlay';
    private const DEFAULT_SERVICE_TYPE = 'wms';
    private const LOG_PREFIX = 'XBeteiligung GIS Layer Manager: ';
    private const LAYER_NAME = 'Planzeichnung';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly GisLayerFactoryInterface $gisLayerFactory,
        private readonly GisLayerCategoryRepositoryInterface $gisLayerCategoryRepository,
        private readonly OafExtractor $oafExtractor,
    ) {
    }


    /**
     * @throws Exception
     */
    public function processUrl(?string $flaechenabgrenzungsUrl, ProcedureInterface $procedure): void {
        if (null === $flaechenabgrenzungsUrl || '' === trim($flaechenabgrenzungsUrl)) {
            $this->logger->info(self::LOG_PREFIX . 'No flaechenabgrenzungsUrl provided - skipping GIS layer creation');

            return;
        }

        $isOafUrl = $this->oafExtractor->validateOafUrl($flaechenabgrenzungsUrl);

        if ($isOafUrl) {
            $this->oafExtractor->processOafUrl($flaechenabgrenzungsUrl, $procedure);

            return;
        }
        $this->processWmsUrl($flaechenabgrenzungsUrl, $procedure);

    }

    /**
     * @throws Exception
     */
    private function processWmsUrl(?string $flaechenabgrenzungsUrl, ProcedureInterface $procedure): void
    {

        $this->logger->info(self::LOG_PREFIX . 'Processing WMS URL for GIS layers', ['url' => $flaechenabgrenzungsUrl]);

        try {
            $this->validateWmsUrl($flaechenabgrenzungsUrl);
            $layersString = $this->extractLayersFromUrl($flaechenabgrenzungsUrl);

            if ('' !== $layersString) {
                $this->updateOrCreateGisLayer($flaechenabgrenzungsUrl, $layersString, $procedure);
            }
        } catch (Exception $e) {
            $this->logger->error(
                self::LOG_PREFIX . 'Failed to process WMS URL for GIS layers',
                [
                    'url' => $flaechenabgrenzungsUrl,
                    'error' => $e->getMessage(),
                    'exception' => $e,
                ]
            );
            throw $e;
        }
    }

    private function validateWmsUrl(string $url): void
    {
        $params = $this->parseUrlParams($url);

        $requiredParams = [self::WMS_PARAM_LAYERS, self::WMS_PARAM_BBOX, self::WMS_PARAM_REQUEST];
        foreach ($requiredParams as $param) {
            if (!$this->hasParam($params, $param)) {
                throw new InvalidArgumentException("Missing required WMS parameter: {$param}");
            }
        }

        if (!$this->hasParam($params, self::WMS_PARAM_SRS) && !$this->hasParam($params, self::WMS_PARAM_CRS)) {
            throw new InvalidArgumentException('Missing SRS or CRS parameter in WMS URL');
        }

        $this->logger->debug(self::LOG_PREFIX . 'WMS URL validation successful', ['url' => $url]);
    }

    private function extractLayersFromUrl(string $url): string
    {
        try {
            $params = $this->parseUrlParams($url);
        } catch (InvalidArgumentException $e) {
            $this->logger->warning(self::LOG_PREFIX . 'Unable to parse URL for layer extraction', ['url' => $url, 'error' => $e->getMessage()]);

            return '';
        }

        $layersString = $this->getParam($params, self::WMS_PARAM_LAYERS) ?? '';
        if ('' === $layersString) {
            $this->logger->warning(self::LOG_PREFIX . 'No LAYERS parameter found in WMS URL', ['url' => $url]);

            return '';
        }

        $layers = array_map('trim', explode(',', $layersString));
        $layers = array_filter($layers); // Remove empty strings
        $cleanLayersString = implode(',', $layers);

        $this->logger->info(self::LOG_PREFIX . 'Extracted layers from WMS URL', [
            'url' => $url,
            'layers' => $cleanLayersString,
            'layerCount' => count($layers),
        ]);

        return $cleanLayersString;
    }

    /**
     * Update existing "Planzeichnung" overlay or create new one if it doesn't exist
     * @throws Exception
     */
    private function updateOrCreateGisLayer(string $url, string $layersString, ProcedureInterface $procedure): void
    {
        $existingLayer = $this->findExistingPlanzeichnungLayer($procedure);

        if (null !== $existingLayer) {
            $this->updateExistingGisLayer($existingLayer, $url, $layersString);
        } else {
            $this->createGisLayer($url, $layersString, $procedure);
        }
    }

    /**
     * Find existing "Planzeichnung" overlay for the procedure
     *
     * @throws Exception
     */
    private function findExistingPlanzeichnungLayer(ProcedureInterface $procedure): ?GisLayerInterface
    {
        $rootCategory = $this->gisLayerCategoryRepository->getRootLayerCategory($procedure->getId());
        if (null === $rootCategory) {
            $this->logger->warning(self::LOG_PREFIX . 'No root layer category found for procedure', [
                'procedureId' => $procedure->getId(),
            ]);
            return null;
        }

        $gisLayers = $rootCategory->getGisLayers();
        /** @var GisLayerInterface $gisLayer */
        foreach ($gisLayers as $gisLayer) {
            if (self::LAYER_NAME === $gisLayer->getName() && !$gisLayer->isDeleted()) {
                $this->logger->info(self::LOG_PREFIX . 'Found existing Planzeichnung layer', [
                    'procedureId' => $procedure->getId(),
                    'layerId' => $gisLayer->getId(),
                ]);
                return $gisLayer;
            }
        }

        $this->logger->info(self::LOG_PREFIX . 'No existing Planzeichnung layer found, will create new one', [
            'procedureId' => $procedure->getId(),
        ]);
        return null;
    }

    /**
     * Update existing GIS layer with new URL and layers
     */
    private function updateExistingGisLayer(GisLayerInterface $gisLayer, string $url, string $layersString): void
    {
        $oldUrl = $gisLayer->getUrl();
        $oldLayers = $gisLayer->getLayers();

        $this->configureGisLayerFromUrl($gisLayer, $url, $layersString, false);

        $this->entityManager->persist($gisLayer);

        $layerCount = substr_count($layersString, ',') + 1;

        $this->logger->info(self::LOG_PREFIX . 'Updated existing Planzeichnung layer', [
            'layerId' => $gisLayer->getId(),
            'procedureId' => $gisLayer->getProcedureId(),
            'oldUrl' => $oldUrl,
            'newUrl' => $gisLayer->getUrl(),
            'oldLayers' => $oldLayers,
            'newLayers' => $layersString,
            'layerCount' => $layerCount,
        ]);
    }

    /**
     * Configure GIS layer with URL, layers, and metadata from WMS URL
     *
     * @param GisLayerInterface $gisLayer The layer to configure
     * @param string $url The WMS URL to parse
     * @param string $layersString Comma-separated list of layer names
     * @param bool $useDefaultOnError If true, sets default service type on error; if false, keeps existing values
     */
    private function configureGisLayerFromUrl(
        GisLayerInterface $gisLayer,
        string $url,
        string $layersString,
        bool $useDefaultOnError
    ): void {
        $gisLayer->setUrl($this->buildCleanLayerUrl($url));
        $gisLayer->setLayers($layersString);

        try {
            $params = $this->parseUrlParams($url);
            $serviceType = $this->getParam($params, self::WMS_PARAM_SERVICE);
            $gisLayer->setServiceType($serviceType ? strtolower($serviceType) : self::DEFAULT_SERVICE_TYPE);

            $version = $this->getParam($params, self::WMS_PARAM_VERSION);
            if ($version) {
                $gisLayer->setLayerVersion($version);
            }
        } catch (InvalidArgumentException $e) {
            $logContext = [
                'url' => $url,
                'error' => $e->getMessage(),
            ];

            if ($useDefaultOnError) {
                $this->logger->warning(self::LOG_PREFIX . 'Could not parse service parameters from URL, using defaults', $logContext);
                $gisLayer->setServiceType(self::DEFAULT_SERVICE_TYPE);
            } else {
                $this->logger->warning(self::LOG_PREFIX . 'Could not parse service parameters from URL during update, keeping existing values', $logContext);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function createGisLayer(string $url, string $layersString, ProcedureInterface $procedure): void
    {
        $rootCategory = $this->gisLayerCategoryRepository->getRootLayerCategory($procedure->getId());
        if (null === $rootCategory) {
            throw new InvalidArgumentException('Procedure has no root layer category, cannot add layers');
        }

        $gisLayer = $this->gisLayerFactory->createGisLayer();

        $gisLayer->setName(self::LAYER_NAME);
        $gisLayer->setProcedureId($procedure->getId());
        $gisLayer->setBplan(true);
        $gisLayer->setType(self::LAYER_TYPE_OVERLAY);
        $gisLayer->setDefaultVisibility(true);

        $this->configureGisLayerFromUrl($gisLayer, $url, $layersString, true);

        $rootCategory->addLayer($gisLayer);

        $this->entityManager->persist($gisLayer);
        $this->entityManager->persist($rootCategory);

        $layerCount = substr_count($layersString, ',') + 1;

        $this->logger->info(self::LOG_PREFIX . 'Created single GIS layer with all layers', [
            'layers' => $layersString,
            'layerCount' => $layerCount,
            'procedureId' => $procedure->getId(),
            'cleanUrl' => $gisLayer->getUrl(),
        ]);
    }

    /**
     * Build clean layer URL by extracting base URL components without query parameters
     *
     * @throws InvalidArgumentException
     */
    private function buildCleanLayerUrl(string $wmsUrl): string
    {
        $scheme = parse_url($wmsUrl, PHP_URL_SCHEME);
        $host = parse_url($wmsUrl, PHP_URL_HOST);
        $path = parse_url($wmsUrl, PHP_URL_PATH);

        if (false === $scheme || false === $host || false === $path) {
            $this->logger->warning(self::LOG_PREFIX . 'Unable to parse WMS URL for clean URL generation, using original URL', [
                'wmsUrl' => $wmsUrl,
            ]);
            return $wmsUrl;
        }

        $cleanUrl = $scheme . '://' . $host . $path;

        $this->logger->debug(self::LOG_PREFIX . 'Built clean layer URL', [
            'originalUrl' => $wmsUrl,
            'cleanUrl' => $cleanUrl,
        ]);

        return $cleanUrl;
    }

    /**
     * @return array<string, string>
     * @throws InvalidArgumentException
     */
    private function parseUrlParams(string $url): array
    {
        $queryString = parse_url($url, PHP_URL_QUERY);
        if (null === $queryString || '' === $queryString) {
            throw new InvalidArgumentException('Invalid WMS URL format - no query parameters');
        }

        parse_str($queryString, $params);

        return $params;
    }

    /**
     * Case-insensitive parameter check
     */
    private function hasParam(array $params, string $paramName): bool
    {
        return null !== $this->getParam($params, $paramName);
    }

    /**
     * Case-insensitive parameter retrieval
     */
    private function getParam(array $params, string $paramName): ?string
    {
        // First try exact case match
        if (isset($params[$paramName])) {
            return $params[$paramName];
        }

        // Then try case-insensitive search
        $paramNameLower = strtolower($paramName);
        foreach ($params as $key => $value) {
            if (strtolower($key) === $paramNameLower) {
                return $value;
            }
        }

        return null;
    }
}
