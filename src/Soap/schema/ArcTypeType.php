<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing ArcTypeType
 *
 *
 * XSD Type: arcType
 */
class ArcTypeType
{
    /**
     * @var string $type
     */
    private $type = null;

    /**
     * @var string $arcrole
     */
    private $arcrole = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Title $title
     */
    private $title = null;

    /**
     * @var string $show
     */
    private $show = null;

    /**
     * @var string $actuate
     */
    private $actuate = null;

    /**
     * @var string $from
     */
    private $from = null;

    /**
     * @var string $to
     */
    private $to = null;

    /**
     * Gets as type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Sets a new type
     *
     * @param string $type
     * @return self
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Gets as arcrole
     *
     * @return string
     */
    public function getArcrole()
    {
        return $this->arcrole;
    }

    /**
     * Sets a new arcrole
     *
     * @param string $arcrole
     * @return self
     */
    public function setArcrole($arcrole)
    {
        $this->arcrole = $arcrole;
        return $this;
    }

    /**
     * Gets as title
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets a new title
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Title $title
     * @return self
     */
    public function setTitle(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Title $title = null)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Gets as show
     *
     * @return string
     */
    public function getShow()
    {
        return $this->show;
    }

    /**
     * Sets a new show
     *
     * @param string $show
     * @return self
     */
    public function setShow($show)
    {
        $this->show = $show;
        return $this;
    }

    /**
     * Gets as actuate
     *
     * @return string
     */
    public function getActuate()
    {
        return $this->actuate;
    }

    /**
     * Sets a new actuate
     *
     * @param string $actuate
     * @return self
     */
    public function setActuate($actuate)
    {
        $this->actuate = $actuate;
        return $this;
    }

    /**
     * Gets as from
     *
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Sets a new from
     *
     * @param string $from
     * @return self
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Gets as to
     *
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Sets a new to
     *
     * @param string $to
     * @return self
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }
}

