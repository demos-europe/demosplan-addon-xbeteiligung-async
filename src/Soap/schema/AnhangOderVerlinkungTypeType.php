<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AnhangOderVerlinkungTypeType
 *
 * Dieser Typ beschreibt die Mechanismen, mit denen eine Bauvorlage oder sonstige Anlage im Zusammenhang mit einer XBau-Fachnachricht übermittelt wird. Die Bauvorlage bzw. sonstige Anlage wird entweder als Anhang (Attachment) zur XBau-Fachnachricht übermittelt (Metadaten dokumentid und dateiname). Oder sie wird dem Adressaten per Verlinkung zur Verfügung gestellt, also über ein Quellsystem, das sich in seiner Kontrolle befindet (Element uriVerlinkung).
 * XSD Type: AnhangOderVerlinkungType
 */
class AnhangOderVerlinkungTypeType
{
    /**
     * Das Dokument wird als Anhang mit dieser XBau-Fachnachricht übertragen. Dazu stehen unterhalb dieses Elements die passenden Metadaten .
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnhangTypeType $anhang
     */
    private $anhang = null;

    /**
     * Falls das Dokument über einen Link zugänglich gemacht werden soll, ist der Link in dieses Element einzutragen. Der Link identifiziert die Anlage (Primärdokument) in einem Quellsystem (das sich in Kontrolle der Behörde befindet) eindeutig. Die Verlinkung auf ein Quellsystem entspricht der Architektur der (externen) Verlinkung.
     *
     * @var string $uriVerlinkung
     */
    private $uriVerlinkung = null;

    /**
     * Gets as anhang
     *
     * Das Dokument wird als Anhang mit dieser XBau-Fachnachricht übertragen. Dazu stehen unterhalb dieses Elements die passenden Metadaten .
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
     * Das Dokument wird als Anhang mit dieser XBau-Fachnachricht übertragen. Dazu stehen unterhalb dieses Elements die passenden Metadaten .
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
     * Gets as uriVerlinkung
     *
     * Falls das Dokument über einen Link zugänglich gemacht werden soll, ist der Link in dieses Element einzutragen. Der Link identifiziert die Anlage (Primärdokument) in einem Quellsystem (das sich in Kontrolle der Behörde befindet) eindeutig. Die Verlinkung auf ein Quellsystem entspricht der Architektur der (externen) Verlinkung.
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
     * Falls das Dokument über einen Link zugänglich gemacht werden soll, ist der Link in dieses Element einzutragen. Der Link identifiziert die Anlage (Primärdokument) in einem Quellsystem (das sich in Kontrolle der Behörde befindet) eindeutig. Die Verlinkung auf ein Quellsystem entspricht der Architektur der (externen) Verlinkung.
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

