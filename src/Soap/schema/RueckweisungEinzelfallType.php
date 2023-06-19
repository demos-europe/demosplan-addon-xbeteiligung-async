<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RueckweisungEinzelfallType
 *
 * Mit diesem Typ können Angaben zu einem zurückgewiesenen Einzelfall aus einer Sammelnachricht übermittelt werden.
 * XSD Type: Rueckweisung.Einzelfall
 */
class RueckweisungEinzelfallType
{
    /**
     * Hier werden die Informationen übermittelt, mit denen der Einzelfall in der (Sammel-)Nachricht eindeutig identifiziert werden kann.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $identifikationEreignis
     */
    private $identifikationEreignis = null;

    /**
     * Für jeden Einzelfall sind hier die Gründe zu übermitteln, aufgrund derer der Einzelfall zurückgewiesen wird.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType[] $grund
     */
    private $grund = [
        
    ];

    /**
     * Gets as identifikationEreignis
     *
     * Hier werden die Informationen übermittelt, mit denen der Einzelfall in der (Sammel-)Nachricht eindeutig identifiziert werden kann.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType
     */
    public function getIdentifikationEreignis()
    {
        return $this->identifikationEreignis;
    }

    /**
     * Sets a new identifikationEreignis
     *
     * Hier werden die Informationen übermittelt, mit denen der Einzelfall in der (Sammel-)Nachricht eindeutig identifiziert werden kann.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $identifikationEreignis
     * @return self
     */
    public function setIdentifikationEreignis(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $identifikationEreignis)
    {
        $this->identifikationEreignis = $identifikationEreignis;
        return $this;
    }

    /**
     * Adds as grund
     *
     * Für jeden Einzelfall sind hier die Gründe zu übermitteln, aufgrund derer der Einzelfall zurückgewiesen wird.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType $grund
     */
    public function addToGrund(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType $grund)
    {
        $this->grund[] = $grund;
        return $this;
    }

    /**
     * isset grund
     *
     * Für jeden Einzelfall sind hier die Gründe zu übermitteln, aufgrund derer der Einzelfall zurückgewiesen wird.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGrund($index)
    {
        return isset($this->grund[$index]);
    }

    /**
     * unset grund
     *
     * Für jeden Einzelfall sind hier die Gründe zu übermitteln, aufgrund derer der Einzelfall zurückgewiesen wird.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGrund($index)
    {
        unset($this->grund[$index]);
    }

    /**
     * Gets as grund
     *
     * Für jeden Einzelfall sind hier die Gründe zu übermitteln, aufgrund derer der Einzelfall zurückgewiesen wird.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType[]
     */
    public function getGrund()
    {
        return $this->grund;
    }

    /**
     * Sets a new grund
     *
     * Für jeden Einzelfall sind hier die Gründe zu übermitteln, aufgrund derer der Einzelfall zurückgewiesen wird.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType[] $grund
     * @return self
     */
    public function setGrund(array $grund)
    {
        $this->grund = $grund;
        return $this;
    }
}

