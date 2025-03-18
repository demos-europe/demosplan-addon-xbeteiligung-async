<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenOK0711;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing AllgemeinStellungnahmeNeuabgegebenOK0711AnonymousPHPType
 */
class AllgemeinStellungnahmeNeuabgegebenOK0711AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Diese Nachricht bestätigt die erfolgreiche Verarbeitung der Originalnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenOK0711\AllgemeinStellungnahmeNeuabgegebenOK0711AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * Diese Nachricht bestätigt die erfolgreiche Verarbeitung der Originalnachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenOK0711\AllgemeinStellungnahmeNeuabgegebenOK0711AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * Diese Nachricht bestätigt die erfolgreiche Verarbeitung der Originalnachricht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenOK0711\AllgemeinStellungnahmeNeuabgegebenOK0711AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenOK0711\AllgemeinStellungnahmeNeuabgegebenOK0711AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

