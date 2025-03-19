<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing CurveTypeType
 *
 *
 * XSD Type: CurveType
 */
class CurveTypeType extends AbstractCurveTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment[] $segments
     */
    private $segments = null;

    /**
     * Adds as abstractCurveSegment
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment $abstractCurveSegment
     */
    public function addToSegments(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment $abstractCurveSegment)
    {
        $this->segments[] = $abstractCurveSegment;
        return $this;
    }

    /**
     * isset segments
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSegments($index)
    {
        return isset($this->segments[$index]);
    }

    /**
     * unset segments
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSegments($index)
    {
        unset($this->segments[$index]);
    }

    /**
     * Gets as segments
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment[]
     */
    public function getSegments()
    {
        return $this->segments;
    }

    /**
     * Sets a new segments
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\AbstractCurveSegment[] $segments
     * @return self
     */
    public function setSegments(array $segments)
    {
        $this->segments = $segments;
        return $this;
    }
}

