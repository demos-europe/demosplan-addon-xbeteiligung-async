<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType;

/**
 * Class representing CodeXBauMimeTypeType
 *
 * Dieser Code-Typ steht für eine Benennung des MimeTypes einer über XBau bereitgestellten Ressource. In diesen Typ ist eine auszuwählende bzw. selbst zu definierende Codeliste einzubinden, die eine dafür passende Auflistung bietet. Im Anwendungskontext sind in die Attribute des vorliegenden Typs die Codelisten-URI und die Nummer der Version der ausgewählten Codeliste (in die XBau-Nachrichteninstanzen) einzutragen. Als Muster wurde eine passende Codeliste definiert und als Angebot zur Einbindung für diesen Typ bereitgestellt. Diese Codeliste kann auf Antrag erweitert bzw. geändert werden. Sie ist im XRepository (www.xrepository.de) unter der Codelisten-URI urn:xoev-de:xbau:codeliste:xbau-mimetypes auffindbar und kann von dort im XML-Format OASIS Genericode abgerufen werden.
 * XSD Type: Code.XBau-MimeType
 */
class CodeXBauMimeTypeType extends CodeType
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

