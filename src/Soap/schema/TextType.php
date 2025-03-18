<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing TextType
 *
 * Diese Typ nimmt Absätze eines formatierungsfreien Textes auf.
 * XSD Type: Text
 */
class TextType
{
    /**
     * Pro Element wird Text im Umfang eines Absatzes (Freitext) festgehalten.
     *
     * @var string[] $textabsatz
     */
    private $textabsatz = [
        
    ];

    /**
     * Adds as textabsatz
     *
     * Pro Element wird Text im Umfang eines Absatzes (Freitext) festgehalten.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToTextabsatz($textabsatz)
    {
        $this->textabsatz[] = $textabsatz;
        return $this;
    }

    /**
     * isset textabsatz
     *
     * Pro Element wird Text im Umfang eines Absatzes (Freitext) festgehalten.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTextabsatz($index)
    {
        return isset($this->textabsatz[$index]);
    }

    /**
     * unset textabsatz
     *
     * Pro Element wird Text im Umfang eines Absatzes (Freitext) festgehalten.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTextabsatz($index)
    {
        unset($this->textabsatz[$index]);
    }

    /**
     * Gets as textabsatz
     *
     * Pro Element wird Text im Umfang eines Absatzes (Freitext) festgehalten.
     *
     * @return string[]
     */
    public function getTextabsatz()
    {
        return $this->textabsatz;
    }

    /**
     * Sets a new textabsatz
     *
     * Pro Element wird Text im Umfang eines Absatzes (Freitext) festgehalten.
     *
     * @param string[] $textabsatz
     * @return self
     */
    public function setTextabsatz(array $textabsatz)
    {
        $this->textabsatz = $textabsatz;
        return $this;
    }
}

