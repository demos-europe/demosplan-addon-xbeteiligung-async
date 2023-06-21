<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AnschriftTypeType
 *
 * Eine Anschrift beschreibt einen Ort mit den klassischen Ordnungsbegriffen wie Orts- und Straßennamen sowie ergänzenden Informationen wie Ortsteil und Postfach. Diese Klasse ist von der XÖV-Kernkomponente Anschrift abgeleitet. Sie verwendet ein ergänztes Element strasseHausnummer.
 * XSD Type: AnschriftType
 */
class AnschriftTypeType
{
    /**
     * Dieses Element bildet den Namen (die Bezeichnung) der Straße ab.
     *
     * @var string $strasse
     */
    private $strasse = null;

    /**
     * Dieses Element nimmt die Hausnummer auf (einschließlich eines möglichen Buchstabens, der zur Hausnummer gehört, wie z.B. 39b).
     *
     * @var string $hausnummer
     */
    private $hausnummer = null;

    /**
     * Element für die Erfassung von Straße und Hausnummer als undifferenzierte Zeichenkette (nur für bestimmte Kontexte: ersetzt dann die Verwendung der Elemente strasse und hausnummer). Falls sich in einem Kontext der Übernahme von Anschriftsdaten (z.B. bei Verwendung der eID-Funktion des maschinenlesbaren Personalausweises für die Erfassung der Daten des Antragstellers) die Bezeichnung der Straße und die Hausnummer nicht maschinell zuverlässig trennen lassen, können diese Informationen in das Element als eine gemeinsame Zeichenkette übernommen werden.
     *
     * @var string $strasseHausnummer
     */
    private $strasseHausnummer = null;

    /**
     * Ein Postfach (oft Postfachnummer) ist ein Schlüssel zur Identifikation eines Postfaches in einer Postfiliale.
     *
     * @var string $postfach
     */
    private $postfach = null;

    /**
     * Eine Postleitzahl ist eine Angabe, um postalische Zustellgebiete unabhängig von Gebietskörperschaften (Gemeinde, Kreis) zu bezeichnen.
     *
     * @var string $postleitzahl
     */
    private $postleitzahl = null;

    /**
     * Dieses Element enthält den Namen des Ortes (Gemeinde, Ortschaft oder Stadt), zu der die Anschrift gehört.
     *
     * @var string $ort
     */
    private $ort = null;

    /**
     * Der Ortsteil umfasst ein Teilgebiet des Ortes (der Gemeinde) und dient der Untergliederung.
     *
     * @var string $ortsteil
     */
    private $ortsteil = null;

    /**
     * Die Komponente "wohnungsgeber" enthält Angaben (Name/Bezeichnung) zum Hauptmieter oder Eigentümer einer Immobilie. Die Angabe eines Wohnungsgebers im Kontext der Anschrift dient der genaueren oder leichteren Adressierung. Sie darf nicht genutzt werden um Mietverhältnisse oder ähnliche rechtliche Beziehungen zwischen Personen auszudrücken. Beispiel: bei Meyer
     *
     * @var string $wohnungsgeber
     */
    private $wohnungsgeber = null;

    /**
     * Ein Anschriftenzusatz beinhaltet ggf. erforderliche weitere Präzisierungen zu einer Anschrift. Beispiele: Hinterhof, 3. Aufgang, Haus A, 3. Stock, Appartement 25a, 3. Stock - Appartement 25 a, #325a, Raum 77
     *
     * @var string $zusatz
     */
    private $zusatz = null;

    /**
     * Der Staat, dem die Anschrift postalisch zugeordnet wird.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StaatTypeType $staat
     */
    private $staat = null;

    /**
     * Gets as strasse
     *
     * Dieses Element bildet den Namen (die Bezeichnung) der Straße ab.
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
     * Dieses Element bildet den Namen (die Bezeichnung) der Straße ab.
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
     * Gets as hausnummer
     *
     * Dieses Element nimmt die Hausnummer auf (einschließlich eines möglichen Buchstabens, der zur Hausnummer gehört, wie z.B. 39b).
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
     * Dieses Element nimmt die Hausnummer auf (einschließlich eines möglichen Buchstabens, der zur Hausnummer gehört, wie z.B. 39b).
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
     * Gets as strasseHausnummer
     *
     * Element für die Erfassung von Straße und Hausnummer als undifferenzierte Zeichenkette (nur für bestimmte Kontexte: ersetzt dann die Verwendung der Elemente strasse und hausnummer). Falls sich in einem Kontext der Übernahme von Anschriftsdaten (z.B. bei Verwendung der eID-Funktion des maschinenlesbaren Personalausweises für die Erfassung der Daten des Antragstellers) die Bezeichnung der Straße und die Hausnummer nicht maschinell zuverlässig trennen lassen, können diese Informationen in das Element als eine gemeinsame Zeichenkette übernommen werden.
     *
     * @return string
     */
    public function getStrasseHausnummer()
    {
        return $this->strasseHausnummer;
    }

