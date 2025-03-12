<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AbstractMetadataPropertyTypeType
 *
 *
 * XSD Type: AbstractMetadataPropertyType
 */
class AbstractMetadataPropertyTypeType
{
    /**
     * @var bool $owns
     */
    private $owns = null;

    /**
     * Gets as owns
     *
     * @return bool
     */
    public function getOwns()
    {
        return $this->owns;
    }

    /**
     * Sets a new owns
     *
     * @param bool $owns
     * @return self
     */
    public function setOwns($owns)
    {
        $this->owns = $owns;
        return $this;
    }
}

