<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType;

/**
 * Class representing NachrichtenkopfG2GType
 *
 * Nachrichtenkopf für Nachrichten von Behörden (bzw. öffentlichen Stellen oder Diensten) an andere Behörden (bzw. öffentliche Stellen oder Dienste).
 * XSD Type: Nachrichtenkopf.G2G
 */
class NachrichtenkopfG2GType extends NachrichtenkopfG2GTypeType
{
    /**
     * Hier werden die Identifkationsmerkmale zur vorliegenden Nachricht genannt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\IdentifikationNachrichtType $identifikationNachricht
     */
    private $identifikationNachricht = null;

    /**
     * Hier wird die Fachbehörde bzw. Organisation genannt, für die die vorliegende Nachricht bestimmt ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType $leser
     */
    private $leser = null;

    /**
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType $autor
     */
    private $autor = null;

    /**
     * Gets as identifikationNachricht
     *
     * Hier werden die Identifkationsmerkmale zur vorliegenden Nachricht genannt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\IdentifikationNachrichtType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\IdentifikationNachrichtType $identifikationNachricht
     * @return self
     */
    public function setIdentifikationNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\IdentifikationNachrichtType $identifikationNachricht)
    {
        $this->identifikationNachricht = $identifikationNachricht;
        return $this;
    }

    /**
     * Gets as leser
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, für die die vorliegende Nachricht bestimmt ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType $leser
     * @return self
     */
    public function setLeser(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType $leser)
    {
        $this->leser = $leser;
        return $this;
    }

    /**
     * Gets as autor
     *
     * Hier wird die Fachbehörde bzw. Organisation genannt, die die vorliegende Nachricht erstellt hat.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType $autor
     * @return self
     */
    public function setAutor(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\BehoerdeTypeType $autor)
    {
        $this->autor = $autor;
        return $this;
    }
}

