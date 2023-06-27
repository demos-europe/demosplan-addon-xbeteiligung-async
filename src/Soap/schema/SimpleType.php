<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing SimpleType
 *
 * Intended for use as the type of user-declared elements to make them
 *  simple links.
 * XSD Type: simple
 */
class SimpleType
{
    /**
     * @var string $type
     */
    private $type = null;

    /**
     * @var string $href
     */
    private $href = null;

    /**
     * @var string $role
     */
    private $role = null;

    /**
     * @var string $arcrole
     */
    private $arcrole = null;

    /**
     * @var string $title
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
     * Gets as href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Sets a new href
     *
     * @param string $href
     * @return self
     */
    public function setHref($href)
    {
        $this->href = $href;
        return $this;
    }

    /**
     * Gets as role
     *
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Sets a new role
     *
     * @param string $role
     * @return self
     */
    public function setRole($role)
    {
        $this->role = $role;
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets a new title
     *
     * @param string $title
     * @return self
     */
    public function setTitle($title)
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
}

