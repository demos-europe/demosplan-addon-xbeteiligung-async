<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259AnonymousPHPType
 */
class Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259AnonymousPHPType extends NachrichtG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259\Beteiligung2dbBeteiligungPlanfeststellungLoeschen0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

