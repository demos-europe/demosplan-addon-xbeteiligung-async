<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheTypeType;

/**
 * Class representing FlaecheAnonymousPHPType
 */
class FlaecheAnonymousPHPType
{
    /**
     * Das Element (globales Element gml:Polygon) enthält die GML-Darstellung einer Grundfläche (es können Außenkanten und Innenkanten dargestellt werden). Aus der GML-Dokumentation: "A Polygon is a special surface that is defined by a single surface patch. The boundary of this patch is coplanar and the polygon uses planar interpolation in its interior. The elements exterior and interior describe the surface boundary of the polygon." Quelle: http://www.datypic.com/sc/niem21/e-gml32_Polygon.html
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PolygonTypeTypeType $polygon
     */
    private $polygon = null;

    /**
     * Gets as polygon
     *
     * Das Element (globales Element gml:Polygon) enthält die GML-Darstellung einer Grundfläche (es können Außenkanten und Innenkanten dargestellt werden). Aus der GML-Dokumentation: "A Polygon is a special surface that is defined by a single surface patch. The boundary of this patch is coplanar and the polygon uses planar interpolation in its interior. The elements exterior and interior describe the surface boundary of the polygon." Quelle: http://www.datypic.com/sc/niem21/e-gml32_Polygon.html
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PolygonTypeTypeType
     */
    public function getPolygon()
    {
        return $this->polygon;
    }

    /**
     * Sets a new polygon
     *
     * Das Element (globales Element gml:Polygon) enthält die GML-Darstellung einer Grundfläche (es können Außenkanten und Innenkanten dargestellt werden). Aus der GML-Dokumentation: "A Polygon is a special surface that is defined by a single surface patch. The boundary of this patch is coplanar and the polygon uses planar interpolation in its interior. The elements exterior and interior describe the surface boundary of the polygon." Quelle: http://www.datypic.com/sc/niem21/e-gml32_Polygon.html
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PolygonTypeTypeType $polygon
     * @return self
     */
    public function setPolygon(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PolygonTypeTypeType $polygon)
    {
        $this->polygon = $polygon;
        return $this;
    }
}

