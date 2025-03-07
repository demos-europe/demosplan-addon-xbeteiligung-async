<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing LocatorTypeType
 *
 *
 * XSD Type: locatorType
 */
class LocatorTypeType
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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Title $title
     */
    private $title = null;

    /**
     * @var string $label
     */
    private $label = null;

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
     * Gets as label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets a new label
     *
     * @param string $label
     * @return self
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }
}

