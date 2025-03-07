<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RingTypeType
 *
 *
 * XSD Type: RingType
 */
class RingTypeType extends AbstractRingTypeType
{
    /**
     * @var string $aggregationType
     */
    private $aggregationType = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveMember[] $curveMember
     */
    private $curveMember = [
        
    ];

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

    /**
     * Adds as curveMember
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveMember $curveMember
     */
    public function addToCurveMember(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveMember $curveMember)
    {
        $this->curveMember[] = $curveMember;
        return $this;
    }

    /**
     * isset curveMember
     *
     * @param int|string $index
     * @return bool
     */
    public function issetCurveMember($index)
    {
        return isset($this->curveMember[$index]);
    }

    /**
     * unset curveMember
     *
     * @param int|string $index
     * @return void
     */
    public function unsetCurveMember($index)
    {
        unset($this->curveMember[$index]);
    }

    /**
     * Gets as curveMember
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveMember[]
     */
    public function getCurveMember()
    {
        return $this->curveMember;
    }

    /**
     * Sets a new curveMember
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveMember[] $curveMember
     * @return self
     */
    public function setCurveMember(array $curveMember)
    {
        $this->curveMember = $curveMember;
        return $this;
    }
}

