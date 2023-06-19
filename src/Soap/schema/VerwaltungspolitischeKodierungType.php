<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing VerwaltungspolitischeKodierungType
 *
 * Dieser Typ beinhaltet Daten, die die eindeutige Zuordnung (z.B. eines Grundstücks) innerhalb der Gemeindegliederung der Länder ermöglichen.
 * XSD Type: VerwaltungspolitischeKodierung
 */
class VerwaltungspolitischeKodierungType
{
    /**
     * In dieses Element ist das Bundesland einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandType $bundesland
     */
    private $bundesland = null;

    /**
     * In dieses Element ist der Bezirk bzw. Regierungsbezirk einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBezirkType $bezirk
     */
    private $bezirk = null;

    /**
     * In dieses Element ist der Kreis bzw. Landkreis einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKreisType $kreis
     */
    private $kreis = null;

    /**
     * In dieses Element ist der Amtliche Gemeindeschlüssel einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherGemeindeschluesselType $gemeindeschluessel
     */
    private $gemeindeschluessel = null;

    /**
     * In dieses Element ist der innerhalb des Landes definierte Schlüssel für den Gemeindeteil einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeteilType $gemeindeteil
     */
    private $gemeindeteil = null;

    /**
     * In dieses Element ist ein Regionalschlüssel einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherRegionalschluesselType $regionalschluessel
     */
    private $regionalschluessel = null;

    /**
     * Gets as bundesland
     *
     * In dieses Element ist das Bundesland einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandType
     */
    public function getBundesland()
    {
        return $this->bundesland;
    }

    /**
     * Sets a new bundesland
     *
     * In dieses Element ist das Bundesland einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandType $bundesland
     * @return self
     */
    public function setBundesland(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandType $bundesland = null)
    {
        $this->bundesland = $bundesland;
        return $this;
    }

    /**
     * Gets as bezirk
     *
     * In dieses Element ist der Bezirk bzw. Regierungsbezirk einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBezirkType
     */
    public function getBezirk()
    {
        return $this->bezirk;
    }

    /**
     * Sets a new bezirk
     *
     * In dieses Element ist der Bezirk bzw. Regierungsbezirk einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBezirkType $bezirk
     * @return self
     */
    public function setBezirk(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBezirkType $bezirk = null)
    {
        $this->bezirk = $bezirk;
        return $this;
    }

    /**
     * Gets as kreis
     *
     * In dieses Element ist der Kreis bzw. Landkreis einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKreisType
     */
    public function getKreis()
    {
        return $this->kreis;
    }

    /**
     * Sets a new kreis
     *
     * In dieses Element ist der Kreis bzw. Landkreis einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKreisType $kreis
     * @return self
     */
    public function setKreis(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKreisType $kreis = null)
    {
        $this->kreis = $kreis;
        return $this;
    }

    /**
     * Gets as gemeindeschluessel
     *
     * In dieses Element ist der Amtliche Gemeindeschlüssel einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherGemeindeschluesselType
     */
    public function getGemeindeschluessel()
    {
        return $this->gemeindeschluessel;
    }

    /**
     * Sets a new gemeindeschluessel
     *
     * In dieses Element ist der Amtliche Gemeindeschlüssel einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherGemeindeschluesselType $gemeindeschluessel
     * @return self
     */
    public function setGemeindeschluessel(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherGemeindeschluesselType $gemeindeschluessel)
    {
        $this->gemeindeschluessel = $gemeindeschluessel;
        return $this;
    }

    /**
     * Gets as gemeindeteil
     *
     * In dieses Element ist der innerhalb des Landes definierte Schlüssel für den Gemeindeteil einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeteilType
     */
    public function getGemeindeteil()
    {
        return $this->gemeindeteil;
    }

    /**
     * Sets a new gemeindeteil
     *
     * In dieses Element ist der innerhalb des Landes definierte Schlüssel für den Gemeindeteil einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeteilType $gemeindeteil
     * @return self
     */
    public function setGemeindeteil(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeGemeindeteilType $gemeindeteil = null)
    {
        $this->gemeindeteil = $gemeindeteil;
        return $this;
    }

    /**
     * Gets as regionalschluessel
     *
     * In dieses Element ist ein Regionalschlüssel einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherRegionalschluesselType
     */
    public function getRegionalschluessel()
    {
        return $this->regionalschluessel;
    }

    /**
     * Sets a new regionalschluessel
     *
     * In dieses Element ist ein Regionalschlüssel einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherRegionalschluesselType $regionalschluessel
     * @return self
     */
    public function setRegionalschluessel(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAmtlicherRegionalschluesselType $regionalschluessel = null)
    {
        $this->regionalschluessel = $regionalschluessel;
        return $this;
    }
}

