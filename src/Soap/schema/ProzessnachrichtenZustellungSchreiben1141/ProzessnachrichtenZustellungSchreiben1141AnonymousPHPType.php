<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenZustellungSchreiben1141;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing ProzessnachrichtenZustellungSchreiben1141AnonymousPHPType
 */
class ProzessnachrichtenZustellungSchreiben1141AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen. Oder Angabe einer Vorgangsnummer für zukünftige Bezugnahme auf diese Nachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug
     */
    private $bezug = null;

    /**
     * In dieses Element ist der Anschreibentext der Nachricht einzutragen.
     *
     * @var string[] $anschreiben
     */
    private $anschreiben = null;

    /**
     * Dieses Element kann verwendet werden, um Webressourcen für ggf. nötige Reaktionen des Lesers auf die vorliegende Nachricht anzugeben .
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungTypeType $angebotFuerAntwortLeser
     */
    private $angebotFuerAntwortLeser = null;

    /**
     * Dieses Element ist für Anlagen zur Nachricht zu verwenden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as bezug
     *
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen. Oder Angabe einer Vorgangsnummer für zukünftige Bezugnahme auf diese Nachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType
     */
    public function getBezug()
    {
        return $this->bezug;
    }

    /**
     * Sets a new bezug
     *
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen. Oder Angabe einer Vorgangsnummer für zukünftige Bezugnahme auf diese Nachricht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug
     * @return self
     */
    public function setBezug(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug)
    {
        $this->bezug = $bezug;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * In dieses Element ist der Anschreibentext der Nachricht einzutragen.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToAnschreiben($textabsatz)
    {
        $this->anschreiben[] = $textabsatz;
        return $this;
    }

    /**
     * isset anschreiben
     *
     * In dieses Element ist der Anschreibentext der Nachricht einzutragen.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnschreiben($index)
    {
        return isset($this->anschreiben[$index]);
    }

    /**
     * unset anschreiben
     *
     * In dieses Element ist der Anschreibentext der Nachricht einzutragen.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnschreiben($index)
    {
        unset($this->anschreiben[$index]);
    }

    /**
     * Gets as anschreiben
     *
     * In dieses Element ist der Anschreibentext der Nachricht einzutragen.
     *
     * @return string[]
     */
    public function getAnschreiben()
    {
        return $this->anschreiben;
    }

    /**
     * Sets a new anschreiben
     *
     * In dieses Element ist der Anschreibentext der Nachricht einzutragen.
     *
     * @param string[] $anschreiben
     * @return self
     */
    public function setAnschreiben(array $anschreiben)
    {
        $this->anschreiben = $anschreiben;
        return $this;
    }

    /**
     * Gets as angebotFuerAntwortLeser
     *
     * Dieses Element kann verwendet werden, um Webressourcen für ggf. nötige Reaktionen des Lesers auf die vorliegende Nachricht anzugeben .
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungTypeType
     */
    public function getAngebotFuerAntwortLeser()
    {
        return $this->angebotFuerAntwortLeser;
    }

    /**
     * Sets a new angebotFuerAntwortLeser
     *
     * Dieses Element kann verwendet werden, um Webressourcen für ggf. nötige Reaktionen des Lesers auf die vorliegende Nachricht anzugeben .
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungTypeType $angebotFuerAntwortLeser
     * @return self
     */
    public function setAngebotFuerAntwortLeser(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungTypeType $angebotFuerAntwortLeser = null)
    {
        $this->angebotFuerAntwortLeser = $angebotFuerAntwortLeser;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Dieses Element ist für Anlagen zur Nachricht zu verwenden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType $anlage
     */
    public function addToAnlagen(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType $anlage)
    {
        $this->anlagen[] = $anlage;
        return $this;
    }

    /**
     * isset anlagen
     *
     * Dieses Element ist für Anlagen zur Nachricht zu verwenden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnlagen($index)
    {
        return isset($this->anlagen[$index]);
    }

    /**
     * unset anlagen
     *
     * Dieses Element ist für Anlagen zur Nachricht zu verwenden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnlagen($index)
    {
        unset($this->anlagen[$index]);
    }

    /**
     * Gets as anlagen
     *
     * Dieses Element ist für Anlagen zur Nachricht zu verwenden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[]
     */
    public function getAnlagen()
    {
        return $this->anlagen;
    }

    /**
     * Sets a new anlagen
     *
     * Dieses Element ist für Anlagen zur Nachricht zu verwenden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[] $anlagen
     * @return self
     */
    public function setAnlagen(array $anlagen = null)
    {
        $this->anlagen = $anlagen;
        return $this;
    }
}

