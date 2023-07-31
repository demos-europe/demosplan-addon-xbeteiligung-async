<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuOK0461;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing Db2BeteiligungBeteiligungKommunalNeuOK0461AnonymousPHPType
 */
class Db2BeteiligungBeteiligungKommunalNeuOK0461AnonymousPHPType extends NachrichtG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuOK0461\Db2BeteiligungBeteiligungKommunalNeuOK0461AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuOK0461\Db2BeteiligungBeteiligungKommunalNeuOK0461AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuOK0461\Db2BeteiligungBeteiligungKommunalNeuOK0461AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuOK0461\Db2BeteiligungBeteiligungKommunalNeuOK0461AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

