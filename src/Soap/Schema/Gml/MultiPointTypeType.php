<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing MultiPointTypeType
 *
 *
 * XSD Type: MultiPointType
 */
class MultiPointTypeType extends AbstractGeometricAggregateTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PointMember[] $pointMember
     */
    private $pointMember = [
        
    ];

    /**
     * Adds as pointMember
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PointMember $pointMember
     */
    public function addToPointMember(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PointMember $pointMember)
    {
        $this->pointMember[] = $pointMember;
        return $this;
    }

    /**
     * isset pointMember
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPointMember($index)
    {
        return isset($this->pointMember[$index]);
    }

    /**
     * unset pointMember
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPointMember($index)
    {
        unset($this->pointMember[$index]);
    }

    /**
     * Gets as pointMember
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PointMember[]
     */
    public function getPointMember()
    {
        return $this->pointMember;
    }

    /**
     * Sets a new pointMember
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\PointMember[] $pointMember
     * @return self
     */
    public function setPointMember(?array $pointMember = null)
    {
        $this->pointMember = $pointMember;
        return $this;
    }
}

