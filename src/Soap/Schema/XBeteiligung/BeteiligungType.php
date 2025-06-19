<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing BeteiligungType
 *
 * Mit diesem Datentyp können die Informationen zu einem Beteiligungsverfahren übermittelt werden, wahlweise im Rahmen der kommunalen Bauleitplanung, der Raumordnung oder der Planfeststellung.
 * XSD Type: Beteiligung
 */
class BeteiligungType
{
    /**
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType $kommunal
     */
    private $kommunal = null;

    /**
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Raumordnung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungType $raumordnung
     */
    private $raumordnung = null;

    /**
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Planfeststellung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType $planfeststellung
     */
    private $planfeststellung = null;

    /**
     * Gets as kommunal
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType
     */
    public function getKommunal()
    {
        return $this->kommunal;
    }

    /**
     * Sets a new kommunal
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType $kommunal
     * @return self
     */
    public function setKommunal(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType $kommunal = null)
    {
        $this->kommunal = $kommunal;
        return $this;
    }

    /**
     * Gets as raumordnung
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Raumordnung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungType
     */
    public function getRaumordnung()
    {
        return $this->raumordnung;
    }

    /**
     * Sets a new raumordnung
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Raumordnung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungType $raumordnung
     * @return self
     */
    public function setRaumordnung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungType $raumordnung = null)
    {
        $this->raumordnung = $raumordnung;
        return $this;
    }

    /**
     * Gets as planfeststellung
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Planfeststellung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType
     */
    public function getPlanfeststellung()
    {
        return $this->planfeststellung;
    }

    /**
     * Sets a new planfeststellung
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Planfeststellung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType $planfeststellung
     * @return self
     */
    public function setPlanfeststellung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType $planfeststellung = null)
    {
        $this->planfeststellung = $planfeststellung;
        return $this;
    }
}

