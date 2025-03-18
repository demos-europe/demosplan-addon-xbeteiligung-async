<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AbstractCurveSegmentTypeType
 *
 *
 * XSD Type: AbstractCurveSegmentType
 */
class AbstractCurveSegmentTypeType
{
    /**
     * @var int $numDerivativesAtStart
     */
    private $numDerivativesAtStart = null;

    /**
     * @var int $numDerivativesAtEnd
     */
    private $numDerivativesAtEnd = null;

    /**
     * @var int $numDerivativeInterior
     */
    private $numDerivativeInterior = null;

    /**
     * Gets as numDerivativesAtStart
     *
     * @return int
     */
    public function getNumDerivativesAtStart()
    {
        return $this->numDerivativesAtStart;
    }

    /**
     * Sets a new numDerivativesAtStart
     *
     * @param int $numDerivativesAtStart
     * @return self
     */
    public function setNumDerivativesAtStart($numDerivativesAtStart)
    {
        $this->numDerivativesAtStart = $numDerivativesAtStart;
        return $this;
    }

    /**
     * Gets as numDerivativesAtEnd
     *
     * @return int
     */
    public function getNumDerivativesAtEnd()
    {
        return $this->numDerivativesAtEnd;
    }

    /**
     * Sets a new numDerivativesAtEnd
     *
     * @param int $numDerivativesAtEnd
     * @return self
     */
    public function setNumDerivativesAtEnd($numDerivativesAtEnd)
    {
        $this->numDerivativesAtEnd = $numDerivativesAtEnd;
        return $this;
    }

    /**
     * Gets as numDerivativeInterior
     *
     * @return int
     */
    public function getNumDerivativeInterior()
    {
        return $this->numDerivativeInterior;
    }

    /**
     * Sets a new numDerivativeInterior
     *
     * @param int $numDerivativeInterior
     * @return self
     */
    public function setNumDerivativeInterior($numDerivativeInterior)
    {
        $this->numDerivativeInterior = $numDerivativeInterior;
        return $this;
    }
}

