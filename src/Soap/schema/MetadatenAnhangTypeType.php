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
     * Größe des Inhaltes der Datei in Bytes. Dieses Attribut kann verwendet werden, um die Dateigröße des Anhangs anzugeben. So kann bei der Übertragung überprüft werden, ob die Anlage komplett geladen wurde, hilfreich z.B. bei sehr großen Dateien.
     *
     * @var int $filesize
     */
    private $filesize = null;

    /**
     * Hashwert der Datei im Anhang. Anhand dieses Wertes kann bei der Übertragung die Integrität der Datei abgesichert werden. Der binäre Hashwert ist in Textform einzutragen als eine Sequenz hexadezimaler Werte (hexadecimal digits).
     *
     * @var string $hashValue
     */
    private $hashValue = null;

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
     * Gets as filesize
     *
     * Größe des Inhaltes der Datei in Bytes. Dieses Attribut kann verwendet werden, um die Dateigröße des Anhangs anzugeben. So kann bei der Übertragung überprüft werden, ob die Anlage komplett geladen wurde, hilfreich z.B. bei sehr großen Dateien.
     *
     * @return int
     */
    public function getFilesize()
    {
        return $this->filesize;
    }

    /**
     * Sets a new filesize
     *
     * Größe des Inhaltes der Datei in Bytes. Dieses Attribut kann verwendet werden, um die Dateigröße des Anhangs anzugeben. So kann bei der Übertragung überprüft werden, ob die Anlage komplett geladen wurde, hilfreich z.B. bei sehr großen Dateien.
     *
     * @param int $filesize
     * @return self
     */
    public function setFilesize($filesize)
    {
        $this->filesize = $filesize;
        return $this;
    }

    /**
     * Gets as hashValue
     *
     * Hashwert der Datei im Anhang. Anhand dieses Wertes kann bei der Übertragung die Integrität der Datei abgesichert werden. Der binäre Hashwert ist in Textform einzutragen als eine Sequenz hexadezimaler Werte (hexadecimal digits).
     *
     * @return string
     */
    public function getHashValue()
    {
        return $this->hashValue;
    }

    /**
     * Sets a new hashValue
     *
     * Hashwert der Datei im Anhang. Anhand dieses Wertes kann bei der Übertragung die Integrität der Datei abgesichert werden. Der binäre Hashwert ist in Textform einzutragen als eine Sequenz hexadezimaler Werte (hexadecimal digits).
     *
     * @param string $hashValue
     * @return self
     */
    public function setHashValue($hashValue)
    {
        $this->hashValue = $hashValue;
        return $this;
    }

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

