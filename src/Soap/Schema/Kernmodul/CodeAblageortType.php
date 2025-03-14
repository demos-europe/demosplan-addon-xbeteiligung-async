<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType;

/**
 * Class representing CodeAblageortType
 *
 * Diese Codeliste enthält Ablageorte des Projektraums, an dem ein Dokument abgelegt werden soll. In diesen Typ ist eine auszuwählende bzw. selbst zu definierende Codeliste einzubinden, die eine solche Klassifikation bietet. Im Anwendungskontext sind in die Attribute des vorliegenden Typs die Codelisten-URI und die Nummer der Version der ausgewählten Codeliste (in die XBau-Nachrichteninstanzen) einzutragen.
 * XSD Type: Code.Ablageort
 */
class CodeAblageortType extends CodeType
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

