<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernNOK0770;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing Extern2BeteiligungStellungnahmeAuffordernNOK0770AnonymousPHPType
 */
class Extern2BeteiligungStellungnahmeAuffordernNOK0770AnonymousPHPType extends NachrichtG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernNOK0770\Extern2BeteiligungStellungnahmeAuffordernNOK0770AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernNOK0770\Extern2BeteiligungStellungnahmeAuffordernNOK0770AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernNOK0770\Extern2BeteiligungStellungnahmeAuffordernNOK0770AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernNOK0770\Extern2BeteiligungStellungnahmeAuffordernNOK0770AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

