<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungStellungnahmeAktualisierenNOK0722\Planung2BeteiligungStellungnahmeAktualisierenNOK0722AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Die Vorgangs-ID der Nachrichten 401 und der sich darauf beziehenden Nachrichten aus der Beteiligung.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Die Stellungnahme-ID, die in der Nachricht 702 von der beteiligten Stelle übermittelt wurde.
     *
     * @var string $stellungnahmeID
     */
    private $stellungnahmeID = null;

    /**
     * Beschreibung der Fehler.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType[] $fehler
     */
    private $fehler = [
        
    ];

    /**
     * Gets as vorgangsID
     *
     * Die Vorgangs-ID der Nachrichten 401 und der sich darauf beziehenden Nachrichten aus der Beteiligung.
     *
     * @return string
     */
    public function getVorgangsID()
    {
        return $this->vorgangsID;
    }

    /**
     * Sets a new vorgangsID
     *
     * Die Vorgangs-ID der Nachrichten 401 und der sich darauf beziehenden Nachrichten aus der Beteiligung.
     *
     * @param string $vorgangsID
     * @return self
     */
    public function setVorgangsID($vorgangsID)
    {
        $this->vorgangsID = $vorgangsID;
        return $this;
    }

    /**
     * Gets as stellungnahmeID
     *
     * Die Stellungnahme-ID, die in der Nachricht 702 von der beteiligten Stelle übermittelt wurde.
     *
     * @return string
     */
    public function getStellungnahmeID()
    {
        return $this->stellungnahmeID;
    }

    /**
     * Sets a new stellungnahmeID
     *
     * Die Stellungnahme-ID, die in der Nachricht 702 von der beteiligten Stelle übermittelt wurde.
     *
     * @param string $stellungnahmeID
     * @return self
     */
    public function setStellungnahmeID($stellungnahmeID)
    {
        $this->stellungnahmeID = $stellungnahmeID;
        return $this;
    }

    /**
     * Adds as fehler
     *
     * Beschreibung der Fehler.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType $fehler
     */
    public function addToFehler(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType $fehler)
    {
        $this->fehler[] = $fehler;
        return $this;
    }

    /**
     * isset fehler
     *
     * Beschreibung der Fehler.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetFehler($index)
    {
        return isset($this->fehler[$index]);
    }

    /**
     * unset fehler
     *
     * Beschreibung der Fehler.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetFehler($index)
    {
        unset($this->fehler[$index]);
    }

    /**
     * Gets as fehler
     *
     * Beschreibung der Fehler.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType[]
     */
    public function getFehler()
    {
        return $this->fehler;
    }

    /**
     * Sets a new fehler
     *
     * Beschreibung der Fehler.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType[] $fehler
     * @return self
     */
    public function setFehler(array $fehler = null)
    {
        $this->fehler = $fehler;
        return $this;
    }
}

