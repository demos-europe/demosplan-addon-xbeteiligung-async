<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommuneType $kommune
     */
    private $kommune = null;

    /**
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Raumordnung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType $raumordnung
     */
    private $raumordnung = null;

    /**
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Planfeststellung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungPlanfeststellungType $planfeststellung
     */
    private $planfeststellung = null;

    /**
     * Gets as kommune
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommuneType
     */
    public function getKommune()
    {
        return $this->kommune;
    }

    /**
     * Sets a new kommune
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommuneType $kommune
     * @return self
     */
    public function setKommune(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommuneType $kommune = null)
    {
        $this->kommune = $kommune;
        return $this;
    }

    /**
     * Gets as raumordnung
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Raumordnung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType $raumordnung
     * @return self
     */
    public function setRaumordnung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType $raumordnung = null)
    {
        $this->raumordnung = $raumordnung;
        return $this;
    }

    /**
     * Gets as planfeststellung
     *
     * Hier können die Informationen zu einem Beteiligungsverfahren im Rahmen der Planfeststellung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungPlanfeststellungType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungPlanfeststellungType $planfeststellung
     * @return self
     */
    public function setPlanfeststellung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungPlanfeststellungType $planfeststellung = null)
    {
        $this->planfeststellung = $planfeststellung;
        return $this;
    }
}

