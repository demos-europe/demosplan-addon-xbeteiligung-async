<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RueckweisungRueckweisendeStelleTypeType
 *
 * Die rückweisende Stelle kann eine der Institutionen sein, die sich auf dem Weg zwischen Autor und Leser befinden.
 * XSD Type: Rueckweisung.RueckweisendeStelleType
 */
class RueckweisungRueckweisendeStelleTypeType
{
    /**
     * Mit diesem Element wird die Stelle bezeichnet, die die Nachricht beanstandet hat.
     *
     * @var string $pruefinstanz
     */
    private $pruefinstanz = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftTypeType $anschrift
     */
    private $anschrift = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $erreichbarkeit
     */
    private $erreichbarkeit = [
        
    ];

    /**
     * Gets as pruefinstanz
     *
     * Mit diesem Element wird die Stelle bezeichnet, die die Nachricht beanstandet hat.
     *
     * @return string
     */
    public function getPruefinstanz()
    {
        return $this->pruefinstanz;
    }

    /**
     * Sets a new pruefinstanz
     *
     * Mit diesem Element wird die Stelle bezeichnet, die die Nachricht beanstandet hat.
     *
     * @param string $pruefinstanz
     * @return self
     */
    public function setPruefinstanz($pruefinstanz)
    {
        $this->pruefinstanz = $pruefinstanz;
        return $this;
    }

    /**
     * Gets as anschrift
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftTypeType
     */
    public function getAnschrift()
    {
        return $this->anschrift;
    }

    /**
     * Sets a new anschrift
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftTypeType $anschrift
     * @return self
     */
    public function setAnschrift(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftTypeType $anschrift)
    {
        $this->anschrift = $anschrift;
        return $this;
    }

    /**
     * Adds as erreichbarkeit
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType $erreichbarkeit
     */
    public function addToErreichbarkeit(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType $erreichbarkeit)
    {
        $this->erreichbarkeit[] = $erreichbarkeit;
        return $this;
    }

    /**
     * isset erreichbarkeit
     *
     * @param int|string $index
     * @return bool
     */
    public function issetErreichbarkeit($index)
    {
        return isset($this->erreichbarkeit[$index]);
    }

    /**
     * unset erreichbarkeit
     *
     * @param int|string $index
     * @return void
     */
    public function unsetErreichbarkeit($index)
    {
        unset($this->erreichbarkeit[$index]);
    }

    /**
     * Gets as erreichbarkeit
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[]
     */
    public function getErreichbarkeit()
    {
        return $this->erreichbarkeit;
    }

    /**
     * Sets a new erreichbarkeit
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $erreichbarkeit
     * @return self
     */
    public function setErreichbarkeit(array $erreichbarkeit)
    {
        $this->erreichbarkeit = $erreichbarkeit;
        return $this;
    }
}

