<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing GeoreferenzierungTypeType
 *
 * Element zur Verortung der einer Stellungnahme.
 * XSD Type: GeoreferenzierungType
 */
class GeoreferenzierungTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType[] $punkt
     */
    private $punkt = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheTypeType[] $flaeche
     */
    private $flaeche = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType[] $linie
     */
    private $linie = [
        
    ];

    /**
     * Adds as punkt
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType $punkt
     */
    public function addToPunkt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType $punkt)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType[]
     */
    public function getPunkt()
    {
        return $this->punkt;
    }

    /**
     * Sets a new punkt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType[] $punkt
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheTypeType $flaeche
     */
    public function addToFlaeche(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheTypeType $flaeche)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheTypeType[]
     */
    public function getFlaeche()
    {
        return $this->flaeche;
    }

    /**
     * Sets a new flaeche
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteFlaecheTypeType[] $flaeche
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType $linie
     */
    public function addToLinie(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType $linie)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType[]
     */
    public function getLinie()
    {
        return $this->linie;
    }

    /**
     * Sets a new linie
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenzierteLinieTypeType[] $linie
     * @return self
     */
    public function setLinie(array $linie = null)
    {
        $this->linie = $linie;
        return $this;
    }
}

