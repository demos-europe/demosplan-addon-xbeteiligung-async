<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType;

/**
 * Class representing CodeTextOfficeMimeTypeType
 *
 * Dieser Code-Typ steht für die Detaillierung des Office MimeTypes von in XBau-Nachrichten als ODF oder OOXML dargestelltem Office Text. Die Codeliste ist im XRepository (www.xrepository.de) unter Nennung ihrer Codelisten-URI auffindbar und kann von dort im XML-Format OASIS Genericode abgerufen werden.
 * XSD Type: Code.TextOfficeMimeType
 */
class CodeTextOfficeMimeTypeType extends CodeType
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
}

