<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing GridFunctionTypeType
 *
 *
 * XSD Type: GridFunctionType
 */
class GridFunctionTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SequenceRuleTypeType $sequenceRule
     */
    private $sequenceRule = null;

    /**
     * @var int[] $startPoint
     */
    private $startPoint = null;

    /**
     * Gets as sequenceRule
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SequenceRuleTypeType
     */
    public function getSequenceRule()
    {
        return $this->sequenceRule;
    }

    /**
     * Sets a new sequenceRule
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SequenceRuleTypeType $sequenceRule
     * @return self
     */
    public function setSequenceRule(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SequenceRuleTypeType $sequenceRule = null)
    {
        $this->sequenceRule = $sequenceRule;
        return $this;
    }

    /**
     * Adds as startPoint
     *
     * @return self
     * @param int $startPoint
     */
    public function addToStartPoint($startPoint)
    {
        $this->startPoint[] = $startPoint;
        return $this;
    }

    /**
     * isset startPoint
     *
     * @param int|string $index
     * @return bool
     */
    public function issetStartPoint($index)
    {
        return isset($this->startPoint[$index]);
    }

    /**
     * unset startPoint
     *
     * @param int|string $index
     * @return void
     */
    public function unsetStartPoint($index)
    {
        unset($this->startPoint[$index]);
    }

    /**
     * Gets as startPoint
     *
     * @return int[]
     */
    public function getStartPoint()
    {
        return $this->startPoint;
    }

    /**
     * Sets a new startPoint
     *
     * @param int[] $startPoint
     * @return self
     */
    public function setStartPoint(array $startPoint = null)
    {
        $this->startPoint = $startPoint;
        return $this;
    }
}

