<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing ZahlungsdatenType
 *
 * Dieser Typ enthält die Angaben, die für die bargeldlose Bezahlung gemäß SEPA benötigt werden.
 * XSD Type: Zahlungsdaten
 */
class ZahlungsdatenType
{
    /**
     * IBAN des Kontos, auf das die Gebühr einzuzahlen ist.
     *
     * @var string $iban
     */
    private $iban = null;

    /**
     * Angabe zur Bank (BIC), bei der das Konto eingerichtet ist, auf das die Gebühr einzuzahlen ist. Für Überweisungen des Anwenders aus dem Ausland.
     *
     * @var string $bic
     */
    private $bic = null;

    /**
     * Hier ist das Kassenzeichen einzutragen, unter dem die Sollstellung durch die Behörde bei der lokalen Finanzverwaltung veranlasst wurde. Ist vom Gebührenzahler bei der Zahlung im Überweisungsformular in das Feld "Verwendungszweck" einzutragen.
     *
     * @var string $verwendungszweck
     */
    private $verwendungszweck = null;

    /**
     * Gets as iban
     *
     * IBAN des Kontos, auf das die Gebühr einzuzahlen ist.
     *
     * @return string
     */
    public function getIban()
    {
        return $this->iban;
    }

    /**
     * Sets a new iban
     *
     * IBAN des Kontos, auf das die Gebühr einzuzahlen ist.
     *
     * @param string $iban
     * @return self
     */
    public function setIban($iban)
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * Gets as bic
     *
     * Angabe zur Bank (BIC), bei der das Konto eingerichtet ist, auf das die Gebühr einzuzahlen ist. Für Überweisungen des Anwenders aus dem Ausland.
     *
     * @return string
     */
    public function getBic()
    {
        return $this->bic;
    }

    /**
     * Sets a new bic
     *
     * Angabe zur Bank (BIC), bei der das Konto eingerichtet ist, auf das die Gebühr einzuzahlen ist. Für Überweisungen des Anwenders aus dem Ausland.
     *
     * @param string $bic
     * @return self
     */
    public function setBic($bic)
    {
        $this->bic = $bic;
        return $this;
    }

    /**
     * Gets as verwendungszweck
     *
     * Hier ist das Kassenzeichen einzutragen, unter dem die Sollstellung durch die Behörde bei der lokalen Finanzverwaltung veranlasst wurde. Ist vom Gebührenzahler bei der Zahlung im Überweisungsformular in das Feld "Verwendungszweck" einzutragen.
     *
     * @return string
     */
    public function getVerwendungszweck()
    {
        return $this->verwendungszweck;
    }

    /**
     * Sets a new verwendungszweck
     *
     * Hier ist das Kassenzeichen einzutragen, unter dem die Sollstellung durch die Behörde bei der lokalen Finanzverwaltung veranlasst wurde. Ist vom Gebührenzahler bei der Zahlung im Überweisungsformular in das Feld "Verwendungszweck" einzutragen.
     *
     * @param string $verwendungszweck
     * @return self
     */
    public function setVerwendungszweck($verwendungszweck)
    {
        $this->verwendungszweck = $verwendungszweck;
        return $this;
    }
}

