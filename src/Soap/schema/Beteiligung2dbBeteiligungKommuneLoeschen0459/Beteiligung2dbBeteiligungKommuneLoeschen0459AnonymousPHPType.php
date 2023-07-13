<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungKommuneLoeschen0459;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing Beteiligung2dbBeteiligungKommuneLoeschen0459AnonymousPHPType
 */
class Beteiligung2dbBeteiligungKommuneLoeschen0459AnonymousPHPType extends NachrichtG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungKommuneLoeschen0459\Beteiligung2dbBeteiligungKommuneLoeschen0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungKommuneLoeschen0459\Beteiligung2dbBeteiligungKommuneLoeschen0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungKommuneLoeschen0459\Beteiligung2dbBeteiligungKommuneLoeschen0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungKommuneLoeschen0459\Beteiligung2dbBeteiligungKommuneLoeschen0459AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

