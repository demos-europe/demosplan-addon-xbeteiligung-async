<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing EnvelopeTypeType
 *
 *
 * XSD Type: EnvelopeType
 */
class EnvelopeTypeType
{
    /**
     * @var string $srsName
     */
    private $srsName = null;

    /**
     * @var int $srsDimension
     */
    private $srsDimension = null;

    /**
     * @var string[] $axisLabels
     */
    private $axisLabels = null;

    /**
     * @var string[] $uomLabels
     */
    private $uomLabels = null;

    /**
     * @var float[] $lowerCorner
     */
    private $lowerCorner = null;

    /**
     * @var float[] $upperCorner
     */
    private $upperCorner = null;

    /**
     * Gets as srsName
     *
     * @return string
     */
    public function getSrsName()
    {
        return $this->srsName;
    }

    /**
     * Sets a new srsName
     *
     * @param string $srsName
     * @return self
     */
    public function setSrsName($srsName)
    {
        $this->srsName = $srsName;
        return $this;
    }

    /**
     * Gets as srsDimension
     *
     * @return int
     */
    public function getSrsDimension()
    {
        return $this->srsDimension;
    }

    /**
     * Sets a new srsDimension
     *
     * @param int $srsDimension
     * @return self
     */
    public function setSrsDimension($srsDimension)
    {
        $this->srsDimension = $srsDimension;
        return $this;
    }

    /**
     * Adds as axisLabels
     *
     * @return self
     * @param string $axisLabels
     */
    public function addToAxisLabels($axisLabels)
    {
        $this->axisLabels[] = $axisLabels;
        return $this;
    }

    /**
     * isset axisLabels
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAxisLabels($index)
    {
        return isset($this->axisLabels[$index]);
    }

    /**
     * unset axisLabels
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAxisLabels($index)
    {
        unset($this->axisLabels[$index]);
    }

    /**
     * Gets as axisLabels
     *
     * @return string[]
     */
    public function getAxisLabels()
    {
        return $this->axisLabels;
    }

    /**
     * Sets a new axisLabels
     *
     * @param string[] $axisLabels
     * @return self
     */
    public function setAxisLabels(array $axisLabels)
    {
        $this->axisLabels = $axisLabels;
        return $this;
    }

    /**
     * Adds as uomLabels
     *
     * @return self
     * @param string $uomLabels
     */
    public function addToUomLabels($uomLabels)
    {
        $this->uomLabels[] = $uomLabels;
        return $this;
    }

    /**
     * isset uomLabels
     *
     * @param int|string $index
     * @return bool
     */
    public function issetUomLabels($index)
    {
        return isset($this->uomLabels[$index]);
    }

    /**
     * unset uomLabels
     *
     * @param int|string $index
     * @return void
     */
    public function unsetUomLabels($index)
    {
        unset($this->uomLabels[$index]);
    }

    /**
     * Gets as uomLabels
     *
     * @return string[]
     */
    public function getUomLabels()
    {
        return $this->uomLabels;
    }

    /**
     * Sets a new uomLabels
     *
     * @param string[] $uomLabels
     * @return self
     */
    public function setUomLabels(array $uomLabels)
    {
        $this->uomLabels = $uomLabels;
        return $this;
    }

    /**
     * Adds as lowerCorner
     *
     * @return self
     * @param float $lowerCorner
     */
    public function addToLowerCorner($lowerCorner)
    {
        $this->lowerCorner[] = $lowerCorner;
        return $this;
    }

    /**
     * isset lowerCorner
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLowerCorner($index)
    {
        return isset($this->lowerCorner[$index]);
    }

    /**
     * unset lowerCorner
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLowerCorner($index)
    {
        unset($this->lowerCorner[$index]);
    }

    /**
     * Gets as lowerCorner
     *
     * @return float[]
     */
    public function getLowerCorner()
    {
        return $this->lowerCorner;
    }

    /**
     * Sets a new lowerCorner
     *
     * @param float[] $lowerCorner
     * @return self
     */
    public function setLowerCorner(array $lowerCorner)
    {
        $this->lowerCorner = $lowerCorner;
        return $this;
    }

    /**
     * Adds as upperCorner
     *
     * @return self
     * @param float $upperCorner
     */
    public function addToUpperCorner($upperCorner)
    {
        $this->upperCorner[] = $upperCorner;
        return $this;
    }

    /**
     * isset upperCorner
     *
     * @param int|string $index
     * @return bool
     */
    public function issetUpperCorner($index)
    {
        return isset($this->upperCorner[$index]);
    }

    /**
     * unset upperCorner
     *
     * @param int|string $index
     * @return void
     */
    public function unsetUpperCorner($index)
    {
        unset($this->upperCorner[$index]);
    }

    /**
     * Gets as upperCorner
     *
     * @return float[]
     */
    public function getUpperCorner()
    {
        return $this->upperCorner;
    }

    /**
     * Sets a new upperCorner
     *
     * @param float[] $upperCorner
     * @return self
     */
    public function setUpperCorner(array $upperCorner)
    {
        $this->upperCorner = $upperCorner;
        return $this;
    }
}

