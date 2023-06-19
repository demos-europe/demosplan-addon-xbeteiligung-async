<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PostalischeInlandsanschriftType
 *
 * Dieser Datentyp beinhaltet die Angaben für die Adressierung im Inland. Es können entweder Angaben zu einer Gebäudeanschrift oder zu einer Postfachanschrift übermittelt werden.
 * XSD Type: PostalischeInlandsanschrift
 */
class PostalischeInlandsanschriftType
{
    /**
     * Angaben für die Übermittlung einer Gebäudeanschrift.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftType $gebaeude
     */
    private $gebaeude = null;

    /**
     * Angaben für die Übermittlung einer Postfachanschrift.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftType $postfach
     */
    private $postfach = null;

    /**
     * Gets as gebaeude
     *
     * Angaben für die Übermittlung einer Gebäudeanschrift.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftType
     */
    public function getGebaeude()
    {
        return $this->gebaeude;
    }

    /**
     * Sets a new gebaeude
     *
     * Angaben für die Übermittlung einer Gebäudeanschrift.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftType $gebaeude
     * @return self
     */
    public function setGebaeude(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftType $gebaeude = null)
    {
        $this->gebaeude = $gebaeude;
        return $this;
    }

    /**
     * Gets as postfach
     *
     * Angaben für die Übermittlung einer Postfachanschrift.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftType
     */
    public function getPostfach()
    {
        return $this->postfach;
    }

    /**
     * Sets a new postfach
     *
     * Angaben für die Übermittlung einer Postfachanschrift.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftType $postfach
     * @return self
     */
    public function setPostfach(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftType $postfach = null)
    {
        $this->postfach = $postfach;
        return $this;
    }
}

