<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing KommunikationTypeType
 *
 * Angaben zur Erreichbarkeit einer Behörde oder einer Person (Telefon, Fax, E-Mail, etc.).
 * XSD Type: KommunikationType
 */
class KommunikationTypeType
{
    /**
     * Es wird angegeben, über welches Kommunikationsmedium (z. B. Telefon, E-Mail) die Erreichbarkeit gegeben ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType $kanal
     */
    private $kanal = null;

    /**
     * Je nach Kommunikationsmedium (siehe Art) werden nähere Angaben gemacht. In der Regel werden hier Adressangaben eingetragen, etwa die Telefonnummer oder die E-Mail-Adresse.
     *
     * @var string $kennung
     */
    private $kennung = null;

    /**
     * Eine zusätzliche Bemerkung.
     *
     * @var string $zusatz
     */
    private $zusatz = null;

    /**
     * Gets as kanal
     *
     * Es wird angegeben, über welches Kommunikationsmedium (z. B. Telefon, E-Mail) die Erreichbarkeit gegeben ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType
     */
    public function getKanal()
    {
        return $this->kanal;
    }

    /**
     * Sets a new kanal
     *
     * Es wird angegeben, über welches Kommunikationsmedium (z. B. Telefon, E-Mail) die Erreichbarkeit gegeben ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType $kanal
     * @return self
     */
    public function setKanal(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType $kanal)
    {
        $this->kanal = $kanal;
        return $this;
    }

    /**
     * Gets as kennung
     *
     * Je nach Kommunikationsmedium (siehe Art) werden nähere Angaben gemacht. In der Regel werden hier Adressangaben eingetragen, etwa die Telefonnummer oder die E-Mail-Adresse.
     *
     * @return string
     */
    public function getKennung()
    {
        return $this->kennung;
    }

    /**
     * Sets a new kennung
     *
     * Je nach Kommunikationsmedium (siehe Art) werden nähere Angaben gemacht. In der Regel werden hier Adressangaben eingetragen, etwa die Telefonnummer oder die E-Mail-Adresse.
     *
     * @param string $kennung
     * @return self
     */
    public function setKennung($kennung)
    {
        $this->kennung = $kennung;
        return $this;
    }

    /**
     * Gets as zusatz
     *
     * Eine zusätzliche Bemerkung.
     *
     * @return string
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * Sets a new zusatz
     *
     * Eine zusätzliche Bemerkung.
     *
     * @param string $zusatz
     * @return self
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = $zusatz;
        return $this;
    }
}

