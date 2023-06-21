<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing InfoOeffentlicheAuslegungTypeType
 *
 * Informationsobjekt für die Öffentliche Auslegung
 * XSD Type: InfoOeffentlicheAuslegungType
 */
class InfoOeffentlicheAuslegungTypeType
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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $auslegungszeitraum
     */
    private $auslegungszeitraum = null;

    /**
     * @var \DateTime $bekanntmachungsdatum
     */
    private $bekanntmachungsdatum = null;

    /**
     * @var \DateTime $nachrichtTOEBDatum
     */
    private $nachrichtTOEBDatum = null;

    /**
     * @var \DateTime $unterschriftleitungDatum
     */
    private $unterschriftleitungDatum = null;

    /**
     * @var \DateTime $versandZwischennachrichtenDatum
     */
    private $versandZwischennachrichtenDatum = null;

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
     * Gets as auslegungszeitraum
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType
     */
    public function getAuslegungszeitraum()
    {
        return $this->auslegungszeitraum;
    }

    /**
     * Sets a new auslegungszeitraum
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $auslegungszeitraum
     * @return self
     */
    public function setAuslegungszeitraum(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $auslegungszeitraum = null)
    {
        $this->auslegungszeitraum = $auslegungszeitraum;
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
     * Gets as nachrichtTOEBDatum
     *
     * @return \DateTime
     */
    public function getNachrichtTOEBDatum()
    {
        return $this->nachrichtTOEBDatum;
    }

    /**
     * Sets a new nachrichtTOEBDatum
     *
     * @param \DateTime $nachrichtTOEBDatum
     * @return self
     */
    public function setNachrichtTOEBDatum(?\DateTime $nachrichtTOEBDatum = null)
    {
        $this->nachrichtTOEBDatum = $nachrichtTOEBDatum;
        return $this;
    }

    /**
     * Gets as unterschriftleitungDatum
     *
     * @return \DateTime
     */
    public function getUnterschriftleitungDatum()
    {
        return $this->unterschriftleitungDatum;
    }

    /**
     * Sets a new unterschriftleitungDatum
     *
     * @param \DateTime $unterschriftleitungDatum
     * @return self
     */
    public function setUnterschriftleitungDatum(?\DateTime $unterschriftleitungDatum = null)
    {
        $this->unterschriftleitungDatum = $unterschriftleitungDatum;
        return $this;
    }

    /**
     * Gets as versandZwischennachrichtenDatum
     *
     * @return \DateTime
     */
    public function getVersandZwischennachrichtenDatum()
    {
        return $this->versandZwischennachrichtenDatum;
    }

    /**
     * Sets a new versandZwischennachrichtenDatum
     *
     * @param \DateTime $versandZwischennachrichtenDatum
     * @return self
     */
    public function setVersandZwischennachrichtenDatum(?\DateTime $versandZwischennachrichtenDatum = null)
    {
        $this->versandZwischennachrichtenDatum = $versandZwischennachrichtenDatum;
        return $this;
    }
}

