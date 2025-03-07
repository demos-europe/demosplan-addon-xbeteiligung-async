<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AbstractFeatureTypeType
 *
 *
 * XSD Type: AbstractFeatureType
 */
class AbstractFeatureTypeType extends AbstractGMLTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BoundedBy $boundedBy
     */
    private $boundedBy = null;

    /**
     * Gets as boundedBy
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BoundedBy
     */
    public function getBoundedBy()
    {
        return $this->boundedBy;
    }

    /**
     * Sets a new boundedBy
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BoundedBy $boundedBy
     * @return self
     */
    public function setBoundedBy(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BoundedBy $boundedBy = null)
    {
        $this->boundedBy = $boundedBy;
        return $this;
    }
}

