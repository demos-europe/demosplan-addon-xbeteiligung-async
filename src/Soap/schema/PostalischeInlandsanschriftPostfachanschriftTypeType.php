<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PostalischeInlandsanschriftPostfachanschriftTypeType
 *
 * Dieser Datentyp beinhaltet die Angaben für die Adressierung im Inland, soweit es sich um eine Postfachanschrift (und nicht um eine Gebäudeanschrift) handelt.
 * XSD Type: PostalischeInlandsanschrift.PostfachanschriftType
 */
class PostalischeInlandsanschriftPostfachanschriftTypeType
{
    /**
     * @var string $postfach
     */
    private $postfach = null;

    /**
     * @var string $wohnort
     */
    private $wohnort = null;

    /**
     * @var string $postleitzahl
     */
    private $postleitzahl = null;

    /**
     * Gets as postfach
     *
     * @return string
     */
    public function getPostfach()
    {
        return $this->postfach;
    }

    /**
     * Sets a new postfach
     *
     * @param string $postfach
     * @return self
     */
    public function setPostfach($postfach)
    {
        $this->postfach = $postfach;
        return $this;
    }

    /**
     * Gets as wohnort
     *
     * @return string
     */
    public function getWohnort()
    {
        return $this->wohnort;
    }

    /**
     * Sets a new wohnort
     *
     * @param string $wohnort
     * @return self
     */
    public function setWohnort($wohnort)
    {
        $this->wohnort = $wohnort;
        return $this;
    }

    /**
     * Gets as postleitzahl
     *
     * @return string
     */
    public function getPostleitzahl()
    {
        return $this->postleitzahl;
    }

    /**
     * Sets a new postleitzahl
     *
     * @param string $postleitzahl
     * @return self
     */
    public function setPostleitzahl($postleitzahl)
    {
        $this->postleitzahl = $postleitzahl;
        return $this;
    }
}

