<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing MultiCurveTypeType
 *
 *
 * XSD Type: MultiCurveType
 */
class MultiCurveTypeType extends AbstractGeometricAggregateTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CurveMember[] $curveMember
     */
    private $curveMember = [
        
    ];

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
    public function setCurveMember(array $curveMember = null)
    {
        $this->curveMember = $curveMember;
        return $this;
    }
}

