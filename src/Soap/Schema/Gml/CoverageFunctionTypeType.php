<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing CoverageFunctionTypeType
 *
 *
 * XSD Type: CoverageFunctionType
 */
class CoverageFunctionTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageMappingRule $coverageMappingRule
     */
    private $coverageMappingRule = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\GridFunction $gridFunction
     */
    private $gridFunction = null;

    /**
     * Gets as coverageMappingRule
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageMappingRule
     */
    public function getCoverageMappingRule()
    {
        return $this->coverageMappingRule;
    }

    /**
     * Sets a new coverageMappingRule
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageMappingRule $coverageMappingRule
     * @return self
     */
    public function setCoverageMappingRule(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\CoverageMappingRule $coverageMappingRule = null)
    {
        $this->coverageMappingRule = $coverageMappingRule;
        return $this;
    }

    /**
     * Gets as gridFunction
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\GridFunction
     */
    public function getGridFunction()
    {
        return $this->gridFunction;
    }

    /**
     * Sets a new gridFunction
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\GridFunction $gridFunction
     * @return self
     */
    public function setGridFunction(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\GridFunction $gridFunction = null)
    {
        $this->gridFunction = $gridFunction;
        return $this;
    }
}

