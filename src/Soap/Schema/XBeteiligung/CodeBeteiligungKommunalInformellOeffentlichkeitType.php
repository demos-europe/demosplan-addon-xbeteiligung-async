<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType;

/**
 * Class representing CodeBeteiligungKommunalInformellOeffentlichkeitType
 *
 * Hier kann die Art des informellen Verfahrens zur Beteiligung der Öffentlichkeit übermittelt werden.
 * XSD Type: Code.BeteiligungKommunalInformellOeffentlichkeit
 */
class CodeBeteiligungKommunalInformellOeffentlichkeitType extends CodeType
{
    /**
     * @var string $listURI
     */
    private $listURI = null;

    /**
     * @var string $listVersionID
     */
    private $listVersionID = null;

    /**
     * @var string $code
     */
    private $code = null;

    /**
     * @var string $name
     */
    private $name = null;

    /**
     * Gets as listURI
     *
     * @return string
     */
    public function getListURI()
    {
        return $this->listURI;
    }

    /**
     * Sets a new listURI
     *
     * @param string $listURI
     * @return self
     */
    public function setListURI($listURI)
    {
        $this->listURI = $listURI;
        return $this;
    }

    /**
     * Gets as listVersionID
     *
     * @return string
     */
    public function getListVersionID()
    {
        return $this->listVersionID;
    }

    /**
     * Sets a new listVersionID
     *
     * @param string $listVersionID
     * @return self
     */
    public function setListVersionID($listVersionID)
    {
        $this->listVersionID = $listVersionID;
        return $this;
    }

    /**
     * Gets as code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * @param string $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}

