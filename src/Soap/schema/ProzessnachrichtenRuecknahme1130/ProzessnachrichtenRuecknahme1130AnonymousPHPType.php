<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenRuecknahme1130;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing ProzessnachrichtenRuecknahme1130AnonymousPHPType
 */
class ProzessnachrichtenRuecknahme1130AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * In dieses Element sind die Referenzen von Antragsteller und ggf. Behörde einzutragen. Eine Referenzierung auf eine Nachricht wird in diese Nachricht nicht eingetragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug
     */
    private $bezug = null;

    /**
     * Hier kann optional durch den Antragsteller eine Begründung eingetragen werden.
     *
     * @var string[] $information
     */
    private $information = null;

    /**
     * Ggf. will der Antragsteller der Rücknahme des Antrags ein Schreiben beilegen. Das lässt sich mit diesem Element abbilden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as bezug
     *
     * In dieses Element sind die Referenzen von Antragsteller und ggf. Behörde einzutragen. Eine Referenzierung auf eine Nachricht wird in diese Nachricht nicht eingetragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType
     */
    public function getBezug()
    {
        return $this->bezug;
    }

    /**
     * Sets a new bezug
     *
     * In dieses Element sind die Referenzen von Antragsteller und ggf. Behörde einzutragen. Eine Referenzierung auf eine Nachricht wird in diese Nachricht nicht eingetragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug
     * @return self
     */
    public function setBezug(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug)
    {
        $this->bezug = $bezug;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * Hier kann optional durch den Antragsteller eine Begründung eingetragen werden.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToInformation($textabsatz)
    {
        $this->information[] = $textabsatz;
        return $this;
    }

    /**
     * isset information
     *
     * Hier kann optional durch den Antragsteller eine Begründung eingetragen werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetInformation($index)
    {
        return isset($this->information[$index]);
    }

    /**
     * unset information
     *
     * Hier kann optional durch den Antragsteller eine Begründung eingetragen werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetInformation($index)
    {
        unset($this->information[$index]);
    }

    /**
     * Gets as information
     *
     * Hier kann optional durch den Antragsteller eine Begründung eingetragen werden.
     *
     * @return string[]
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Sets a new information
     *
     * Hier kann optional durch den Antragsteller eine Begründung eingetragen werden.
     *
     * @param string[] $information
     * @return self
     */
    public function setInformation(array $information = null)
    {
        $this->information = $information;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Ggf. will der Antragsteller der Rücknahme des Antrags ein Schreiben beilegen. Das lässt sich mit diesem Element abbilden.
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
     * Ggf. will der Antragsteller der Rücknahme des Antrags ein Schreiben beilegen. Das lässt sich mit diesem Element abbilden.
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
     * Ggf. will der Antragsteller der Rücknahme des Antrags ein Schreiben beilegen. Das lässt sich mit diesem Element abbilden.
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
     * Ggf. will der Antragsteller der Rücknahme des Antrags ein Schreiben beilegen. Das lässt sich mit diesem Element abbilden.
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
     * Ggf. will der Antragsteller der Rücknahme des Antrags ein Schreiben beilegen. Das lässt sich mit diesem Element abbilden.
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

