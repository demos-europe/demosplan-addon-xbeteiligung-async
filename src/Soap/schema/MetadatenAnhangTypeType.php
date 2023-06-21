<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing MetadatenAnhangTypeType
 *
 * Dieser Typ dient zur Aufnahme der Metadaten eines lokalen Anhangs, der mit einer XBau-Fachnachricht übertragen wird.
 * XSD Type: MetadatenAnhangType
 */
class MetadatenAnhangTypeType
{
    /**
     * Die innerhalb des Transportcontainers eindeutige Kennung des Dokuments.
     *
     * @var string $dokumentid
     */
    private $dokumentid = null;

    /**
     * Hier ist der Name der Datei (Primärdokument) einzutragen, eindeutig innerhalb des Transportcontainers.
     *
     * @var string $dateiname
     */
    private $dateiname = null;

    /**
     * Gets as dokumentid
     *
     * Die innerhalb des Transportcontainers eindeutige Kennung des Dokuments.
     *
     * @return string
     */
    public function getDokumentid()
    {
        return $this->dokumentid;
    }

    /**
     * Sets a new dokumentid
     *
     * Die innerhalb des Transportcontainers eindeutige Kennung des Dokuments.
     *
     * @param string $dokumentid
     * @return self
     */
    public function setDokumentid($dokumentid)
    {
        $this->dokumentid = $dokumentid;
        return $this;
    }

    /**
     * Gets as dateiname
     *
     * Hier ist der Name der Datei (Primärdokument) einzutragen, eindeutig innerhalb des Transportcontainers.
     *
     * @return string
     */
    public function getDateiname()
    {
        return $this->dateiname;
    }

    /**
     * Sets a new dateiname
     *
     * Hier ist der Name der Datei (Primärdokument) einzutragen, eindeutig innerhalb des Transportcontainers.
     *
     * @param string $dateiname
     * @return self
     */
    public function setDateiname($dateiname)
    {
        $this->dateiname = $dateiname;
        return $this;
    }
}

