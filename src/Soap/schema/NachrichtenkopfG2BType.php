<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NachrichtenkopfG2BType
 *
 * Nachrichtenkopf für Nachrichten von Behörden an Unternehmen.
 * XSD Type: Nachrichtenkopf.G2B
 */
class NachrichtenkopfG2BType
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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType $leser
     */
    private $leser = null;

    /**
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $autor
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType $leser
     * @return self
     */
    public function setLeser(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurType $leser)
    {
        $this->leser = $leser;
        return $this;
    }

    /**
     * Gets as autor
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $autor
     * @return self
     */
    public function setAutor(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $autor)
    {
        $this->autor = $autor;
        return $this;
    }
}

