<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationAktualisiert0252;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing PlanfeststellungInformationAktualisiert0252AnonymousPHPType
 */
class PlanfeststellungInformationAktualisiert0252AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationAktualisiert0252\PlanfeststellungInformationAktualisiert0252AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationAktualisiert0252\PlanfeststellungInformationAktualisiert0252AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationAktualisiert0252\PlanfeststellungInformationAktualisiert0252AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationAktualisiert0252\PlanfeststellungInformationAktualisiert0252AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

