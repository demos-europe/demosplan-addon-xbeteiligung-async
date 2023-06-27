<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernOK0760;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing Extern2BeteiligungStellungnahmeAuffordernOK0760AnonymousPHPType
 */
class Extern2BeteiligungStellungnahmeAuffordernOK0760AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernOK0760\Extern2BeteiligungStellungnahmeAuffordernOK0760AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernOK0760\Extern2BeteiligungStellungnahmeAuffordernOK0760AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernOK0760\Extern2BeteiligungStellungnahmeAuffordernOK0760AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Extern2BeteiligungStellungnahmeAuffordernOK0760\Extern2BeteiligungStellungnahmeAuffordernOK0760AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