    /**
     * Sets a new strasseHausnummer
     *
     * Element für die Erfassung von Straße und Hausnummer als undifferenzierte Zeichenkette (nur für bestimmte Kontexte: ersetzt dann die Verwendung der Elemente strasse und hausnummer). Falls sich in einem Kontext der Übernahme von Anschriftsdaten (z.B. bei Verwendung der eID-Funktion des maschinenlesbaren Personalausweises für die Erfassung der Daten des Antragstellers) die Bezeichnung der Straße und die Hausnummer nicht maschinell zuverlässig trennen lassen, können diese Informationen in das Element als eine gemeinsame Zeichenkette übernommen werden.
     *
     * @param string $strasseHausnummer
     * @return self
     */
    public function setStrasseHausnummer($strasseHausnummer)
    {
        $this->strasseHausnummer = $strasseHausnummer;
        return $this;
    }

    /**
     * Gets as postfach
     *
     * Ein Postfach (oft Postfachnummer) ist ein Schlüssel zur Identifikation eines Postfaches in einer Postfiliale.
     *
     * @return string
     */
    public function getPostfach()
    {
        return $this->postfach;
    }

    /**
     * Sets a new postfach
     *
     * Ein Postfach (oft Postfachnummer) ist ein Schlüssel zur Identifikation eines Postfaches in einer Postfiliale.
     *
     * @param string $postfach
     * @return self
     */
    public function setPostfach($postfach)
    {
        $this->postfach = $postfach;
        return $this;
    }

    /**
     * Gets as postleitzahl
     *
     * Eine Postleitzahl ist eine Angabe, um postalische Zustellgebiete unabhängig von Gebietskörperschaften (Gemeinde, Kreis) zu bezeichnen.
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
     * Eine Postleitzahl ist eine Angabe, um postalische Zustellgebiete unabhängig von Gebietskörperschaften (Gemeinde, Kreis) zu bezeichnen.
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
     * Gets as ort
     *
     * Dieses Element enthält den Namen des Ortes (Gemeinde, Ortschaft oder Stadt), zu der die Anschrift gehört.
     *
     * @return string
     */
    public function getOrt()
    {
        return $this->ort;
    }

    /**
     * Sets a new ort
     *
     * Dieses Element enthält den Namen des Ortes (Gemeinde, Ortschaft oder Stadt), zu der die Anschrift gehört.
     *
     * @param string $ort
     * @return self
     */
    public function setOrt($ort)
    {
        $this->ort = $ort;
        return $this;
    }

    /**
     * Gets as ortsteil
     *
     * Der Ortsteil umfasst ein Teilgebiet des Ortes (der Gemeinde) und dient der Untergliederung.
     *
     * @return string
     */
    public function getOrtsteil()
    {
        return $this->ortsteil;
    }

    /**
     * Sets a new ortsteil
     *
     * Der Ortsteil umfasst ein Teilgebiet des Ortes (der Gemeinde) und dient der Untergliederung.
     *
     * @param string $ortsteil
     * @return self
     */
    public function setOrtsteil($ortsteil)
    {
        $this->ortsteil = $ortsteil;
        return $this;
    }

    /**
     * Gets as wohnungsgeber
     *
     * Die Komponente "wohnungsgeber" enthält Angaben (Name/Bezeichnung) zum Hauptmieter oder Eigentümer einer Immobilie. Die Angabe eines Wohnungsgebers im Kontext der Anschrift dient der genaueren oder leichteren Adressierung. Sie darf nicht genutzt werden um Mietverhältnisse oder ähnliche rechtliche Beziehungen zwischen Personen auszudrücken. Beispiel: bei Meyer
     *
     * @return string
     */
    public function getWohnungsgeber()
    {
        return $this->wohnungsgeber;
    }

    /**
     * Sets a new wohnungsgeber
     *
     * Die Komponente "wohnungsgeber" enthält Angaben (Name/Bezeichnung) zum Hauptmieter oder Eigentümer einer Immobilie. Die Angabe eines Wohnungsgebers im Kontext der Anschrift dient der genaueren oder leichteren Adressierung. Sie darf nicht genutzt werden um Mietverhältnisse oder ähnliche rechtliche Beziehungen zwischen Personen auszudrücken. Beispiel: bei Meyer
     *
     * @param string $wohnungsgeber
     * @return self
     */
    public function setWohnungsgeber($wohnungsgeber)
    {
        $this->wohnungsgeber = $wohnungsgeber;
        return $this;
    }

    /**
     * Gets as zusatz
     *
     * Ein Anschriftenzusatz beinhaltet ggf. erforderliche weitere Präzisierungen zu einer Anschrift. Beispiele: Hinterhof, 3. Aufgang, Haus A, 3. Stock, Appartement 25a, 3. Stock - Appartement 25 a, #325a, Raum 77
     *
     * @return string
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * Sets a new zusatz
     *
     * Ein Anschriftenzusatz beinhaltet ggf. erforderliche weitere Präzisierungen zu einer Anschrift. Beispiele: Hinterhof, 3. Aufgang, Haus A, 3. Stock, Appartement 25a, 3. Stock - Appartement 25 a, #325a, Raum 77
     *
     * @param string $zusatz
     * @return self
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = $zusatz;
        return $this;
    }

    /**
     * Gets as staat
     *
     * Der Staat, dem die Anschrift postalisch zugeordnet wird.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StaatTypeType
     */
    public function getStaat()
    {
        return $this->staat;
    }

    /**
     * Sets a new staat
     *
     * Der Staat, dem die Anschrift postalisch zugeordnet wird.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StaatTypeType $staat
     * @return self
     */
    public function setStaat(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StaatTypeType $staat = null)
    {
        $this->staat = $staat;
        return $this;
    }
}

