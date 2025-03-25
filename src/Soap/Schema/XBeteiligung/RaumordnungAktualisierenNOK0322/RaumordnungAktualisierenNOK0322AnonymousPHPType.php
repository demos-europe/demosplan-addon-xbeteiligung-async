<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;

/**
 * Class representing RaumordnungAktualisierenNOK0322AnonymousPHPType
 */
class RaumordnungAktualisierenNOK0322AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Diese Nachricht unterrichtet über einen Fehler bei der Verarbeitung der Originalnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322\RaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     */
    private $nachrichteninhalt = null;

    /**
     * Gets as nachrichteninhalt
     *
     * Diese Nachricht unterrichtet über einen Fehler bei der Verarbeitung der Originalnachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322\RaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322\RaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt
     * @return self
     */
    public function setNachrichteninhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisierenNOK0322\RaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType $nachrichteninhalt)
    {
        $this->nachrichteninhalt = $nachrichteninhalt;
        return $this;
    }
}

