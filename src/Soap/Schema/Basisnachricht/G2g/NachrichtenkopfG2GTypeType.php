<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g;

/**
 * Class representing NachrichtenkopfG2GTypeType
 *
 * Nachrichtenkopf für Nachrichten zwischen Behörden und anderen (öffentlichen) Stellen. Der Nachrichtenkopf umfasst Angaben zur eindeutigen Identifikation des Autors und des Lesers der Nachricht sowie der Nachricht selbst.
 * XSD Type: Nachrichtenkopf.G2GType
 */
class NachrichtenkopfG2GTypeType
{
    /**
     * Dieses Element enthält Angaben zur eindeutigen Identifikation einer Nachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachricht $identifikationNachricht
     */
    private $identifikationNachricht = null;

    /**
     * Dieses Element enthält Angaben zum Leser der Nachricht. Der Leser ist die fachlich zuständige Behörde / öffentliche Stelle, der die Nachricht zugestellt werden soll und die die Nachricht fachlich verarbeiten soll.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Leser $leser
     */
    private $leser = null;

    /**
     * Dieses Element enthält Angaben zum Autor der Nachricht, die es dem Leser ermöglichen, bei Bedarf mit dem Autor in Verbindung zu treten. Der Autor ist die fachlich zuständige Behörde / öffentliche Stelle, die die Nachricht erstellt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Autor $autor
     */
    private $autor = null;

    /**
     * Gets as identifikationNachricht
     *
     * Dieses Element enthält Angaben zur eindeutigen Identifikation einer Nachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachricht
     */
    public function getIdentifikationNachricht()
    {
        return $this->identifikationNachricht;
    }

    /**
     * Sets a new identifikationNachricht
     *
     * Dieses Element enthält Angaben zur eindeutigen Identifikation einer Nachricht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachricht $identifikationNachricht
     * @return self
     */
    public function setIdentifikationNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachricht $identifikationNachricht)
    {
        $this->identifikationNachricht = $identifikationNachricht;
        return $this;
    }

    /**
     * Gets as leser
     *
     * Dieses Element enthält Angaben zum Leser der Nachricht. Der Leser ist die fachlich zuständige Behörde / öffentliche Stelle, der die Nachricht zugestellt werden soll und die die Nachricht fachlich verarbeiten soll.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Leser
     */
    public function getLeser()
    {
        return $this->leser;
    }

    /**
     * Sets a new leser
     *
     * Dieses Element enthält Angaben zum Leser der Nachricht. Der Leser ist die fachlich zuständige Behörde / öffentliche Stelle, der die Nachricht zugestellt werden soll und die die Nachricht fachlich verarbeiten soll.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Leser $leser
     * @return self
     */
    public function setLeser(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Leser $leser)
    {
        $this->leser = $leser;
        return $this;
    }

    /**
     * Gets as autor
     *
     * Dieses Element enthält Angaben zum Autor der Nachricht, die es dem Leser ermöglichen, bei Bedarf mit dem Autor in Verbindung zu treten. Der Autor ist die fachlich zuständige Behörde / öffentliche Stelle, die die Nachricht erstellt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Autor
     */
    public function getAutor()
    {
        return $this->autor;
    }

    /**
     * Sets a new autor
     *
     * Dieses Element enthält Angaben zum Autor der Nachricht, die es dem Leser ermöglichen, bei Bedarf mit dem Autor in Verbindung zu treten. Der Autor ist die fachlich zuständige Behörde / öffentliche Stelle, die die Nachricht erstellt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Autor $autor
     * @return self
     */
    public function setAutor(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Autor $autor)
    {
        $this->autor = $autor;
        return $this;
    }
}

