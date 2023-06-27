<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AbstractCoverageTypeType
 *
 *
 * XSD Type: AbstractCoverageType
 */
class AbstractCoverageTypeType extends AbstractFeatureTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DomainSet $domainSet
     */
    private $domainSet = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeSet $rangeSet
     */
    private $rangeSet = null;

    /**
     * Gets as domainSet
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DomainSet
     */
    public function getDomainSet()
    {
        return $this->domainSet;
    }

    /**
     * Sets a new domainSet
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DomainSet $domainSet
     * @return self
     */
    public function setDomainSet(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DomainSet $domainSet)
    {
        $this->domainSet = $domainSet;
        return $this;
    }

    /**
     * Gets as rangeSet
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeSet
     */
    public function getRangeSet()
    {
        return $this->rangeSet;
    }

    /**
     * Sets a new rangeSet
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeSet $rangeSet
     * @return self
     */
    public function setRangeSet(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RangeSet $rangeSet)
    {
        $this->rangeSet = $rangeSet;
        return $this;
    }
}

