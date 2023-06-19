<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing InfoFoebType
 *
 * Informationen für die Frühzeitige Öffentlichkeitsbeteiligung
 * XSD Type: InfoFoeb
 */
class InfoFoebType
{
    /**
     * Das Ende eines Zeitraumes beschreibt den Zeitpunkt, ab dem ein Sachverhalt endet bzw. nicht mehr rechtskräftig ist. Das Ende ist Teil der Dauer des Zeitraumes. Anmerkung: Bei der Ableitung von Fachkomponenten sollten zusätzliche Festlegungen getroffen werden wie das Ende des Zeitraums zu interpretieren ist. z.B.: "Wird ein Monat als Ende angegeben, dann gilt der letzte Tag des Monats als Ende des Zeitraums" Beispiel: identisch mit *Fristdatum (Bau) *Ablaufdatum (Finanz) *Faelligkeitsdatum (Finanz) *Wirksamkeitsdatum der Aufhebung/Scheidung der Ehe (Personenstand)
     *
     * @var int $amtlicherAnzeigerNummer
     */
    private $amtlicherAnzeigerNummer = null;

    /**
     * @var int $amtlicherAnzeigerSeite
     */
    private $amtlicherAnzeigerSeite = null;

    /**
     * @var string $amtlicherAnzeigerLink
     */
    private $amtlicherAnzeigerLink = null;

    /**
     * @var \DateTime $bekanntmachungsdatum
     */
    private $bekanntmachungsdatum = null;

    /**
     * Beschreibungfeld, falls foebArt=Sonstiges Verfahren
     *
     * @var string $foebBeschreibung
     */
    private $foebBeschreibung = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum
     */
    private $zeitraum = null;

    /**
     * Gets as amtlicherAnzeigerNummer
     *
     * Das Ende eines Zeitraumes beschreibt den Zeitpunkt, ab dem ein Sachverhalt endet bzw. nicht mehr rechtskräftig ist. Das Ende ist Teil der Dauer des Zeitraumes. Anmerkung: Bei der Ableitung von Fachkomponenten sollten zusätzliche Festlegungen getroffen werden wie das Ende des Zeitraums zu interpretieren ist. z.B.: "Wird ein Monat als Ende angegeben, dann gilt der letzte Tag des Monats als Ende des Zeitraums" Beispiel: identisch mit *Fristdatum (Bau) *Ablaufdatum (Finanz) *Faelligkeitsdatum (Finanz) *Wirksamkeitsdatum der Aufhebung/Scheidung der Ehe (Personenstand)
     *
     * @return int
     */
    public function getAmtlicherAnzeigerNummer()
    {
        return $this->amtlicherAnzeigerNummer;
    }

    /**
     * Sets a new amtlicherAnzeigerNummer
     *
     * Das Ende eines Zeitraumes beschreibt den Zeitpunkt, ab dem ein Sachverhalt endet bzw. nicht mehr rechtskräftig ist. Das Ende ist Teil der Dauer des Zeitraumes. Anmerkung: Bei der Ableitung von Fachkomponenten sollten zusätzliche Festlegungen getroffen werden wie das Ende des Zeitraums zu interpretieren ist. z.B.: "Wird ein Monat als Ende angegeben, dann gilt der letzte Tag des Monats als Ende des Zeitraums" Beispiel: identisch mit *Fristdatum (Bau) *Ablaufdatum (Finanz) *Faelligkeitsdatum (Finanz) *Wirksamkeitsdatum der Aufhebung/Scheidung der Ehe (Personenstand)
     *
     * @param int $amtlicherAnzeigerNummer
     * @return self
     */
    public function setAmtlicherAnzeigerNummer($amtlicherAnzeigerNummer)
    {
        $this->amtlicherAnzeigerNummer = $amtlicherAnzeigerNummer;
        return $this;
    }

    /**
     * Gets as amtlicherAnzeigerSeite
     *
     * @return int
     */
    public function getAmtlicherAnzeigerSeite()
    {
        return $this->amtlicherAnzeigerSeite;
    }

    /**
     * Sets a new amtlicherAnzeigerSeite
     *
     * @param int $amtlicherAnzeigerSeite
     * @return self
     */
    public function setAmtlicherAnzeigerSeite($amtlicherAnzeigerSeite)
    {
        $this->amtlicherAnzeigerSeite = $amtlicherAnzeigerSeite;
        return $this;
    }

    /**
     * Gets as amtlicherAnzeigerLink
     *
     * @return string
     */
    public function getAmtlicherAnzeigerLink()
    {
        return $this->amtlicherAnzeigerLink;
    }

    /**
     * Sets a new amtlicherAnzeigerLink
     *
     * @param string $amtlicherAnzeigerLink
     * @return self
     */
    public function setAmtlicherAnzeigerLink($amtlicherAnzeigerLink)
    {
        $this->amtlicherAnzeigerLink = $amtlicherAnzeigerLink;
        return $this;
    }

    /**
     * Gets as bekanntmachungsdatum
     *
     * @return \DateTime
     */
    public function getBekanntmachungsdatum()
    {
        return $this->bekanntmachungsdatum;
    }

    /**
     * Sets a new bekanntmachungsdatum
     *
     * @param \DateTime $bekanntmachungsdatum
     * @return self
     */
    public function setBekanntmachungsdatum(?\DateTime $bekanntmachungsdatum = null)
    {
        $this->bekanntmachungsdatum = $bekanntmachungsdatum;
        return $this;
    }

    /**
     * Gets as foebBeschreibung
     *
     * Beschreibungfeld, falls foebArt=Sonstiges Verfahren
     *
     * @return string
     */
    public function getFoebBeschreibung()
    {
        return $this->foebBeschreibung;
    }

    /**
     * Sets a new foebBeschreibung
     *
     * Beschreibungfeld, falls foebArt=Sonstiges Verfahren
     *
     * @param string $foebBeschreibung
     * @return self
     */
    public function setFoebBeschreibung($foebBeschreibung)
    {
        $this->foebBeschreibung = $foebBeschreibung;
        return $this;
    }

    /**
     * Gets as zeitraum
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum
     * @return self
     */
    public function setZeitraum(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType $zeitraum = null)
    {
        $this->zeitraum = $zeitraum;
        return $this;
    }
}

