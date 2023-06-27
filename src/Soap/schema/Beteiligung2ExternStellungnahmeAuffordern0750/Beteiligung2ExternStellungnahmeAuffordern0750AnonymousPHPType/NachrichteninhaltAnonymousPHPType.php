<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2ExternStellungnahmeAuffordern0750\Beteiligung2ExternStellungnahmeAuffordern0750AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Hier muss eine Vorgangs-ID übermittelt werden, auf die sich die beteiligte Stelle in ihrer Antwort bezieht. Die Vorgangs-ID wird dabei unverändert in die Antwortnachricht übernommen.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Dieses Element enhält alle Angaben zur Initiierung einer Beteiligung.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungTypeType $beteiligung
     */
    private $beteiligung = null;

    /**
     * Gets as vorgangsID
     *
     * Hier muss eine Vorgangs-ID übermittelt werden, auf die sich die beteiligte Stelle in ihrer Antwort bezieht. Die Vorgangs-ID wird dabei unverändert in die Antwortnachricht übernommen.
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
     * Hier muss eine Vorgangs-ID übermittelt werden, auf die sich die beteiligte Stelle in ihrer Antwort bezieht. Die Vorgangs-ID wird dabei unverändert in die Antwortnachricht übernommen.
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
     * Gets as beteiligung
     *
     * Dieses Element enhält alle Angaben zur Initiierung einer Beteiligung.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungTypeType
     */
    public function getBeteiligung()
    {
        return $this->beteiligung;
    }

    /**
     * Sets a new beteiligung
     *
     * Dieses Element enhält alle Angaben zur Initiierung einer Beteiligung.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungTypeType $beteiligung
     * @return self
     */
    public function setBeteiligung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungTypeType $beteiligung)
    {
        $this->beteiligung = $beteiligung;
        return $this;
    }
}

