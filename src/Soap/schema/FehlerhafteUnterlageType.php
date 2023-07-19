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
     * ID der Unterlage.
     *
     * @var string $fileID
     */
    private $fileID = null;

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
     * Gets as fileID
     *
     * ID der Unterlage.
     *
     * @return string
     */
    public function getFileID()
    {
        return $this->fileID;
    }

    /**
     * Sets a new fileID
     *
     * ID der Unterlage.
     *
     * @param string $fileID
     * @return self
     */
    public function setFileID($fileID)
    {
        $this->fileID = $fileID;
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

