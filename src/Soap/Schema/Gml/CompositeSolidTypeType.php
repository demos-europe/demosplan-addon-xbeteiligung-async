<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing CompositeSolidTypeType
 *
 *
 * XSD Type: CompositeSolidType
 */
class CompositeSolidTypeType extends AbstractSolidTypeType
{
    /**
     * @var string $aggregationType
     */
    private $aggregationType = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SolidMember[] $solidMember
     */
    private $solidMember = [
        
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
     * Adds as solidMember
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SolidMember $solidMember
     */
    public function addToSolidMember(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SolidMember $solidMember)
    {
        $this->solidMember[] = $solidMember;
        return $this;
    }

    /**
     * isset solidMember
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSolidMember($index)
    {
        return isset($this->solidMember[$index]);
    }

    /**
     * unset solidMember
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSolidMember($index)
    {
        unset($this->solidMember[$index]);
    }

    /**
     * Gets as solidMember
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SolidMember[]
     */
    public function getSolidMember()
    {
        return $this->solidMember;
    }

    /**
     * Sets a new solidMember
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\SolidMember[] $solidMember
     * @return self
     */
    public function setSolidMember(array $solidMember)
    {
        $this->solidMember = $solidMember;
        return $this;
    }
}

