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
     * @var string $zuordnungID
     */
    private $zuordnungID = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZuordnungType $zuordnungCode
     */
    private $zuordnungCode = null;

    /**
     * @var int $vonCharNr
     */
    private $vonCharNr = null;

    /**
     * @var int $bisCharNr
     */
    private $bisCharNr = null;

    /**
     * Gets as zuordnungID
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZuordnungType
     */
    public function getZuordnungCode()
    {
        return $this->zuordnungCode;
    }

    /**
     * Sets a new zuordnungCode
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
     * @return int
     */
    public function getVonCharNr()
    {
        return $this->vonCharNr;
    }

    /**
     * Sets a new vonCharNr
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
     * @return int
     */
    public function getBisCharNr()
    {
        return $this->bisCharNr;
    }

    /**
     * Sets a new bisCharNr
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

