<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType
 */
class AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701\AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701\AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701\AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegeben0701\AllgemeinStellungnahmeNeuabgegeben0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

