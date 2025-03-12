<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701\AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Vorgangs-ID, die in der Nachricht 0201, 0301 oder 0401 übermittelt wurde.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Dieses Element enthält die Stellungnahme der beteiligten Stelle.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StellungnahmeType $stellungnahme
     */
    private $stellungnahme = null;

    /**
     * Gets as vorgangsID
     *
     * Vorgangs-ID, die in der Nachricht 0201, 0301 oder 0401 übermittelt wurde.
     *
     * @return string
     */
    public function getVorgangsID()
    {
        return $this->vorgangsID;
    }

    /**
     * Sets a new vorgangsID
     *
     * Vorgangs-ID, die in der Nachricht 0201, 0301 oder 0401 übermittelt wurde.
     *
     * @param string $vorgangsID
     * @return self
     */
    public function setVorgangsID($vorgangsID)
    {
        $this->vorgangsID = $vorgangsID;
        return $this;
    }

    /**
     * Gets as stellungnahme
     *
     * Dieses Element enthält die Stellungnahme der beteiligten Stelle.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StellungnahmeType
     */
    public function getStellungnahme()
    {
        return $this->stellungnahme;
    }

    /**
     * Sets a new stellungnahme
     *
     * Dieses Element enthält die Stellungnahme der beteiligten Stelle.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StellungnahmeType $stellungnahme
     * @return self
     */
    public function setStellungnahme(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StellungnahmeType $stellungnahme = null)
    {
        $this->stellungnahme = $stellungnahme;
        return $this;
    }
}

