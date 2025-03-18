<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalAktualisierenNOK0422\KommunalAktualisierenNOK0422AnonymousPHPType;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateNOKType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType extends NachrichteninhaltTemplateNOKType
{
    /**
     * Beteiligungs-ID, die in der Nachricht 0402 übermittelt wurde.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Gets as beteiligungsID
     *
     * Beteiligungs-ID, die in der Nachricht 0402 übermittelt wurde.
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
     * Beteiligungs-ID, die in der Nachricht 0402 übermittelt wurde.
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

