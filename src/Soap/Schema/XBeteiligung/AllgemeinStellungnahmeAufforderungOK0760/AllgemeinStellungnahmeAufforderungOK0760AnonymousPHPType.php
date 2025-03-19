<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeAufforderungOK0760;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;

/**
 * Class representing AllgemeinStellungnahmeAufforderungOK0760AnonymousPHPType
 */
class AllgemeinStellungnahmeAufforderungOK0760AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Diese Nachricht bestätigt die erfolgreiche Verarbeitung der Originalnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateOKType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * Diese Nachricht bestätigt die erfolgreiche Verarbeitung der Originalnachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateOKType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateOKType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\NachrichteninhaltTemplateOKType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

