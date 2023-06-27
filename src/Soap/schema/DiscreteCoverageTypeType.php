<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing DiscreteCoverageTypeType
 *
 *
 * XSD Type: DiscreteCoverageType
 */
class DiscreteCoverageTypeType extends AbstractCoverageTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CoverageFunction $coverageFunction
     */
    private $coverageFunction = null;

    /**
     * Gets as coverageFunction
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CoverageFunction
     */
    public function getCoverageFunction()
    {
        return $this->coverageFunction;
    }

    /**
     * Sets a new coverageFunction
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CoverageFunction $coverageFunction
     * @return self
     */
    public function setCoverageFunction(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CoverageFunction $coverageFunction = null)
    {
        $this->coverageFunction = $coverageFunction;
        return $this;
    }
}

