<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AnhangOderVerlinkungType
 *
 * Dieser Typ beschreibt die Mechanismen, mit denen Anhänge mit einer Fachnachricht übermittelt werden können. Das Dokument wird entweder als Anhang (Attachment) zur Fachnachricht übermittelt (Metadaten dokumentid und dateiname). Sieht die Transportinfrastruktur keine Anhänge an Fachnachrichten vor, so kann das Element "dokument" zur Übermittlung innerhalb der Fachnachricht verwendet werden. Die Fachnachricht darf dann die Größe von 128 MB nicht überschreiten. Alternativ kann das Dokument dem Adressaten per Verlinkung zur Verfügung gestellt, also über ein Quellsystem, das sich in seiner Kontrolle befindet (Element uriVerlinkung).
 * XSD Type: AnhangOderVerlinkung
 */
class AnhangOderVerlinkungType
{
    /**
     * Das Dokument wird als Anhang mit dieser Fachnachricht übertragen. Dazu stehen unterhalb dieses Elements die passenden Metadaten.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangTypeType $anhang
     */
    private $anhang = null;

    /**
     * Hier kann das anzuhängende Dokument base64-codiert direkt innerhalb der Fachnachricht übermittelt werden, solange die Größe der Nachricht 128 MB nicht übersteigt,
     *
     * @var string $dokument
     */
    private $dokument = null;

    /**
     * Falls das Dokument über einen Link zugänglich gemacht werden soll, ist der Link in dieses Element einzutragen. Der Link identifiziert die Anlage (Primärdokument) in einem Quellsystem (das sich in Kontrolle der Behörde befindet) eindeutig.
     *
     * @var string $uriVerlinkung
     */
    private $uriVerlinkung = null;

    /**
     * Gets as anhang
     *
     * Das Dokument wird als Anhang mit dieser Fachnachricht übertragen. Dazu stehen unterhalb dieses Elements die passenden Metadaten.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangTypeType
     */
    public function getAnhang()
    {
        return $this->anhang;
    }

    /**
     * Sets a new anhang
     *
     * Das Dokument wird als Anhang mit dieser Fachnachricht übertragen. Dazu stehen unterhalb dieses Elements die passenden Metadaten.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangTypeType $anhang
     * @return self
     */
    public function setAnhang(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangTypeType $anhang = null)
    {
        $this->anhang = $anhang;
        return $this;
    }

    /**
     * Gets as dokument
     *
     * Hier kann das anzuhängende Dokument base64-codiert direkt innerhalb der Fachnachricht übermittelt werden, solange die Größe der Nachricht 128 MB nicht übersteigt,
     *
     * @return string
     */
    public function getDokument()
    {
        return $this->dokument;
    }

    /**
     * Sets a new dokument
     *
     * Hier kann das anzuhängende Dokument base64-codiert direkt innerhalb der Fachnachricht übermittelt werden, solange die Größe der Nachricht 128 MB nicht übersteigt,
     *
     * @param string $dokument
     * @return self
     */
    public function setDokument($dokument)
    {
        $this->dokument = $dokument;
        return $this;
    }

    /**
     * Gets as uriVerlinkung
     *
     * Falls das Dokument über einen Link zugänglich gemacht werden soll, ist der Link in dieses Element einzutragen. Der Link identifiziert die Anlage (Primärdokument) in einem Quellsystem (das sich in Kontrolle der Behörde befindet) eindeutig.
     *
     * @return string
     */
    public function getUriVerlinkung()
    {
        return $this->uriVerlinkung;
    }

    /**
     * Sets a new uriVerlinkung
     *
     * Falls das Dokument über einen Link zugänglich gemacht werden soll, ist der Link in dieses Element einzutragen. Der Link identifiziert die Anlage (Primärdokument) in einem Quellsystem (das sich in Kontrolle der Behörde befindet) eindeutig.
     *
     * @param string $uriVerlinkung
     * @return self
     */
    public function setUriVerlinkung($uriVerlinkung)
    {
        $this->uriVerlinkung = $uriVerlinkung;
        return $this;
    }
}

