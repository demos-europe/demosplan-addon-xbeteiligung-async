<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing TagesdatumMitUnbekanntType
 *
 * Mit diesem Datentyp kann entweder ein vollständiges Tagesdatum übermittelt werden oder angegeben werden, dass ein Tagesdatum unbekannt ist. Falls das Tagesdatum vollständig bekannt ist, wird dieses im Kindelement datum übermittelt. Andernfalls wird das Kindelement unbekannt übermittelt, welches den Wert true enthält.
 * XSD Type: TagesdatumMitUnbekannt
 */
class TagesdatumMitUnbekanntType
{
    /**
     * Das vollständig bekannte Datum.
     *
     * @var \DateTime $datum
     */
    private $datum = null;

    /**
     * Das Merkmal mit dem angezeigt wird, dass das Datum unbekannt ist.
     *
     * @var bool $unbekannt
     */
    private $unbekannt = null;

    /**
     * Gets as datum
     *
     * Das vollständig bekannte Datum.
     *
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * Sets a new datum
     *
     * Das vollständig bekannte Datum.
     *
     * @param \DateTime $datum
     * @return self
     */
    public function setDatum(\DateTime $datum = null)
    {
        $this->datum = $datum;
        return $this;
    }

    /**
     * Gets as unbekannt
     *
     * Das Merkmal mit dem angezeigt wird, dass das Datum unbekannt ist.
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
     * Das Merkmal mit dem angezeigt wird, dass das Datum unbekannt ist.
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

