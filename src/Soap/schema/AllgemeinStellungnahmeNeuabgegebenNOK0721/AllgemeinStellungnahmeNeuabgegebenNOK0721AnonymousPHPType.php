<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenNOK0721;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing AllgemeinStellungnahmeNeuabgegebenNOK0721AnonymousPHPType
 */
class AllgemeinStellungnahmeNeuabgegebenNOK0721AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Diese Nachricht unterrichtet über einen Fehler bei der Verarbeitung der Originalnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenNOK0721\AllgemeinStellungnahmeNeuabgegebenNOK0721AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * Diese Nachricht unterrichtet über einen Fehler bei der Verarbeitung der Originalnachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenNOK0721\AllgemeinStellungnahmeNeuabgegebenNOK0721AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * Diese Nachricht unterrichtet über einen Fehler bei der Verarbeitung der Originalnachricht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenNOK0721\AllgemeinStellungnahmeNeuabgegebenNOK0721AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeNeuabgegebenNOK0721\AllgemeinStellungnahmeNeuabgegebenNOK0721AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

