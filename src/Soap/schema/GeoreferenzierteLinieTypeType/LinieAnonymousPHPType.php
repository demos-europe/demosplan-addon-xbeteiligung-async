<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType;

/**
 * Class representing LinieAnonymousPHPType
 */
class LinieAnonymousPHPType
{
    /**
     * Das Element (globales Element gml:CurveType) enthält die GML-Darstellung einer Linie.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveTypeTypeType $linie
     */
    private $linie = null;

    /**
     * Gets as linie
     *
     * Das Element (globales Element gml:CurveType) enthält die GML-Darstellung einer Linie.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveTypeTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveTypeTypeType $linie
     * @return self
     */
    public function setLinie(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveTypeTypeType $linie)
    {
        $this->linie = $linie;
        return $this;
    }
}

