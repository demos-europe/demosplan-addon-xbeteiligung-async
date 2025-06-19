<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing BezugErweitertType
 *
 * Dieser Typ erweitert den Typ xbau:Bezug um Angaben zu einem API-Key und einer anwendungsspezifischen Erweiterung.
 * XSD Type: BezugErweitert
 */
class BezugErweitertType extends BezugType
{
    /**
     * Dieses Element kann verwendet werden, um die behördliche Fachanwendung an das Antragsportal oder die Kollaborationsplattform anzubinden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnbindungFachverfahrenType $anbindungFachverfahren
     */
    private $anbindungFachverfahren = null;

    /**
     * Gets as anbindungFachverfahren
     *
     * Dieses Element kann verwendet werden, um die behördliche Fachanwendung an das Antragsportal oder die Kollaborationsplattform anzubinden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnbindungFachverfahrenType
     */
    public function getAnbindungFachverfahren()
    {
        return $this->anbindungFachverfahren;
    }

    /**
     * Sets a new anbindungFachverfahren
     *
     * Dieses Element kann verwendet werden, um die behördliche Fachanwendung an das Antragsportal oder die Kollaborationsplattform anzubinden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnbindungFachverfahrenType $anbindungFachverfahren
     * @return self
     */
    public function setAnbindungFachverfahren(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnbindungFachverfahrenType $anbindungFachverfahren = null)
    {
        $this->anbindungFachverfahren = $anbindungFachverfahren;
        return $this;
    }
}

