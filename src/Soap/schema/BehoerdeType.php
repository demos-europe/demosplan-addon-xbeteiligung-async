<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BehoerdeType
 *
 * Dieser Typ enthält Angaben über den Namen und die Erreichbarkeit einer Behörde. Eine Behörde im Sinne des Verwaltungsverfahrensgesetzes ist jede Stelle, die Aufgaben der öffentlichen Verwaltung wahrnimmt.
 * XSD Type: Behoerde
 */
class BehoerdeType
{
    /**
     * Es wird die eindeutige Behördenkennung angegeben, über die die Behörde im DVDV ermittelt werden kann. Diese hier übermittelte Behördenkennung muss es dem Leser einer Nachricht ermöglichen, den Autor einer Nachricht im DVDV zu ermitteln um diesem ggf. erforderliche elektronische Mitteilungen senden zu können (bspw. Quittungen oder Fehlernachrichten).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdenkennungType $behoerdenkennung
     */
    private $behoerdenkennung = null;

    /**
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType[] $erreichbarkeit
     */
    private $erreichbarkeit = [
        
    ];

    /**
     * Die Anschrift dieser Behörde (für persönliches Erscheinen oder die Zusendung von Dokumenten per Briefpost an die Behörde).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftType $anschrift
     */
    private $anschrift = null;

    /**
     * Es ist der Name der Behörde zu übermitteln. Er dient auch dazu, eine ggfs. erforderliche manuelle Klärung zu beschleunigen, indem bspw. der Autor einer Nachricht im Klartext übermittelt, an welche Behörde er die Nachricht schicken wollte.
     *
     * @var string $behoerdenname
     */
    private $behoerdenname = null;

    /**
     * Gets as behoerdenkennung
     *
     * Es wird die eindeutige Behördenkennung angegeben, über die die Behörde im DVDV ermittelt werden kann. Diese hier übermittelte Behördenkennung muss es dem Leser einer Nachricht ermöglichen, den Autor einer Nachricht im DVDV zu ermitteln um diesem ggf. erforderliche elektronische Mitteilungen senden zu können (bspw. Quittungen oder Fehlernachrichten).
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdenkennungType
     */
    public function getBehoerdenkennung()
    {
        return $this->behoerdenkennung;
    }

    /**
     * Sets a new behoerdenkennung
     *
     * Es wird die eindeutige Behördenkennung angegeben, über die die Behörde im DVDV ermittelt werden kann. Diese hier übermittelte Behördenkennung muss es dem Leser einer Nachricht ermöglichen, den Autor einer Nachricht im DVDV zu ermitteln um diesem ggf. erforderliche elektronische Mitteilungen senden zu können (bspw. Quittungen oder Fehlernachrichten).
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdenkennungType $behoerdenkennung
     * @return self
     */
    public function setBehoerdenkennung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdenkennungType $behoerdenkennung)
    {
        $this->behoerdenkennung = $behoerdenkennung;
        return $this;
    }

    /**
     * Adds as erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType $erreichbarkeit
     */
    public function addToErreichbarkeit(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType $erreichbarkeit)
    {
        $this->erreichbarkeit[] = $erreichbarkeit;
        return $this;
    }

    /**
     * isset erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetErreichbarkeit($index)
    {
        return isset($this->erreichbarkeit[$index]);
    }

    /**
     * unset erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetErreichbarkeit($index)
    {
        unset($this->erreichbarkeit[$index]);
    }

    /**
     * Gets as erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType[]
     */
    public function getErreichbarkeit()
    {
        return $this->erreichbarkeit;
    }

    /**
     * Sets a new erreichbarkeit
     *
     * Angaben zur Erreichbarkeit dieser Behörde per Telefon, Telefax, E-Mail etc.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationType[] $erreichbarkeit
     * @return self
     */
    public function setErreichbarkeit(array $erreichbarkeit = null)
    {
        $this->erreichbarkeit = $erreichbarkeit;
        return $this;
    }

    /**
     * Gets as anschrift
     *
     * Die Anschrift dieser Behörde (für persönliches Erscheinen oder die Zusendung von Dokumenten per Briefpost an die Behörde).
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftType
     */
    public function getAnschrift()
    {
        return $this->anschrift;
    }

    /**
     * Sets a new anschrift
     *
     * Die Anschrift dieser Behörde (für persönliches Erscheinen oder die Zusendung von Dokumenten per Briefpost an die Behörde).
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftType $anschrift
     * @return self
     */
    public function setAnschrift(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftType $anschrift = null)
    {
        $this->anschrift = $anschrift;
        return $this;
    }

    /**
     * Gets as behoerdenname
     *
     * Es ist der Name der Behörde zu übermitteln. Er dient auch dazu, eine ggfs. erforderliche manuelle Klärung zu beschleunigen, indem bspw. der Autor einer Nachricht im Klartext übermittelt, an welche Behörde er die Nachricht schicken wollte.
     *
     * @return string
     */
    public function getBehoerdenname()
    {
        return $this->behoerdenname;
    }

    /**
     * Sets a new behoerdenname
     *
     * Es ist der Name der Behörde zu übermitteln. Er dient auch dazu, eine ggfs. erforderliche manuelle Klärung zu beschleunigen, indem bspw. der Autor einer Nachricht im Klartext übermittelt, an welche Behörde er die Nachricht schicken wollte.
     *
     * @param string $behoerdenname
     * @return self
     */
    public function setBehoerdenname($behoerdenname)
    {
        $this->behoerdenname = $behoerdenname;
        return $this;
    }
}

