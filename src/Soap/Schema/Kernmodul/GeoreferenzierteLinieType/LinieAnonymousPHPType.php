<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType;

/**
 * Class representing LinieAnonymousPHPType
 */
class LinieAnonymousPHPType
{
    /**
     * Das Element (globales Element gml:CurveType) enthält die GML-Darstellung einer Linie.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CurveTypeType $linie
     */
    private $linie = null;

    /**
     * Gets as linie
     *
     * Das Element (globales Element gml:CurveType) enthält die GML-Darstellung einer Linie.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CurveTypeType
     */
    public function getLinie()
    {
        return $this->linie;
    }

    /**
     * Sets a new linie
     *
     * Das Element (globales Element gml:CurveType) enthält die GML-Darstellung einer Linie.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CurveTypeType $linie
     * @return self
     */
    public function setLinie(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CurveTypeType $linie)
    {
        $this->linie = $linie;
        return $this;
    }
}

