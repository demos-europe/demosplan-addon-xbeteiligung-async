<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungAsynchron0010;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GRueckweisungTypeType;

/**
 * Class representing RueckweisungAsynchron0010AnonymousPHPType
 */
class RueckweisungAsynchron0010AnonymousPHPType extends NachrichtG2GRueckweisungTypeType
{
    /**
     * Inhalte der Rückweisungsnachricht
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronTypeType $rueckweisung
     */
    private $rueckweisung = null;

    /**
     * Gets as rueckweisung
     *
     * Inhalte der Rückweisungsnachricht
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronTypeType $rueckweisung
     * @return self
     */
    public function setRueckweisung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTemplateAsynchronTypeType $rueckweisung)
    {
        $this->rueckweisung = $rueckweisung;
        return $this;
    }
}

