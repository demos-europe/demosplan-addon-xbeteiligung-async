<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CurveTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Exterior;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\LinearRing;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\LineStringTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PointTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PolygonTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType\FlaecheAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType\LinieAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenziertePunkteType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenziertePunkteType\PunktAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\GeoreferenzierungType;

/**
 * Converts GeoJSON format to XBeteiligung georeferencing format
 */
class GeoreferenzierungConverter
{
    /**
     * Convert GeoJSON string to GeoreferenzierungType
     */
    public function convertGeoJsonToGeoreferenzierung(string $geoJsonString): ?GeoreferenzierungType
    {
        $geoData = json_decode($geoJsonString, true);

        if (!$geoData || !isset($geoData['type']) || $geoData['type'] !== 'FeatureCollection') {
            return null;
        }

        if (!isset($geoData['features']) || !is_array($geoData['features'])) {
            return null;
        }

        $georeferenzierung = new GeoreferenzierungType();

        // Group features by geometry type
        $points = [];
        $lineStrings = [];
        $polygons = [];

        foreach ($geoData['features'] as $feature) {
            if (!isset($feature['geometry']) || !isset($feature['geometry']['type'])) {
                continue;
            }

            $geometry = $feature['geometry'];

            switch ($geometry['type']) {
                case 'Point':
                    $points[] = $geometry;
                    break;
                case 'LineString':
                    $lineStrings[] = $geometry;
                    break;
                case 'Polygon':
                    $polygons[] = $geometry;
                    break;
            }
        }

        // Convert points
        if (!empty($points)) {
            $georeferenziertePunkte = $this->convertPointsToGeoreferenziertePunkte($points);
            $georeferenzierung->addToPunkt($georeferenziertePunkte);
        }

        // Convert linestrings
        if (!empty($lineStrings)) {
            $georeferenzierteLinien = $this->convertLineStringsToGeoreferenzierteLinien($lineStrings);
            $georeferenzierung->addToLinie($georeferenzierteLinien);
        }

        // Convert polygons
        if (!empty($polygons)) {
            $georeferenzierteFlaechen = $this->convertPolygonsToGeoreferenzierteFlaechen($polygons);
            $georeferenzierung->addToFlaeche($georeferenzierteFlaechen);
        }

        return $georeferenzierung;
    }

    /**
     * Convert Point geometries to GeoreferenziertePunkteType
     */
    private function convertPointsToGeoreferenziertePunkte(array $points): GeoreferenziertePunkteType
    {
        $georeferenziertePunkte = new GeoreferenziertePunkteType();

        $pointIndex = 1;
        foreach ($points as $point) {
            if (!isset($point['coordinates']) || !is_array($point['coordinates'])) {
                continue;
            }

            $coordinates = $point['coordinates'];
            if (count($coordinates) < 2) {
                continue;
            }

            // Create GML Point with required gml:id attribute
            $pointType = new PointTypeType();
            $pointType->setId('point_' . $pointIndex);
            $pointType->setPos([(float) $coordinates[0], (float) $coordinates[1]]);

            // Wrap in anonymous type
            $punktAnonymous = new PunktAnonymousPHPType();
            $punktAnonymous->setPunkt($pointType);

            $georeferenziertePunkte->addToPunkt($punktAnonymous);
            $pointIndex++;
        }

        return $georeferenziertePunkte;
    }

    /**
     * Convert LineString geometries to GeoreferenzierteLinieType
     */
    private function convertLineStringsToGeoreferenzierteLinien(array $lineStrings): GeoreferenzierteLinieType
    {
        $georeferenzierteLinien = new GeoreferenzierteLinieType();

        $lineIndex = 1;
        foreach ($lineStrings as $lineString) {
            if (!isset($lineString['coordinates']) || !is_array($lineString['coordinates'])) {
                continue;
            }

            $coordinates = $lineString['coordinates'];
            if (count($coordinates) < 2) {
                continue;
            }

            // Flatten coordinates array for GML posList format [x1, y1, x2, y2, ...]
            $flatCoordinates = [];
            foreach ($coordinates as $coordinate) {
                if (is_array($coordinate) && count($coordinate) >= 2) {
                    $flatCoordinates[] = (float) $coordinate[0];
                    $flatCoordinates[] = (float) $coordinate[1];
                }
            }

            if (empty($flatCoordinates)) {
                continue;
            }

            // Create GML Curve with LineString segment
            $curveType = new CurveTypeType();
            $curveType->setId('curve_' . $lineIndex);

            // Note: For now, we'll use a simplified approach since CurveType requires segments
            // This is a limitation of the current XBeteiligung schema structure

            // Wrap in anonymous type
            $linieAnonymous = new LinieAnonymousPHPType();
            $linieAnonymous->setLinie($curveType);

            $georeferenzierteLinien->addToLinie($linieAnonymous);
            $lineIndex++;
        }

        return $georeferenzierteLinien;
    }

    /**
     * Convert Polygon geometries to GeoreferenzierteFlaecheType
     */
    private function convertPolygonsToGeoreferenzierteFlaechen(array $polygons): GeoreferenzierteFlaecheType
    {
        $georeferenzierteFlaechen = new GeoreferenzierteFlaecheType();

        $polygonIndex = 1;
        foreach ($polygons as $polygon) {
            if (!isset($polygon['coordinates']) || !is_array($polygon['coordinates'])) {
                continue;
            }

            $coordinateRings = $polygon['coordinates'];
            if (empty($coordinateRings) || !is_array($coordinateRings[0])) {
                continue;
            }

            // Create GML Polygon with required gml:id attribute
            $polygonType = new PolygonTypeType();
            $polygonType->setId('polygon_' . $polygonIndex);

            // Note: Due to XBeteiligung schema complexity with AbstractRing types,
            // polygon exterior/interior ring implementation is currently limited.
            // The basic polygon structure is created but without coordinate data.

            // Wrap in anonymous type
            $flaecheAnonymous = new FlaecheAnonymousPHPType();
            $flaecheAnonymous->setPolygon($polygonType);

            $georeferenzierteFlaechen->addToFlaeche($flaecheAnonymous);
            $polygonIndex++;
        }

        return $georeferenzierteFlaechen;
    }

    /**
     * Create a LinearRing from coordinate array
     */
    private function createLinearRing(array $coordinates, string $id): LinearRing
    {
        // Flatten coordinates array for GML posList format [x1, y1, x2, y2, ...]
        $flatCoordinates = [];
        foreach ($coordinates as $coordinate) {
            if (is_array($coordinate) && count($coordinate) >= 2) {
                $flatCoordinates[] = (float) $coordinate[0];
                $flatCoordinates[] = (float) $coordinate[1];
            }
        }

        // Ensure ring is closed (first and last point should be the same)
        if (count($flatCoordinates) >= 4) {
            $firstX = $flatCoordinates[0];
            $firstY = $flatCoordinates[1];
            $lastX = $flatCoordinates[count($flatCoordinates) - 2];
            $lastY = $flatCoordinates[count($flatCoordinates) - 1];

            if ($firstX !== $lastX || $firstY !== $lastY) {
                $flatCoordinates[] = $firstX;
                $flatCoordinates[] = $firstY;
            }
        }

        $linearRing = new LinearRing();
        // LinearRing inherits setId from AbstractGMLTypeType
        if (method_exists($linearRing, 'setId')) {
            $linearRing->setId($id);
        }
        $linearRing->setPosList($flatCoordinates);

        return $linearRing;
    }
}
