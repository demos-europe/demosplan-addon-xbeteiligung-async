<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing TextFormatiertTypeType
 *
 * Diese Typ nimmt Absätze eines Textes auf, so dass das Empfängersystem diesen Text formatierungstreu anzeigen und medienbruchfrei weiterverarbeiten kannn.
 * XSD Type: TextFormatiertType
 */
class TextFormatiertTypeType
{
    /**
     * Dieses Element steht für einen Textabschnitt, der ggf. einschließlich Formatierung und Abbildungen, auf Basis von HTML-5-Markup eingetragen ist.
     *
     * @var string $text
     */
    private $text = null;

    /**
     * Dieses Element gestattet es, den Textabschnitt zusätzlich im XML-basierten Format ODF oder OOXML abzubilden.
     *
     * @var string $textOffice
     */
    private $textOffice = null;

    /**
     * Gets as text
     *
     * Dieses Element steht für einen Textabschnitt, der ggf. einschließlich Formatierung und Abbildungen, auf Basis von HTML-5-Markup eingetragen ist.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Sets a new text
     *
     * Dieses Element steht für einen Textabschnitt, der ggf. einschließlich Formatierung und Abbildungen, auf Basis von HTML-5-Markup eingetragen ist.
     *
     * @param string $text
     * @return self
     */
    public function setText($text)
    {
        $this->text = $text;
        return $this;
    }

    /**
     * Gets as textOffice
     *
     * Dieses Element gestattet es, den Textabschnitt zusätzlich im XML-basierten Format ODF oder OOXML abzubilden.
     *
     * @return string
     */
    public function getTextOffice()
    {
        return $this->textOffice;
    }

    /**
     * Sets a new textOffice
     *
     * Dieses Element gestattet es, den Textabschnitt zusätzlich im XML-basierten Format ODF oder OOXML abzubilden.
     *
     * @param string $textOffice
     * @return self
     */
    public function setTextOffice($textOffice)
    {
        $this->textOffice = $textOffice;
        return $this;
    }
}

