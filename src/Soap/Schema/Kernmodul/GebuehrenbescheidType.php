<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing GebuehrenbescheidType
 *
 * Dieser Typ beinhaltet die Parameter eines Gebührenbescheids der Fachbehörde. Es sind die Daten zu Gegenstand, Herleitung, Betrag und Bezahlung der Gebühren enthalten.
 * XSD Type: Gebuehrenbescheid
 */
class GebuehrenbescheidType
{
    /**
     * Falls es sich nicht um den abschließenden Gebührenbescheid, sondern um einen Vorauszahlungsbescheid handelt, ist hier true einzutragen. Dann weiß der Empfänger, dass weitere Gebührenbescheide zum angegebenen Vorgang zu erwarten sind.
     *
     * @var bool $istVorauszahlungsbescheid
     */
    private $istVorauszahlungsbescheid = null;

    /**
     * Hier ist die Rechtsgrundlage für die Gebühr bzw. eine sonstige Begründung einzutragen.
     *
     * @var string[] $begruendung
     */
    private $begruendung = null;

    /**
     * Dieses Objekt enthält alle Informationen zu den Positionen des Gebührenbescheids und deren Summierung zu einem Geldbetrag.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KostenberechnungType $kostenberechnung
     */
    private $kostenberechnung = null;

    /**
     * Dieses Element enthält die Angaben, die der Anwender zur Veranlassung einer bargeldlosen Bezahlung gemäß SEPA benötigt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\ZahlungsdatenType $zahlungsdaten
     */
    private $zahlungsdaten = null;

    /**
     * Hier werden die Rechtshelfsbelehrungen zum Bescheid gegeben.
     *
     * @var string[] $rechtshelfsbelehrung
     */
    private $rechtshelfsbelehrung = null;

    /**
     * Hier werden Angaben zu Zuschlägen bei Fristversäumnis eingetragen.
     *
     * @var string[] $informationVerspaetungszuschlag
     */
    private $informationVerspaetungszuschlag = null;

    /**
     * Mit diesem Element kann der Gebührenbescheid als PDF-Datei übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType[] $anlage
     */
    private $anlage = null;

    /**
     * Gets as istVorauszahlungsbescheid
     *
     * Falls es sich nicht um den abschließenden Gebührenbescheid, sondern um einen Vorauszahlungsbescheid handelt, ist hier true einzutragen. Dann weiß der Empfänger, dass weitere Gebührenbescheide zum angegebenen Vorgang zu erwarten sind.
     *
     * @return bool
     */
    public function getIstVorauszahlungsbescheid()
    {
        return $this->istVorauszahlungsbescheid;
    }

    /**
     * Sets a new istVorauszahlungsbescheid
     *
     * Falls es sich nicht um den abschließenden Gebührenbescheid, sondern um einen Vorauszahlungsbescheid handelt, ist hier true einzutragen. Dann weiß der Empfänger, dass weitere Gebührenbescheide zum angegebenen Vorgang zu erwarten sind.
     *
     * @param bool $istVorauszahlungsbescheid
     * @return self
     */
    public function setIstVorauszahlungsbescheid($istVorauszahlungsbescheid)
    {
        $this->istVorauszahlungsbescheid = $istVorauszahlungsbescheid;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * Hier ist die Rechtsgrundlage für die Gebühr bzw. eine sonstige Begründung einzutragen.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToBegruendung($textabsatz)
    {
        $this->begruendung[] = $textabsatz;
        return $this;
    }

    /**
     * isset begruendung
     *
     * Hier ist die Rechtsgrundlage für die Gebühr bzw. eine sonstige Begründung einzutragen.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBegruendung($index)
    {
        return isset($this->begruendung[$index]);
    }

    /**
     * unset begruendung
     *
     * Hier ist die Rechtsgrundlage für die Gebühr bzw. eine sonstige Begründung einzutragen.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBegruendung($index)
    {
        unset($this->begruendung[$index]);
    }

    /**
     * Gets as begruendung
     *
     * Hier ist die Rechtsgrundlage für die Gebühr bzw. eine sonstige Begründung einzutragen.
     *
     * @return string[]
     */
    public function getBegruendung()
    {
        return $this->begruendung;
    }

    /**
     * Sets a new begruendung
     *
     * Hier ist die Rechtsgrundlage für die Gebühr bzw. eine sonstige Begründung einzutragen.
     *
     * @param string[] $begruendung
     * @return self
     */
    public function setBegruendung(array $begruendung)
    {
        $this->begruendung = $begruendung;
        return $this;
    }

    /**
     * Gets as kostenberechnung
     *
     * Dieses Objekt enthält alle Informationen zu den Positionen des Gebührenbescheids und deren Summierung zu einem Geldbetrag.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KostenberechnungType
     */
    public function getKostenberechnung()
    {
        return $this->kostenberechnung;
    }

    /**
     * Sets a new kostenberechnung
     *
     * Dieses Objekt enthält alle Informationen zu den Positionen des Gebührenbescheids und deren Summierung zu einem Geldbetrag.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KostenberechnungType $kostenberechnung
     * @return self
     */
    public function setKostenberechnung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KostenberechnungType $kostenberechnung = null)
    {
        $this->kostenberechnung = $kostenberechnung;
        return $this;
    }

