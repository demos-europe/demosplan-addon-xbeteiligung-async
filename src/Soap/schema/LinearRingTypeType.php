<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing LinearRingTypeType
 *
 *
 * XSD Type: LinearRingType
 */
class LinearRingTypeType extends AbstractRingTypeType
{
    /**
     * @var float[] $posList
     */
    private $posList = null;

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

