<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AbstractRingPropertyTypeType
 *
 *
 * XSD Type: AbstractRingPropertyType
 */
class AbstractRingPropertyTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractRing $abstractRing
     */
    private $abstractRing = null;

    /**
     * Gets as abstractRing
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractRing
     */
    public function getAbstractRing()
    {
        return $this->abstractRing;
    }

    /**
     * Sets a new abstractRing
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractRing $abstractRing
     * @return self
     */
    public function setAbstractRing(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AbstractRing $abstractRing)
    {
        $this->abstractRing = $abstractRing;
        return $this;
    }
}

