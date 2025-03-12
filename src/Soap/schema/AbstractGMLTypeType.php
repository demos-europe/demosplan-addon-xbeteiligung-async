<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AbstractGMLTypeType
 *
 *
 * XSD Type: AbstractGMLType
 */
class AbstractGMLTypeType
{
    /**
     * @var string $id
     */
    private $id = null;

    /**
     * @var string $description
     */
    private $description = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DescriptionReference $descriptionReference
     */
    private $descriptionReference = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Identifier $identifier
     */
    private $identifier = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Name $name
     */
    private $name = null;

    /**
     * Gets as id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets as description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Sets a new description
     *
     * @param string $description
     * @return self
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Gets as descriptionReference
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DescriptionReference
     */
    public function getDescriptionReference()
    {
        return $this->descriptionReference;
    }

    /**
     * Sets a new descriptionReference
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DescriptionReference $descriptionReference
     * @return self
     */
    public function setDescriptionReference(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\DescriptionReference $descriptionReference = null)
    {
        $this->descriptionReference = $descriptionReference;
        return $this;
    }

    /**
     * Gets as identifier
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Identifier
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Sets a new identifier
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Identifier $identifier
     * @return self
     */
    public function setIdentifier(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Identifier $identifier = null)
    {
        $this->identifier = $identifier;
        return $this;
    }

    /**
     * Gets as name
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Name $name
     * @return self
     */
    public function setName(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Name $name = null)
    {
        $this->name = $name;
        return $this;
    }
}

