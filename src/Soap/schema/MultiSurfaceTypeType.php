<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing MultiSurfaceTypeType
 *
 *
 * XSD Type: MultiSurfaceType
 */
class MultiSurfaceTypeType extends AbstractGeometricAggregateTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\SurfaceMember[] $surfaceMember
     */
    private $surfaceMember = [
        
    ];

    /**
     * Adds as surfaceMember
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\SurfaceMember $surfaceMember
     */
    public function addToSurfaceMember(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\SurfaceMember $surfaceMember)
    {
        $this->surfaceMember[] = $surfaceMember;
        return $this;
    }

    /**
     * isset surfaceMember
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSurfaceMember($index)
    {
        return isset($this->surfaceMember[$index]);
    }

    /**
     * unset surfaceMember
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSurfaceMember($index)
    {
        unset($this->surfaceMember[$index]);
    }

    /**
     * Gets as surfaceMember
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\SurfaceMember[]
     */
    public function getSurfaceMember()
    {
        return $this->surfaceMember;
    }

    /**
     * Sets a new surfaceMember
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\SurfaceMember[] $surfaceMember
     * @return self
     */
    public function setSurfaceMember(array $surfaceMember = null)
    {
        $this->surfaceMember = $surfaceMember;
        return $this;
    }
}

