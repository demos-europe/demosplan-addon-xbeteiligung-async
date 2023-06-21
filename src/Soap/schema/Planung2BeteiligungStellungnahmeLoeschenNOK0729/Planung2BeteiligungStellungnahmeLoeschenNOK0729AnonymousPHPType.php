<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungStellungnahmeLoeschenNOK0729;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing Planung2BeteiligungStellungnahmeLoeschenNOK0729AnonymousPHPType
 */
class Planung2BeteiligungStellungnahmeLoeschenNOK0729AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungStellungnahmeLoeschenNOK0729\Planung2BeteiligungStellungnahmeLoeschenNOK0729AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungStellungnahmeLoeschenNOK0729\Planung2BeteiligungStellungnahmeLoeschenNOK0729AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungStellungnahmeLoeschenNOK0729\Planung2BeteiligungStellungnahmeLoeschenNOK0729AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungStellungnahmeLoeschenNOK0729\Planung2BeteiligungStellungnahmeLoeschenNOK0729AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

