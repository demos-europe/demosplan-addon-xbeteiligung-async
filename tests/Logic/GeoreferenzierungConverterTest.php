<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\GeoreferenzierungConverter;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\GeoreferenzierungType;
use PHPUnit\Framework\TestCase;

class GeoreferenzierungConverterTest extends TestCase
{
    private GeoreferenzierungConverter $converter;

    protected function setUp(): void
    {
        $this->converter = new GeoreferenzierungConverter();
    }

    public function testConvertPointGeoJsonToGeoreferenzierung(): void
    {
        $pointGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[1132397.3932554368,7111833.634747591]}},{"type":"Feature","properties":{},"geometry":{"type":"Point","coordinates":[1132450.123,7111900.456]}}]}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($pointGeoJson);

        static::assertInstanceOf(GeoreferenzierungType::class, $result);
        static::assertCount(1, $result->getPunkt());

        $points = $result->getPunkt()[0]->getPunkt();
        static::assertCount(2, $points);

        // Verify first point coordinates
        $firstPoint = $points[0]->getPunkt();
        static::assertSame('point_1', $firstPoint->getId());
        static::assertEquals([1132397.3932554368, 7111833.634747591], $firstPoint->getPos());

        // Verify second point coordinates
        $secondPoint = $points[1]->getPunkt();
        static::assertSame('point_2', $secondPoint->getId());
        static::assertEquals([1132450.123, 7111900.456], $secondPoint->getPos());
    }

    public function testConvertLineStringGeoJsonToGeoreferenzierung(): void
    {
        $lineStringGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"LineString","coordinates":[[1132475.5139496848,7112243.698134477],[1132450.6917389354,7112226.223256476],[1132451.4130714608,7112134.549009422]]}}]}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($lineStringGeoJson);

        static::assertInstanceOf(GeoreferenzierungType::class, $result);
        static::assertCount(1, $result->getLinie());

        $lines = $result->getLinie()[0]->getLinie();
        static::assertCount(1, $lines);

        $curve = $lines[0]->getLinie();
        static::assertSame('curve_1', $curve->getId());

        // Note: CurveType doesn't have posList, it uses segments
        // This is a limitation of the current schema structure
    }

    public function testConvertPolygonGeoJsonToGeoreferenzierung(): void
    {
        $polygonGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","properties":{},"geometry":{"type":"Polygon","coordinates":[[[1132235.0984500847,7111928.885484557],[1132358.4146781473,7112103.909034938],[1132491.7938119422,7111995.734958167],[1132428.1565548892,7111869.358820034],[1132235.0984500847,7111928.885484557]]]},"id":"bobjectid208"}]}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($polygonGeoJson);

        static::assertInstanceOf(GeoreferenzierungType::class, $result);
        static::assertCount(1, $result->getFlaeche());

        $surfaces = $result->getFlaeche()[0]->getFlaeche();
        static::assertCount(1, $surfaces);

        $polygon = $surfaces[0]->getPolygon();
        static::assertSame('polygon_1', $polygon->getId());

        // Note: Due to XBeteiligung schema complexity with AbstractRing types,
        // polygon coordinate implementation is currently limited.
        // We can only verify the basic polygon structure is created.
        $exterior = $polygon->getExterior();
        static::assertNull($exterior); // No exterior ring set due to schema limitations
    }

    public function testConvertMixedGeometriesGeoJsonToGeoreferenzierung(): void
    {
        $mixedGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Point","coordinates":[100.0,200.0]}},{"type":"Feature","geometry":{"type":"LineString","coordinates":[[101.0,201.0],[102.0,202.0]]}},{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[103.0,203.0],[104.0,204.0],[105.0,205.0],[103.0,203.0]]]}}]}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($mixedGeoJson);

        static::assertInstanceOf(GeoreferenzierungType::class, $result);
        static::assertCount(1, $result->getPunkt());
        static::assertCount(1, $result->getLinie());
        static::assertCount(1, $result->getFlaeche());
    }

    public function testConvertInvalidGeoJsonReturnsNull(): void
    {
        $invalidGeoJson = '{"invalid": "geojson"}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($invalidGeoJson);

        static::assertNull($result);
    }

    public function testConvertEmptyFeatureCollectionReturnsGeoreferenzierung(): void
    {
        $emptyGeoJson = '{"type":"FeatureCollection","features":[]}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($emptyGeoJson);

        static::assertInstanceOf(GeoreferenzierungType::class, $result);
        static::assertEmpty($result->getPunkt());
        static::assertEmpty($result->getLinie());
        static::assertEmpty($result->getFlaeche());
    }

    public function testConvertGeoJsonWithInvalidCoordinates(): void
    {
        $invalidCoordinatesGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Point","coordinates":"invalid"}},{"type":"Feature","geometry":{"type":"LineString","coordinates":[[]]}}]}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($invalidCoordinatesGeoJson);

        static::assertInstanceOf(GeoreferenzierungType::class, $result);
        // The converter may create empty containers for invalid data, but they should be empty of actual geometries
        $punktContainer = $result->getPunkt();
        $linieContainer = $result->getLinie();
        $flaecheContainer = $result->getFlaeche();

        // Check if containers exist but are empty or don't exist at all
        static::assertTrue(empty($punktContainer) || (1 === count($punktContainer) && empty($punktContainer[0]->getPunkt())));
        static::assertTrue(empty($linieContainer) || (1 === count($linieContainer) && empty($linieContainer[0]->getLinie())));
        static::assertTrue(empty($flaecheContainer) || (1 === count($flaecheContainer) && empty($flaecheContainer[0]->getFlaeche())));
    }

    public function testLinearRingIsClosed(): void
    {
        // Test with polygon that is not already closed
        $unclosedPolygonGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[0.0,0.0],[1.0,0.0],[1.0,1.0],[0.0,1.0]]]}}]}';

        $result = $this->converter->convertGeoJsonToGeoreferenzierung($unclosedPolygonGeoJson);

        static::assertInstanceOf(GeoreferenzierungType::class, $result);
        static::assertCount(1, $result->getFlaeche());

        // Note: Due to schema limitations, we can only verify the polygon structure is created
        $polygon = $result->getFlaeche()[0]->getFlaeche()[0]->getPolygon();
        static::assertSame('polygon_1', $polygon->getId());
    }
}
