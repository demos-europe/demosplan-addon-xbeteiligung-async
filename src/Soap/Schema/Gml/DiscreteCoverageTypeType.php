<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing DiscreteCoverageTypeType
 *
 *
 * XSD Type: DiscreteCoverageType
 */
class DiscreteCoverageTypeType extends AbstractCoverageTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageFunction $coverageFunction
     */
    private $coverageFunction = null;

    /**
     * Gets as coverageFunction
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageFunction
     */
    public function getCoverageFunction()
    {
        return $this->coverageFunction;
    }

    /**
     * Sets a new coverageFunction
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageFunction $coverageFunction
     * @return self
     */
    public function setCoverageFunction(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageFunction $coverageFunction = null)
    {
        $this->coverageFunction = $coverageFunction;
        return $this;
    }
}

