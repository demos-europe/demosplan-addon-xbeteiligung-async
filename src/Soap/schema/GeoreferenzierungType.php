<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing GeoreferenzierungType
 *
 * Element zur Verortung der einer Stellungnahme.
 * XSD Type: Georeferenzierung
 */
class GeoreferenzierungType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteType[] $punkt
     */
    private $punkt = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheType[] $flaeche
     */
    private $flaeche = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieType[] $linie
     */
    private $linie = [
        
    ];

    /**
     * Adds as punkt
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteType $punkt
     */
    public function addToPunkt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteType $punkt)
    {
        $this->punkt[] = $punkt;
        return $this;
    }

    /**
     * isset punkt
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPunkt($index)
    {
        return isset($this->punkt[$index]);
    }

    /**
     * unset punkt
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPunkt($index)
    {
        unset($this->punkt[$index]);
    }

    /**
     * Gets as punkt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteType[]
     */
    public function getPunkt()
    {
        return $this->punkt;
    }

    /**
     * Sets a new punkt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteType[] $punkt
     * @return self
     */
    public function setPunkt(array $punkt = null)
    {
        $this->punkt = $punkt;
        return $this;
    }

    /**
     * Adds as flaeche
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheType $flaeche
     */
    public function addToFlaeche(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheType $flaeche)
    {
        $this->flaeche[] = $flaeche;
        return $this;
    }

    /**
     * isset flaeche
     *
     * @param int|string $index
     * @return bool
     */
    public function issetFlaeche($index)
    {
        return isset($this->flaeche[$index]);
    }

    /**
     * unset flaeche
     *
     * @param int|string $index
     * @return void
     */
    public function unsetFlaeche($index)
    {
        unset($this->flaeche[$index]);
    }

    /**
     * Gets as flaeche
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheType[]
     */
    public function getFlaeche()
    {
        return $this->flaeche;
    }

    /**
     * Sets a new flaeche
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheType[] $flaeche
     * @return self
     */
    public function setFlaeche(array $flaeche = null)
    {
        $this->flaeche = $flaeche;
        return $this;
    }

    /**
     * Adds as linie
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieType $linie
     */
    public function addToLinie(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieType $linie)
    {
        $this->linie[] = $linie;
        return $this;
    }

    /**
     * isset linie
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieType[]
     */
    public function getLinie()
    {
        return $this->linie;
    }

    /**
     * Sets a new linie
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieType[] $linie
     * @return self
     */
    public function setLinie(array $linie = null)
    {
        $this->linie = $linie;
        return $this;
    }
}

