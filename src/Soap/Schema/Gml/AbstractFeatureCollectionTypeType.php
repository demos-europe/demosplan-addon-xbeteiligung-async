<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing AbstractFeatureCollectionTypeType
 *
 *
 * XSD Type: AbstractFeatureCollectionType
 */
class AbstractFeatureCollectionTypeType extends AbstractFeatureTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\FeatureMember[] $featureMember
     */
    private $featureMember = [
        
    ];

    /**
     * Adds as featureMember
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\FeatureMember $featureMember
     */
    public function addToFeatureMember(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\FeatureMember $featureMember)
    {
        $this->featureMember[] = $featureMember;
        return $this;
    }

    /**
     * isset featureMember
     *
     * @param int|string $index
     * @return bool
     */
    public function issetFeatureMember($index)
    {
        return isset($this->featureMember[$index]);
    }

    /**
     * unset featureMember
     *
     * @param int|string $index
     * @return void
     */
    public function unsetFeatureMember($index)
    {
        unset($this->featureMember[$index]);
    }

    /**
     * Gets as featureMember
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\FeatureMember[]
     */
    public function getFeatureMember()
    {
        return $this->featureMember;
    }

    /**
     * Sets a new featureMember
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\FeatureMember[] $featureMember
     * @return self
     */
    public function setFeatureMember(array $featureMember = null)
    {
        $this->featureMember = $featureMember;
        return $this;
    }
}

