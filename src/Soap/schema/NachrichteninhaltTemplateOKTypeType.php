<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NachrichteninhaltTemplateOKTypeType
 *
 * Vorlage für positive Quittungsnachrichten
 * XSD Type: Nachrichteninhalt.template.OKType
 */
class NachrichteninhaltTemplateOKTypeType
{
    /**
     * Hier wird Vorgangs-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Hier wird die Plan-ID übermittelt, auf die sich die Ursprungsnachricht bezieht.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Hier wird die Beteiligungs-ID übermittelt, auf die sich die Ursprungsnachricht bezieht.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Gets as vorgangsID
     *
     * Hier wird Vorgangs-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
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
     * Hier wird Vorgangs-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
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
     * Hier wird die Plan-ID übermittelt, auf die sich die Ursprungsnachricht bezieht.
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
     * Hier wird die Plan-ID übermittelt, auf die sich die Ursprungsnachricht bezieht.
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
     * Hier wird die Beteiligungs-ID übermittelt, auf die sich die Ursprungsnachricht bezieht.
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
     * Hier wird die Beteiligungs-ID übermittelt, auf die sich die Ursprungsnachricht bezieht.
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

