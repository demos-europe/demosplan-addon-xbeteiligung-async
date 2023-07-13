<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungNeu0301\Planung2BeteiligungBeteiligungRaumordnungNeu0301AnonymousPHPType;

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
     * Dieses Element enhält alle Angaben zur Initiierung einer Beteiligung.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType $beteiligung
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
     * Dieses Element enhält alle Angaben zur Initiierung einer Beteiligung.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType $beteiligung
     * @return self
     */
    public function setBeteiligung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType $beteiligung)
    {
        $this->beteiligung = $beteiligung;
        return $this;
    }
}

