<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungAktualisierenOK0412\Beteiligung2PlanungBeteiligungAktualisierenOK0412AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Plan-ID, die in der Nachricht 402 übermittelt wurde.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Plan-ID, die in der Nachricht 402 übermittelt wurde.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Beteiligungs-ID, die in der Nachricht 402 übermittelt wurde.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Gets as vorgangsID
     *
     * Plan-ID, die in der Nachricht 402 übermittelt wurde.
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
     * Plan-ID, die in der Nachricht 402 übermittelt wurde.
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
     * Gets as planID
     *
     * Plan-ID, die in der Nachricht 402 übermittelt wurde.
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
     * Plan-ID, die in der Nachricht 402 übermittelt wurde.
     *
     * @param string $planID
     * @return self
     */
    public function setPlanID($planID)
    {
        $this->planID = $planID;
        return $this;
    }

    /**
     * Gets as beteiligungsID
     *
     * Beteiligungs-ID, die in der Nachricht 402 übermittelt wurde.
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
     * Beteiligungs-ID, die in der Nachricht 402 übermittelt wurde.
     *
     * @param string $beteiligungsID
     * @return self
     */
    public function setBeteiligungsID($beteiligungsID)
    {
        $this->beteiligungsID = $beteiligungsID;
        return $this;
    }
}

