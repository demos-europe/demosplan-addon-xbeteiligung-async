<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml;

/**
 * Class representing MultiPointPropertyTypeType
 *
 *
 * XSD Type: MultiPointPropertyType
 */
class MultiPointPropertyTypeType
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
     * @var string $nilReason
     */
    private $nilReason = null;

    /**
     * @var bool $owns
     */
    private $owns = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\MultiPoint $multiPoint
     */
    private $multiPoint = null;

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

    /**
     * Gets as nilReason
     *
     * @return string
     */
    public function getNilReason()
    {
        return $this->nilReason;
    }

    /**
     * Sets a new nilReason
     *
     * @param string $nilReason
     * @return self
     */
    public function setNilReason($nilReason)
    {
        $this->nilReason = $nilReason;
        return $this;
    }

    /**
     * Gets as owns
     *
     * @return bool
     */
    public function getOwns()
    {
        return $this->owns;
    }

    /**
     * Sets a new owns
     *
     * @param bool $owns
     * @return self
     */
    public function setOwns($owns)
    {
        $this->owns = $owns;
        return $this;
    }

    /**
     * Gets as multiPoint
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\MultiPoint
     */
    public function getMultiPoint()
    {
        return $this->multiPoint;
    }

    /**
     * Sets a new multiPoint
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\MultiPoint $multiPoint
     * @return self
     */
    public function setMultiPoint(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Gml\MultiPoint $multiPoint = null)
    {
        $this->multiPoint = $multiPoint;
        return $this;
    }
}

