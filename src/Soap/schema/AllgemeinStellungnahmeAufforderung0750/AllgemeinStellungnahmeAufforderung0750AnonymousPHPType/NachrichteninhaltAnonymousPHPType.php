<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeAufforderung0750\AllgemeinStellungnahmeAufforderung0750AnonymousPHPType;

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
     * Dieses Element enhält alle Angaben zur dem Beteiligungsverfahren für das zu einer Stellungnahme aufgefordert wird.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungType $beteiligung
     */
    private $beteiligung = null;

    /**
     * Hier können Rechtsfolgen übermittelt werden, die sich aus dem Verzicht auf Abgabe einer Stellungnahme ergeben.
     *
     * @var string $rechtsfolgen
     */
    private $rechtsfolgen = null;

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
     * Dieses Element enhält alle Angaben zur dem Beteiligungsverfahren für das zu einer Stellungnahme aufgefordert wird.
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
     * Dieses Element enhält alle Angaben zur dem Beteiligungsverfahren für das zu einer Stellungnahme aufgefordert wird.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungType $beteiligung
     * @return self
     */
    public function setBeteiligung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungType $beteiligung)
    {
        $this->beteiligung = $beteiligung;
        return $this;
    }

    /**
     * Gets as rechtsfolgen
     *
     * Hier können Rechtsfolgen übermittelt werden, die sich aus dem Verzicht auf Abgabe einer Stellungnahme ergeben.
     *
     * @return string
     */
    public function getRechtsfolgen()
    {
        return $this->rechtsfolgen;
    }

    /**
     * Sets a new rechtsfolgen
     *
     * Hier können Rechtsfolgen übermittelt werden, die sich aus dem Verzicht auf Abgabe einer Stellungnahme ergeben.
     *
     * @param string $rechtsfolgen
     * @return self
     */
    public function setRechtsfolgen($rechtsfolgen)
    {
        $this->rechtsfolgen = $rechtsfolgen;
        return $this;
    }
}

