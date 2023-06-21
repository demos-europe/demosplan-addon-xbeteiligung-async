<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BeteiligungTypeType
 *
 * Komponente der Datentypen, die eine Aufforderung zur Beteiligung beschreiben.
 * XSD Type: BeteiligungType
 */
class BeteiligungTypeType
{
    /**
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenTypeType $akteurVorhaben
     */
    private $akteurVorhaben = null;

    /**
     * Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * @var string $planname
     */
    private $planname = null;

    /**
     * @var string $arbeitstitel
     */
    private $arbeitstitel = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartTypeType $planart
     */
    private $planart = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartTypeType $verfahrensart
     */
    private $verfahrensart = null;

    /**
     * Hier kann die fachliche Beschreibung des Planverfahrens übermittelt werden.
     *
     * @var string $beschreibungPlanungsanlass
     */
    private $beschreibungPlanungsanlass = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittTypeType $verfahrensschritt
     */
    private $verfahrensschritt = null;

    /**
     * @var int $durchgang
     */
    private $durchgang = null;

    /**
     * URL eines Kartendienstes
     *
     * @var string $flaechenabgrenzungUrl
     */
    private $flaechenabgrenzungUrl = null;

    /**
     * Hier ist die räumliche Beschreibung des Geltungsbereichs als Polygon im Format GeoJSON FG Notation zu übermitteln.
     *
     * @var string $geltungsbereich
     */
    private $geltungsbereich = null;

    /**
     * Hier ist der räumliche Geltungsbereich zu beschreiben.
     *
     * @var string $raeumlicheBeschreibung
     */
    private $raeumlicheBeschreibung = null;

    /**
     * Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
     *
     * @var \DateTime $bekanntmachung
     */
    private $bekanntmachung = null;

    /**
     * @var string[] $aktuelleMitteilung
     */
    private $aktuelleMitteilung = [
        
    ];

    /**
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $zeitraum
     */
    private $zeitraum = null;

    /**
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as akteurVorhaben
     *
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenTypeType $akteurVorhaben
     * @return self
     */
    public function setAkteurVorhaben(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenTypeType $akteurVorhaben)
    {
        $this->akteurVorhaben = $akteurVorhaben;
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
     * Gets as arbeitstitel
     *
     * @return string
     */
    public function getArbeitstitel()
    {
        return $this->arbeitstitel;
    }

    /**
     * Sets a new arbeitstitel
     *
     * @param string $arbeitstitel
     * @return self
     */
    public function setArbeitstitel($arbeitstitel)
    {
        $this->arbeitstitel = $arbeitstitel;
        return $this;
    }

    /**
     * Gets as planart
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartTypeType
     */
    public function getPlanart()
    {
        return $this->planart;
    }

    /**
     * Sets a new planart
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartTypeType $planart
     * @return self
     */
    public function setPlanart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartTypeType $planart = null)
    {
        $this->planart = $planart;
        return $this;
    }

    /**
     * Gets as verfahrensart
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartTypeType
     */
    public function getVerfahrensart()
    {
        return $this->verfahrensart;
    }

