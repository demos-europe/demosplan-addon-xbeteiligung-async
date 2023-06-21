<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RueckweisungRueckweisungsgrundTypeType
 *
 * Mit diesem Element wird ein Grund übermittelt, aus dem die Nachricht zurückgewiesen wird. Die im Rahmen der hier zu verwendenden Schlüsseltabelle zur Verfügung gestellten Rückweisungsgründe sind kontextunabhängig und finden in allen xinneres-fachmodulen Anwendung. Die allgemeinen Rückweisungsgründe können durch kontextspezifische Gründe erläutert werden. Sofern in diesem Element xinneres-fachmodul-spezifische Rückweisungsgründe übermittelt werden sollen, ist in dem Kindelement grund der Schlüssel S999 und in den Kindelementen grundSpezifisch ein oder mehrere dem xinneres-fachmodul entstammende Rückweisungsgründe zu übermitteln.
 * XSD Type: Rueckweisung.RueckweisungsgrundType
 */
class RueckweisungRueckweisungsgrundTypeType
{
    /**
     * Mit diesem Element wird ein Grund übermittelt, aus dem die Nachricht zurückgewiesen wird. Die im Rahmen der hier zu verwendenden Schlüsseltabelle zur Verfügung gestellten Rückweisungsgründe sind kontextunabhängig und finden daher in allen xinneres-fachmodulen Anwendung. Sofern der Schlüssel S999 verwendet wird, sind ergänzende Angaben in dem Element grundSpezifisch verpflichtend zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeXInneresTypeType $grund
     */
    private $grund = null;

    /**
     * In diesem Element können weitere - im Allgemeinen kontextspezifische Angaben - zu dem Grund übermittelt werden, aus dem die Nachricht zurückgewiesen wird.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungGrundSpezifischTypeType[] $grundSpezifisch
     */
    private $grundSpezifisch = [
        
    ];

    /**
     * Gets as grund
     *
     * Mit diesem Element wird ein Grund übermittelt, aus dem die Nachricht zurückgewiesen wird. Die im Rahmen der hier zu verwendenden Schlüsseltabelle zur Verfügung gestellten Rückweisungsgründe sind kontextunabhängig und finden daher in allen xinneres-fachmodulen Anwendung. Sofern der Schlüssel S999 verwendet wird, sind ergänzende Angaben in dem Element grundSpezifisch verpflichtend zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeXInneresTypeType
     */
    public function getGrund()
    {
        return $this->grund;
    }

    /**
     * Sets a new grund
     *
     * Mit diesem Element wird ein Grund übermittelt, aus dem die Nachricht zurückgewiesen wird. Die im Rahmen der hier zu verwendenden Schlüsseltabelle zur Verfügung gestellten Rückweisungsgründe sind kontextunabhängig und finden daher in allen xinneres-fachmodulen Anwendung. Sofern der Schlüssel S999 verwendet wird, sind ergänzende Angaben in dem Element grundSpezifisch verpflichtend zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeXInneresTypeType $grund
     * @return self
     */
    public function setGrund(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeFehlercodeXInneresTypeType $grund)
    {
        $this->grund = $grund;
        return $this;
    }

    /**
     * Adds as grundSpezifisch
     *
     * In diesem Element können weitere - im Allgemeinen kontextspezifische Angaben - zu dem Grund übermittelt werden, aus dem die Nachricht zurückgewiesen wird.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungGrundSpezifischTypeType $grundSpezifisch
     */
    public function addToGrundSpezifisch(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungGrundSpezifischTypeType $grundSpezifisch)
    {
        $this->grundSpezifisch[] = $grundSpezifisch;
        return $this;
    }

    /**
     * isset grundSpezifisch
     *
     * In diesem Element können weitere - im Allgemeinen kontextspezifische Angaben - zu dem Grund übermittelt werden, aus dem die Nachricht zurückgewiesen wird.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGrundSpezifisch($index)
    {
        return isset($this->grundSpezifisch[$index]);
    }

    /**
     * unset grundSpezifisch
     *
     * In diesem Element können weitere - im Allgemeinen kontextspezifische Angaben - zu dem Grund übermittelt werden, aus dem die Nachricht zurückgewiesen wird.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGrundSpezifisch($index)
    {
        unset($this->grundSpezifisch[$index]);
    }

    /**
     * Gets as grundSpezifisch
     *
     * In diesem Element können weitere - im Allgemeinen kontextspezifische Angaben - zu dem Grund übermittelt werden, aus dem die Nachricht zurückgewiesen wird.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungGrundSpezifischTypeType[]
     */
    public function getGrundSpezifisch()
    {
        return $this->grundSpezifisch;
    }

    /**
     * Sets a new grundSpezifisch
     *
     * In diesem Element können weitere - im Allgemeinen kontextspezifische Angaben - zu dem Grund übermittelt werden, aus dem die Nachricht zurückgewiesen wird.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungGrundSpezifischTypeType[] $grundSpezifisch
     * @return self
     */
    public function setGrundSpezifisch(array $grundSpezifisch = null)
    {
        $this->grundSpezifisch = $grundSpezifisch;
        return $this;
    }
}

