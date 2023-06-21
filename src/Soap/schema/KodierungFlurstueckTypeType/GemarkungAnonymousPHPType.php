<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KodierungFlurstueckTypeType;

/**
 * Class representing GemarkungAnonymousPHPType
 */
class GemarkungAnonymousPHPType
{
    /**
     * In dieses Element ist die Nummer der Gemarkung gemäß lokalem Liegenschaftskataster einzutragen (Gemarkungsnummer bestehend aus maximal 4 Stellen).
     *
     * @var string $nummer
     */
    private $nummer = null;

    /**
     * Dieses Element steht für die deskriptive Bezeichnung der Gemarkung.
     *
     * @var string $bezeichnung
     */
    private $bezeichnung = null;

    /**
     * Gets as nummer
     *
     * In dieses Element ist die Nummer der Gemarkung gemäß lokalem Liegenschaftskataster einzutragen (Gemarkungsnummer bestehend aus maximal 4 Stellen).
     *
     * @return string
     */
    public function getNummer()
    {
        return $this->nummer;
    }

    /**
     * Sets a new nummer
     *
     * In dieses Element ist die Nummer der Gemarkung gemäß lokalem Liegenschaftskataster einzutragen (Gemarkungsnummer bestehend aus maximal 4 Stellen).
     *
     * @param string $nummer
     * @return self
     */
    public function setNummer($nummer)
    {
        $this->nummer = $nummer;
        return $this;
    }

    /**
     * Gets as bezeichnung
     *
     * Dieses Element steht für die deskriptive Bezeichnung der Gemarkung.
     *
     * @return string
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * Sets a new bezeichnung
     *
     * Dieses Element steht für die deskriptive Bezeichnung der Gemarkung.
     *
     * @param string $bezeichnung
     * @return self
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
        return $this;
    }
}

