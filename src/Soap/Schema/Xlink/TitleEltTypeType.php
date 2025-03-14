<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Xlink;

/**
 * Class representing TitleEltTypeType
 *
 *
 * XSD Type: titleEltType
 */
class TitleEltTypeType
{
    /**
     * @var string $type
     */
    private $type = null;

    /**
     * xml:lang is not required, but provides much of the
     *  motivation for title elements in addition to attributes, and so
     *  is provided here for convenience.
     *
     * @var string $lang
     */
    private $lang = null;

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
     * Gets as lang
     *
     * xml:lang is not required, but provides much of the
     *  motivation for title elements in addition to attributes, and so
     *  is provided here for convenience.
     *
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Sets a new lang
     *
     * xml:lang is not required, but provides much of the
     *  motivation for title elements in addition to attributes, and so
     *  is provided here for convenience.
     *
     * @param string $lang
     * @return self
     */
    public function setLang($lang)
    {
        $this->lang = $lang;
        return $this;
    }
}

