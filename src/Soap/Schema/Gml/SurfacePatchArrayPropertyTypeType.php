<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing SurfacePatchArrayPropertyTypeType
 *
 *
 * XSD Type: SurfacePatchArrayPropertyType
 */
class SurfacePatchArrayPropertyTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractSurfacePatch[] $abstractSurfacePatch
     */
    private $abstractSurfacePatch = [
        
    ];

    /**
     * Adds as abstractSurfacePatch
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractSurfacePatch $abstractSurfacePatch
     */
    public function addToAbstractSurfacePatch(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractSurfacePatch $abstractSurfacePatch)
    {
        $this->abstractSurfacePatch[] = $abstractSurfacePatch;
        return $this;
    }

    /**
     * isset abstractSurfacePatch
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAbstractSurfacePatch($index)
    {
        return isset($this->abstractSurfacePatch[$index]);
    }

    /**
     * unset abstractSurfacePatch
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAbstractSurfacePatch($index)
    {
        unset($this->abstractSurfacePatch[$index]);
    }

    /**
     * Gets as abstractSurfacePatch
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractSurfacePatch[]
     */
    public function getAbstractSurfacePatch()
    {
        return $this->abstractSurfacePatch;
    }

    /**
     * Sets a new abstractSurfacePatch
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractSurfacePatch[] $abstractSurfacePatch
     * @return self
     */
    public function setAbstractSurfacePatch(?array $abstractSurfacePatch = null)
    {
        $this->abstractSurfacePatch = $abstractSurfacePatch;
        return $this;
    }
}

