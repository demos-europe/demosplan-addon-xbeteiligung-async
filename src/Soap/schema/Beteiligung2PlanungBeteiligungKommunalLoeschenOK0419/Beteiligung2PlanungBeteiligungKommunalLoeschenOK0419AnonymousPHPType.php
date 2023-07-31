<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType
 */
class Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType extends NachrichtG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

