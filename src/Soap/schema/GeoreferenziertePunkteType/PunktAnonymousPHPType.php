<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteType;

/**
 * Class representing PunktAnonymousPHPType
 */
class PunktAnonymousPHPType
{
    /**
     * Das Element (globales Element gml:PointType) enthält die GML-Darstellung einer Grundfläche .
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PointTypeType $punkt
     */
    private $punkt = null;

    /**
     * Gets as punkt
     *
     * Das Element (globales Element gml:PointType) enthält die GML-Darstellung einer Grundfläche .
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PointTypeType
     */
    public function getPunkt()
    {
        return $this->punkt;
    }

    /**
     * Sets a new punkt
     *
     * Das Element (globales Element gml:PointType) enthält die GML-Darstellung einer Grundfläche .
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PointTypeType $punkt
     * @return self
     */
    public function setPunkt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PointTypeType $punkt)
    {
        $this->punkt = $punkt;
        return $this;
    }
}

