<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungAsynchron0010;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GRueckweisungType;

/**
 * Class representing RueckweisungAsynchron0010AnonymousPHPType
 */
class RueckweisungAsynchron0010AnonymousPHPType extends NachrichtG2GRueckweisungType
{
    /**
     * Inhalte der Rückweisungsnachricht
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronType $rueckweisung
     */
    private $rueckweisung = null;

    /**
     * Gets as rueckweisung
     *
     * Inhalte der Rückweisungsnachricht
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronType
     */
    public function getRueckweisung()
    {
        return $this->rueckweisung;
    }

    /**
     * Sets a new rueckweisung
     *
     * Inhalte der Rückweisungsnachricht
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronType $rueckweisung
     * @return self
     */
    public function setRueckweisung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronType $rueckweisung)
    {
        $this->rueckweisung = $rueckweisung;
        return $this;
    }
}

