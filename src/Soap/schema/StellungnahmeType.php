<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing StellungnahmeType
 *
 * Dieser Datentyp dient der Übermittlung von Stellungnahmen.
 * XSD Type: Stellungnahme
 */
class StellungnahmeType
{
    /**
     * Hier ist die ID der Stellungsnahme zu übermitteln.
     *
     * @var string $stellungnahmeID
     */
    private $stellungnahmeID = null;

    /**
     * Hier ist die ID des Verfahrens zu übermitteln.
     *
     * @var string $stellungnahmeVerfahrenID
     */
    private $stellungnahmeVerfahrenID = null;

    /**
     * Hier ist die ID der Beteiligung zu übermitteln.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Hier ist die ID des Planverfahrens zu übermittleln.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Hier ist der Status der Stellungsnahme zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeType $status
     */
    private $status = null;

    /**
     * Für die Stellungnahme verantwortliche Organisation bzw. Behörde.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $verfasser
     */
    private $verfasser = null;

    /**
     * Hier kann ein Titel übermittelt werden.
     *
     * @var string $titel
     */
    private $titel = null;

    /**
     * Hier kann die Beschreibung der Stellungnahme übermittelt werden.
     *
     * @var string $beschreibung
     */
    private $beschreibung = null;

    /**
     * Hier kann das Datum der Stellungnahme übermittelt werden.
     *
     * @var \DateTime $datum
     */
    private $datum = null;

    /**
     * Hier können Vormerkungen übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungType[] $vormerkung
     */
    private $vormerkung = [
        
    ];

    /**
     * Hier kann die Zurodnung der Stellungnahme übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungType[] $zuordnung
     */
    private $zuordnung = [
        
    ];

    /**
     * Element zur Spezifizierung einer Zustimmung.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungType $zustimmung
     */
    private $zustimmung = null;

    /**
     * Hier können Georeferenzierungen übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungType[] $georeferenzierung
     */
    private $georeferenzierung = [
        
    ];

    /**
     * Hier kann die Priorität der Stellungnahme übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeType $prioritaet
     */
    private $prioritaet = null;

    /**
     * Hier kann ein Abwägungsvorschlag zur Stellungnahme übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagType $abwaegungsvorschlag
     */
    private $abwaegungsvorschlag = null;

    /**
     * Hier können Schlagwörter übermittelt werden.
     *
     * @var string[] $schlagwort
     */
    private $schlagwort = [
        
    ];

    /**
     * Die der Stellungsnahme zugehörigen Anlagen werden in diesem Element referenziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as stellungnahmeID
     *
     * Hier ist die ID der Stellungsnahme zu übermitteln.
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
     * Hier ist die ID der Stellungsnahme zu übermitteln.
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
     * Gets as stellungnahmeVerfahrenID
     *
     * Hier ist die ID des Verfahrens zu übermitteln.
     *
     * @return string
     */
    public function getStellungnahmeVerfahrenID()
    {
        return $this->stellungnahmeVerfahrenID;
    }

    /**
     * Sets a new stellungnahmeVerfahrenID
     *
     * Hier ist die ID des Verfahrens zu übermitteln.
     *
     * @param string $stellungnahmeVerfahrenID
     * @return self
     */
    public function setStellungnahmeVerfahrenID($stellungnahmeVerfahrenID)
    {
        $this->stellungnahmeVerfahrenID = $stellungnahmeVerfahrenID;
        return $this;
    }

