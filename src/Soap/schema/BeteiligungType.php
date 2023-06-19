<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BeteiligungType
 *
 * Komponente der Datentypen, die eine Aufforderung zur Beteiligung beschreiben.
 * XSD Type: Beteiligung
 */
class BeteiligungType
{
    /**
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType $akteurVorhaben
     */
    private $akteurVorhaben = null;

    /**
     * @var string $planname
     */
    private $planname = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartType $planart
     */
    private $planart = null;

    /**
     * Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * @var string $beschreibungPlanungsanlass
     */
    private $beschreibungPlanungsanlass = null;

    /**
     * K1 erstellt einen parametrisierten Link für einen WMS-Aufruf
     *
     * @var string $flaechenabgrenzungWmsUrl
     */
    private $flaechenabgrenzungWmsUrl = null;

    /**
     * @var string $beschreibungGeltungsbereich
     */
    private $beschreibungGeltungsbereich = null;

    /**
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum
     */
    private $zeitraum = null;

    /**
     * @var \DateTime $bekanntmachung
     */
    private $bekanntmachung = null;

    /**
     * In diesem Element können Verfahren spezifiziert werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrenType $verfahren
     */
    private $verfahren = null;

    /**
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as akteurVorhaben
     *
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType
     */
    public function getAkteurVorhaben()
    {
        return $this->akteurVorhaben;
    }

    /**
     * Sets a new akteurVorhaben
     *
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType $akteurVorhaben
     * @return self
     */
    public function setAkteurVorhaben(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType $akteurVorhaben)
    {
        $this->akteurVorhaben = $akteurVorhaben;
        return $this;
    }

    /**
     * Gets as planname
     *
     * @return string
     */
    public function getPlanname()
    {
        return $this->planname;
    }

    /**
     * Sets a new planname
     *
     * @param string $planname
     * @return self
     */
    public function setPlanname($planname)
    {
        $this->planname = $planname;
        return $this;
    }

    /**
     * Gets as planart
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartType
     */
    public function getPlanart()
    {
        return $this->planart;
    }

    /**
     * Sets a new planart
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartType $planart
     * @return self
     */
    public function setPlanart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartType $planart = null)
    {
        $this->planart = $planart;
        return $this;
    }

    /**
     * Gets as planID
     *
     * Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird.
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
     * Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird.
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
     * Gets as beschreibungPlanungsanlass
     *
     * @return string
     */
    public function getBeschreibungPlanungsanlass()
    {
        return $this->beschreibungPlanungsanlass;
    }

    /**
     * Sets a new beschreibungPlanungsanlass
     *
     * @param string $beschreibungPlanungsanlass
     * @return self
     */
    public function setBeschreibungPlanungsanlass($beschreibungPlanungsanlass)
    {
        $this->beschreibungPlanungsanlass = $beschreibungPlanungsanlass;
        return $this;
    }

    /**
     * Gets as flaechenabgrenzungWmsUrl
     *
     * K1 erstellt einen parametrisierten Link für einen WMS-Aufruf
     *
     * @return string
     */
    public function getFlaechenabgrenzungWmsUrl()
    {
        return $this->flaechenabgrenzungWmsUrl;
    }

    /**
     * Sets a new flaechenabgrenzungWmsUrl
     *
     * K1 erstellt einen parametrisierten Link für einen WMS-Aufruf
     *
     * @param string $flaechenabgrenzungWmsUrl
     * @return self
     */
    public function setFlaechenabgrenzungWmsUrl($flaechenabgrenzungWmsUrl)
    {
        $this->flaechenabgrenzungWmsUrl = $flaechenabgrenzungWmsUrl;
        return $this;
    }

    /**
     * Gets as beschreibungGeltungsbereich
     *
     * @return string
     */
    public function getBeschreibungGeltungsbereich()
    {
        return $this->beschreibungGeltungsbereich;
    }

    /**
     * Sets a new beschreibungGeltungsbereich
     *
     * @param string $beschreibungGeltungsbereich
     * @return self
     */
    public function setBeschreibungGeltungsbereich($beschreibungGeltungsbereich)
    {
        $this->beschreibungGeltungsbereich = $beschreibungGeltungsbereich;
        return $this;
    }

    /**
     * Gets as zeitraum
     *
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType
     */
    public function getZeitraum()
    {
        return $this->zeitraum;
    }

    /**
     * Sets a new zeitraum
     *
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum
     * @return self
     */
    public function setZeitraum(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum = null)
    {
        $this->zeitraum = $zeitraum;
        return $this;
    }

    /**
     * Gets as bekanntmachung
     *
     * @return \DateTime
     */
    public function getBekanntmachung()
    {
        return $this->bekanntmachung;
    }

    /**
     * Sets a new bekanntmachung
     *
     * @param \DateTime $bekanntmachung
     * @return self
     */
    public function setBekanntmachung(\DateTime $bekanntmachung)
    {
        $this->bekanntmachung = $bekanntmachung;
        return $this;
    }

    /**
     * Gets as verfahren
     *
     * In diesem Element können Verfahren spezifiziert werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrenType
     */
    public function getVerfahren()
    {
        return $this->verfahren;
    }

    /**
     * Sets a new verfahren
     *
     * In diesem Element können Verfahren spezifiziert werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrenType $verfahren
     * @return self
     */
    public function setVerfahren(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrenType $verfahren = null)
    {
        $this->verfahren = $verfahren;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType $anlage
     */
    public function addToAnlagen(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType $anlage)
    {
        $this->anlagen[] = $anlage;
        return $this;
    }

    /**
     * isset anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnlagen($index)
    {
        return isset($this->anlagen[$index]);
    }

    /**
     * unset anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnlagen($index)
    {
        unset($this->anlagen[$index]);
    }

    /**
     * Gets as anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType[]
     */
    public function getAnlagen()
    {
        return $this->anlagen;
    }

    /**
     * Sets a new anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType[] $anlagen
     * @return self
     */
    public function setAnlagen(array $anlagen = null)
    {
        $this->anlagen = $anlagen;
        return $this;
    }
}

