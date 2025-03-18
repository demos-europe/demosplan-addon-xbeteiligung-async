<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing CodeTypeType
 *
 *
 * XSD Type: CodeType
 */
class CodeTypeType
{
    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * @var string $codeSpace
     */
    private $codeSpace = null;

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
     * Gets as codeSpace
     *
     * @return string
     */
    public function getCodeSpace()
    {
        return $this->codeSpace;
    }

    /**
     * Sets a new codeSpace
     *
     * @param string $codeSpace
     * @return self
     */
    public function setCodeSpace($codeSpace)
    {
        $this->codeSpace = $codeSpace;
        return $this;
    }
}

