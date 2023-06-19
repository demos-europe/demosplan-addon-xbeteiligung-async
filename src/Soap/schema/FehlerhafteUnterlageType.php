<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing FehlerhafteUnterlageType
 *
 *
 * XSD Type: FehlerhafteUnterlage
 */
class FehlerhafteUnterlageType
{
    /**
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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlerartType $art
     */
    private $art = null;

    /**
     * Gets as fehlerbeschreibung
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

