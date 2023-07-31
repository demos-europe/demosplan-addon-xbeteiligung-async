<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BeteiligungKommunalType
 *
 * Dieser Datentyp enthält alle Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung.
 * XSD Type: BeteiligungKommunal
 */
class BeteiligungKommunalType
{
    /**
     * Hier sind Informationen zu den am Planungsvorhaben beteiligten Akteuren zu übermitteln. Mindestens der Veranlasser ist zu benennen.
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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommunalType $planartKommunal
     */
    private $planartKommunal = null;

    /**
     * Hier ist der Verfahrensschritt zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschrittKommunal
     */
    private $verfahrensschrittKommunal = null;

    /**
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommunalType $verfahrensartKommunal
     */
    private $verfahrensartKommunal = null;

    /**
     * Hier kann die fachliche Beschreibung des Planverfahrens übermittelt werden.
     *
     * @var string $beschreibungPlanungsanlass
     */
    private $beschreibungPlanungsanlass = null;

    /**
     * Hier kann zur Visualisierung der Flächenabgrenzung eine URL auf einen Kartendienst übermittelt werden.
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
     * Hier kann eine URL übermittelt werden, unter der Detailinformationen zum Beteiligungsverfahren eingesehen werden können.
     *
     * @var string $beteiligungURL
     */
    private $beteiligungURL = null;

    /**
     * Hier können Angaben zur Beteiligung der Öffentlichkeit gemacht werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit
     */
    private $beteiligungOeffentlichkeit = null;

    /**
     * Hier können Angaben zur Beteiligung der Träger öffentlicher Belange gemacht werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType $beteiligungTOEB
     */
    private $beteiligungTOEB = null;

    /**
     * Gets as akteurVorhaben
     *
     * Hier sind Informationen zu den am Planungsvorhaben beteiligten Akteuren zu übermitteln. Mindestens der Veranlasser ist zu benennen.
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
     * Hier sind Informationen zu den am Planungsvorhaben beteiligten Akteuren zu übermitteln. Mindestens der Veranlasser ist zu benennen.
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
     * Gets as planartKommunal
     *
     * Hier kann die Art des Planverfahrens übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommunalType
     */
    public function getPlanartKommunal()
    {
        return $this->planartKommunal;
    }

    /**
     * Sets a new planartKommunal
     *
     * Hier kann die Art des Planverfahrens übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommunalType $planartKommunal
     * @return self
     */
    public function setPlanartKommunal(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommunalType $planartKommunal = null)
    {
        $this->planartKommunal = $planartKommunal;
        return $this;
    }

    /**
     * Gets as verfahrensschrittKommunal
     *
     * Hier ist der Verfahrensschritt zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType
     */
    public function getVerfahrensschrittKommunal()
    {
        return $this->verfahrensschrittKommunal;
    }

    /**
     * Sets a new verfahrensschrittKommunal
     *
     * Hier ist der Verfahrensschritt zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschrittKommunal
     * @return self
     */
    public function setVerfahrensschrittKommunal(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschrittKommunal)
    {
        $this->verfahrensschrittKommunal = $verfahrensschrittKommunal;
        return $this;
    }

    /**
     * Gets as verfahrensartKommunal
     *
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommunalType
     */
    public function getVerfahrensartKommunal()
    {
        return $this->verfahrensartKommunal;
    }

    /**
     * Sets a new verfahrensartKommunal
     *
     * Hier kann die Art des Beteiligungsverfahrens übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommunalType $verfahrensartKommunal
     * @return self
     */
    public function setVerfahrensartKommunal(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartKommunalType $verfahrensartKommunal = null)
    {
        $this->verfahrensartKommunal = $verfahrensartKommunal;
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
     * Gets as flaechenabgrenzungUrl
     *
     * Hier kann zur Visualisierung der Flächenabgrenzung eine URL auf einen Kartendienst übermittelt werden.
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
     * Hier kann zur Visualisierung der Flächenabgrenzung eine URL auf einen Kartendienst übermittelt werden.
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
     * Gets as beteiligungURL
     *
     * Hier kann eine URL übermittelt werden, unter der Detailinformationen zum Beteiligungsverfahren eingesehen werden können.
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
     * Hier kann eine URL übermittelt werden, unter der Detailinformationen zum Beteiligungsverfahren eingesehen werden können.
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
     * Gets as beteiligungOeffentlichkeit
     *
     * Hier können Angaben zur Beteiligung der Öffentlichkeit gemacht werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType
     */
    public function getBeteiligungOeffentlichkeit()
    {
        return $this->beteiligungOeffentlichkeit;
    }

    /**
     * Sets a new beteiligungOeffentlichkeit
     *
     * Hier können Angaben zur Beteiligung der Öffentlichkeit gemacht werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit
     * @return self
     */
    public function setBeteiligungOeffentlichkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit = null)
    {
        $this->beteiligungOeffentlichkeit = $beteiligungOeffentlichkeit;
        return $this;
    }

    /**
     * Gets as beteiligungTOEB
     *
     * Hier können Angaben zur Beteiligung der Träger öffentlicher Belange gemacht werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType
     */
    public function getBeteiligungTOEB()
    {
        return $this->beteiligungTOEB;
    }

    /**
     * Sets a new beteiligungTOEB
     *
     * Hier können Angaben zur Beteiligung der Träger öffentlicher Belange gemacht werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType $beteiligungTOEB
     * @return self
     */
    public function setBeteiligungTOEB(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType $beteiligungTOEB = null)
    {
        $this->beteiligungTOEB = $beteiligungTOEB;
        return $this;
    }
}

