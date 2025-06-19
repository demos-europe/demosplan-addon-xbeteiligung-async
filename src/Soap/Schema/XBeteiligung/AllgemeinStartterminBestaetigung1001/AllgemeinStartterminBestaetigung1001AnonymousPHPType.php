<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStartterminBestaetigung1001;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;

/**
 * Class representing AllgemeinStartterminBestaetigung1001AnonymousPHPType
 */
class AllgemeinStartterminBestaetigung1001AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Nachricht bestätigt, den Beginn des Auslegungszeitraums und der entsprechenden Veröffentlichung eine Woche vorher.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStartterminBestaetigung1001\AllgemeinStartterminBestaetigung1001AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * Nachricht bestätigt, den Beginn des Auslegungszeitraums und der entsprechenden Veröffentlichung eine Woche vorher.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStartterminBestaetigung1001\AllgemeinStartterminBestaetigung1001AnonymousPHPType\NachrichteninhaltAnonymousPHPType
     */
    public function getNachrichteninhalt()
    {
        return $this->nachrichteninhalt;
    }

    /**
     * Sets a new nachrichteninhalt
     *
     * Nachricht bestätigt, den Beginn des Auslegungszeitraums und der entsprechenden Veröffentlichung eine Woche vorher.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStartterminBestaetigung1001\AllgemeinStartterminBestaetigung1001AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStartterminBestaetigung1001\AllgemeinStartterminBestaetigung1001AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

