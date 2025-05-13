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
     * @return string
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Sets a new lang
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

