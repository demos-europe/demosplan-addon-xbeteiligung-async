<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenFachlicheKommunikation1142;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing ProzessnachrichtenFachlicheKommunikation1142AnonymousPHPType
 */
class ProzessnachrichtenFachlicheKommunikation1142AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug
     */
    private $bezug = null;

    /**
     * In dieses Element ist der Anschreibentext der Nachricht (Rückfrage des Beteiligten oder Antwort der Behörde) einzutragen. Der Anschreibentext kann unformatiert oder formatiert übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenFachlicheKommunikation1142\ProzessnachrichtenFachlicheKommunikation1142AnonymousPHPType\AnschreibenAnonymousPHPType $anschreiben
     */
    private $anschreiben = null;

    /**
     * Mit diesem Element können Metadaten zu Anlagen übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as bezug
     *
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen.
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
     * Bezug auf Vorgang und Nachricht, auf die sich die Inhalte der vorliegenden Nachricht beziehen.
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
     * Gets as anschreiben
     *
     * In dieses Element ist der Anschreibentext der Nachricht (Rückfrage des Beteiligten oder Antwort der Behörde) einzutragen. Der Anschreibentext kann unformatiert oder formatiert übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenFachlicheKommunikation1142\ProzessnachrichtenFachlicheKommunikation1142AnonymousPHPType\AnschreibenAnonymousPHPType
     */
    public function getAnschreiben()
    {
        return $this->anschreiben;
    }

    /**
     * Sets a new anschreiben
     *
     * In dieses Element ist der Anschreibentext der Nachricht (Rückfrage des Beteiligten oder Antwort der Behörde) einzutragen. Der Anschreibentext kann unformatiert oder formatiert übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenFachlicheKommunikation1142\ProzessnachrichtenFachlicheKommunikation1142AnonymousPHPType\AnschreibenAnonymousPHPType $anschreiben
     * @return self
     */
    public function setAnschreiben(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenFachlicheKommunikation1142\ProzessnachrichtenFachlicheKommunikation1142AnonymousPHPType\AnschreibenAnonymousPHPType $anschreiben)
    {
        $this->anschreiben = $anschreiben;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Mit diesem Element können Metadaten zu Anlagen übermittelt werden.
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
     * Mit diesem Element können Metadaten zu Anlagen übermittelt werden.
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
     * Mit diesem Element können Metadaten zu Anlagen übermittelt werden.
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
     * Mit diesem Element können Metadaten zu Anlagen übermittelt werden.
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
     * Mit diesem Element können Metadaten zu Anlagen übermittelt werden.
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

