<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing MappingRuleTypeType
 *
 *
 * XSD Type: MappingRuleType
 */
class MappingRuleTypeType
{
    /**
     * @var string $ruleDefinition
     */
    private $ruleDefinition = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\ReferenceTypeType $ruleReference
     */
    private $ruleReference = null;

    /**
     * Gets as ruleDefinition
     *
     * @return string
     */
    public function getRuleDefinition()
    {
        return $this->ruleDefinition;
    }

    /**
     * Sets a new ruleDefinition
     *
     * @param string $ruleDefinition
     * @return self
     */
    public function setRuleDefinition($ruleDefinition)
    {
        $this->ruleDefinition = $ruleDefinition;
        return $this;
    }

    /**
     * Gets as ruleReference
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\ReferenceTypeType
     */
    public function getRuleReference()
    {
        return $this->ruleReference;
    }

    /**
     * Sets a new ruleReference
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\ReferenceTypeType $ruleReference
     * @return self
     */
    public function setRuleReference(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\ReferenceTypeType $ruleReference = null)
    {
        $this->ruleReference = $ruleReference;
        return $this;
    }
}

