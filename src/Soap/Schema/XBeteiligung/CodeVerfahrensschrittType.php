<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing CodeVerfahrensschrittType
 *
 * Dieser Datentyp kann genutzt werden, wenn ein Verfahrensschritt übermittelt werden soll, unabhängig davon, ob es sich um ein Verfahren der kommunalen Bauleitplanung, der Raumordnung oder der Planfeststellung handelt.
 * XSD Type: Code.Verfahrensschritt
 */
class CodeVerfahrensschrittType
{
    /**
     * Hier ist der Verfahrensschritt für ein Planfeststellungsverfahren zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $verfahrensschrittPlanfeststellung
     */
    private $verfahrensschrittPlanfeststellung = null;

    /**
     * Hier ist der Verfahrensschritt für ein Verfahren der kommunalen Bauleitplanung zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType $verfahrensschrittKommunal
     */
    private $verfahrensschrittKommunal = null;

    /**
     * Hier ist der Verfahrensschritt für ein Raumordnungsverfahren zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $verfahrensschrittRaumordnung
     */
    private $verfahrensschrittRaumordnung = null;

    /**
     * Gets as verfahrensschrittPlanfeststellung
     *
     * Hier ist der Verfahrensschritt für ein Planfeststellungsverfahren zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType
     */
    public function getVerfahrensschrittPlanfeststellung()
    {
        return $this->verfahrensschrittPlanfeststellung;
    }

    /**
     * Sets a new verfahrensschrittPlanfeststellung
     *
     * Hier ist der Verfahrensschritt für ein Planfeststellungsverfahren zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $verfahrensschrittPlanfeststellung
     * @return self
     */
    public function setVerfahrensschrittPlanfeststellung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $verfahrensschrittPlanfeststellung = null)
    {
        $this->verfahrensschrittPlanfeststellung = $verfahrensschrittPlanfeststellung;
        return $this;
    }

    /**
     * Gets as verfahrensschrittKommunal
     *
     * Hier ist der Verfahrensschritt für ein Verfahren der kommunalen Bauleitplanung zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType
     */
    public function getVerfahrensschrittKommunal()
    {
        return $this->verfahrensschrittKommunal;
    }

    /**
     * Sets a new verfahrensschrittKommunal
     *
     * Hier ist der Verfahrensschritt für ein Verfahren der kommunalen Bauleitplanung zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType $verfahrensschrittKommunal
     * @return self
     */
    public function setVerfahrensschrittKommunal(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType $verfahrensschrittKommunal = null)
    {
        $this->verfahrensschrittKommunal = $verfahrensschrittKommunal;
        return $this;
    }

    /**
     * Gets as verfahrensschrittRaumordnung
     *
     * Hier ist der Verfahrensschritt für ein Raumordnungsverfahren zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType
     */
    public function getVerfahrensschrittRaumordnung()
    {
        return $this->verfahrensschrittRaumordnung;
    }

    /**
     * Sets a new verfahrensschrittRaumordnung
     *
     * Hier ist der Verfahrensschritt für ein Raumordnungsverfahren zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $verfahrensschrittRaumordnung
     * @return self
     */
    public function setVerfahrensschrittRaumordnung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType $verfahrensschrittRaumordnung = null)
    {
        $this->verfahrensschrittRaumordnung = $verfahrensschrittRaumordnung;
        return $this;
    }
}

