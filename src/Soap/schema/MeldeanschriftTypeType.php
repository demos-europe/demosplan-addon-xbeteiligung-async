<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing MeldeanschriftTypeType
 *
 * Dieser Datentyp repräsentiert die gemeinsamen fachlichen Vorgaben der drei Standardisierungsbereiche Meldewesen, Ausländerwesen und Personenstandswesen für eine inländische Meldeanschrift auf der Grundlage des DSMeld. Hinweis zu Hausnummernbereichen: Der DSMeld kennt keine Hausnummernbereiche. In diesen Fällen ist nur das erste Element des Hausnummernbereichs im Feld hausnummer einzutragen. Das zweite Element des Hausnummernbereichs kann in diesem Datentyp nicht übermittelt werden.
 * XSD Type: MeldeanschriftType
 */
class MeldeanschriftTypeType
{
    /**
     * Es ist der vom Statistischen Bundesamt herausgegebene bundeseinheitliche Gemeindeschlüssel der Gemeinde anzugeben, in der die Wohnung liegt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeVZAmtlicherGemeindeschluesselTypeType $gemeindeschluessel
     */
    private $gemeindeschluessel = null;

    /**
     * Es sind nur die Ziffern einer Hausnummer anzugeben.
     *
     * @var string $hausnummer
     */
    private $hausnummer = null;

    /**
     * Es sind die Buchstaben oder die Zusatzziffern zur Hausnummer gemäß der amtlichen Festlegung der Gemeinde zur Hausnummer anzugeben. Beispiel: 124 a, 124 A, 109.5, 135.44, 116/1
     *
     * @var string $hausnummerBuchstabeZusatzziffer
     */
    private $hausnummerBuchstabeZusatzziffer = null;

    /**
     * Es ist die Postleitzahl anzugeben.
     *
     * @var string $postleitzahl
     */
    private $postleitzahl = null;

    /**
     * Es können Stockwerks- oder Wohnungsnummern angegeben werden, soweit sie für die Adressierung erforderlich sind. Beispiele: 7OG, 13OG, P für Parterre, HP für Hochparterre, St für Souterrain oder (Wohnung) 115.
     *
     * @var string $stockwerkswohnungsnummer
     */
    private $stockwerkswohnungsnummer = null;

    /**
     * Es ist die Bezeichnung der Straße anzugeben. Die Feldlänge ist auf 55 Zeichen beschränkt. Bei Überschreitung einer Länge von 25 Zeichen darf sinnvoll abgekürzt werden. Ist keine Straßenbezeichnung - wohl aber eine Hausnummer - vorhanden, so ist die Zeichenkette Hausnummer anzugeben. Sind weder Straßenbezeichnung noch Hausnummer vorhanden, so ist die Zeichenkette ohne Hausnummer anzugeben.
     *
     * @var string $strasse
     */
    private $strasse = null;

    /**
     * Es sind Teilnummern zur Hausnummer anzugeben. Beispiel: 16 1/7
     *
     * @var string $teilnummerDerHausnummer
     */
    private $teilnummerDerHausnummer = null;

    /**
     * Es ist die postalische Wohnortsbezeichnung anzugeben. Die Feldlänge ist auf 40 Zeichen beschränkt.
     *
     * @var string $wohnort
     */
    private $wohnort = null;

    /**
     * Es ist der frühere Gemeindename anzugeben, der als Stadt- bzw. Ortsteilname dem jetzigen Gemeindenamen hinzugefügt werden kann.Der frühere Gemeindename (jetziger Ortsteil- oder Stadtteilname) ist bei Adressierungen unterhalb des Namens (oberhalb der Straßenbezeichnung) anzugeben.Beispiel: Frau Rita Scholl Zuffenhausen Am Stadtpark 12 70123 Stuttgart Die Feldlänge ist auf 40 Zeichen beschränkt.
     *
     * @var string $wohnortFruehererGemeindename
     */
    private $wohnortFruehererGemeindename = null;

    /**
     * In diesem Element ist der Hauptmieter oder Eigentümer der Wohnung anzugeben, soweit dies für die Adressierung erforderlich ist. Bei Überschreitung einer Länge von 26 Zeichen darf sinnvoll abgekürzt werden.
     *
     * @var string $wohnungsinhaber
     */
    private $wohnungsinhaber = null;

    /**
     * Es sind Zusatzangaben zur Anschrift anzugeben. Beispiele: Hinterhaus, Gartenhaus. Bei Überschreitung einer Länge von 21 Zeichen darf sinnvoll abgekürzt werden.
     *
     * @var string $zusatzangaben
     */
    private $zusatzangaben = null;

    /**
     * Gets as gemeindeschluessel
     *
     * Es ist der vom Statistischen Bundesamt herausgegebene bundeseinheitliche Gemeindeschlüssel der Gemeinde anzugeben, in der die Wohnung liegt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeVZAmtlicherGemeindeschluesselTypeType
     */
    public function getGemeindeschluessel()
    {
        return $this->gemeindeschluessel;
    }

