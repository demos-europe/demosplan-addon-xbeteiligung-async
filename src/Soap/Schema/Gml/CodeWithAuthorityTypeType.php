<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing CodeWithAuthorityTypeType
 *
 *
 * XSD Type: CodeWithAuthorityType
 */
class CodeWithAuthorityTypeType extends CodeTypeType
{
    /**
     * @var string $codeSpace
     */
    private $codeSpace = null;

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

