<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PolygonPatchTypeType
 *
 *
 * XSD Type: PolygonPatchType
 */
class PolygonPatchTypeType extends AbstractSurfacePatchTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Exterior $exterior
     */
    private $exterior = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Interior[] $interior
     */
    private $interior = [
        
    ];

    /**
     * Gets as exterior
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Exterior
     */
    public function getExterior()
    {
        return $this->exterior;
    }

    /**
     * Sets a new exterior
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Exterior $exterior
     * @return self
     */
    public function setExterior(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Exterior $exterior = null)
    {
        $this->exterior = $exterior;
        return $this;
    }

    /**
     * Adds as interior
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Interior $interior
     */
    public function addToInterior(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Interior $interior)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Interior[]
     */
    public function getInterior()
    {
        return $this->interior;
    }

    /**
     * Sets a new interior
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Interior[] $interior
     * @return self
     */
    public function setInterior(array $interior = null)
    {
        $this->interior = $interior;
        return $this;
    }
}

