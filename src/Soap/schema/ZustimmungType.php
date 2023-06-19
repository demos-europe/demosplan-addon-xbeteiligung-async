<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing ZustimmungType
 *
 * Element zur Spezifizierung einer Zustimmung in einer Stellungnahme.
 * XSD Type: Zustimmung
 */
class ZustimmungType
{
    /**
     * "Kommunikation" fasst Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z.B. Telefon, Fax, E-Mail) zusammen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType[] $kommunikation
     */
    private $kommunikation = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerStellungnahmeType $artDerStellungnahme
     */
    private $artDerStellungnahme = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerRueckmeldungType $artDerRueckmeldung
     */
    private $artDerRueckmeldung = null;

    /**
     * @var string $nutzername
     */
    private $nutzername = null;

    /**
     * Adds as kommunikation
     *
     * "Kommunikation" fasst Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z.B. Telefon, Fax, E-Mail) zusammen.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType $kommunikation
     */
    public function addToKommunikation(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType $kommunikation)
    {
        $this->kommunikation[] = $kommunikation;
        return $this;
    }

    /**
     * isset kommunikation
     *
     * "Kommunikation" fasst Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z.B. Telefon, Fax, E-Mail) zusammen.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetKommunikation($index)
    {
        return isset($this->kommunikation[$index]);
    }

    /**
     * unset kommunikation
     *
     * "Kommunikation" fasst Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z.B. Telefon, Fax, E-Mail) zusammen.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetKommunikation($index)
    {
        unset($this->kommunikation[$index]);
    }

    /**
     * Gets as kommunikation
     *
     * "Kommunikation" fasst Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z.B. Telefon, Fax, E-Mail) zusammen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType[]
     */
    public function getKommunikation()
    {
        return $this->kommunikation;
    }

    /**
     * Sets a new kommunikation
     *
     * "Kommunikation" fasst Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z.B. Telefon, Fax, E-Mail) zusammen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType[] $kommunikation
     * @return self
     */
    public function setKommunikation(array $kommunikation = null)
    {
        $this->kommunikation = $kommunikation;
        return $this;
    }

    /**
     * Gets as artDerStellungnahme
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerStellungnahmeType
     */
    public function getArtDerStellungnahme()
    {
        return $this->artDerStellungnahme;
    }

    /**
     * Sets a new artDerStellungnahme
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerStellungnahmeType $artDerStellungnahme
     * @return self
     */
    public function setArtDerStellungnahme(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerStellungnahmeType $artDerStellungnahme)
    {
        $this->artDerStellungnahme = $artDerStellungnahme;
        return $this;
    }

    /**
     * Gets as artDerRueckmeldung
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerRueckmeldungType
     */
    public function getArtDerRueckmeldung()
    {
        return $this->artDerRueckmeldung;
    }

    /**
     * Sets a new artDerRueckmeldung
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerRueckmeldungType $artDerRueckmeldung
     * @return self
     */
    public function setArtDerRueckmeldung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeArtDerRueckmeldungType $artDerRueckmeldung)
    {
        $this->artDerRueckmeldung = $artDerRueckmeldung;
        return $this;
    }

    /**
     * Gets as nutzername
     *
     * @return string
     */
    public function getNutzername()
    {
        return $this->nutzername;
    }

    /**
     * Sets a new nutzername
     *
     * @param string $nutzername
     * @return self
     */
    public function setNutzername($nutzername)
    {
        $this->nutzername = $nutzername;
        return $this;
    }
}

