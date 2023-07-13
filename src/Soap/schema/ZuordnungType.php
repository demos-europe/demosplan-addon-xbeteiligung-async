<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing ZuordnungType
 *
 * Fachliche Zuordnung einer Stellungnahme.
 * XSD Type: Zuordnung
 */
class ZuordnungType
{
    /**
     * Hier ist die ID der Zuordnung zu übermitteln.
     *
     * @var string $zuordnungID
     */
    private $zuordnungID = null;

    /**
     * Hier kann der Code der Zuordnung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZuordnungType $zuordnungCode
     */
    private $zuordnungCode = null;

    /**
     * Hier kann der Beginn der Zurordnung übermittelt werden.
     *
     * @var int $vonCharNr
     */
    private $vonCharNr = null;

    /**
     * Hier kann das Ende der Zuordnung übermittelt werden.
     *
     * @var int $bisCharNr
     */
    private $bisCharNr = null;

    /**
     * Gets as zuordnungID
     *
     * Hier ist die ID der Zuordnung zu übermitteln.
     *
     * @return string
     */
    public function getZuordnungID()
    {
        return $this->zuordnungID;
    }

    /**
     * Sets a new zuordnungID
     *
     * Hier ist die ID der Zuordnung zu übermitteln.
     *
     * @param string $zuordnungID
     * @return self
     */
    public function setZuordnungID($zuordnungID)
    {
        $this->zuordnungID = $zuordnungID;
        return $this;
    }

    /**
     * Gets as zuordnungCode
     *
     * Hier kann der Code der Zuordnung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZuordnungType
     */
    public function getZuordnungCode()
    {
        return $this->zuordnungCode;
    }

    /**
     * Sets a new zuordnungCode
     *
     * Hier kann der Code der Zuordnung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZuordnungType $zuordnungCode
     * @return self
     */
    public function setZuordnungCode(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZuordnungType $zuordnungCode = null)
    {
        $this->zuordnungCode = $zuordnungCode;
        return $this;
    }

    /**
     * Gets as vonCharNr
     *
     * Hier kann der Beginn der Zurordnung übermittelt werden.
     *
     * @return int
     */
    public function getVonCharNr()
    {
        return $this->vonCharNr;
    }

    /**
     * Sets a new vonCharNr
     *
     * Hier kann der Beginn der Zurordnung übermittelt werden.
     *
     * @param int $vonCharNr
     * @return self
     */
    public function setVonCharNr($vonCharNr)
    {
        $this->vonCharNr = $vonCharNr;
        return $this;
    }

    /**
     * Gets as bisCharNr
     *
     * Hier kann das Ende der Zuordnung übermittelt werden.
     *
     * @return int
     */
    public function getBisCharNr()
    {
        return $this->bisCharNr;
    }

    /**
     * Sets a new bisCharNr
     *
     * Hier kann das Ende der Zuordnung übermittelt werden.
     *
     * @param int $bisCharNr
     * @return self
     */
    public function setBisCharNr($bisCharNr)
    {
        $this->bisCharNr = $bisCharNr;
        return $this;
    }
}

