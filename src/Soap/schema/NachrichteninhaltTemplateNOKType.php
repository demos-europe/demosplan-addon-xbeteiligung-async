<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NachrichteninhaltTemplateNOKType
 *
 * Vorlage für negative Quittungsnachrichten
 * XSD Type: Nachrichteninhalt.template.NOK
 */
class NachrichteninhaltTemplateNOKType
{
    /**
     * Hier wird die Vorgangs-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Hier wird die Plan-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Hier kann die Beschreibung des Fehlers übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType[] $fehler
     */
    private $fehler = [
        
    ];

    /**
     * In diesem Element können fehlerhafte Planungsunterlagen beschrieben werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerhafteUnterlageType[] $fehlerhafteUnterlagen
     */
    private $fehlerhafteUnterlagen = [
        
    ];

    /**
     * Gets as vorgangsID
     *
     * Hier wird die Vorgangs-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
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
     * Hier wird die Vorgangs-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
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
     * Gets as planID
     *
     * Hier wird die Plan-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
     *
     * @return string
     */
    public function getPlanID()
    {
        return $this->planID;
    }

    /**
     * Sets a new planID
     *
     * Hier wird die Plan-ID übermittelt, die in der Ursprungsnachricht übermittelt wurde.
     *
     * @param string $planID
     * @return self
     */
    public function setPlanID($planID)
    {
        $this->planID = $planID;
        return $this;
    }

    /**
     * Adds as fehler
     *
     * Hier kann die Beschreibung des Fehlers übermittelt werden.
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
     * Hier kann die Beschreibung des Fehlers übermittelt werden.
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
     * Hier kann die Beschreibung des Fehlers übermittelt werden.
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
     * Hier kann die Beschreibung des Fehlers übermittelt werden.
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
     * Hier kann die Beschreibung des Fehlers übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType[] $fehler
     * @return self
     */
    public function setFehler(array $fehler = null)
    {
        $this->fehler = $fehler;
        return $this;
    }

    /**
     * Adds as fehlerhafteUnterlagen
     *
     * In diesem Element können fehlerhafte Planungsunterlagen beschrieben werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerhafteUnterlageType $fehlerhafteUnterlagen
     */
    public function addToFehlerhafteUnterlagen(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerhafteUnterlageType $fehlerhafteUnterlagen)
    {
        $this->fehlerhafteUnterlagen[] = $fehlerhafteUnterlagen;
        return $this;
    }

    /**
     * isset fehlerhafteUnterlagen
     *
     * In diesem Element können fehlerhafte Planungsunterlagen beschrieben werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetFehlerhafteUnterlagen($index)
    {
        return isset($this->fehlerhafteUnterlagen[$index]);
    }

    /**
     * unset fehlerhafteUnterlagen
     *
     * In diesem Element können fehlerhafte Planungsunterlagen beschrieben werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetFehlerhafteUnterlagen($index)
    {
        unset($this->fehlerhafteUnterlagen[$index]);
    }

    /**
     * Gets as fehlerhafteUnterlagen
     *
     * In diesem Element können fehlerhafte Planungsunterlagen beschrieben werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerhafteUnterlageType[]
     */
    public function getFehlerhafteUnterlagen()
    {
        return $this->fehlerhafteUnterlagen;
    }

    /**
     * Sets a new fehlerhafteUnterlagen
     *
     * In diesem Element können fehlerhafte Planungsunterlagen beschrieben werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerhafteUnterlageType[] $fehlerhafteUnterlagen
     * @return self
     */
    public function setFehlerhafteUnterlagen(array $fehlerhafteUnterlagen = null)
    {
        $this->fehlerhafteUnterlagen = $fehlerhafteUnterlagen;
        return $this;
    }
}

