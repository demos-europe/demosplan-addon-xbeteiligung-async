<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInformationInitiiert0251;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;

/**
 * Class representing PlanfeststellungInformationInitiiert0251AnonymousPHPType
 */
class PlanfeststellungInformationInitiiert0251AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInformationInitiiert0251\PlanfeststellungInformationInitiiert0251AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInformationInitiiert0251\PlanfeststellungInformationInitiiert0251AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInformationInitiiert0251\PlanfeststellungInformationInitiiert0251AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInformationInitiiert0251\PlanfeststellungInformationInitiiert0251AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

