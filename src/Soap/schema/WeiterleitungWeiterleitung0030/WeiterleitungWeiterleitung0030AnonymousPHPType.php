<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitungWeiterleitung0030;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GWeiterleitungTypeType;

/**
 * Class representing WeiterleitungWeiterleitung0030AnonymousPHPType
 */
class WeiterleitungWeiterleitung0030AnonymousPHPType extends NachrichtG2GWeiterleitungTypeType
{
    /**
     * In diesem Element wird die vollständige Liste der Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Diese Liste wird gebildet, indem die Behörde, die die Weiterleitungsnachricht erstellt, sich selbst an die (ggf. leere) Liste der weiterleitendeStellen anhängt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleTypeType[] $weiterleitendeStelle
     */
    private $weiterleitendeStelle = [
        
    ];

    /**
     * In diesem Element werden Informationen zu der den Prozess auslösenden Behörde übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarTypeType $ausloesendeStelle
     */
    private $ausloesendeStelle = null;

    /**
     * In diesem Element wird die unveränderte fachliche Nachricht übermittelt, wie sie von der den Prozess auslösenden Behörde erstellt wurde.
     *
     * @var string $weitergeleiteteNachricht
     */
    private $weitergeleiteteNachricht = null;

    /**
     * In diesem Kindelement wird der Nachrichtentyp der weitergeleiteten Nachricht übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeNachrichtentypTyp4TypeType $weitergeleiteteNachrichtTyp
     */
    private $weitergeleiteteNachrichtTyp = null;

    /**
     * In diesem Kindelement können Bemerkungen zu der weitergeleiteten Nachricht übermittelt werden.
     *
     * @var string $bemerkungen
     */
    private $bemerkungen = null;

    /**
     * Adds as weiterleitendeStelle
     *
     * In diesem Element wird die vollständige Liste der Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Diese Liste wird gebildet, indem die Behörde, die die Weiterleitungsnachricht erstellt, sich selbst an die (ggf. leere) Liste der weiterleitendeStellen anhängt.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleTypeType $weiterleitendeStelle
     */
    public function addToWeiterleitendeStelle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleTypeType $weiterleitendeStelle)
    {
        $this->weiterleitendeStelle[] = $weiterleitendeStelle;
        return $this;
    }

    /**
     * isset weiterleitendeStelle
     *
     * In diesem Element wird die vollständige Liste der Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Diese Liste wird gebildet, indem die Behörde, die die Weiterleitungsnachricht erstellt, sich selbst an die (ggf. leere) Liste der weiterleitendeStellen anhängt.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWeiterleitendeStelle($index)
    {
        return isset($this->weiterleitendeStelle[$index]);
    }

    /**
     * unset weiterleitendeStelle
     *
     * In diesem Element wird die vollständige Liste der Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Diese Liste wird gebildet, indem die Behörde, die die Weiterleitungsnachricht erstellt, sich selbst an die (ggf. leere) Liste der weiterleitendeStellen anhängt.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWeiterleitendeStelle($index)
    {
        unset($this->weiterleitendeStelle[$index]);
    }

    /**
     * Gets as weiterleitendeStelle
     *
     * In diesem Element wird die vollständige Liste der Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Diese Liste wird gebildet, indem die Behörde, die die Weiterleitungsnachricht erstellt, sich selbst an die (ggf. leere) Liste der weiterleitendeStellen anhängt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleTypeType[]
     */
    public function getWeiterleitendeStelle()
    {
        return $this->weiterleitendeStelle;
    }

    /**
     * Sets a new weiterleitendeStelle
     *
     * In diesem Element wird die vollständige Liste der Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Diese Liste wird gebildet, indem die Behörde, die die Weiterleitungsnachricht erstellt, sich selbst an die (ggf. leere) Liste der weiterleitendeStellen anhängt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleTypeType[] $weiterleitendeStelle
     * @return self
     */
    public function setWeiterleitendeStelle(array $weiterleitendeStelle)
    {
        $this->weiterleitendeStelle = $weiterleitendeStelle;
        return $this;
    }

    /**
     * Gets as ausloesendeStelle
     *
     * In diesem Element werden Informationen zu der den Prozess auslösenden Behörde übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarTypeType
     */
    public function getAusloesendeStelle()
    {
        return $this->ausloesendeStelle;
    }

    /**
     * Sets a new ausloesendeStelle
     *
     * In diesem Element werden Informationen zu der den Prozess auslösenden Behörde übermittelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarTypeType $ausloesendeStelle
     * @return self
     */
    public function setAusloesendeStelle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarTypeType $ausloesendeStelle)
    {
        $this->ausloesendeStelle = $ausloesendeStelle;
        return $this;
    }

    /**
     * Gets as weitergeleiteteNachricht
     *
     * In diesem Element wird die unveränderte fachliche Nachricht übermittelt, wie sie von der den Prozess auslösenden Behörde erstellt wurde.
     *
     * @return string
     */
    public function getWeitergeleiteteNachricht()
    {
        return $this->weitergeleiteteNachricht;
    }

    /**
     * Sets a new weitergeleiteteNachricht
     *
     * In diesem Element wird die unveränderte fachliche Nachricht übermittelt, wie sie von der den Prozess auslösenden Behörde erstellt wurde.
     *
     * @param string $weitergeleiteteNachricht
     * @return self
     */
    public function setWeitergeleiteteNachricht($weitergeleiteteNachricht)
    {
        $this->weitergeleiteteNachricht = $weitergeleiteteNachricht;
        return $this;
    }

    /**
     * Gets as weitergeleiteteNachrichtTyp
     *
     * In diesem Kindelement wird der Nachrichtentyp der weitergeleiteten Nachricht übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeNachrichtentypTyp4TypeType
     */
    public function getWeitergeleiteteNachrichtTyp()
    {
        return $this->weitergeleiteteNachrichtTyp;
    }

    /**
     * Sets a new weitergeleiteteNachrichtTyp
     *
     * In diesem Kindelement wird der Nachrichtentyp der weitergeleiteten Nachricht übermittelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeNachrichtentypTyp4TypeType $weitergeleiteteNachrichtTyp
     * @return self
     */
    public function setWeitergeleiteteNachrichtTyp(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeNachrichtentypTyp4TypeType $weitergeleiteteNachrichtTyp)
    {
        $this->weitergeleiteteNachrichtTyp = $weitergeleiteteNachrichtTyp;
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

