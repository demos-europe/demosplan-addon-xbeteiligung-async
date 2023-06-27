<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2ExternStellungnahmeAuffordern0750;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing Beteiligung2ExternStellungnahmeAuffordern0750AnonymousPHPType
 */
class Beteiligung2ExternStellungnahmeAuffordern0750AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2ExternStellungnahmeAuffordern0750\Beteiligung2ExternStellungnahmeAuffordern0750AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2ExternStellungnahmeAuffordern0750\Beteiligung2ExternStellungnahmeAuffordern0750AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2ExternStellungnahmeAuffordern0750\Beteiligung2ExternStellungnahmeAuffordern0750AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2ExternStellungnahmeAuffordern0750\Beteiligung2ExternStellungnahmeAuffordern0750AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

