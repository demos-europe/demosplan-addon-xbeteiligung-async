<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing TextFormatiertTypeType
 *
 * Dieser Typ nimmt Absätze eines Textes in einer Form auf, so dass das Empfängersystem diesen Text formatierungstreu anzeigen und medienbruchfrei weiterverarbeiten kann.
 * XSD Type: TextFormatiertType
 */
class TextFormatiertTypeType
{
    /**
     * In dieses Element ist ein Textabschnitt in Form von HTML-5 Markup einzutragen. Das schließt die Möglichkeit von Formatierung und Integration von Abbildungen ein. Folgende Regeln sind beim Eintrag von HTML-5-Text zu beachten: Das Textfeld enthält nur den Inhalt des BODY-Elements (nicht dieses selber), also nur "Flow Content" im Sinne der HTML5-Spezifikation (https://html.spec.whatwg.org/multipage/dom.html#flow-content). Es ist zu beachten, dass XBau die Menge des erlaubten HTML5-Markups grundlegend einschränkt (vgl. ) und dass diese Einschränkungen Vorrang haben. Als Encoding wird stets "UTF-8" verwendet. Externe CSS-Referenzen dürfen nicht verwendet werden, sondern lediglich HTML Inline Styles.
     *
     * @var string $text
     */
    private $text = null;

    /**
     * Unterhalb dieses Elements kann derselbe Inhalt wie im Element text in einem alternativen technischen Format angeboten werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType\TextOfficeAnonymousPHPType $textOffice
     */
    private $textOffice = null;

    /**
     * Gets as text
     *
     * In dieses Element ist ein Textabschnitt in Form von HTML-5 Markup einzutragen. Das schließt die Möglichkeit von Formatierung und Integration von Abbildungen ein. Folgende Regeln sind beim Eintrag von HTML-5-Text zu beachten: Das Textfeld enthält nur den Inhalt des BODY-Elements (nicht dieses selber), also nur "Flow Content" im Sinne der HTML5-Spezifikation (https://html.spec.whatwg.org/multipage/dom.html#flow-content). Es ist zu beachten, dass XBau die Menge des erlaubten HTML5-Markups grundlegend einschränkt (vgl. ) und dass diese Einschränkungen Vorrang haben. Als Encoding wird stets "UTF-8" verwendet. Externe CSS-Referenzen dürfen nicht verwendet werden, sondern lediglich HTML Inline Styles.
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
     * In dieses Element ist ein Textabschnitt in Form von HTML-5 Markup einzutragen. Das schließt die Möglichkeit von Formatierung und Integration von Abbildungen ein. Folgende Regeln sind beim Eintrag von HTML-5-Text zu beachten: Das Textfeld enthält nur den Inhalt des BODY-Elements (nicht dieses selber), also nur "Flow Content" im Sinne der HTML5-Spezifikation (https://html.spec.whatwg.org/multipage/dom.html#flow-content). Es ist zu beachten, dass XBau die Menge des erlaubten HTML5-Markups grundlegend einschränkt (vgl. ) und dass diese Einschränkungen Vorrang haben. Als Encoding wird stets "UTF-8" verwendet. Externe CSS-Referenzen dürfen nicht verwendet werden, sondern lediglich HTML Inline Styles.
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
     * Unterhalb dieses Elements kann derselbe Inhalt wie im Element text in einem alternativen technischen Format angeboten werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType\TextOfficeAnonymousPHPType
     */
    public function getTextOffice()
    {
        return $this->textOffice;
    }

    /**
     * Sets a new textOffice
     *
     * Unterhalb dieses Elements kann derselbe Inhalt wie im Element text in einem alternativen technischen Format angeboten werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType\TextOfficeAnonymousPHPType $textOffice
     * @return self
     */
    public function setTextOffice(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TextFormatiertTypeType\TextOfficeAnonymousPHPType $textOffice = null)
    {
        $this->textOffice = $textOffice;
        return $this;
    }
}

