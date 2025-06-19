<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInformationGeloescht0359\RaumordnungInformationGeloescht0359AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Die Vorgangs-ID, die in der Nachricht 0301 übermittelt wurde.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Die Beteiligungs-ID, die in der Nachricht 0301 übermittelt wurde.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Die Plan-ID, die in der Nachricht 0301 übermittelt wurde.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Gets as vorgangsID
     *
     * Die Vorgangs-ID, die in der Nachricht 0301 übermittelt wurde.
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
     * Die Vorgangs-ID, die in der Nachricht 0301 übermittelt wurde.
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
     * Gets as beteiligungsID
     *
     * Die Beteiligungs-ID, die in der Nachricht 0301 übermittelt wurde.
     *
     * @return string
     */
    public function getBeteiligungsID()
    {
        return $this->beteiligungsID;
    }

    /**
     * Sets a new beteiligungsID
     *
     * Die Beteiligungs-ID, die in der Nachricht 0301 übermittelt wurde.
     *
     * @param string $beteiligungsID
     * @return self
     */
    public function setBeteiligungsID($beteiligungsID)
    {
        $this->beteiligungsID = $beteiligungsID;
        return $this;
    }

    /**
     * Gets as planID
     *
     * Die Plan-ID, die in der Nachricht 0301 übermittelt wurde.
     *
     * @return string
     */
    public function getPlanID()
    {
        return $this->planID;
    }

    /**
     * Sets a new planID
     *
     * Die Plan-ID, die in der Nachricht 0301 übermittelt wurde.
     *
     * @param string $planID
     * @return self
     */
    public function setPlanID($planID)
    {
        $this->planID = $planID;
        return $this;
    }
}

