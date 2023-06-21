<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing ZeitraumTypeType
 *
 * Der Zeitraum kennzeichnet einen Abschnitt auf einem Zeitstrahl durch Angabe von Beginn und/oder Ende.
 * XSD Type: ZeitraumType
 */
class ZeitraumTypeType
{
    /**
     * Der Beginn eines Zeitraums beschreibt den Zeitpunkt, ab dem ein Sachverhalt eintritt bzw. rechtskräftig wirksam ist. Der Beginn ist immer Teil der Dauer des Zeitraumes.
     *
     * @var \DateTime $beginn
     */
    private $beginn = null;

    /**
     * Das Ende eines Zeitraumes beschreibt den Zeitpunkt, ab dem ein Sachverhalt endet bzw. nicht mehr rechtskräftig ist. Das Ende ist Teil der Dauer des Zeitraumes.
     *
     * @var \DateTime $ende
     */
    private $ende = null;

    /**
     * Der Zusatz enthält weitere textuelle Beschreibungen des festgelegten Zeitraums.
     *
     * @var string $zusatz
     */
    private $zusatz = null;

    /**
     * Gets as beginn
     *
     * Der Beginn eines Zeitraums beschreibt den Zeitpunkt, ab dem ein Sachverhalt eintritt bzw. rechtskräftig wirksam ist. Der Beginn ist immer Teil der Dauer des Zeitraumes.
     *
     * @return \DateTime
     */
    public function getBeginn()
    {
        return $this->beginn;
    }

    /**
     * Sets a new beginn
     *
     * Der Beginn eines Zeitraums beschreibt den Zeitpunkt, ab dem ein Sachverhalt eintritt bzw. rechtskräftig wirksam ist. Der Beginn ist immer Teil der Dauer des Zeitraumes.
     *
     * @param \DateTime $beginn
     * @return self
     */
    public function setBeginn(?\DateTime $beginn = null)
    {
        $this->beginn = $beginn;
        return $this;
    }

    /**
     * Gets as ende
     *
     * Das Ende eines Zeitraumes beschreibt den Zeitpunkt, ab dem ein Sachverhalt endet bzw. nicht mehr rechtskräftig ist. Das Ende ist Teil der Dauer des Zeitraumes.
     *
     * @return \DateTime
     */
    public function getEnde()
    {
        return $this->ende;
    }

    /**
     * Sets a new ende
     *
     * Das Ende eines Zeitraumes beschreibt den Zeitpunkt, ab dem ein Sachverhalt endet bzw. nicht mehr rechtskräftig ist. Das Ende ist Teil der Dauer des Zeitraumes.
     *
     * @param \DateTime $ende
     * @return self
     */
    public function setEnde(?\DateTime $ende = null)
    {
        $this->ende = $ende;
        return $this;
    }

    /**
     * Gets as zusatz
     *
     * Der Zusatz enthält weitere textuelle Beschreibungen des festgelegten Zeitraums.
     *
     * @return string
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * Sets a new zusatz
     *
     * Der Zusatz enthält weitere textuelle Beschreibungen des festgelegten Zeitraums.
     *
     * @param string $zusatz
     * @return self
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = $zusatz;
        return $this;
    }
}

