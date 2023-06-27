<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType;

/**
 * Class representing AnsprechpartnerAnonymousPHPType
 */
class AnsprechpartnerAnonymousPHPType
{
    /**
     * Hier ist der Name des Ansprechpartners einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $name
     */
    private $name = null;

    /**
     * Hier kann über eine Funktionsangabe die Zuständigkeit des Ansprechpartners bezeichnet werden.
     *
     * @var string $funktion
     */
    private $funktion = null;

    /**
     * Pro Element kann eine Angaben zur Erreichbarkeit des Ansprechpartners innerhalb der Organisation gemacht werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $kommunikation
     */
    private $kommunikation = [
        
    ];

    /**
     * Gets as name
     *
     * Hier ist der Name des Ansprechpartners einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Hier ist der Name des Ansprechpartners einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $name
     * @return self
     */
    public function setName(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as funktion
     *
     * Hier kann über eine Funktionsangabe die Zuständigkeit des Ansprechpartners bezeichnet werden.
     *
     * @return string
     */
    public function getFunktion()
    {
        return $this->funktion;
    }

    /**
     * Sets a new funktion
     *
     * Hier kann über eine Funktionsangabe die Zuständigkeit des Ansprechpartners bezeichnet werden.
     *
     * @param string $funktion
     * @return self
     */
    public function setFunktion($funktion)
    {
        $this->funktion = $funktion;
        return $this;
    }

    /**
     * Adds as kommunikation
     *
     * Pro Element kann eine Angaben zur Erreichbarkeit des Ansprechpartners innerhalb der Organisation gemacht werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType $kommunikation
     */
    public function addToKommunikation(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType $kommunikation)
    {
        $this->kommunikation[] = $kommunikation;
        return $this;
    }

    /**
     * isset kommunikation
     *
     * Pro Element kann eine Angaben zur Erreichbarkeit des Ansprechpartners innerhalb der Organisation gemacht werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetKommunikation($index)
    {
        return isset($this->kommunikation[$index]);
    }

    /**
     * unset kommunikation
     *
     * Pro Element kann eine Angaben zur Erreichbarkeit des Ansprechpartners innerhalb der Organisation gemacht werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetKommunikation($index)
    {
        unset($this->kommunikation[$index]);
    }

    /**
     * Gets as kommunikation
     *
     * Pro Element kann eine Angaben zur Erreichbarkeit des Ansprechpartners innerhalb der Organisation gemacht werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[]
     */
    public function getKommunikation()
    {
        return $this->kommunikation;
    }

    /**
     * Sets a new kommunikation
     *
     * Pro Element kann eine Angaben zur Erreichbarkeit des Ansprechpartners innerhalb der Organisation gemacht werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $kommunikation
     * @return self
     */
    public function setKommunikation(array $kommunikation = null)
    {
        $this->kommunikation = $kommunikation;
        return $this;
    }
}

