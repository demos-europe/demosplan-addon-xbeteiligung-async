<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing CubicSplineTypeType
 *
 *
 * XSD Type: CubicSplineType
 */
class CubicSplineTypeType extends AbstractCurveSegmentTypeType
{
    /**
     * @var string $interpolation
     */
    private $interpolation = null;

    /**
     * @var int $degree
     */
    private $degree = null;

    /**
     * @var float[] $posList
     */
    private $posList = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType $vectorAtStart
     */
    private $vectorAtStart = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType $vectorAtEnd
     */
    private $vectorAtEnd = null;

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
     * Gets as degree
     *
     * @return int
     */
    public function getDegree()
    {
        return $this->degree;
    }

    /**
     * Sets a new degree
     *
     * @param int $degree
     * @return self
     */
    public function setDegree($degree)
    {
        $this->degree = $degree;
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

    /**
     * Gets as vectorAtStart
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType
     */
    public function getVectorAtStart()
    {
        return $this->vectorAtStart;
    }

    /**
     * Sets a new vectorAtStart
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType $vectorAtStart
     * @return self
     */
    public function setVectorAtStart(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType $vectorAtStart)
    {
        $this->vectorAtStart = $vectorAtStart;
        return $this;
    }

    /**
     * Gets as vectorAtEnd
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType
     */
    public function getVectorAtEnd()
    {
        return $this->vectorAtEnd;
    }

    /**
     * Sets a new vectorAtEnd
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType $vectorAtEnd
     * @return self
     */
    public function setVectorAtEnd(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VectorTypeType $vectorAtEnd)
    {
        $this->vectorAtEnd = $vectorAtEnd;
        return $this;
    }
}

