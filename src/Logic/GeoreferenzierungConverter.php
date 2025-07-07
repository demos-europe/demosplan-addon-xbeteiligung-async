<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PointTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType;
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
     *
     * @param string $geoJsonString
     * @return GeoreferenzierungType|null
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
     *
     * @param array $points
     * @return GeoreferenziertePunkteType
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
     *
     * @param array $lineStrings
     * @return GeoreferenzierteLinieType
     */
    private function convertLineStringsToGeoreferenzierteLinien(array $lineStrings): GeoreferenzierteLinieType
    {
        $georeferenzierteLinien = new GeoreferenzierteLinieType();
        
        // Note: LineString to GML Curve conversion is complex and may need
        // specific implementation based on the XBeteiligung standard requirements
        // This is a placeholder that would need proper GML curve implementation
        
        return $georeferenzierteLinien;
    }
    
    /**
     * Convert Polygon geometries to GeoreferenzierteFlaecheType
     *
     * @param array $polygons
     * @return GeoreferenzierteFlaecheType
     */
    private function convertPolygonsToGeoreferenzierteFlaechen(array $polygons): GeoreferenzierteFlaecheType
    {
        $georeferenzierteFlaechen = new GeoreferenzierteFlaecheType();
        
        // Note: Polygon implementation is currently not possible as the GML PolygonType
        // structure in the XBeteiligung standard has empty Exterior and Interior AbstractRing
        // types that lack proper coordinate implementation
        
        return $georeferenzierteFlaechen;
    }
}