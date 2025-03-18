<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiierenOK0411;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing KommunalInitiierenOK0411AnonymousPHPType
 */
class KommunalInitiierenOK0411AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Diese Nachricht bestätigt die erfolgreiche Verarbeitung der Originalnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateOKType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * Diese Nachricht bestätigt die erfolgreiche Verarbeitung der Originalnachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateOKType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateOKType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateOKType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

