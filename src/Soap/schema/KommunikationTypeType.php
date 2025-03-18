<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing KommunikationTypeType
 *
 * Dieser Typ enthält Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z. B. Telefon, E-Mail).
 * XSD Type: KommunikationType
 */
class KommunikationTypeType
{
    /**
     * Der 'kanal' gibt an, über welchen Kommunikationskanal eine Erreichbarkeit besteht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKommunikationKanalTypeType $kanal
     */
    private $kanal = null;

    /**
     * Die Kennung beinhaltet die konkreten Angaben zur Erreichbarkeit über einen Kommunikationskanal, d. h. die Telefonnummer, E-Mail-Adresse oder dergleichen.
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
     * Der 'kanal' gibt an, über welchen Kommunikationskanal eine Erreichbarkeit besteht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKommunikationKanalTypeType
     */
    public function getKanal()
    {
        return $this->kanal;
    }

    /**
     * Sets a new kanal
     *
     * Der 'kanal' gibt an, über welchen Kommunikationskanal eine Erreichbarkeit besteht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKommunikationKanalTypeType $kanal
     * @return self
     */
    public function setKanal(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKommunikationKanalTypeType $kanal)
    {
        $this->kanal = $kanal;
        return $this;
    }

    /**
     * Gets as kennung
     *
     * Die Kennung beinhaltet die konkreten Angaben zur Erreichbarkeit über einen Kommunikationskanal, d. h. die Telefonnummer, E-Mail-Adresse oder dergleichen.
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
     * Die Kennung beinhaltet die konkreten Angaben zur Erreichbarkeit über einen Kommunikationskanal, d. h. die Telefonnummer, E-Mail-Adresse oder dergleichen.
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

