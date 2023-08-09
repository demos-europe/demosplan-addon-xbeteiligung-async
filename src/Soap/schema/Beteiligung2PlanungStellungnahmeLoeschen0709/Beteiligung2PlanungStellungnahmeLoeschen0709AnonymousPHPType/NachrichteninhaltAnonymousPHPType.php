<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungStellungnahmeLoeschen0709\Beteiligung2PlanungStellungnahmeLoeschen0709AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Vorgangs-ID, die in der Nachricht 401 übermittelt wurde.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Stellungnahme-ID, die in der Nachricht 701/702 übermittelt wurde.
     *
     * @var string $stellungnahmeID
     */
    private $stellungnahmeID = null;

    /**
     * Die Plan-ID, die in der Nachricht 401 übermittelt wurde.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Gets as vorgangsID
     *
     * Vorgangs-ID, die in der Nachricht 401 übermittelt wurde.
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
     * Vorgangs-ID, die in der Nachricht 401 übermittelt wurde.
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
     * Gets as stellungnahmeID
     *
     * Stellungnahme-ID, die in der Nachricht 701/702 übermittelt wurde.
     *
     * @return string
     */
    public function getStellungnahmeID()
    {
        return $this->stellungnahmeID;
    }

    /**
     * Sets a new stellungnahmeID
     *
     * Stellungnahme-ID, die in der Nachricht 701/702 übermittelt wurde.
     *
     * @param string $stellungnahmeID
     * @return self
     */
    public function setStellungnahmeID($stellungnahmeID)
    {
        $this->stellungnahmeID = $stellungnahmeID;
        return $this;
    }

    /**
     * Gets as planID
     *
     * Die Plan-ID, die in der Nachricht 401 übermittelt wurde.
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
     * Die Plan-ID, die in der Nachricht 401 übermittelt wurde.
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

