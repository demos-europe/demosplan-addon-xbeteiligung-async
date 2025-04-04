<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing AbstractGeometryTypeType
 *
 *
 * XSD Type: AbstractGeometryType
 */
class AbstractGeometryTypeType extends AbstractGMLTypeType
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
}

