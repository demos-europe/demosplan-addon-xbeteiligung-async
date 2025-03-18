<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing SurfaceTypeType
 *
 *
 * XSD Type: SurfaceType
 */
class SurfaceTypeType extends AbstractSurfaceTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractSurfacePatch[] $patches
     */
    private $patches = null;

    /**
     * Adds as abstractSurfacePatch
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractSurfacePatch $abstractSurfacePatch
     */
    public function addToPatches(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractSurfacePatch $abstractSurfacePatch)
    {
        $this->patches[] = $abstractSurfacePatch;
        return $this;
    }

    /**
     * isset patches
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPatches($index)
    {
        return isset($this->patches[$index]);
    }

    /**
     * unset patches
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPatches($index)
    {
        unset($this->patches[$index]);
    }

    /**
     * Gets as patches
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractSurfacePatch[]
     */
    public function getPatches()
    {
        return $this->patches;
    }

    /**
     * Sets a new patches
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractSurfacePatch[] $patches
     * @return self
     */
    public function setPatches(array $patches)
    {
        $this->patches = $patches;
        return $this;
    }
}

