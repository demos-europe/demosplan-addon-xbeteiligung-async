<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PointTypeType
 *
 *
 * XSD Type: PointType
 */
class PointTypeType extends AbstractGeometricPrimitiveTypeType
{
    /**
     * @var float[] $pos
     */
    private $pos = null;

    /**
     * Adds as pos
     *
     * @return self
     * @param float $pos
     */
    public function addToPos($pos)
    {
        $this->pos[] = $pos;
        return $this;
    }

    /**
     * isset pos
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPos($index)
    {
        return isset($this->pos[$index]);
    }

    /**
     * unset pos
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPos($index)
    {
        unset($this->pos[$index]);
    }

    /**
     * Gets as pos
     *
     * @return float[]
     */
    public function getPos()
    {
        return $this->pos;
    }

    /**
     * Sets a new pos
     *
     * @param float[] $pos
     * @return self
     */
    public function setPos(array $pos)
    {
        $this->pos = $pos;
        return $this;
    }
}

