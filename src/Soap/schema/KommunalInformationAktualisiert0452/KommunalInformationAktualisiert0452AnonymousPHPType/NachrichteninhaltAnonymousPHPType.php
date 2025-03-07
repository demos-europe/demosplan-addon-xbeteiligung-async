<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInformationAktualisiert0452\KommunalInformationAktualisiert0452AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Vorgangs-ID des Beteiligungsverfahrens, auf die sich die beteiligte Stelle in ihrer Antwort bezieht.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Dieses Element enhält die aktualisierten Angaben für zu beteiligende Stelle.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalDBType $beteiligung
     */
    private $beteiligung = null;

    /**
     * Gets as vorgangsID
     *
     * Vorgangs-ID des Beteiligungsverfahrens, auf die sich die beteiligte Stelle in ihrer Antwort bezieht.
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
     * Vorgangs-ID des Beteiligungsverfahrens, auf die sich die beteiligte Stelle in ihrer Antwort bezieht.
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
     * Dieses Element enhält die aktualisierten Angaben für zu beteiligende Stelle.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalDBType
     */
    public function getBeteiligung()
    {
        return $this->beteiligung;
    }

    /**
     * Sets a new beteiligung
     *
     * Dieses Element enhält die aktualisierten Angaben für zu beteiligende Stelle.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalDBType $beteiligung
     * @return self
     */
    public function setBeteiligung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalDBType $beteiligung)
    {
        $this->beteiligung = $beteiligung;
        return $this;
    }
}

