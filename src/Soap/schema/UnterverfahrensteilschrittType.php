<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing UnterverfahrensteilschrittType
 *
 *
 * XSD Type: Unterverfahrensteilschritt
 */
class UnterverfahrensteilschrittType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType $uvtscode
     */
    private $uvtscode = null;

    /**
     * @var bool $mandatory
     */
    private $mandatory = null;

    /**
     * Gets as uvtscode
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType
     */
    public function getUvtscode()
    {
        return $this->uvtscode;
    }

    /**
     * Sets a new uvtscode
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType $uvtscode
     * @return self
     */
    public function setUvtscode(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType $uvtscode = null)
    {
        $this->uvtscode = $uvtscode;
        return $this;
    }

    /**
     * Gets as mandatory
     *
     * @return bool
     */
    public function getMandatory()
    {
        return $this->mandatory;
    }

    /**
     * Sets a new mandatory
     *
     * @param bool $mandatory
     * @return self
     */
    public function setMandatory($mandatory)
    {
        $this->mandatory = $mandatory;
        return $this;
    }
}

