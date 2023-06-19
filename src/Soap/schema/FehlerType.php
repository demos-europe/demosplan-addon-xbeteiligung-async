<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing FehlerType
 *
 *
 * XSD Type: Fehler
 */
class FehlerType
{
    /**
     * @var string $beschreibung
     */
    private $beschreibung = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType $art
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType
     */
    public function getArt()
    {
        return $this->art;
    }

    /**
     * Sets a new art
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType $art
     * @return self
     */
    public function setArt(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType $art = null)
    {
        $this->art = $art;
        return $this;
    }
}

