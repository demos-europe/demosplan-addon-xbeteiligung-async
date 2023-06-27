<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungRaumordnungLoeschen0359;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing Beteiligung2dbBeteiligungRaumordnungLoeschen0359AnonymousPHPType
 */
class Beteiligung2dbBeteiligungRaumordnungLoeschen0359AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungRaumordnungLoeschen0359\Beteiligung2dbBeteiligungRaumordnungLoeschen0359AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungRaumordnungLoeschen0359\Beteiligung2dbBeteiligungRaumordnungLoeschen0359AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungRaumordnungLoeschen0359\Beteiligung2dbBeteiligungRaumordnungLoeschen0359AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungRaumordnungLoeschen0359\Beteiligung2dbBeteiligungRaumordnungLoeschen0359AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

