<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInformationInitiiert0351;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing RaumordnungInformationInitiiert0351AnonymousPHPType
 */
class RaumordnungInformationInitiiert0351AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInformationInitiiert0351\RaumordnungInformationInitiiert0351AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInformationInitiiert0351\RaumordnungInformationInitiiert0351AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInformationInitiiert0351\RaumordnungInformationInitiiert0351AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInformationInitiiert0351\RaumordnungInformationInitiiert0351AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

