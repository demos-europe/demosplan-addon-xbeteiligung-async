<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229AnonymousPHPType;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateNOKTypeType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType extends NachrichteninhaltTemplateNOKTypeType
{
    /**
     * Beteiligungs-ID, die in der Nachricht 409 übermittelt wurde.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Gets as beteiligungsID
     *
     * Beteiligungs-ID, die in der Nachricht 409 übermittelt wurde.
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
     * Beteiligungs-ID, die in der Nachricht 409 übermittelt wurde.
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