    /**
     * Gets as zahlungsdaten
     *
     * Dieses Element enthält die Angaben, die der Anwender zur Veranlassung einer bargeldlosen Bezahlung gemäß SEPA benötigt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\ZahlungsdatenType
     */
    public function getZahlungsdaten()
    {
        return $this->zahlungsdaten;
    }

    /**
     * Sets a new zahlungsdaten
     *
     * Dieses Element enthält die Angaben, die der Anwender zur Veranlassung einer bargeldlosen Bezahlung gemäß SEPA benötigt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\ZahlungsdatenType $zahlungsdaten
     * @return self
     */
    public function setZahlungsdaten(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\ZahlungsdatenType $zahlungsdaten = null)
    {
        $this->zahlungsdaten = $zahlungsdaten;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * Hier werden die Rechtshelfsbelehrungen zum Bescheid gegeben.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToRechtshelfsbelehrung($textabsatz)
    {
        $this->rechtshelfsbelehrung[] = $textabsatz;
        return $this;
    }

    /**
     * isset rechtshelfsbelehrung
     *
     * Hier werden die Rechtshelfsbelehrungen zum Bescheid gegeben.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetRechtshelfsbelehrung($index)
    {
        return isset($this->rechtshelfsbelehrung[$index]);
    }

    /**
     * unset rechtshelfsbelehrung
     *
     * Hier werden die Rechtshelfsbelehrungen zum Bescheid gegeben.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetRechtshelfsbelehrung($index)
    {
        unset($this->rechtshelfsbelehrung[$index]);
    }

    /**
     * Gets as rechtshelfsbelehrung
     *
     * Hier werden die Rechtshelfsbelehrungen zum Bescheid gegeben.
     *
     * @return string[]
     */
    public function getRechtshelfsbelehrung()
    {
        return $this->rechtshelfsbelehrung;
    }

    /**
     * Sets a new rechtshelfsbelehrung
     *
     * Hier werden die Rechtshelfsbelehrungen zum Bescheid gegeben.
     *
     * @param string[] $rechtshelfsbelehrung
     * @return self
     */
    public function setRechtshelfsbelehrung(array $rechtshelfsbelehrung)
    {
        $this->rechtshelfsbelehrung = $rechtshelfsbelehrung;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * Hier werden Angaben zu Zuschlägen bei Fristversäumnis eingetragen.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToInformationVerspaetungszuschlag($textabsatz)
    {
        $this->informationVerspaetungszuschlag[] = $textabsatz;
        return $this;
    }

    /**
     * isset informationVerspaetungszuschlag
     *
     * Hier werden Angaben zu Zuschlägen bei Fristversäumnis eingetragen.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetInformationVerspaetungszuschlag($index)
    {
        return isset($this->informationVerspaetungszuschlag[$index]);
    }

    /**
     * unset informationVerspaetungszuschlag
     *
     * Hier werden Angaben zu Zuschlägen bei Fristversäumnis eingetragen.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetInformationVerspaetungszuschlag($index)
    {
        unset($this->informationVerspaetungszuschlag[$index]);
    }

    /**
     * Gets as informationVerspaetungszuschlag
     *
     * Hier werden Angaben zu Zuschlägen bei Fristversäumnis eingetragen.
     *
     * @return string[]
     */
    public function getInformationVerspaetungszuschlag()
    {
        return $this->informationVerspaetungszuschlag;
    }

    /**
     * Sets a new informationVerspaetungszuschlag
     *
     * Hier werden Angaben zu Zuschlägen bei Fristversäumnis eingetragen.
     *
     * @param string[] $informationVerspaetungszuschlag
     * @return self
     */
    public function setInformationVerspaetungszuschlag(?array $informationVerspaetungszuschlag = null)
    {
        $this->informationVerspaetungszuschlag = $informationVerspaetungszuschlag;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Mit diesem Element kann der Gebührenbescheid als PDF-Datei übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType $anlage
     */
    public function addToAnlage(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType $anlage)
    {
        $this->anlage[] = $anlage;
        return $this;
    }

    /**
     * isset anlage
     *
     * Mit diesem Element kann der Gebührenbescheid als PDF-Datei übermittelt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnlage($index)
    {
        return isset($this->anlage[$index]);
    }

    /**
     * unset anlage
     *
     * Mit diesem Element kann der Gebührenbescheid als PDF-Datei übermittelt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnlage($index)
    {
        unset($this->anlage[$index]);
    }

    /**
     * Gets as anlage
     *
     * Mit diesem Element kann der Gebührenbescheid als PDF-Datei übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType[]
     */
    public function getAnlage()
    {
        return $this->anlage;
    }

    /**
     * Sets a new anlage
     *
     * Mit diesem Element kann der Gebührenbescheid als PDF-Datei übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType[] $anlage
     * @return self
     */
    public function setAnlage(?array $anlage = null)
    {
        $this->anlage = $anlage;
        return $this;
    }
}

