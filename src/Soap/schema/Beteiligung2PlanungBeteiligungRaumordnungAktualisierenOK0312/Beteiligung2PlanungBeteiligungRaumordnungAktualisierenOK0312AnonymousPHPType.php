<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType
 */
class Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

