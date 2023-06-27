<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202AnonymousPHPType
 */
class Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

