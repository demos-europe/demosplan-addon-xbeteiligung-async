<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuNOK0471;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing Db2BeteiligungBeteiligungKommunalNeuNOK0471AnonymousPHPType
 */
class Db2BeteiligungBeteiligungKommunalNeuNOK0471AnonymousPHPType extends NachrichtG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuNOK0471\Db2BeteiligungBeteiligungKommunalNeuNOK0471AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuNOK0471\Db2BeteiligungBeteiligungKommunalNeuNOK0471AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuNOK0471\Db2BeteiligungBeteiligungKommunalNeuNOK0471AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Db2BeteiligungBeteiligungKommunalNeuNOK0471\Db2BeteiligungBeteiligungKommunalNeuNOK0471AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