    /**
     * Gets as beteiligungsID
     *
     * Hier ist die ID der Beteiligung zu übermitteln.
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
     * Hier ist die ID der Beteiligung zu übermitteln.
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
     * Hier ist die ID des Planverfahrens zu übermittleln.
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
     * Hier ist die ID des Planverfahrens zu übermittleln.
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
     * Gets as status
     *
     * Hier ist der Status der Stellungsnahme zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeType
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * Hier ist der Status der Stellungsnahme zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeType $status
     * @return self
     */
    public function setStatus(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeType $status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Gets as verfasser
     *
     * Für die Stellungnahme verantwortliche Organisation bzw. Behörde.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType
     */
    public function getVerfasser()
    {
        return $this->verfasser;
    }

    /**
     * Sets a new verfasser
     *
     * Für die Stellungnahme verantwortliche Organisation bzw. Behörde.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $verfasser
     * @return self
     */
    public function setVerfasser(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $verfasser = null)
    {
        $this->verfasser = $verfasser;
        return $this;
    }

    /**
     * Gets as titel
     *
     * Hier kann ein Titel übermittelt werden.
     *
     * @return string
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * Sets a new titel
     *
     * Hier kann ein Titel übermittelt werden.
     *
     * @param string $titel
     * @return self
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
        return $this;
    }

    /**
     * Gets as beschreibung
     *
     * Hier kann die Beschreibung der Stellungnahme übermittelt werden.
     *
     * @return string
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Sets a new beschreibung
     *
     * Hier kann die Beschreibung der Stellungnahme übermittelt werden.
     *
     * @param string $beschreibung
     * @return self
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
        return $this;
    }

    /**
     * Gets as datum
     *
     * Hier kann das Datum der Stellungnahme übermittelt werden.
     *
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * Sets a new datum
     *
     * Hier kann das Datum der Stellungnahme übermittelt werden.
     *
     * @param \DateTime $datum
     * @return self
     */
    public function setDatum(?\DateTime $datum = null)
    {
        $this->datum = $datum;
        return $this;
    }

    /**
     * Adds as vormerkung
     *
     * Hier können Vormerkungen übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungType $vormerkung
     */
    public function addToVormerkung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungType $vormerkung)
    {
        $this->vormerkung[] = $vormerkung;
        return $this;
    }

    /**
     * isset vormerkung
     *
     * Hier können Vormerkungen übermittelt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVormerkung($index)
    {
        return isset($this->vormerkung[$index]);
    }

    /**
     * unset vormerkung
     *
     * Hier können Vormerkungen übermittelt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVormerkung($index)
    {
        unset($this->vormerkung[$index]);
    }

    /**
     * Gets as vormerkung
     *
     * Hier können Vormerkungen übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungType[]
     */
    public function getVormerkung()
    {
        return $this->vormerkung;
    }

    /**
     * Sets a new vormerkung
     *
     * Hier können Vormerkungen übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungType[] $vormerkung
     * @return self
     */
    public function setVormerkung(array $vormerkung = null)
    {
        $this->vormerkung = $vormerkung;
        return $this;
    }

