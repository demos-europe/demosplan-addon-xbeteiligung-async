<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PostalischeInlandsanschriftTypeType
 *
 * Dieser Datentyp beinhaltet die Angaben für die Adressierung im Inland. Es können entweder Angaben zu einer Gebäudeanschrift oder zu einer Postfachanschrift übermittelt werden.
 * XSD Type: PostalischeInlandsanschriftType
 */
class PostalischeInlandsanschriftTypeType
{
    /**
     * Angaben für die Übermittlung einer Gebäudeanschrift.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType $gebaeude
     */
    private $gebaeude = null;

    /**
     * Angaben für die Übermittlung einer Postfachanschrift.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftTypeType $postfach
     */
    private $postfach = null;

    /**
     * Gets as gebaeude
     *
     * Angaben für die Übermittlung einer Gebäudeanschrift.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType $gebaeude
     * @return self
     */
    public function setGebaeude(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType $gebaeude = null)
    {
        $this->gebaeude = $gebaeude;
        return $this;
    }

    /**
     * Gets as postfach
     *
     * Angaben für die Übermittlung einer Postfachanschrift.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftTypeType $postfach
     * @return self
     */
    public function setPostfach(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftTypeType $postfach = null)
    {
        $this->postfach = $postfach;
        return $this;
    }
}

