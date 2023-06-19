<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RueckweisungGrundSpezifischType
 *
 * In diesem Datentyp wird ein Grund für die Rückweisung der Nachricht in Form eines beliebigen kontextspezifischen Fehlercodes (bspw. aus dem BZSt-Fehlerkatalog) oder einer Fehlerbeschreibung genauer spezifiziert. Sofern in einem (fachlichen) Prozess kontextspezifische Fehlercodes verwendet werden, ist dies durch das xinneres-fachmodul in der Prozessbeschreibung vorzugeben und die zu verwendende Schlüsseltabelle festzulegen.
 * XSD Type: Rueckweisung.GrundSpezifisch
 */
class RueckweisungGrundSpezifischType
{
    /**
     * In diesem Element wird ein Fehlercode übermittelt. Die zugrundeliegende Schlüsseltabelle muss mit der listURI (bspw. urn:de:bund:bzst:schluessel:rts.fehlercodes) und listVersionID (bspw. 2016-01-19) identifiziert werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeSpezifischType $fehlercode
     */
    private $fehlercode = null;

    /**
     * Alternativ oder ergänzend zu dem übermittelten Fehlercode können hier weitere Hinweise übermittelt werden, die der Klärung des Sachverhalts dienen.
     *
     * @var string $fehlerbeschreibung
     */
    private $fehlerbeschreibung = null;

    /**
     * Gets as fehlercode
     *
     * In diesem Element wird ein Fehlercode übermittelt. Die zugrundeliegende Schlüsseltabelle muss mit der listURI (bspw. urn:de:bund:bzst:schluessel:rts.fehlercodes) und listVersionID (bspw. 2016-01-19) identifiziert werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeSpezifischType
     */
    public function getFehlercode()
    {
        return $this->fehlercode;
    }

    /**
     * Sets a new fehlercode
     *
     * In diesem Element wird ein Fehlercode übermittelt. Die zugrundeliegende Schlüsseltabelle muss mit der listURI (bspw. urn:de:bund:bzst:schluessel:rts.fehlercodes) und listVersionID (bspw. 2016-01-19) identifiziert werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeSpezifischType $fehlercode
     * @return self
     */
    public function setFehlercode(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeSpezifischType $fehlercode = null)
    {
        $this->fehlercode = $fehlercode;
        return $this;
    }

    /**
     * Gets as fehlerbeschreibung
     *
     * Alternativ oder ergänzend zu dem übermittelten Fehlercode können hier weitere Hinweise übermittelt werden, die der Klärung des Sachverhalts dienen.
     *
     * @return string
     */
    public function getFehlerbeschreibung()
    {
        return $this->fehlerbeschreibung;
    }

    /**
     * Sets a new fehlerbeschreibung
     *
     * Alternativ oder ergänzend zu dem übermittelten Fehlercode können hier weitere Hinweise übermittelt werden, die der Klärung des Sachverhalts dienen.
     *
     * @param string $fehlerbeschreibung
     * @return self
     */
    public function setFehlerbeschreibung($fehlerbeschreibung)
    {
        $this->fehlerbeschreibung = $fehlerbeschreibung;
        return $this;
    }
}

