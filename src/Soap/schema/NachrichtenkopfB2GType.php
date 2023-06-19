<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NachrichtenkopfB2GType
 *
 * Nachrichtenkopf für Nachrichten von Unternehmen an Behörden.
 * XSD Type: Nachrichtenkopf.B2G
 */
class NachrichtenkopfB2GType
{
    /**
     * Hier werden die Identifkationsmerkmale zur vorliegenden Nachricht genannt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $identifikationNachricht
     */
    private $identifikationNachricht = null;

    /**
     * Hier wird die Fachbehörde bzw. Organisation genannt, für die die vorliegende Nachricht bestimmt ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $leser
     */
    private $leser = null;

    /**
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType $autor
     */
    private $autor = null;

    /**
     * Gets as identifikationNachricht
     *
     * Hier werden die Identifkationsmerkmale zur vorliegenden Nachricht genannt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $identifikationNachricht
     * @return self
     */
    public function setIdentifikationNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $identifikationNachricht)
    {
        $this->identifikationNachricht = $identifikationNachricht;
        return $this;
    }

    /**
     * Gets as leser
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, für die die vorliegende Nachricht bestimmt ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $leser
     * @return self
     */
    public function setLeser(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $leser)
    {
        $this->leser = $leser;
        return $this;
    }

    /**
     * Gets as autor
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType $autor
     * @return self
     */
    public function setAutor(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType $autor)
    {
        $this->autor = $autor;
        return $this;
    }
}