    /**
     * Sets a new verfahrensart
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartTypeType $verfahrensart
     * @return self
     */
    public function setVerfahrensart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartTypeType $verfahrensart = null)
    {
        $this->verfahrensart = $verfahrensart;
        return $this;
    }

    /**
     * Gets as beschreibungPlanungsanlass
     *
     * Hier kann die fachliche Beschreibung des Planverfahrens übermittelt werden.
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
     * Hier kann die fachliche Beschreibung des Planverfahrens übermittelt werden.
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
     * Gets as verfahrensschritt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittTypeType
     */
    public function getVerfahrensschritt()
    {
        return $this->verfahrensschritt;
    }

    /**
     * Sets a new verfahrensschritt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittTypeType $verfahrensschritt
     * @return self
     */
    public function setVerfahrensschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittTypeType $verfahrensschritt)
    {
        $this->verfahrensschritt = $verfahrensschritt;
        return $this;
    }

    /**
     * Gets as durchgang
     *
     * @return int
     */
    public function getDurchgang()
    {
        return $this->durchgang;
    }

    /**
     * Sets a new durchgang
     *
     * @param int $durchgang
     * @return self
     */
    public function setDurchgang($durchgang)
    {
        $this->durchgang = $durchgang;
        return $this;
    }

    /**
     * Gets as flaechenabgrenzungUrl
     *
     * URL eines Kartendienstes
     *
     * @return string
     */
    public function getFlaechenabgrenzungUrl()
    {
        return $this->flaechenabgrenzungUrl;
    }

    /**
     * Sets a new flaechenabgrenzungUrl
     *
     * URL eines Kartendienstes
     *
     * @param string $flaechenabgrenzungUrl
     * @return self
     */
    public function setFlaechenabgrenzungUrl($flaechenabgrenzungUrl)
    {
        $this->flaechenabgrenzungUrl = $flaechenabgrenzungUrl;
        return $this;
    }

    /**
     * Gets as geltungsbereich
     *
     * Hier ist die räumliche Beschreibung des Geltungsbereichs als Polygon im Format GeoJSON FG Notation zu übermitteln.
     *
     * @return string
     */
    public function getGeltungsbereich()
    {
        return $this->geltungsbereich;
    }

    /**
     * Sets a new geltungsbereich
     *
     * Hier ist die räumliche Beschreibung des Geltungsbereichs als Polygon im Format GeoJSON FG Notation zu übermitteln.
     *
     * @param string $geltungsbereich
     * @return self
     */
    public function setGeltungsbereich($geltungsbereich)
    {
        $this->geltungsbereich = $geltungsbereich;
        return $this;
    }

    /**
     * Gets as raeumlicheBeschreibung
     *
     * Hier ist der räumliche Geltungsbereich zu beschreiben.
     *
     * @return string
     */
    public function getRaeumlicheBeschreibung()
    {
        return $this->raeumlicheBeschreibung;
    }

    /**
     * Sets a new raeumlicheBeschreibung
     *
     * Hier ist der räumliche Geltungsbereich zu beschreiben.
     *
     * @param string $raeumlicheBeschreibung
     * @return self
     */
    public function setRaeumlicheBeschreibung($raeumlicheBeschreibung)
    {
        $this->raeumlicheBeschreibung = $raeumlicheBeschreibung;
        return $this;
    }

    /**
     * Gets as bekanntmachung
     *
     * Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
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
     * Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
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
     * Adds as aktuelleMitteilung
     *
     * @return self
     * @param string $aktuelleMitteilung
     */
    public function addToAktuelleMitteilung($aktuelleMitteilung)
    {
        $this->aktuelleMitteilung[] = $aktuelleMitteilung;
        return $this;
    }

    /**
     * isset aktuelleMitteilung
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAktuelleMitteilung($index)
    {
        return isset($this->aktuelleMitteilung[$index]);
    }

    /**
     * unset aktuelleMitteilung
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAktuelleMitteilung($index)
    {
        unset($this->aktuelleMitteilung[$index]);
    }

    /**
     * Gets as aktuelleMitteilung
     *
     * @return string[]
     */
    public function getAktuelleMitteilung()
    {
        return $this->aktuelleMitteilung;
    }

    /**
     * Sets a new aktuelleMitteilung
     *
     * @param string[] $aktuelleMitteilung
     * @return self
     */
    public function setAktuelleMitteilung(array $aktuelleMitteilung = null)
    {
        $this->aktuelleMitteilung = $aktuelleMitteilung;
        return $this;
    }

    /**
     * Gets as zeitraum
     *
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $zeitraum
     * @return self
     */
    public function setZeitraum(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $zeitraum)
    {
        $this->zeitraum = $zeitraum;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType $anlage
     */
    public function addToAnlagen(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType $anlage)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType[]
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType[] $anlagen
     * @return self
     */
    public function setAnlagen(array $anlagen = null)
    {
        $this->anlagen = $anlagen;
        return $this;
    }
}

