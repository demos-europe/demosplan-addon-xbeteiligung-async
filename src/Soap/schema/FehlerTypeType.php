<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing FehlerTypeType
 *
 *
 * XSD Type: FehlerType
 */
class FehlerTypeType
{
    /**
     * @var string $beschreibung
     */
    private $beschreibung = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartTypeType $art
     */
    private $art = null;

    /**
     * Gets as beschreibung
     *
     * @return string
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Sets a new beschreibung
     *
     * @param string $beschreibung
     * @return self
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
        return $this;
    }

    /**
     * Gets as art
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartTypeType
     */
    public function getArt()
    {
        return $this->art;
    }

    /**
     * Sets a new art
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartTypeType $art
     * @return self
     */
    public function setArt(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartTypeType $art = null)
    {
        $this->art = $art;
        return $this;
    }
}

