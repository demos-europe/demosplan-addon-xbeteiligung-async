<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing PolygonTypeType
 *
 *
 * XSD Type: PolygonType
 */
class PolygonTypeType extends AbstractSurfaceTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Exterior $exterior
     */
    private $exterior = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Interior[] $interior
     */
    private $interior = [
        
    ];

    /**
     * Gets as exterior
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Exterior
     */
    public function getExterior()
    {
        return $this->exterior;
    }

    /**
     * Sets a new exterior
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Exterior $exterior
     * @return self
     */
    public function setExterior(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Exterior $exterior = null)
    {
        $this->exterior = $exterior;
        return $this;
    }

    /**
     * Adds as interior
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Interior $interior
     */
    public function addToInterior(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Interior $interior)
    {
        $this->interior[] = $interior;
        return $this;
    }

    /**
     * isset interior
     *
     * @param int|string $index
     * @return bool
     */
    public function issetInterior($index)
    {
        return isset($this->interior[$index]);
    }

    /**
     * unset interior
     *
     * @param int|string $index
     * @return void
     */
    public function unsetInterior($index)
    {
        unset($this->interior[$index]);
    }

    /**
     * Gets as interior
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Interior[]
     */
    public function getInterior()
    {
        return $this->interior;
    }

    /**
     * Sets a new interior
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\Interior[] $interior
     * @return self
     */
    public function setInterior(?array $interior = null)
    {
        $this->interior = $interior;
        return $this;
    }
}

