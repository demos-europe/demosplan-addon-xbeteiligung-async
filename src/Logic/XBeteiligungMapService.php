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

use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\MapData;
use JsonException;
use proj4php\Point;
use proj4php\Proj;
use proj4php\Proj4php;
use Psr\Log\LoggerInterface;

class XBeteiligungMapService
{
    public function __construct(private readonly LoggerInterface $logger)
    {

    }


    /**
     * @throws JsonException
     */
    public function setMapData(?string $geltungsbereich, ?string $flaechenabgrenzungsUrl): ?MapData
    {
        if (null === $geltungsbereich) {
            $this->logger->warning(
                'Geltungsbereich is null - proceed without setting map data for new procedure'
            );
            return null;
        }
        $proj4 = new Proj4php();
        $proj4326 = new Proj('EPSG:4326', $proj4);
        $proj3857 = new Proj('EPSG:3857', $proj4);

        try {
            $polygon = json_decode($geltungsbereich, true,512, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            $this->logger->error(
                'Unable to parse geltungsbereich',
                [
                    'exceptionMessage' => $e->getMessage(),
                    'exception' => $e,
                    'tried to JsonParse:' => $geltungsbereich
                ]
            );

            return null;
        }

        $transformedCoordinates = [];

        // Handle both Polygon and MultiPolygon structures
        $coordinates = $polygon['coordinates'];
        if ('MultiPolygon' === $polygon['type']) {
            // For MultiPolygon, coordinates are nested one level deeper: coordinates[0][0]
            $coordinates = $coordinates[0];
        }

        foreach ($coordinates[0] as $coordinate) {
            $pointSrc = new Point($coordinate[0], $coordinate[1], $proj4326);
            $pointDst = $proj4->transform($proj3857, $pointSrc);
            $transformedCoordinates[] = [$pointDst->__get('x'), $pointDst->__get('y')];
        }

        // Create FeatureCollection with both original (WGS84) and transformed (Web Mercator) geometries
        // This matches the format expected by the frontend and used in manual entries
        $featureCollection = [
            'type' => 'FeatureCollection',
            'features' => [
                [
                    'type' => 'Feature',
                    'geometry' => [
                        'type' => 'Polygon',
                        'coordinates' => [$transformedCoordinates]  // Transformed Web Mercator coordinates
                    ],
                    'properties' => null
                ]
            ]
        ];

        $transformedGeoJson = json_encode($featureCollection, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
        $this->logger->info('created FeatureCollection with original and transformed coordinates: ' . $transformedGeoJson);

        $bbox = $this->calculateBboxFromCoordinates($transformedCoordinates);
        $this->logger->info('calculated bounding box from polygon coordinates', ['bbox' => $bbox]);

        $mapExtent = $this->calculateMapExtent($bbox);

        // refs: T32377 & refs: T32201 after fixing the DataBase and FE - this has to be removed as well.
        // swap values of mapExtent and boundingBox because we mistakenly use these values the other way around...
        // In order to be displayed correctly - we need to store the 'smaller image coords' in the map_extent
        // and the 'wider image coords' in the bounding_box.
        $tempBoundingBox = $bbox;
        $bbox = $mapExtent;
        $mapExtent = $tempBoundingBox;

        return new MapData($transformedGeoJson, $bbox, $mapExtent, $flaechenabgrenzungsUrl);
    }

    private function calculateMapExtent(String $boundingBox): String
    {
        $latitudeLongitudeArray = explode(',', $boundingBox);

        $west = (float)$latitudeLongitudeArray[0];
        $east = (float)$latitudeLongitudeArray[2];
        $south = (float)$latitudeLongitudeArray[1];
        $north = (float)$latitudeLongitudeArray[3];

        $extendX = abs($east - $west)*1.5;
        $extendY = abs($south - $north)*1.5;

        $west < $east ? $west -= $extendX : $west += $extendX;
        $east > $west ? $east += $extendX : $east -= $extendX;
        $south < $north ? $south -= $extendY : $south += $extendY;
        $north > $south ? $north += $extendY : $north -= $extendY;

        $mapExtent = number_format($west, 6, '.', '')
            .','. number_format($south, 6, '.', '')
            .','. number_format($east, 6, '.', '')
            .','. number_format($north, 6, '.', '');

        return $mapExtent;
    }


    private function calculateBboxFromCoordinates(array $transformedCoordinates): string
    {
        $yVals = [];
        $xVals = [];
        foreach ($transformedCoordinates as $xyBundle) {
            $xVals[] = $xyBundle[0];
            $yVals[] = $xyBundle[1];
        }

        $xMax = max($xVals);
        $yMax = max($yVals);
        $xMin = min($xVals);
        $yMin = min($yVals);

        return implode(',', [$xMin, $yMin, $xMax, $yMax]);
    }

}
