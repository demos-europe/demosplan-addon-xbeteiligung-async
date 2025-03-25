<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing LineStringSegmentTypeType
 *
 *
 * XSD Type: LineStringSegmentType
 */
class LineStringSegmentTypeType extends AbstractCurveSegmentTypeType
{
    /**
     * @var string $interpolation
     */
    private $interpolation = null;

    /**
     * @var float[] $posList
     */
    private $posList = null;

    /**
     * Gets as interpolation
     *
     * @return string
     */
    public function getInterpolation()
    {
        return $this->interpolation;
    }

    /**
     * Sets a new interpolation
     *
     * @param string $interpolation
     * @return self
     */
    public function setInterpolation($interpolation)
    {
        $this->interpolation = $interpolation;
        return $this;
    }

    /**
     * Adds as posList
     *
     * @return self
     * @param float $posList
     */
    public function addToPosList($posList)
    {
        $this->posList[] = $posList;
        return $this;
    }

    /**
     * isset posList
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPosList($index)
    {
        return isset($this->posList[$index]);
    }

    /**
     * unset posList
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPosList($index)
    {
        unset($this->posList[$index]);
    }

    /**
     * Gets as posList
     *
     * @return float[]
     */
    public function getPosList()
    {
        return $this->posList;
    }

    /**
     * Sets a new posList
     *
     * @param float[] $posList
     * @return self
     */
    public function setPosList(array $posList)
    {
        $this->posList = $posList;
        return $this;
    }
}

