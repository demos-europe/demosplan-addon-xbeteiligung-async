<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NachrichtenkopfB2GTypeType
 *
 * Nachrichtenkopf für Nachrichten von Unternehmen an Behörden.
 * XSD Type: Nachrichtenkopf.B2GType
 */
class NachrichtenkopfB2GTypeType
{
    /**
     * Hier werden die Identifkationsmerkmale zur vorliegenden Nachricht genannt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType $identifikationNachricht
     */
    private $identifikationNachricht = null;

    /**
     * Hier wird die Fachbehörde bzw. Organisation genannt, für die die vorliegende Nachricht bestimmt ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType $leser
     */
    private $leser = null;

    /**
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurTypeType $autor
     */
    private $autor = null;

    /**
     * Gets as identifikationNachricht
     *
     * Hier werden die Identifkationsmerkmale zur vorliegenden Nachricht genannt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType
     */
    public function getIdentifikationNachricht()
    {
        return $this->identifikationNachricht;
    }

    /**
     * Sets a new identifikationNachricht
     *
     * Hier werden die Identifkationsmerkmale zur vorliegenden Nachricht genannt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType $identifikationNachricht
     * @return self
     */
    public function setIdentifikationNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType $identifikationNachricht)
    {
        $this->identifikationNachricht = $identifikationNachricht;
        return $this;
    }

    /**
     * Gets as leser
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, für die die vorliegende Nachricht bestimmt ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType
     */
    public function getLeser()
    {
        return $this->leser;
    }

    /**
     * Sets a new leser
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, für die die vorliegende Nachricht bestimmt ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType $leser
     * @return self
     */
    public function setLeser(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType $leser)
    {
        $this->leser = $leser;
        return $this;
    }

    /**
     * Gets as autor
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurTypeType
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Sets a new autor
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurTypeType $autor
     * @return self
     */
    public function setAutor(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurTypeType $autor)
    {
        $this->autor = $autor;
        return $this;
    }
}