    /**
     * Sets a new gemeindeschluessel
     *
     * Es ist der vom Statistischen Bundesamt herausgegebene bundeseinheitliche Gemeindeschlüssel der Gemeinde anzugeben, in der die Wohnung liegt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeVZAmtlicherGemeindeschluesselTypeType $gemeindeschluessel
     * @return self
     */
    public function setGemeindeschluessel(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeVZAmtlicherGemeindeschluesselTypeType $gemeindeschluessel = null)
    {
        $this->gemeindeschluessel = $gemeindeschluessel;
        return $this;
    }

    /**
     * Gets as hausnummer
     *
     * Es sind nur die Ziffern einer Hausnummer anzugeben.
     *
     * @return string
     */
    public function getHausnummer()
    {
        return $this->hausnummer;
    }

    /**
     * Sets a new hausnummer
     *
     * Es sind nur die Ziffern einer Hausnummer anzugeben.
     *
     * @param string $hausnummer
     * @return self
     */
    public function setHausnummer($hausnummer)
    {
        $this->hausnummer = $hausnummer;
        return $this;
    }

    /**
     * Gets as hausnummerBuchstabeZusatzziffer
     *
     * Es sind die Buchstaben oder die Zusatzziffern zur Hausnummer gemäß der amtlichen Festlegung der Gemeinde zur Hausnummer anzugeben. Beispiel: 124 a, 124 A, 109.5, 135.44, 116/1
     *
     * @return string
     */
    public function getHausnummerBuchstabeZusatzziffer()
    {
        return $this->hausnummerBuchstabeZusatzziffer;
    }

    /**
     * Sets a new hausnummerBuchstabeZusatzziffer
     *
     * Es sind die Buchstaben oder die Zusatzziffern zur Hausnummer gemäß der amtlichen Festlegung der Gemeinde zur Hausnummer anzugeben. Beispiel: 124 a, 124 A, 109.5, 135.44, 116/1
     *
     * @param string $hausnummerBuchstabeZusatzziffer
     * @return self
     */
    public function setHausnummerBuchstabeZusatzziffer($hausnummerBuchstabeZusatzziffer)
    {
        $this->hausnummerBuchstabeZusatzziffer = $hausnummerBuchstabeZusatzziffer;
        return $this;
    }

    /**
     * Gets as postleitzahl
     *
     * Es ist die Postleitzahl anzugeben.
     *
     * @return string
     */
    public function getPostleitzahl()
    {
        return $this->postleitzahl;
    }

    /**
     * Sets a new postleitzahl
     *
     * Es ist die Postleitzahl anzugeben.
     *
     * @param string $postleitzahl
     * @return self
     */
    public function setPostleitzahl($postleitzahl)
    {
        $this->postleitzahl = $postleitzahl;
        return $this;
    }

    /**
     * Gets as stockwerkswohnungsnummer
     *
     * Es können Stockwerks- oder Wohnungsnummern angegeben werden, soweit sie für die Adressierung erforderlich sind. Beispiele: 7OG, 13OG, P für Parterre, HP für Hochparterre, St für Souterrain oder (Wohnung) 115.
     *
     * @return string
     */
    public function getStockwerkswohnungsnummer()
    {
        return $this->stockwerkswohnungsnummer;
    }

    /**
     * Sets a new stockwerkswohnungsnummer
     *
     * Es können Stockwerks- oder Wohnungsnummern angegeben werden, soweit sie für die Adressierung erforderlich sind. Beispiele: 7OG, 13OG, P für Parterre, HP für Hochparterre, St für Souterrain oder (Wohnung) 115.
     *
     * @param string $stockwerkswohnungsnummer
     * @return self
     */
    public function setStockwerkswohnungsnummer($stockwerkswohnungsnummer)
    {
        $this->stockwerkswohnungsnummer = $stockwerkswohnungsnummer;
        return $this;
    }

    /**
     * Gets as strasse
     *
     * Es ist die Bezeichnung der Straße anzugeben. Die Feldlänge ist auf 55 Zeichen beschränkt. Bei Überschreitung einer Länge von 25 Zeichen darf sinnvoll abgekürzt werden. Ist keine Straßenbezeichnung - wohl aber eine Hausnummer - vorhanden, so ist die Zeichenkette Hausnummer anzugeben. Sind weder Straßenbezeichnung noch Hausnummer vorhanden, so ist die Zeichenkette ohne Hausnummer anzugeben.
     *
     * @return string
     */
    public function getStrasse()
    {
        return $this->strasse;
    }

    /**
     * Sets a new strasse
     *
     * Es ist die Bezeichnung der Straße anzugeben. Die Feldlänge ist auf 55 Zeichen beschränkt. Bei Überschreitung einer Länge von 25 Zeichen darf sinnvoll abgekürzt werden. Ist keine Straßenbezeichnung - wohl aber eine Hausnummer - vorhanden, so ist die Zeichenkette Hausnummer anzugeben. Sind weder Straßenbezeichnung noch Hausnummer vorhanden, so ist die Zeichenkette ohne Hausnummer anzugeben.
     *
     * @param string $strasse
     * @return self
     */
    public function setStrasse($strasse)
    {
        $this->strasse = $strasse;
        return $this;
    }

