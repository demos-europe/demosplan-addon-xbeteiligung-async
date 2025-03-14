<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink;

/**
 * Class representing ExtendedType
 *
 * Intended for use as the type of user-declared elements to make them
 *  extended links.
 *  Note that the elements referenced in the content model are all abstract.
 *  The intention is that by simply declaring elements with these as their
 *  substitutionGroup, all the right things will happen.
 * XSD Type: extended
 */
class ExtendedType
{
    /**
     * @var string $type
     */
    private $type = null;

    /**
     * @var string $role
     */
    private $role = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title $title
     */
    private $title = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd $resource
     */
    private $resource = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator $locator
     */
    private $locator = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc $arc
     */
    private $arc = null;

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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets a new title
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title $title
     * @return self
     */
    public function setTitle(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title $title = null)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Gets as resource
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets a new resource
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd $resource
     * @return self
     */
    public function setResource(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd $resource = null)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Gets as locator
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * Sets a new locator
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator $locator
     * @return self
     */
    public function setLocator(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator $locator = null)
    {
        $this->locator = $locator;
        return $this;
    }

    /**
     * Gets as arc
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc
     */
    public function getArc()
    {
        return $this->arc;
    }

    /**
     * Sets a new arc
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc $arc
     * @return self
     */
    public function setArc(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc $arc = null)
    {
        $this->arc = $arc;
        return $this;
    }
}

