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
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangType $anhang
     */
    private $anhang = null;

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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangType $anhang
     * @return self
     */
    public function setAnhang(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangType $anhang = null)
    {
        $this->anhang = $anhang;
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

