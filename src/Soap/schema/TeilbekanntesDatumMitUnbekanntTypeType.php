<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing TeilbekanntesDatumMitUnbekanntTypeType
 *
 * Mit diesem Datentyp kann entweder ein teilweise bekanntes Datum übermittelt oder angegeben werden, dass ein Tagesdatum vollständig unbekannt ist. Ist das Datum vollständig unbekannt, wird das Kindelement unbekannt übermittelt, welches den Wert true enthält.
 * XSD Type: TeilbekanntesDatumMitUnbekanntType
 */
class TeilbekanntesDatumMitUnbekanntTypeType
{
    /**
     * Das teilweise bekannte Datum
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TeilbekanntesDatumTypeType $teilbekanntesDatum
     */
    private $teilbekanntesDatum = null;

    /**
     * Die Verwendung dieses Merkmals zeigt an, dass das Datum vollständig unbekannt ist.
     *
     * @var bool $unbekannt
     */
    private $unbekannt = null;

    /**
     * Gets as teilbekanntesDatum
     *
     * Das teilweise bekannte Datum
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TeilbekanntesDatumTypeType
     */
    public function getTeilbekanntesDatum()
    {
        return $this->teilbekanntesDatum;
    }

    /**
     * Sets a new teilbekanntesDatum
     *
     * Das teilweise bekannte Datum
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TeilbekanntesDatumTypeType $teilbekanntesDatum
     * @return self
     */
    public function setTeilbekanntesDatum(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\TeilbekanntesDatumTypeType $teilbekanntesDatum = null)
    {
        $this->teilbekanntesDatum = $teilbekanntesDatum;
        return $this;
    }

    /**
     * Gets as unbekannt
     *
     * Die Verwendung dieses Merkmals zeigt an, dass das Datum vollständig unbekannt ist.
     *
     * @return bool
     */
    public function getUnbekannt()
    {
        return $this->unbekannt;
    }

    /**
     * Sets a new unbekannt
     *
     * Die Verwendung dieses Merkmals zeigt an, dass das Datum vollständig unbekannt ist.
     *
     * @param bool $unbekannt
     * @return self
     */
    public function setUnbekannt($unbekannt)
    {
        $this->unbekannt = $unbekannt;
        return $this;
    }
}

