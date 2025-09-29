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
    private const LAYER_NAME_PREFIX = 'XBeteiligung';
    private const LOG_PREFIX = 'XBeteiligung GIS Layer Manager: ';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly LoggerInterface $logger,
        private readonly GisLayerFactoryInterface $gisLayerFactory,
        private readonly GisLayerCategoryRepositoryInterface $gisLayerCategoryRepository,
    ) {
    }

    /**
     * @throws Exception
     */
    public function processWmsUrl(?string $flaechenabgrenzungsUrl, ProcedureInterface $procedure): void
    {
        if (null === $flaechenabgrenzungsUrl || '' === trim($flaechenabgrenzungsUrl)) {
            $this->logger->info(self::LOG_PREFIX . 'No flaechenabgrenzungsUrl provided - skipping GIS layer creation');

            return;
        }

        $this->logger->info(self::LOG_PREFIX . 'Processing WMS URL for GIS layers', ['url' => $flaechenabgrenzungsUrl]);

        try {
            $this->validateWmsUrl($flaechenabgrenzungsUrl);
            $layers = $this->extractLayersFromUrl($flaechenabgrenzungsUrl);

            foreach ($layers as $layerName) {
                $this->createGisLayer($flaechenabgrenzungsUrl, $layerName, $procedure);
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
            if (!isset($params[$param])) {
                throw new InvalidArgumentException("Missing required WMS parameter: {$param}");
            }
        }

        if (!isset($params[self::WMS_PARAM_SRS]) && !isset($params[self::WMS_PARAM_CRS])) {
            throw new InvalidArgumentException('Missing SRS or CRS parameter in WMS URL');
        }

        $this->logger->debug(self::LOG_PREFIX . 'WMS URL validation successful', ['url' => $url]);
    }

    private function extractLayersFromUrl(string $url): array
    {
        try {
            $params = $this->parseUrlParams($url);
        } catch (InvalidArgumentException $e) {
            $this->logger->warning(self::LOG_PREFIX . 'Unable to parse URL for layer extraction', ['url' => $url, 'error' => $e->getMessage()]);

            return [];
        }

        $layersString = $params[self::WMS_PARAM_LAYERS] ?? '';
        if ('' === $layersString) {
            $this->logger->warning(self::LOG_PREFIX . 'No LAYERS parameter found in WMS URL', ['url' => $url]);

            return [];
        }

        $layers = array_map('trim', explode(',', $layersString));
        $layers = array_filter($layers); // Remove empty strings

        $this->logger->info(self::LOG_PREFIX . 'Extracted layers from WMS URL', [
            'url' => $url,
            'layers' => $layers,
            'layerCount' => count($layers),
        ]);

        return $layers;
    }

    private function createGisLayer(string $url, string $layerName, ProcedureInterface $procedure): void
    {
        $rootCategory = $this->gisLayerCategoryRepository->getRootLayerCategory($procedure->getId());
        if (null === $rootCategory) {
            throw new InvalidArgumentException('Procedure has no root layer category, cannot add layers');
        }

        $gisLayer = $this->gisLayerFactory->createGisLayer();

        $gisLayer->setName(self::LAYER_NAME_PREFIX . ": {$layerName}");
        $gisLayer->setUrl($url);
        $gisLayer->setLayers($layerName);
        $gisLayer->setProcedureId($procedure->getId());

        $gisLayer->setType(self::LAYER_TYPE_OVERLAY);
        $gisLayer->setDefaultVisibility(true);

        try {
            $params = $this->parseUrlParams($url);
            $serviceType = isset($params[self::WMS_PARAM_SERVICE]) ? strtolower($params[self::WMS_PARAM_SERVICE]) : self::DEFAULT_SERVICE_TYPE;
            $gisLayer->setServiceType($serviceType);

            if (isset($params[self::WMS_PARAM_VERSION])) {
                $gisLayer->setLayerVersion($params[self::WMS_PARAM_VERSION]);
            }
        } catch (InvalidArgumentException $e) {
            $this->logger->warning(self::LOG_PREFIX . 'Could not parse service parameters from URL, using defaults', [
                'url' => $url,
                'error' => $e->getMessage()
            ]);
            $gisLayer->setServiceType(self::DEFAULT_SERVICE_TYPE);
        }

        $rootCategory->addLayer($gisLayer);

        $this->entityManager->persist($gisLayer);
        $this->entityManager->persist($rootCategory);

        $this->logger->info(self::LOG_PREFIX . 'Created GIS layer', [
            'layerName' => $layerName,
            'procedureId' => $procedure->getId(),
            'url' => $url,
        ]);
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
}