    /**
     * Adds as zuordnung
     *
     * Hier kann die Zurodnung der Stellungnahme übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungType $zuordnung
     */
    public function addToZuordnung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungType $zuordnung)
    {
        $this->zuordnung[] = $zuordnung;
        return $this;
    }

    /**
     * isset zuordnung
     *
     * Hier kann die Zurodnung der Stellungnahme übermittelt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetZuordnung($index)
    {
        return isset($this->zuordnung[$index]);
    }

    /**
     * unset zuordnung
     *
     * Hier kann die Zurodnung der Stellungnahme übermittelt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetZuordnung($index)
    {
        unset($this->zuordnung[$index]);
    }

    /**
     * Gets as zuordnung
     *
     * Hier kann die Zurodnung der Stellungnahme übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungType[]
     */
    public function getZuordnung()
    {
        return $this->zuordnung;
    }

    /**
     * Sets a new zuordnung
     *
     * Hier kann die Zurodnung der Stellungnahme übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungType[] $zuordnung
     * @return self
     */
    public function setZuordnung(array $zuordnung = null)
    {
        $this->zuordnung = $zuordnung;
        return $this;
    }

    /**
     * Gets as zustimmung
     *
     * Element zur Spezifizierung einer Zustimmung.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungType
     */
    public function getZustimmung()
    {
        return $this->zustimmung;
    }

    /**
     * Sets a new zustimmung
     *
     * Element zur Spezifizierung einer Zustimmung.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungType $zustimmung
     * @return self
     */
    public function setZustimmung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungType $zustimmung = null)
    {
        $this->zustimmung = $zustimmung;
        return $this;
    }

    /**
     * Adds as georeferenzierung
     *
     * Hier können Georeferenzierungen übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungType $georeferenzierung
     */
    public function addToGeoreferenzierung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungType $georeferenzierung)
    {
        $this->georeferenzierung[] = $georeferenzierung;
        return $this;
    }

    /**
     * isset georeferenzierung
     *
     * Hier können Georeferenzierungen übermittelt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGeoreferenzierung($index)
    {
        return isset($this->georeferenzierung[$index]);
    }

    /**
     * unset georeferenzierung
     *
     * Hier können Georeferenzierungen übermittelt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGeoreferenzierung($index)
    {
        unset($this->georeferenzierung[$index]);
    }

    /**
     * Gets as georeferenzierung
     *
     * Hier können Georeferenzierungen übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungType[]
     */
    public function getGeoreferenzierung()
    {
        return $this->georeferenzierung;
    }

    /**
     * Sets a new georeferenzierung
     *
     * Hier können Georeferenzierungen übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungType[] $georeferenzierung
     * @return self
     */
    public function setGeoreferenzierung(array $georeferenzierung = null)
    {
        $this->georeferenzierung = $georeferenzierung;
        return $this;
    }

    /**
     * Gets as prioritaet
     *
     * Hier kann die Priorität der Stellungnahme übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeType
     */
    public function getPrioritaet()
    {
        return $this->prioritaet;
    }

    /**
     * Sets a new prioritaet
     *
     * Hier kann die Priorität der Stellungnahme übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeType $prioritaet
     * @return self
     */
    public function setPrioritaet(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeType $prioritaet = null)
    {
        $this->prioritaet = $prioritaet;
        return $this;
    }

    /**
     * Gets as abwaegungsvorschlag
     *
     * Hier kann ein Abwägungsvorschlag zur Stellungnahme übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagType
     */
    public function getAbwaegungsvorschlag()
    {
        return $this->abwaegungsvorschlag;
    }

    /**
     * Sets a new abwaegungsvorschlag
     *
     * Hier kann ein Abwägungsvorschlag zur Stellungnahme übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagType $abwaegungsvorschlag
     * @return self
     */
    public function setAbwaegungsvorschlag(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagType $abwaegungsvorschlag = null)
    {
        $this->abwaegungsvorschlag = $abwaegungsvorschlag;
        return $this;
    }

    /**
     * Adds as schlagwort
     *
     * Hier können Schlagwörter übermittelt werden.
     *
     * @return self
     * @param string $schlagwort
     */
    public function addToSchlagwort($schlagwort)
    {
        $this->schlagwort[] = $schlagwort;
        return $this;
    }

    /**
     * isset schlagwort
     *
     * Hier können Schlagwörter übermittelt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetSchlagwort($index)
    {
        return isset($this->schlagwort[$index]);
    }

    /**
     * unset schlagwort
     *
     * Hier können Schlagwörter übermittelt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetSchlagwort($index)
    {
        unset($this->schlagwort[$index]);
    }

    /**
     * Gets as schlagwort
     *
     * Hier können Schlagwörter übermittelt werden.
     *
     * @return string[]
     */
    public function getSchlagwort()
    {
        return $this->schlagwort;
    }

    /**
     * Sets a new schlagwort
     *
     * Hier können Schlagwörter übermittelt werden.
     *
     * @param string $schlagwort
     * @return self
     */
    public function setSchlagwort(array $schlagwort = null)
    {
        $this->schlagwort = $schlagwort;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Die der Stellungsnahme zugehörigen Anlagen werden in diesem Element referenziert.
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
     * Die der Stellungsnahme zugehörigen Anlagen werden in diesem Element referenziert.
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
     * Die der Stellungsnahme zugehörigen Anlagen werden in diesem Element referenziert.
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
     * Die der Stellungsnahme zugehörigen Anlagen werden in diesem Element referenziert.
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
     * Die der Stellungsnahme zugehörigen Anlagen werden in diesem Element referenziert.
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

