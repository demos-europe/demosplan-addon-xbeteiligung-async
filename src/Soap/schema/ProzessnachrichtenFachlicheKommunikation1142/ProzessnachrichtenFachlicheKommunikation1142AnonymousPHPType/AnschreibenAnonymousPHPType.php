<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenFachlicheKommunikation1142\ProzessnachrichtenFachlicheKommunikation1142AnonymousPHPType;

/**
 * Class representing AnschreibenAnonymousPHPType
 */
class AnschreibenAnonymousPHPType
{
    /**
     * Hier wird der unformatierte Text übermittelt.
     *
     * @var string[] $textUnformatiert
     */
    private $textUnformatiert = null;

    /**
     * Hier wird der formatierte Text übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType $textFormatiert
     */
    private $textFormatiert = null;

    /**
     * Adds as textabsatz
     *
     * Hier wird der unformatierte Text übermittelt.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToTextUnformatiert($textabsatz)
    {
        $this->textUnformatiert[] = $textabsatz;
        return $this;
    }

    /**
     * isset textUnformatiert
     *
     * Hier wird der unformatierte Text übermittelt.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTextUnformatiert($index)
    {
        return isset($this->textUnformatiert[$index]);
    }

    /**
     * unset textUnformatiert
     *
     * Hier wird der unformatierte Text übermittelt.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTextUnformatiert($index)
    {
        unset($this->textUnformatiert[$index]);
    }

    /**
     * Gets as textUnformatiert
     *
     * Hier wird der unformatierte Text übermittelt.
     *
     * @return string[]
     */
    public function getTextUnformatiert()
    {
        return $this->textUnformatiert;
    }

    /**
     * Sets a new textUnformatiert
     *
     * Hier wird der unformatierte Text übermittelt.
     *
     * @param string[] $textUnformatiert
     * @return self
     */
    public function setTextUnformatiert(array $textUnformatiert = null)
    {
        $this->textUnformatiert = $textUnformatiert;
        return $this;
    }

    /**
     * Gets as textFormatiert
     *
     * Hier wird der formatierte Text übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType
     */
    public function getTextFormatiert()
    {
        return $this->textFormatiert;
    }

    /**
     * Sets a new textFormatiert
     *
     * Hier wird der formatierte Text übermittelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType $textFormatiert
     * @return self
     */
    public function setTextFormatiert(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType $textFormatiert = null)
    {
        $this->textFormatiert = $textFormatiert;
        return $this;
    }
}

