<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing KostenberechnungType
 *
 *
 * XSD Type: Kostenberechnung
 */
class KostenberechnungType
{
    /**
     * Dieses Element steht für eine Gebührenposition.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\GebuehrenpositionAnonymousPHPType[] $gebuehrenposition
     */
    private $gebuehrenposition = [
        
    ];

    /**
     * Dieses Element steht für eine Auslagenposition.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\AuslagenpositionAnonymousPHPType[] $auslagenposition
     */
    private $auslagenposition = [
        
    ];

    /**
     * Hier ist Summe aller Positionen in Eurocent einzutragen.
     *
     * @var int $summe
     */
    private $summe = null;

    /**
     * Adds as gebuehrenposition
     *
     * Dieses Element steht für eine Gebührenposition.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\GebuehrenpositionAnonymousPHPType $gebuehrenposition
     */
    public function addToGebuehrenposition(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\GebuehrenpositionAnonymousPHPType $gebuehrenposition)
    {
        $this->gebuehrenposition[] = $gebuehrenposition;
        return $this;
    }

    /**
     * isset gebuehrenposition
     *
     * Dieses Element steht für eine Gebührenposition.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetGebuehrenposition($index)
    {
        return isset($this->gebuehrenposition[$index]);
    }

    /**
     * unset gebuehrenposition
     *
     * Dieses Element steht für eine Gebührenposition.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetGebuehrenposition($index)
    {
        unset($this->gebuehrenposition[$index]);
    }

    /**
     * Gets as gebuehrenposition
     *
     * Dieses Element steht für eine Gebührenposition.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\GebuehrenpositionAnonymousPHPType[]
     */
    public function getGebuehrenposition()
    {
        return $this->gebuehrenposition;
    }

    /**
     * Sets a new gebuehrenposition
     *
     * Dieses Element steht für eine Gebührenposition.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\GebuehrenpositionAnonymousPHPType[] $gebuehrenposition
     * @return self
     */
    public function setGebuehrenposition(array $gebuehrenposition = null)
    {
        $this->gebuehrenposition = $gebuehrenposition;
        return $this;
    }

    /**
     * Adds as auslagenposition
     *
     * Dieses Element steht für eine Auslagenposition.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\AuslagenpositionAnonymousPHPType $auslagenposition
     */
    public function addToAuslagenposition(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\AuslagenpositionAnonymousPHPType $auslagenposition)
    {
        $this->auslagenposition[] = $auslagenposition;
        return $this;
    }

    /**
     * isset auslagenposition
     *
     * Dieses Element steht für eine Auslagenposition.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAuslagenposition($index)
    {
        return isset($this->auslagenposition[$index]);
    }

    /**
     * unset auslagenposition
     *
     * Dieses Element steht für eine Auslagenposition.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAuslagenposition($index)
    {
        unset($this->auslagenposition[$index]);
    }

    /**
     * Gets as auslagenposition
     *
     * Dieses Element steht für eine Auslagenposition.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\AuslagenpositionAnonymousPHPType[]
     */
    public function getAuslagenposition()
    {
        return $this->auslagenposition;
    }

    /**
     * Sets a new auslagenposition
     *
     * Dieses Element steht für eine Auslagenposition.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KostenberechnungType\AuslagenpositionAnonymousPHPType[] $auslagenposition
     * @return self
     */
    public function setAuslagenposition(array $auslagenposition = null)
    {
        $this->auslagenposition = $auslagenposition;
        return $this;
    }

    /**
     * Gets as summe
     *
     * Hier ist Summe aller Positionen in Eurocent einzutragen.
     *
     * @return int
     */
    public function getSumme()
    {
        return $this->summe;
    }

    /**
     * Sets a new summe
     *
     * Hier ist Summe aller Positionen in Eurocent einzutragen.
     *
     * @param int $summe
     * @return self
     */
    public function setSumme($summe)
    {
        $this->summe = $summe;
        return $this;
    }
}

