<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing BeteiligungPlanfeststellungDBType
 *
 * Komponente der Datentypen, die eine Aufforderung zur Beteiligung beschreiben.
 * XSD Type: BeteiligungPlanfeststellungDB
 */
class BeteiligungPlanfeststellungDBType
{
    /**
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AkteurVorhabenType $akteurVorhaben
     */
    private $akteurVorhaben = null;

    /**
     * Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Hier ist der Name des Planverfahrens zu übermitteln.
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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartPlanfeststellungType $planart
     */
    private $planart = null;

    /**
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensartPlanfeststellungType $verfahrensart
     */
    private $verfahrensart = null;

    /**
     * Hier kann die fachliche Beschreibung des Planverfahrens übermittelt werden.
     *
     * @var string $beschreibungPlanungsanlass
     */
    private $beschreibungPlanungsanlass = null;

    /**
     * Hier wrd der Verfahrensschritt übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $verfahrensschritt
     */
    private $verfahrensschritt = null;

    /**
     * Hier ist die Durchgangsnummer des Beteiligungsverfahrens zu übermitteln.
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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $zeitraum
     */
    private $zeitraum = null;

    /**
     * Hier kann der Zeitraum (Beginn und Ende) übermittelt werden, für den die Dokumente zu einer Beteiligungsphase öffentlich bereitgestellt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $veroeffentlichungszeitraum
     */
    private $veroeffentlichungszeitraum = null;

    /**
     * Hier kann eine URL übermittelt werden, unter der Detailinformationen zum Beteiligungsverfahren eingesehen werden können. Beim Mapping auf DCAT-AP-plu kann die Dokumentart plu:docType: participationURL verwendet werden.
     *
     * @var string $beteiligungURL
     */
    private $beteiligungURL = null;

    /**
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert. Für die Übermittlung an die Beteiligungs-DB ist nur die Übermittlung von Links auf Unterlagen zulässig.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as akteurVorhaben
     *
     * Am Planungsvorhaben beteiligte Akteure.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AkteurVorhabenType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AkteurVorhabenType $akteurVorhaben
     * @return self
     */
    public function setAkteurVorhaben(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AkteurVorhabenType $akteurVorhaben)
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
     * Hier ist der Name des Planverfahrens zu übermitteln.
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
     * Hier ist der Name des Planverfahrens zu übermitteln.
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartPlanfeststellungType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartPlanfeststellungType $planart
     * @return self
     */
    public function setPlanart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartPlanfeststellungType $planart = null)
    {
        $this->planart = $planart;
        return $this;
    }

    /**
     * Gets as verfahrensart
     *
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensartPlanfeststellungType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensartPlanfeststellungType $verfahrensart
     * @return self
     */
    public function setVerfahrensart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensartPlanfeststellungType $verfahrensart = null)
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
     * Hier wrd der Verfahrensschritt übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType
     */
    public function getVerfahrensschritt()
    {
        return $this->verfahrensschritt;
    }

    /**
     * Sets a new verfahrensschritt
     *
     * Hier wrd der Verfahrensschritt übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $verfahrensschritt
     * @return self
     */
    public function setVerfahrensschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType $verfahrensschritt)
    {
        $this->verfahrensschritt = $verfahrensschritt;
        return $this;
    }

    /**
     * Gets as durchgang
     *
     * Hier ist die Durchgangsnummer des Beteiligungsverfahrens zu übermitteln.
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
     * Hier ist die Durchgangsnummer des Beteiligungsverfahrens zu übermitteln.
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $zeitraum
     * @return self
     */
    public function setZeitraum(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $zeitraum = null)
    {
        $this->zeitraum = $zeitraum;
        return $this;
    }

    /**
     * Gets as veroeffentlichungszeitraum
     *
     * Hier kann der Zeitraum (Beginn und Ende) übermittelt werden, für den die Dokumente zu einer Beteiligungsphase öffentlich bereitgestellt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType
     */
    public function getVeroeffentlichungszeitraum()
    {
        return $this->veroeffentlichungszeitraum;
    }

    /**
     * Sets a new veroeffentlichungszeitraum
     *
     * Hier kann der Zeitraum (Beginn und Ende) übermittelt werden, für den die Dokumente zu einer Beteiligungsphase öffentlich bereitgestellt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $veroeffentlichungszeitraum
     * @return self
     */
    public function setVeroeffentlichungszeitraum(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $veroeffentlichungszeitraum = null)
    {
        $this->veroeffentlichungszeitraum = $veroeffentlichungszeitraum;
        return $this;
    }

    /**
     * Gets as beteiligungURL
     *
     * Hier kann eine URL übermittelt werden, unter der Detailinformationen zum Beteiligungsverfahren eingesehen werden können. Beim Mapping auf DCAT-AP-plu kann die Dokumentart plu:docType: participationURL verwendet werden.
     *
     * @return string
     */
    public function getBeteiligungURL()
    {
        return $this->beteiligungURL;
    }

    /**
     * Sets a new beteiligungURL
     *
     * Hier kann eine URL übermittelt werden, unter der Detailinformationen zum Beteiligungsverfahren eingesehen werden können. Beim Mapping auf DCAT-AP-plu kann die Dokumentart plu:docType: participationURL verwendet werden.
     *
     * @param string $beteiligungURL
     * @return self
     */
    public function setBeteiligungURL($beteiligungURL)
    {
        $this->beteiligungURL = $beteiligungURL;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert. Für die Übermittlung an die Beteiligungs-DB ist nur die Übermittlung von Links auf Unterlagen zulässig.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType $anlage
     */
    public function addToAnlagen(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType $anlage)
    {
        $this->anlagen[] = $anlage;
        return $this;
    }

    /**
     * isset anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert. Für die Übermittlung an die Beteiligungs-DB ist nur die Übermittlung von Links auf Unterlagen zulässig.
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
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert. Für die Übermittlung an die Beteiligungs-DB ist nur die Übermittlung von Links auf Unterlagen zulässig.
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
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert. Für die Übermittlung an die Beteiligungs-DB ist nur die Übermittlung von Links auf Unterlagen zulässig.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType[]
     */
    public function getAnlagen()
    {
        return $this->anlagen;
    }

    /**
     * Sets a new anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert. Für die Übermittlung an die Beteiligungs-DB ist nur die Übermittlung von Links auf Unterlagen zulässig.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType[] $anlagen
     * @return self
     */
    public function setAnlagen(array $anlagen = null)
    {
        $this->anlagen = $anlagen;
        return $this;
    }
}

