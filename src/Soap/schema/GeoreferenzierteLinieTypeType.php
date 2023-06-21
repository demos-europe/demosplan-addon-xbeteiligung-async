<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing GeoreferenzierteLinieTypeType
 *
 * In eine Instanz diesen Typs werden die Geodaten eines Vorhabens oder einer sonstigen Entität als Linie eingetragen.
 * XSD Type: GeoreferenzierteLinieType
 */
class GeoreferenzierteLinieTypeType
{
    /**
     * In diesem Element lassen sich georeferenzierte Daten zu linienförmigen Elementen übermitteln, z. B. die Lage einer geplanten Breitbandtrasse.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType\LinieAnonymousPHPType[] $linie
     */
    private $linie = [
        
    ];

    /**
     * Hier können ergänzend Erläuterungen zu den georeferenzierten Daten gegeben werden.
     *
     * @var string $erlaeuterung
     */
    private $erlaeuterung = null;

    /**
     * Adds as linie
     *
     * In diesem Element lassen sich georeferenzierte Daten zu linienförmigen Elementen übermitteln, z. B. die Lage einer geplanten Breitbandtrasse.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType\LinieAnonymousPHPType $linie
     */
    public function addToLinie(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType\LinieAnonymousPHPType $linie)
    {
        $this->linie[] = $linie;
        return $this;
    }

    /**
     * isset linie
     *
     * In diesem Element lassen sich georeferenzierte Daten zu linienförmigen Elementen übermitteln, z. B. die Lage einer geplanten Breitbandtrasse.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLinie($index)
    {
        return isset($this->linie[$index]);
    }

    /**
     * unset linie
     *
     * In diesem Element lassen sich georeferenzierte Daten zu linienförmigen Elementen übermitteln, z. B. die Lage einer geplanten Breitbandtrasse.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLinie($index)
    {
        unset($this->linie[$index]);
    }

    /**
     * Gets as linie
     *
     * In diesem Element lassen sich georeferenzierte Daten zu linienförmigen Elementen übermitteln, z. B. die Lage einer geplanten Breitbandtrasse.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType\LinieAnonymousPHPType[]
     */
    public function getLinie()
    {
        return $this->linie;
    }

    /**
     * Sets a new linie
     *
     * In diesem Element lassen sich georeferenzierte Daten zu linienförmigen Elementen übermitteln, z. B. die Lage einer geplanten Breitbandtrasse.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType\LinieAnonymousPHPType[] $linie
     * @return self
     */
    public function setLinie(array $linie)
    {
        $this->linie = $linie;
        return $this;
    }

    /**
     * Gets as erlaeuterung
     *
     * Hier können ergänzend Erläuterungen zu den georeferenzierten Daten gegeben werden.
     *
     * @return string
     */
    public function getErlaeuterung()
    {
        return $this->erlaeuterung;
    }

    /**
     * Sets a new erlaeuterung
     *
     * Hier können ergänzend Erläuterungen zu den georeferenzierten Daten gegeben werden.
     *
     * @param string $erlaeuterung
     * @return self
     */
    public function setErlaeuterung($erlaeuterung)
    {
        $this->erlaeuterung = $erlaeuterung;
        return $this;
    }
}

