<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing StellungnahmeTypeType
 *
 * Dieser Datentyp dient der Übermittlung von Stellungnahmen von Institutionen.
 * XSD Type: StellungnahmeType
 */
class StellungnahmeTypeType
{
    /**
     * @var string $stellungnahmeID
     */
    private $stellungnahmeID = null;

    /**
     * @var string $stellungnahmeVerfahrenID
     */
    private $stellungnahmeVerfahrenID = null;

    /**
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * @var string $planID
     */
    private $planID = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeTypeType $status
     */
    private $status = null;

    /**
     * Für die Stellungnahme verantwortliche Organisation bzw. Behörde.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $verfasser
     */
    private $verfasser = null;

    /**
     * @var string $titel
     */
    private $titel = null;

    /**
     * @var string $beschreibung
     */
    private $beschreibung = null;

    /**
     * @var \DateTime $datum
     */
    private $datum = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungTypeType[] $vormerkung
     */
    private $vormerkung = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungTypeType[] $zuordnung
     */
    private $zuordnung = [
        
    ];

    /**
     * Element zur Spezifizierung einer Zustimmung.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungTypeType $zustimmung
     */
    private $zustimmung = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungTypeType[] $georeferenzierung
     */
    private $georeferenzierung = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeTypeType $prioritaet
     */
    private $prioritaet = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagTypeType $abwaegungsvorschlag
     */
    private $abwaegungsvorschlag = null;

    /**
     * @var string[] $schlagwort
     */
    private $schlagwort = [
        
    ];

    /**
     * Die der Stellungsnahme zugehörigen Anlagen werden in diesem Element referenziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as stellungnahmeID
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
     * @return string
     */
    public function getStellungnahmeVerfahrenID()
    {
        return $this->stellungnahmeVerfahrenID;
    }

    /**
     * Sets a new stellungnahmeVerfahrenID
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
     * @return string
     */
    public function getBeteiligungsID()
    {
        return $this->beteiligungsID;
    }

    /**
     * Sets a new beteiligungsID
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
     * @return string
     */
    public function getPlanID()
    {
        return $this->planID;
    }

    /**
     * Sets a new planID
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeTypeType
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Sets a new status
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeTypeType $status
     * @return self
     */
    public function setStatus(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStatusDerStellungnahmeTypeType $status)
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
     * @return string
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * Sets a new titel
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
     * @return string
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Sets a new beschreibung
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
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * Sets a new datum
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
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungTypeType $vormerkung
     */
    public function addToVormerkung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungTypeType $vormerkung)
    {
        $this->vormerkung[] = $vormerkung;
        return $this;
    }

    /**
     * isset vormerkung
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungTypeType[]
     */
    public function getVormerkung()
    {
        return $this->vormerkung;
    }

    /**
     * Sets a new vormerkung
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVormerkungTypeType[] $vormerkung
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
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungTypeType $zuordnung
     */
    public function addToZuordnung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungTypeType $zuordnung)
    {
        $this->zuordnung[] = $zuordnung;
        return $this;
    }

    /**
     * isset zuordnung
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungTypeType[]
     */
    public function getZuordnung()
    {
        return $this->zuordnung;
    }

    /**
     * Sets a new zuordnung
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZuordnungTypeType[] $zuordnung
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungTypeType $zustimmung
     * @return self
     */
    public function setZustimmung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZustimmungTypeType $zustimmung = null)
    {
        $this->zustimmung = $zustimmung;
        return $this;
    }

    /**
     * Adds as georeferenzierung
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungTypeType $georeferenzierung
     */
    public function addToGeoreferenzierung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungTypeType $georeferenzierung)
    {
        $this->georeferenzierung[] = $georeferenzierung;
        return $this;
    }

    /**
     * isset georeferenzierung
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungTypeType[]
     */
    public function getGeoreferenzierung()
    {
        return $this->georeferenzierung;
    }

    /**
     * Sets a new georeferenzierung
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierungTypeType[] $georeferenzierung
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeTypeType
     */
    public function getPrioritaet()
    {
        return $this->prioritaet;
    }

    /**
     * Sets a new prioritaet
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeTypeType $prioritaet
     * @return self
     */
    public function setPrioritaet(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePrioritaetDerStellungnahmeTypeType $prioritaet = null)
    {
        $this->prioritaet = $prioritaet;
        return $this;
    }

    /**
     * Gets as abwaegungsvorschlag
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagTypeType
     */
    public function getAbwaegungsvorschlag()
    {
        return $this->abwaegungsvorschlag;
    }

    /**
     * Sets a new abwaegungsvorschlag
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagTypeType $abwaegungsvorschlag
     * @return self
     */
    public function setAbwaegungsvorschlag(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAbwaegungsvorschlagTypeType $abwaegungsvorschlag = null)
    {
        $this->abwaegungsvorschlag = $abwaegungsvorschlag;
        return $this;
    }

    /**
     * Adds as schlagwort
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
     * @return string[]
     */
    public function getSchlagwort()
    {
        return $this->schlagwort;
    }

    /**
     * Sets a new schlagwort
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType[]
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageTypeType[] $anlagen
     * @return self
     */
    public function setAnlagen(array $anlagen = null)
    {
        $this->anlagen = $anlagen;
        return $this;
    }
}

