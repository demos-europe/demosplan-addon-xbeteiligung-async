<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing FehlerhafteUnterlageType
 *
 * Dieser Typ beschreibt eine fehlerhafte Unterlage.
 * XSD Type: FehlerhafteUnterlage
 */
class FehlerhafteUnterlageType
{
    /**
     * Hier ist die Beschreibung des Fehlers zu übermitteln.
     *
     * @var string $fehlerbeschreibung
     */
    private $fehlerbeschreibung = null;

    /**
     * ID der fehlerhaften Unterlage.
     *
     * @var string $dokumentID
     */
    private $dokumentID = null;

    /**
     * Hier kann die Art des Fehlers übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType $art
     */
    private $art = null;

    /**
     * Gets as fehlerbeschreibung
     *
     * Hier ist die Beschreibung des Fehlers zu übermitteln.
     *
     * @return string
     */
    public function getFehlerbeschreibung()
    {
        return $this->fehlerbeschreibung;
    }

    /**
     * Sets a new fehlerbeschreibung
     *
     * Hier ist die Beschreibung des Fehlers zu übermitteln.
     *
     * @param string $fehlerbeschreibung
     * @return self
     */
    public function setFehlerbeschreibung($fehlerbeschreibung)
    {
        $this->fehlerbeschreibung = $fehlerbeschreibung;
        return $this;
    }

    /**
     * Gets as dokumentID
     *
     * ID der fehlerhaften Unterlage.
     *
     * @return string
     */
    public function getDokumentID()
    {
        return $this->dokumentID;
    }

    /**
     * Sets a new dokumentID
     *
     * ID der fehlerhaften Unterlage.
     *
     * @param string $dokumentID
     * @return self
     */
    public function setDokumentID($dokumentID)
    {
        $this->dokumentID = $dokumentID;
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

