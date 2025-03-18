<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AbstractGeometricAggregateTypeType
 *
 *
 * XSD Type: AbstractGeometricAggregateType
 */
class AbstractGeometricAggregateTypeType extends AbstractGeometryTypeType
{
    /**
     * @var string $aggregationType
     */
    private $aggregationType = null;

    /**
     * Gets as aggregationType
     *
     * @return string
     */
    public function getAggregationType()
    {
        return $this->aggregationType;
    }

    /**
     * Sets a new aggregationType
     *
     * @param string $aggregationType
     * @return self
     */
    public function setAggregationType($aggregationType)
    {
        $this->aggregationType = $aggregationType;
        return $this;
    }
}

