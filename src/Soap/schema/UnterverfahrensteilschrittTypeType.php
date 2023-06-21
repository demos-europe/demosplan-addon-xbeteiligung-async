<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing UnterverfahrensteilschrittTypeType
 *
 *
 * XSD Type: UnterverfahrensteilschrittType
 */
class UnterverfahrensteilschrittTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType $uvtscode
     */
    private $uvtscode = null;

    /**
     * @var bool $mandatory
     */
    private $mandatory = null;

    /**
     * Gets as uvtscode
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType
     */
    public function getUvtscode()
    {
        return $this->uvtscode;
    }

    /**
     * Sets a new uvtscode
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType $uvtscode
     * @return self
     */
    public function setUvtscode(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType $uvtscode = null)
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

