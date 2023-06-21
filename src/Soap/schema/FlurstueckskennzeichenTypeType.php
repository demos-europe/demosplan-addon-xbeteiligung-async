<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing FlurstueckskennzeichenTypeType
 *
 * Typ zur eindeutigen Identifikation eines Flurstücks. Ein Flurstück ist ein amtlich vermessener Teil der Erdoberfläche. Das Liegenschaftskataster ordnet jedem Flurstück des jeweiligen Nummerierungsbezirks (Flur oder Gemarkung) eine Flurstücksnummer zu.
 * XSD Type: FlurstueckskennzeichenType
 */
class FlurstueckskennzeichenTypeType
{
    /**
     * Hier wird das Bundesland genannt, in dem sich das Flurstück befindet.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandTypeType $bundesland
     */
    private $bundesland = null;

    /**
     * Hier sind die identifzierenden Angaben zum Flurstück gemäß Systematik des regionalen Liegenschaftskatasters einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KodierungFlurstueckTypeType $kodierungFlurstueck
     */
    private $kodierungFlurstueck = null;

    /**
     * Gets as bundesland
     *
     * Hier wird das Bundesland genannt, in dem sich das Flurstück befindet.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandTypeType
     */
    public function getBundesland()
    {
        return $this->bundesland;
    }

    /**
     * Sets a new bundesland
     *
     * Hier wird das Bundesland genannt, in dem sich das Flurstück befindet.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandTypeType $bundesland
     * @return self
     */
    public function setBundesland(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBundeslandTypeType $bundesland)
    {
        $this->bundesland = $bundesland;
        return $this;
    }

    /**
     * Gets as kodierungFlurstueck
     *
     * Hier sind die identifzierenden Angaben zum Flurstück gemäß Systematik des regionalen Liegenschaftskatasters einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KodierungFlurstueckTypeType
     */
    public function getKodierungFlurstueck()
    {
        return $this->kodierungFlurstueck;
    }

    /**
     * Sets a new kodierungFlurstueck
     *
     * Hier sind die identifzierenden Angaben zum Flurstück gemäß Systematik des regionalen Liegenschaftskatasters einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KodierungFlurstueckTypeType $kodierungFlurstueck
     * @return self
     */
    public function setKodierungFlurstueck(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KodierungFlurstueckTypeType $kodierungFlurstueck)
    {
        $this->kodierungFlurstueck = $kodierungFlurstueck;
        return $this;
    }
}

