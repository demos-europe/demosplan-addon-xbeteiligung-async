<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitungAbgabe0031;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GWeiterleitungType;

/**
 * Class representing WeiterleitungAbgabe0031AnonymousPHPType
 */
class WeiterleitungAbgabe0031AnonymousPHPType extends NachrichtG2GWeiterleitungType
{
    /**
     * Hier werden Angaben zu der Behörde übermittelt, an die die fachliche Nachricht weitergeleitet wurde.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $zustaendigeStelle
     */
    private $zustaendigeStelle = null;

    /**
     * In diesem Kindelement wird die den Prozess auslösende fachliche Nachricht identifiziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $weitergeleiteteNachricht
     */
    private $weitergeleiteteNachricht = null;

    /**
     * In diesem Kindelement werden Angaben zur Identifizierung des Geschäftsvorfalls übermittelt, der den Prozess ausgelöst hat. Das Kindelement ist nur dann zu befüllen, wenn die den Prozess auslösende fachliche Nachricht identifizierende Angaben zu dem Geschäftsvorfall enthält.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $weitergeleitetesEreignis
     */
    private $weitergeleitetesEreignis = null;

    /**
     * In diesem Kindelement können Bemerkungen zu der weitergeleiteten Nachricht übermittelt werden.
     *
     * @var string $bemerkungen
     */
    private $bemerkungen = null;

    /**
     * Gets as zustaendigeStelle
     *
     * Hier werden Angaben zu der Behörde übermittelt, an die die fachliche Nachricht weitergeleitet wurde.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType
     */
    public function getZustaendigeStelle()
    {
        return $this->zustaendigeStelle;
    }

    /**
     * Sets a new zustaendigeStelle
     *
     * Hier werden Angaben zu der Behörde übermittelt, an die die fachliche Nachricht weitergeleitet wurde.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $zustaendigeStelle
     * @return self
     */
    public function setZustaendigeStelle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeType $zustaendigeStelle)
    {
        $this->zustaendigeStelle = $zustaendigeStelle;
        return $this;
    }

    /**
     * Gets as weitergeleiteteNachricht
     *
     * In diesem Kindelement wird die den Prozess auslösende fachliche Nachricht identifiziert.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type
     */
    public function getWeitergeleiteteNachricht()
    {
        return $this->weitergeleiteteNachricht;
    }

    /**
     * Sets a new weitergeleiteteNachricht
     *
     * In diesem Kindelement wird die den Prozess auslösende fachliche Nachricht identifiziert.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $weitergeleiteteNachricht
     * @return self
     */
    public function setWeitergeleiteteNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $weitergeleiteteNachricht)
    {
        $this->weitergeleiteteNachricht = $weitergeleiteteNachricht;
        return $this;
    }

    /**
     * Gets as weitergeleitetesEreignis
     *
     * In diesem Kindelement werden Angaben zur Identifizierung des Geschäftsvorfalls übermittelt, der den Prozess ausgelöst hat. Das Kindelement ist nur dann zu befüllen, wenn die den Prozess auslösende fachliche Nachricht identifizierende Angaben zu dem Geschäftsvorfall enthält.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType
     */
    public function getWeitergeleitetesEreignis()
    {
        return $this->weitergeleitetesEreignis;
    }

    /**
     * Sets a new weitergeleitetesEreignis
     *
     * In diesem Kindelement werden Angaben zur Identifizierung des Geschäftsvorfalls übermittelt, der den Prozess ausgelöst hat. Das Kindelement ist nur dann zu befüllen, wenn die den Prozess auslösende fachliche Nachricht identifizierende Angaben zu dem Geschäftsvorfall enthält.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $weitergeleitetesEreignis
     * @return self
     */
    public function setWeitergeleitetesEreignis(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $weitergeleitetesEreignis = null)
    {
        $this->weitergeleitetesEreignis = $weitergeleitetesEreignis;
        return $this;
    }

    /**
     * Gets as bemerkungen
     *
     * In diesem Kindelement können Bemerkungen zu der weitergeleiteten Nachricht übermittelt werden.
     *
     * @return string
     */
    public function getBemerkungen()
    {
        return $this->bemerkungen;
    }

    /**
     * Sets a new bemerkungen
     *
     * In diesem Kindelement können Bemerkungen zu der weitergeleiteten Nachricht übermittelt werden.
     *
     * @param string $bemerkungen
     * @return self
     */
    public function setBemerkungen($bemerkungen)
    {
        $this->bemerkungen = $bemerkungen;
        return $this;
    }
}

