<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungLoeschenNOK0429\Beteiligung2PlanungBeteiligungLoeschenNOK0429AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Vorgangs-ID, die in der Nachricht 409 übermittelt wurde.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Beteiligungs-ID, die in der Nachricht 409 übermittelt wurde.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Plan-ID, die in der Nachricht 409 übermittelt wurde.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Beschreibung der Fehler.
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
     * Vorgangs-ID, die in der Nachricht 409 übermittelt wurde.
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
     * Vorgangs-ID, die in der Nachricht 409 übermittelt wurde.
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
     * Gets as beteiligungsID
     *
     * Beteiligungs-ID, die in der Nachricht 409 übermittelt wurde.
     *
     * @return string
     */
    public function getBeteiligungsID()
    {
        return $this->beteiligungsID;
    }

    /**
     * Sets a new beteiligungsID
     *
     * Beteiligungs-ID, die in der Nachricht 409 übermittelt wurde.
     *
     * @param string $beteiligungsID
     * @return self
     */
    public function setBeteiligungsID($beteiligungsID)
    {
        $this->beteiligungsID = $beteiligungsID;
        return $this;
    }

    /**
     * Gets as planID
     *
     * Plan-ID, die in der Nachricht 409 übermittelt wurde.
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
     * Plan-ID, die in der Nachricht 409 übermittelt wurde.
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

