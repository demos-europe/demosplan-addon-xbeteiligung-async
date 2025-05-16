<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing MeasureTypeType
 *
 *
 * XSD Type: MeasureType
 */
class MeasureTypeType
{
    /**
     * @var float $__value
     */
    private $__value = null;

    /**
     * @var string $uom
     */
    private $uom = null;

    /**
     * Construct
     *
     * @param float $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param float $value
     * @return float
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }

    /**
     * Gets as uom
     *
     * @return string
     */
    public function getUom()
    {
        return $this->uom;
    }

    /**
     * Sets a new uom
     *
     * @param string $uom
     * @return self
     */
    public function setUom($uom)
    {
        $this->uom = $uom;
        return $this;
    }
}

