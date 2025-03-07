<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationGeloescht0259;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing PlanfeststellungInformationGeloescht0259AnonymousPHPType
 */
class PlanfeststellungInformationGeloescht0259AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationGeloescht0259\PlanfeststellungInformationGeloescht0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationGeloescht0259\PlanfeststellungInformationGeloescht0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationGeloescht0259\PlanfeststellungInformationGeloescht0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInformationGeloescht0259\PlanfeststellungInformationGeloescht0259AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

