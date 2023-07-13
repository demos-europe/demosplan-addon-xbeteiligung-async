<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType
 */
class Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType extends NachrichtG2GType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

