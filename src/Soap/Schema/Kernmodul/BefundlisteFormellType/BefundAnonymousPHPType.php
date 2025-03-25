<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\BefundlisteFormellType;

/**
 * Class representing BefundAnonymousPHPType
 */
class BefundAnonymousPHPType
{
    /**
     * In diesem Element wird die Befundkategorie angegeben, der dieser Befund zugeordnet werden kann.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeFormelleBefundeArtType $artDesBefundes
     */
    private $artDesBefundes = null;

    /**
     * Hier wird der Sachverhalt des Befundes beschrieben. Dieser Sachverhalt enthält Details, die für den Adressaten hilfreich sind ergänzend zur angegebenen Art des Befundes.
     *
     * @var string[] $beschreibung
     */
    private $beschreibung = null;

    /**
     * Gets as artDesBefundes
     *
     * In diesem Element wird die Befundkategorie angegeben, der dieser Befund zugeordnet werden kann.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeFormelleBefundeArtType
     */
    public function getArtDesBefundes()
    {
        return $this->artDesBefundes;
    }

    /**
     * Sets a new artDesBefundes
     *
     * In diesem Element wird die Befundkategorie angegeben, der dieser Befund zugeordnet werden kann.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeFormelleBefundeArtType $artDesBefundes
     * @return self
     */
    public function setArtDesBefundes(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeFormelleBefundeArtType $artDesBefundes)
    {
        $this->artDesBefundes = $artDesBefundes;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * Hier wird der Sachverhalt des Befundes beschrieben. Dieser Sachverhalt enthält Details, die für den Adressaten hilfreich sind ergänzend zur angegebenen Art des Befundes.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToBeschreibung($textabsatz)
    {
        $this->beschreibung[] = $textabsatz;
        return $this;
    }

    /**
     * isset beschreibung
     *
     * Hier wird der Sachverhalt des Befundes beschrieben. Dieser Sachverhalt enthält Details, die für den Adressaten hilfreich sind ergänzend zur angegebenen Art des Befundes.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBeschreibung($index)
    {
        return isset($this->beschreibung[$index]);
    }

    /**
     * unset beschreibung
     *
     * Hier wird der Sachverhalt des Befundes beschrieben. Dieser Sachverhalt enthält Details, die für den Adressaten hilfreich sind ergänzend zur angegebenen Art des Befundes.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBeschreibung($index)
    {
        unset($this->beschreibung[$index]);
    }

    /**
     * Gets as beschreibung
     *
     * Hier wird der Sachverhalt des Befundes beschrieben. Dieser Sachverhalt enthält Details, die für den Adressaten hilfreich sind ergänzend zur angegebenen Art des Befundes.
     *
     * @return string[]
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Sets a new beschreibung
     *
     * Hier wird der Sachverhalt des Befundes beschrieben. Dieser Sachverhalt enthält Details, die für den Adressaten hilfreich sind ergänzend zur angegebenen Art des Befundes.
     *
     * @param string[] $beschreibung
     * @return self
     */
    public function setBeschreibung(array $beschreibung)
    {
        $this->beschreibung = $beschreibung;
        return $this;
    }
}

