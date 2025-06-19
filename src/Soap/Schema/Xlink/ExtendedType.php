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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title[] $title
     */
    private $title = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd[] $resource
     */
    private $resource = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator[] $locator
     */
    private $locator = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc[] $arc
     */
    private $arc = [
        
    ];

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
     * Adds as title
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title $title
     */
    public function addToTitle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title $title)
    {
        $this->title[] = $title;
        return $this;
    }

    /**
     * isset title
     *
     * @param int|string $index
     * @return bool
     */
    public function issetTitle($index)
    {
        return isset($this->title[$index]);
    }

    /**
     * unset title
     *
     * @param int|string $index
     * @return void
     */
    public function unsetTitle($index)
    {
        unset($this->title[$index]);
    }

    /**
     * Gets as title
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title[]
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Sets a new title
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Title[] $title
     * @return self
     */
    public function setTitle(array $title = null)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Adds as resource
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd $resource
     */
    public function addToResource(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd $resource)
    {
        $this->resource[] = $resource;
        return $this;
    }

    /**
     * isset resource
     *
     * @param int|string $index
     * @return bool
     */
    public function issetResource($index)
    {
        return isset($this->resource[$index]);
    }

    /**
     * unset resource
     *
     * @param int|string $index
     * @return void
     */
    public function unsetResource($index)
    {
        unset($this->resource[$index]);
    }

    /**
     * Gets as resource
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd[]
     */
    public function getResource()
    {
        return $this->resource;
    }

    /**
     * Sets a new resource
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\ResourceXsd[] $resource
     * @return self
     */
    public function setResource(array $resource = null)
    {
        $this->resource = $resource;
        return $this;
    }

    /**
     * Adds as locator
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator $locator
     */
    public function addToLocator(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator $locator)
    {
        $this->locator[] = $locator;
        return $this;
    }

    /**
     * isset locator
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLocator($index)
    {
        return isset($this->locator[$index]);
    }

    /**
     * unset locator
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLocator($index)
    {
        unset($this->locator[$index]);
    }

    /**
     * Gets as locator
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator[]
     */
    public function getLocator()
    {
        return $this->locator;
    }

    /**
     * Sets a new locator
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Locator[] $locator
     * @return self
     */
    public function setLocator(array $locator = null)
    {
        $this->locator = $locator;
        return $this;
    }

    /**
     * Adds as arc
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc $arc
     */
    public function addToArc(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc $arc)
    {
        $this->arc[] = $arc;
        return $this;
    }

    /**
     * isset arc
     *
     * @param int|string $index
     * @return bool
     */
    public function issetArc($index)
    {
        return isset($this->arc[$index]);
    }

    /**
     * unset arc
     *
     * @param int|string $index
     * @return void
     */
    public function unsetArc($index)
    {
        unset($this->arc[$index]);
    }

    /**
     * Gets as arc
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc[]
     */
    public function getArc()
    {
        return $this->arc;
    }

    /**
     * Sets a new arc
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink\Arc[] $arc
     * @return self
     */
    public function setArc(array $arc = null)
    {
        $this->arc = $arc;
        return $this;
    }
}

