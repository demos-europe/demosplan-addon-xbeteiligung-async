<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing CurveSegmentArrayPropertyTypeType
 *
 *
 * XSD Type: CurveSegmentArrayPropertyType
 */
class CurveSegmentArrayPropertyTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment[] $abstractCurveSegment
     */
    private $abstractCurveSegment = [
        
    ];

    /**
     * Adds as abstractCurveSegment
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment $abstractCurveSegment
     */
    public function addToAbstractCurveSegment(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment $abstractCurveSegment)
    {
        $this->abstractCurveSegment[] = $abstractCurveSegment;
        return $this;
    }

    /**
     * isset abstractCurveSegment
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAbstractCurveSegment($index)
    {
        return isset($this->abstractCurveSegment[$index]);
    }

    /**
     * unset abstractCurveSegment
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAbstractCurveSegment($index)
    {
        unset($this->abstractCurveSegment[$index]);
    }

    /**
     * Gets as abstractCurveSegment
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment[]
     */
    public function getAbstractCurveSegment()
    {
        return $this->abstractCurveSegment;
    }

    /**
     * Sets a new abstractCurveSegment
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment[] $abstractCurveSegment
     * @return self
     */
    public function setAbstractCurveSegment(array $abstractCurveSegment = null)
    {
        $this->abstractCurveSegment = $abstractCurveSegment;
        return $this;
    }
}

