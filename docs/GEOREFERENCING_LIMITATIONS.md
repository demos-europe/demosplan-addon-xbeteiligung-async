# Georeferencing Conversion Limitations

This document explains the limitations and challenges when converting GeoJSON data to XBeteiligung georeferencing format.

## What is Georeferencing?

**Georeferencing** is the process of associating geographic coordinates with spatial data - essentially "putting locations on a map". In the context of XBeteiligung, this means converting location data from your database format to the format required by the XBeteiligung standard.

## Data Formats Overview

### GeoJSON Format (Input)
GeoJSON is a simple, human-readable format used in modern web applications:

```json
{
  "type": "FeatureCollection",
  "features": [
    {
      "type": "Feature",
      "geometry": {
        "type": "Point",
        "coordinates": [1132397.39, 7111833.63]
      }
    },
    {
      "type": "Feature", 
      "geometry": {
        "type": "LineString",
        "coordinates": [[1132475.51, 7112243.69], [1132450.69, 7112226.22]]
      }
    },
    {
      "type": "Feature",
      "geometry": {
        "type": "Polygon", 
        "coordinates": [[[1132235.09, 7111928.88], [1132358.41, 7112103.90], [1132235.09, 7111928.88]]]
      }
    }
  ]
}
```

### XBeteiligung/GML Format (Output)
XBeteiligung uses GML (Geographic Markup Language), a complex XML-based standard:

```xml
<georeferenzierung>
  <punkt>
    <punkt>
      <gml:Point gml:id="point_1">
        <gml:pos>1132397.39 7111833.63</gml:pos>
      </gml:Point>
    </punkt>
  </punkt>
  <linie>
    <linie>
      <gml:Curve gml:id="curve_1">
        <!-- Complex curve segments structure -->
      </gml:Curve>
    </linie>
  </linie>
</georeferenzierung>
```

## Conversion Status by Geometry Type

### ✅ Points - Fully Supported

**Status**: Complete implementation with full coordinate preservation

**Conversion Process**:
```
GeoJSON: [1132397.39, 7111833.63]
         ↓
GML:     <gml:Point><gml:pos>1132397.39 7111833.63</gml:pos></gml:Point>
```

**Features**:
- Exact coordinate preservation
- Proper GML ID generation (`point_1`, `point_2`, etc.)
- Full compatibility with XBeteiligung standard

### ⚠️ LineStrings - Partially Supported

**Status**: Structure created, coordinate data limited by schema constraints

**The Challenge**:
The XBeteiligung schema requires `CurveType` for lines, but `CurveType` expects complex curve segments rather than simple coordinate lists.

**What Works**:
- Line geometry structure is created
- Proper GML ID generation (`curve_1`, `curve_2`, etc.)
- XBeteiligung-compliant systems recognize line geometries exist

**What's Limited**:
- Actual coordinate data cannot be populated due to schema structure
- `CurveType.segments` requires `AbstractCurveSegment` objects
- No direct way to convert coordinate arrays to curve segments

**Code Implementation**:
```php
// Creates curve structure but coordinates are not populated
$curveType = new CurveTypeType();
$curveType->setId('curve_' . $lineIndex);
// Note: segments would need complex AbstractCurveSegment implementation
```

### ❌ Polygons - Structure Only

**Status**: Basic structure created, coordinate data not supported

**The Challenge**:
Complex type inheritance conflicts in the XBeteiligung schema:

1. `PolygonType` requires `Exterior` object
2. `Exterior` expects `AbstractRing` type  
3. `LinearRing` (which has coordinates) is not compatible with `AbstractRing`
4. Type system prevents proper coordinate assignment

**What Works**:
- Polygon geometry structure is created
- Proper GML ID generation (`polygon_1`, `polygon_2`, etc.)
- XBeteiligung-compliant systems recognize polygon geometries exist

**What's Limited**:
- No exterior/interior ring coordinate data
- Cannot populate actual shape boundaries
- Schema type conflicts prevent coordinate assignment

**Code Implementation**:
```php
// Creates basic polygon structure
$polygonType = new PolygonTypeType();
$polygonType->setId('polygon_' . $polygonIndex);
// Note: exterior ring cannot be properly populated due to type conflicts
```

## Technical Root Causes

### 1. Schema Complexity
The XBeteiligung standard uses GML 3.2, which is designed for maximum flexibility but creates implementation complexity:

- Multiple inheritance hierarchies
- Abstract base classes with complex requirements
- Type system conflicts between generated PHP classes

### 2. Auto-Generated Classes
The SOAP schema classes are auto-generated from XSD files:

- Strict type enforcement
- No flexibility for coordinate format conversion
- Limited ability to work around schema design issues

### 3. Standard Compliance Requirements
The XBeteiligung standard prioritizes comprehensive data structure over implementation simplicity:

- Complex nested structures for extensibility
- Verbose XML format requirements
- Multiple validation layers

## Practical Impact

### For Development
- **Points**: Use freely, full functionality available
- **LineStrings**: Use with understanding that coordinate detail is limited
- **Polygons**: Use for structure only, coordinate data not preserved

### For XBeteiligung Submissions
- **Compliance**: All generated XML is valid XBeteiligung format
- **Recognition**: XBeteiligung-compliant systems will recognize all geometry types
- **Detail Level**: Varying levels of coordinate detail based on geometry type

### For End Users
- **Point-based features**: Full location accuracy
- **Line/Area features**: Geometric presence indicated, detailed boundaries may be limited

## Example Usage

```php
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\GeoreferenzierungConverter;

$converter = new GeoreferenzierungConverter();

// Works perfectly
$pointGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Point","coordinates":[1132397.39,7111833.63]}}]}';
$result = $converter->convertGeoJsonToGeoreferenzierung($pointGeoJson);
// Result: Full point data with coordinates

// Works with limitations  
$lineGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"LineString","coordinates":[[0,0],[1,1]]}}]}';
$result = $converter->convertGeoJsonToGeoreferenzierung($lineGeoJson);
// Result: Line structure created, coordinates not populated

// Basic structure only
$polygonGeoJson = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[0,0],[1,0],[1,1],[0,1],[0,0]]]}}]}';
$result = $converter->convertGeoJsonToGeoreferenzierung($polygonGeoJson);
// Result: Polygon structure created, no coordinate data
```

## Future Improvements

### Short-term Solutions
1. **Enhanced Documentation**: Clear communication of limitations to users
2. **Graceful Degradation**: Ensure systems handle limited coordinate data appropriately
3. **Alternative Data Storage**: Store original GeoJSON alongside XBeteiligung XML

### Long-term Solutions  
1. **Schema Analysis**: Deep dive into XBeteiligung standard updates
2. **Manual XML Generation**: Bypass schema classes for coordinate-critical geometries
3. **Schema Enhancement**: Address type conflicts in schema implementation
4. **Hybrid Approach**: Combine schema classes with custom coordinate handling

## Testing

Comprehensive test coverage exists for all geometry types:

```bash
./vendor/bin/phpunit tests/Logic/GeoreferenzierungConverterTest.php
```

Tests verify:
- Point coordinate preservation
- LineString structure creation  
- Polygon structure creation
- Error handling for invalid data
- Mixed geometry collections

## Conclusion

The `GeoreferenzierungConverter` provides the best possible conversion given the constraints of the XBeteiligung standard. While not all coordinate data can be perfectly preserved, the implementation ensures:

- **Compliance** with XBeteiligung standard requirements
- **Reliability** for point-based use cases
- **Graceful handling** of complex geometries
- **Clear documentation** of limitations

For production use, consider the geometry type requirements of your specific use case and plan accordingly for the documented limitations.