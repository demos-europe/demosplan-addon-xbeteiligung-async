<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PolygonPatch
 *
 * A gml:PolygonPatch is a surface patch that is defined by a set of boundary curves and an underlying surface to which these curves adhere. The curves shall be coplanar and the polygon uses planar interpolation in its interior. 
 * interpolation is fixed to "planar", i.e. an interpolation shall return points on a single plane. The boundary of the patch shall be contained within that plane.
 */
class PolygonPatch extends PolygonPatchTypeType
{
}

