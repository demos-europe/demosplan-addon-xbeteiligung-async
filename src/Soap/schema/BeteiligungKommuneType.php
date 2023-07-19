<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BeteiligungKommuneType
 *
 * Dieser Datentyp enthält alle Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung.
 * XSD Type: BeteiligungKommune
 */
class BeteiligungKommuneType
{
    /**
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType $akteurVorhaben
     */
    private $akteurVorhaben = null;

    /**
     * Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Hier ist der Name des Planvorhabens zu übermitteln.
     *
     * @var string $planname
     */
    private $planname = null;

    /**
     * Hier kann der Arbeitstitel übermittelt werden.
     *
     * @var string $arbeitstitel
     */
    private $arbeitstitel = null;

    /**
     * Hier kann die Art des Planverfahrens übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommuneType $planart
     */
    private $planart = null;

    /**
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommuneType $verfahrensart
     */
    private $verfahrensart = null;

    /**
     * Hier kann die fachliche Beschreibung des Planverfahrens übermittelt werden.
     *
     * @var string $beschreibungPlanungsanlass
     */
    private $beschreibungPlanungsanlass = null;

    /**
     * Hier ist der Verfahrensschritt zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschritt
     */
    private $verfahrensschritt = null;

    /**
     * Hier ist die Durchgangsnummer des Verfahrens zu übermitteln.
     *
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
     * Hier können aktuelle Mitteilungen übermittelt werden.
     *
     * @var string[] $aktuelleMitteilung
     */
    private $aktuelleMitteilung = [
        
    ];

    /**
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum
     */
    private $zeitraum = null;

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
     * Hier ist der Name des Planvorhabens zu übermitteln.
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
     * Hier ist der Name des Planvorhabens zu übermitteln.
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
     * Hier kann der Arbeitstitel übermittelt werden.
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
     * Hier kann der Arbeitstitel übermittelt werden.
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
     * Hier kann die Art des Planverfahrens übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommuneType
     */
    public function getPlanart()
    {
        return $this->planart;
    }

    /**
     * Sets a new planart
     *
     * Hier kann die Art des Planverfahrens übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommuneType $planart
     * @return self
     */
    public function setPlanart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommuneType $planart = null)
    {
        $this->planart = $planart;
        return $this;
    }

    /**
     * Gets as verfahrensart
     *
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommuneType
     */
    public function getVerfahrensart()
    {
        return $this->verfahrensart;
    }

    /**
     * Sets a new verfahrensart
     *
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommuneType $verfahrensart
     * @return self
     */
    public function setVerfahrensart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommuneType $verfahrensart = null)
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
     * Hier ist der Verfahrensschritt zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType
     */
    public function getVerfahrensschritt()
    {
        return $this->verfahrensschritt;
    }

    /**
     * Sets a new verfahrensschritt
     *
     * Hier ist der Verfahrensschritt zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschritt
     * @return self
     */
    public function setVerfahrensschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschritt)
    {
        $this->verfahrensschritt = $verfahrensschritt;
        return $this;
    }

    /**
     * Gets as durchgang
     *
     * Hier ist die Durchgangsnummer des Verfahrens zu übermitteln.
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
     * Hier ist die Durchgangsnummer des Verfahrens zu übermitteln.
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
     * Hier können aktuelle Mitteilungen übermittelt werden.
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
     * Hier können aktuelle Mitteilungen übermittelt werden.
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
     * Hier können aktuelle Mitteilungen übermittelt werden.
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
     * Hier können aktuelle Mitteilungen übermittelt werden.
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
     * Hier können aktuelle Mitteilungen übermittelt werden.
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
    public function setZeitraum(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum)
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

