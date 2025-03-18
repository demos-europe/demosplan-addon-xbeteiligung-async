<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing FehlerType
 *
 * Dieser Typ beschreibt einen Fehler.
 * XSD Type: Fehler
 */
class FehlerType
{
    /**
     * Hier ist die Beschreibung des Fehlers zu übermitteln.
     *
     * @var string $beschreibung
     */
    private $beschreibung = null;

    /**
     * Hier kann die Art des Fehlers übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType $art
     */
    private $art = null;

    /**
     * Gets as beschreibung
     *
     * Hier ist die Beschreibung des Fehlers zu übermitteln.
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
     * Hier ist die Beschreibung des Fehlers zu übermitteln.
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
     * Hier kann die Art des Fehlers übermittelt werden.
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
     * Hier kann die Art des Fehlers übermittelt werden.
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

