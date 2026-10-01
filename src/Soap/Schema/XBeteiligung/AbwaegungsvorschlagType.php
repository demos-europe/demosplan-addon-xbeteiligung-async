<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing AbwaegungsvorschlagType
 *
 * Dieser Typ beschreibt einen Abwägungsvorschlag.
 * XSD Type: Abwaegungsvorschlag
 */
class AbwaegungsvorschlagType
{
    /**
     * Codeliste mit Empfehlungen für das Ergebnis der Abwägung.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeAbwaegungsvorschlagType $empfehlung
     */
    private $empfehlung = null;

    /**
     * Textliche Erläuterung zur Abwägung der Stellungnahme.
     *
     * @var string $erlaeuterung
     */
    private $erlaeuterung = null;

    /**
     * Gets as empfehlung
     *
     * Codeliste mit Empfehlungen für das Ergebnis der Abwägung.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeAbwaegungsvorschlagType
     */
    public function getEmpfehlung()
    {
        return $this->empfehlung;
    }

    /**
     * Sets a new empfehlung
     *
     * Codeliste mit Empfehlungen für das Ergebnis der Abwägung.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeAbwaegungsvorschlagType $empfehlung
     * @return self
     */
    public function setEmpfehlung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeAbwaegungsvorschlagType $empfehlung)
    {
        $this->empfehlung = $empfehlung;
        return $this;
    }

    /**
     * Gets as erlaeuterung
     *
     * Textliche Erläuterung zur Abwägung der Stellungnahme.
     *
     * @return string
     */
    public function getErlaeuterung()
    {
        return $this->erlaeuterung;
    }

    /**
     * Sets a new erlaeuterung
     *
     * Textliche Erläuterung zur Abwägung der Stellungnahme.
     *
     * @param string $erlaeuterung
     * @return self
     */
    public function setErlaeuterung($erlaeuterung)
    {
        $this->erlaeuterung = $erlaeuterung;
        return $this;
    }
}

