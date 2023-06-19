<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungNeu0401\Planung2BeteiligungBeteiligungNeu0401AnonymousPHPType;

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
     * Initiator des Beteiligungsverfahrens, falls die Einleitung des Verfahrens nicht von der durchführenden Behörde ausgeht (z.B. durch eine Beteiligungsplattform).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $initiator
     */
    private $initiator = null;

    /**
     * Dieses Element enhält alle Angaben zur Initiierung einer Beteiligung.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungType $beteiligung
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
     * Gets as initiator
     *
     * Initiator des Beteiligungsverfahrens, falls die Einleitung des Verfahrens nicht von der durchführenden Behörde ausgeht (z.B. durch eine Beteiligungsplattform).
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType
     */
    public function getInitiator()
    {
        return $this->initiator;
    }

    /**
     * Sets a new initiator
     *
     * Initiator des Beteiligungsverfahrens, falls die Einleitung des Verfahrens nicht von der durchführenden Behörde ausgeht (z.B. durch eine Beteiligungsplattform).
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $initiator
     * @return self
     */
    public function setInitiator(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $initiator = null)
    {
        $this->initiator = $initiator;
        return $this;
    }

    /**
     * Gets as beteiligung
     *
     * Dieses Element enhält alle Angaben zur Initiierung einer Beteiligung.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungType $beteiligung
     * @return self
     */
    public function setBeteiligung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungType $beteiligung = null)
    {
        $this->beteiligung = $beteiligung;
        return $this;
    }
}

