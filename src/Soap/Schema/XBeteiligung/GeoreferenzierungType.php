<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing GeoreferenzierungType
 *
 * Element zur Verortung der einer Stellungnahme.
 * XSD Type: Georeferenzierung
 */
class GeoreferenzierungType
{
    /**
     * Hier können georefierenzierte Punkte zur Verwortung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenziertePunkteType[] $punkt
     */
    private $punkt = [
        
    ];

    /**
     * Hier können georefierenzierte Flächen zur Verwortung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType[] $flaeche
     */
    private $flaeche = [
        
    ];

    /**
     * Hier können georefierenzierte Linien zur Verwortung übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType[] $linie
     */
    private $linie = [
        
    ];

    /**
     * Adds as punkt
     *
     * Hier können georefierenzierte Punkte zur Verwortung übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenziertePunkteType $punkt
     */
    public function addToPunkt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenziertePunkteType $punkt)
    {
        $this->punkt[] = $punkt;
        return $this;
    }

    /**
     * isset punkt
     *
     * Hier können georefierenzierte Punkte zur Verwortung übermittelt werden.
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
     * Hier können georefierenzierte Punkte zur Verwortung übermittelt werden.
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
     * Hier können georefierenzierte Punkte zur Verwortung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenziertePunkteType[]
     */
    public function getPunkt()
    {
        return $this->punkt;
    }

    /**
     * Sets a new punkt
     *
     * Hier können georefierenzierte Punkte zur Verwortung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenziertePunkteType[] $punkt
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
     * Hier können georefierenzierte Flächen zur Verwortung übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType $flaeche
     */
    public function addToFlaeche(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType $flaeche)
    {
        $this->flaeche[] = $flaeche;
        return $this;
    }

    /**
     * isset flaeche
     *
     * Hier können georefierenzierte Flächen zur Verwortung übermittelt werden.
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
     * Hier können georefierenzierte Flächen zur Verwortung übermittelt werden.
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
     * Hier können georefierenzierte Flächen zur Verwortung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType[]
     */
    public function getFlaeche()
    {
        return $this->flaeche;
    }

    /**
     * Sets a new flaeche
     *
     * Hier können georefierenzierte Flächen zur Verwortung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteFlaecheType[] $flaeche
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
     * Hier können georefierenzierte Linien zur Verwortung übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType $linie
     */
    public function addToLinie(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType $linie)
    {
        $this->linie[] = $linie;
        return $this;
    }

    /**
     * isset linie
     *
     * Hier können georefierenzierte Linien zur Verwortung übermittelt werden.
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
     * Hier können georefierenzierte Linien zur Verwortung übermittelt werden.
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
     * Hier können georefierenzierte Linien zur Verwortung übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType[]
     */
    public function getLinie()
    {
        return $this->linie;
    }

    /**
     * Sets a new linie
     *
     * Hier können georefierenzierte Linien zur Verwortung übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\GeoreferenzierteLinieType[] $linie
     * @return self
     */
    public function setLinie(array $linie = null)
    {
        $this->linie = $linie;
        return $this;
    }
}