    /**
     * Gets as teilnummerDerHausnummer
     *
     * Es sind Teilnummern zur Hausnummer anzugeben. Beispiel: 16 1/7
     *
     * @return string
     */
    public function getTeilnummerDerHausnummer()
    {
        return $this->teilnummerDerHausnummer;
    }

    /**
     * Sets a new teilnummerDerHausnummer
     *
     * Es sind Teilnummern zur Hausnummer anzugeben. Beispiel: 16 1/7
     *
     * @param string $teilnummerDerHausnummer
     * @return self
     */
    public function setTeilnummerDerHausnummer($teilnummerDerHausnummer)
    {
        $this->teilnummerDerHausnummer = $teilnummerDerHausnummer;
        return $this;
    }

    /**
     * Gets as wohnort
     *
     * Es ist die postalische Wohnortsbezeichnung anzugeben. Die Feldlänge ist auf 40 Zeichen beschränkt.
     *
     * @return string
     */
    public function getWohnort()
    {
        return $this->wohnort;
    }

    /**
     * Sets a new wohnort
     *
     * Es ist die postalische Wohnortsbezeichnung anzugeben. Die Feldlänge ist auf 40 Zeichen beschränkt.
     *
     * @param string $wohnort
     * @return self
     */
    public function setWohnort($wohnort)
    {
        $this->wohnort = $wohnort;
        return $this;
    }

    /**
     * Gets as wohnortFruehererGemeindename
     *
     * Es ist der frühere Gemeindename anzugeben, der als Stadt- bzw. Ortsteilname dem jetzigen Gemeindenamen hinzugefügt werden kann.Der frühere Gemeindename (jetziger Ortsteil- oder Stadtteilname) ist bei Adressierungen unterhalb des Namens (oberhalb der Straßenbezeichnung) anzugeben.Beispiel: Frau Rita Scholl Zuffenhausen Am Stadtpark 12 70123 Stuttgart Die Feldlänge ist auf 40 Zeichen beschränkt.
     *
     * @return string
     */
    public function getWohnortFruehererGemeindename()
    {
        return $this->wohnortFruehererGemeindename;
    }

    /**
     * Sets a new wohnortFruehererGemeindename
     *
     * Es ist der frühere Gemeindename anzugeben, der als Stadt- bzw. Ortsteilname dem jetzigen Gemeindenamen hinzugefügt werden kann.Der frühere Gemeindename (jetziger Ortsteil- oder Stadtteilname) ist bei Adressierungen unterhalb des Namens (oberhalb der Straßenbezeichnung) anzugeben.Beispiel: Frau Rita Scholl Zuffenhausen Am Stadtpark 12 70123 Stuttgart Die Feldlänge ist auf 40 Zeichen beschränkt.
     *
     * @param string $wohnortFruehererGemeindename
     * @return self
     */
    public function setWohnortFruehererGemeindename($wohnortFruehererGemeindename)
    {
        $this->wohnortFruehererGemeindename = $wohnortFruehererGemeindename;
        return $this;
    }

    /**
     * Gets as wohnungsinhaber
     *
     * In diesem Element ist der Hauptmieter oder Eigentümer der Wohnung anzugeben, soweit dies für die Adressierung erforderlich ist. Bei Überschreitung einer Länge von 26 Zeichen darf sinnvoll abgekürzt werden.
     *
     * @return string
     */
    public function getWohnungsinhaber()
    {
        return $this->wohnungsinhaber;
    }

    /**
     * Sets a new wohnungsinhaber
     *
     * In diesem Element ist der Hauptmieter oder Eigentümer der Wohnung anzugeben, soweit dies für die Adressierung erforderlich ist. Bei Überschreitung einer Länge von 26 Zeichen darf sinnvoll abgekürzt werden.
     *
     * @param string $wohnungsinhaber
     * @return self
     */
    public function setWohnungsinhaber($wohnungsinhaber)
    {
        $this->wohnungsinhaber = $wohnungsinhaber;
        return $this;
    }

    /**
     * Gets as zusatzangaben
     *
     * Es sind Zusatzangaben zur Anschrift anzugeben. Beispiele: Hinterhaus, Gartenhaus. Bei Überschreitung einer Länge von 21 Zeichen darf sinnvoll abgekürzt werden.
     *
     * @return string
     */
    public function getZusatzangaben()
    {
        return $this->zusatzangaben;
    }

    /**
     * Sets a new zusatzangaben
     *
     * Es sind Zusatzangaben zur Anschrift anzugeben. Beispiele: Hinterhaus, Gartenhaus. Bei Überschreitung einer Länge von 21 Zeichen darf sinnvoll abgekürzt werden.
     *
     * @param string $zusatzangaben
     * @return self
     */
    public function setZusatzangaben($zusatzangaben)
    {
        $this->zusatzangaben = $zusatzangaben;
        return $this;
    }
}

