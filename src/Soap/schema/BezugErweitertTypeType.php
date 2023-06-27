<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BezugErweitertTypeType
 *
 * Dieser Typ erweitert den Typ xbau:Bezug um Angaben zu einem API-Key und einer anwendungsspezifischen Erweiterung.
 * XSD Type: BezugErweitertType
 */
class BezugErweitertTypeType extends BezugTypeType
{
    /**
     * Dieses Element kann verwendet werden, um die behördliche Fachanwendung an das Antragsportal oder die Kollaborationsplattform anzubinden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnbindungFachverfahrenTypeType $anbindungFachverfahren
     */
    private $anbindungFachverfahren = null;

    /**
     * Gets as anbindungFachverfahren
     *
     * Dieses Element kann verwendet werden, um die behördliche Fachanwendung an das Antragsportal oder die Kollaborationsplattform anzubinden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnbindungFachverfahrenTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnbindungFachverfahrenTypeType $anbindungFachverfahren
     * @return self
     */
    public function setAnbindungFachverfahren(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnbindungFachverfahrenTypeType $anbindungFachverfahren = null)
    {
        $this->anbindungFachverfahren = $anbindungFachverfahren;
        return $this;
    }
}

