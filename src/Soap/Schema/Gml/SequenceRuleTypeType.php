<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing SequenceRuleTypeType
 *
 *
 * XSD Type: SequenceRuleType
 */
class SequenceRuleTypeType
{
    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * @var string[] $axisOrder
     */
    private $axisOrder = null;

    /**
     * Construct
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param string $value
     * @return string
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
     * Adds as axisOrder
     *
     * @return self
     * @param string $axisOrder
     */
    public function addToAxisOrder($axisOrder)
    {
        $this->axisOrder[] = $axisOrder;
        return $this;
    }

    /**
     * isset axisOrder
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAxisOrder($index)
    {
        return isset($this->axisOrder[$index]);
    }

    /**
     * unset axisOrder
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAxisOrder($index)
    {
        unset($this->axisOrder[$index]);
    }

    /**
     * Gets as axisOrder
     *
     * @return string[]
     */
    public function getAxisOrder()
    {
        return $this->axisOrder;
    }

    /**
     * Sets a new axisOrder
     *
     * @param string $axisOrder
     * @return self
     */
    public function setAxisOrder(array $axisOrder)
    {
        $this->axisOrder = $axisOrder;
        return $this;
    }
}

