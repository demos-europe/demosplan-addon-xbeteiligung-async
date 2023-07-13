<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing MetadatenAnlageTypeType
 *
 * Dieser Kernmodul-Basistyp bildet die Metadaten zu einer Bauvorlage bzw. sonstigen Anlage ab, die gemäß der Architektur der Übertragung von Primärdokumenten im Zusammenhang mit einer XBau-Fachnachricht benötigt werden. XBau-Fachmodule können nach ihrem Bedarf vom vorliegenden Basistyp ableiten.
 * XSD Type: MetadatenAnlageType
 */
class MetadatenAnlageTypeType extends MetadatenAnlageTypeType
{
    /**
     * Hier ist eine deskriptive Bezeichnung der Bauvorlage bzw. sonstigen Anlage einzutragen, z. B. "Grundriss 3. OG".
     *
     * @var string $bezeichnung
     */
    private $bezeichnung = null;

    /**
     * Hier ist eine Versionsnummer einzutragen. Sie dient dazu, (sequentiell eingereichte) Bauvorlagen (oder sonstige Anlagen) zu unterscheiden, die dieselbe Bezeichnung tragen.
     *
     * @var int $versionsnummer
     */
    private $versionsnummer = null;

    /**
     * Hier ist das Datum der Erstellung bzw. der letzten Bearbeitung dieser Bauvorlage bzw. sonstigen Anlage einzutragen.
     *
     * @var \DateTime $datum
     */
    private $datum = null;

    /**
     * Unterhalb dieses Elements wird die Art dieser Bauvorlage oder sonstigen Anlage näher spezifiziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType $anlageart
     */
    private $anlageart = null;

    /**
     * Dieses Element nennt - analog zur Übermittlung von E-Mail-Anlagen - den MIME-Typ der angehängten oder verlinkten Bauvorlage bzw. sonstigen Anlage (z.B. text/xml, text/plain, application/gzip oder application/pdf). Die Angabe ist mandatorisch, weil eine für den Empfänger zur Verarbeitung der Daten notwendige Information. Als Anlagen können u.a. auch IFC-Darstellungen (gemäß der Technologie Building Information Modeling (BIM)) des Bauvorhabens übermittelt werden. Entsprechende Kennzeichnungen des mimeTypes (ifc, ifcXML, fcZIP) sind dafür verfügbar.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType $mimeType
     */
    private $mimeType = null;

    /**
     * Eine Instanz dieses Elements enthält passende Metadaten zur Bauvorlage bzw. sonstigen Anlage, je nachdem ob diese (i) als Anhang mit dieser XBau-Nachricht übermittelt wird oder ob sie (ii) über einen Link zugänglich gemacht wird, der in diese XBau-Nachricht eingetragen ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungTypeType $anhangOderVerlinkung
     */
    private $anhangOderVerlinkung = null;

    /**
     * Gets as bezeichnung
     *
     * Hier ist eine deskriptive Bezeichnung der Bauvorlage bzw. sonstigen Anlage einzutragen, z. B. "Grundriss 3. OG".
     *
     * @return string
     */
    public function getBezeichnung()
    {
        return $this->bezeichnung;
    }

    /**
     * Sets a new bezeichnung
     *
     * Hier ist eine deskriptive Bezeichnung der Bauvorlage bzw. sonstigen Anlage einzutragen, z. B. "Grundriss 3. OG".
     *
     * @param string $bezeichnung
     * @return self
     */
    public function setBezeichnung($bezeichnung)
    {
        $this->bezeichnung = $bezeichnung;
        return $this;
    }

    /**
     * Gets as versionsnummer
     *
     * Hier ist eine Versionsnummer einzutragen. Sie dient dazu, (sequentiell eingereichte) Bauvorlagen (oder sonstige Anlagen) zu unterscheiden, die dieselbe Bezeichnung tragen.
     *
     * @return int
     */
    public function getVersionsnummer()
    {
        return $this->versionsnummer;
    }

    /**
     * Sets a new versionsnummer
     *
     * Hier ist eine Versionsnummer einzutragen. Sie dient dazu, (sequentiell eingereichte) Bauvorlagen (oder sonstige Anlagen) zu unterscheiden, die dieselbe Bezeichnung tragen.
     *
     * @param int $versionsnummer
     * @return self
     */
    public function setVersionsnummer($versionsnummer)
    {
        $this->versionsnummer = $versionsnummer;
        return $this;
    }

    /**
     * Gets as datum
     *
     * Hier ist das Datum der Erstellung bzw. der letzten Bearbeitung dieser Bauvorlage bzw. sonstigen Anlage einzutragen.
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
     * Hier ist das Datum der Erstellung bzw. der letzten Bearbeitung dieser Bauvorlage bzw. sonstigen Anlage einzutragen.
     *
     * @param \DateTime $datum
     * @return self
     */
    public function setDatum(?\DateTime $datum = null)
    {
        $this->datum = $datum;
        return $this;
    }

    /**
     * Gets as anlageart
     *
     * Unterhalb dieses Elements wird die Art dieser Bauvorlage oder sonstigen Anlage näher spezifiziert.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType
     */
    public function getAnlageart()
    {
        return $this->anlageart;
    }

    /**
     * Sets a new anlageart
     *
     * Unterhalb dieses Elements wird die Art dieser Bauvorlage oder sonstigen Anlage näher spezifiziert.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType $anlageart
     * @return self
     */
    public function setAnlageart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType $anlageart = null)
    {
        $this->anlageart = $anlageart;
        return $this;
    }

    /**
     * Gets as mimeType
     *
     * Dieses Element nennt - analog zur Übermittlung von E-Mail-Anlagen - den MIME-Typ der angehängten oder verlinkten Bauvorlage bzw. sonstigen Anlage (z.B. text/xml, text/plain, application/gzip oder application/pdf). Die Angabe ist mandatorisch, weil eine für den Empfänger zur Verarbeitung der Daten notwendige Information. Als Anlagen können u.a. auch IFC-Darstellungen (gemäß der Technologie Building Information Modeling (BIM)) des Bauvorhabens übermittelt werden. Entsprechende Kennzeichnungen des mimeTypes (ifc, ifcXML, fcZIP) sind dafür verfügbar.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Sets a new mimeType
     *
     * Dieses Element nennt - analog zur Übermittlung von E-Mail-Anlagen - den MIME-Typ der angehängten oder verlinkten Bauvorlage bzw. sonstigen Anlage (z.B. text/xml, text/plain, application/gzip oder application/pdf). Die Angabe ist mandatorisch, weil eine für den Empfänger zur Verarbeitung der Daten notwendige Information. Als Anlagen können u.a. auch IFC-Darstellungen (gemäß der Technologie Building Information Modeling (BIM)) des Bauvorhabens übermittelt werden. Entsprechende Kennzeichnungen des mimeTypes (ifc, ifcXML, fcZIP) sind dafür verfügbar.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType $mimeType
     * @return self
     */
    public function setMimeType(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType $mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }

    /**
     * Gets as anhangOderVerlinkung
     *
     * Eine Instanz dieses Elements enthält passende Metadaten zur Bauvorlage bzw. sonstigen Anlage, je nachdem ob diese (i) als Anhang mit dieser XBau-Nachricht übermittelt wird oder ob sie (ii) über einen Link zugänglich gemacht wird, der in diese XBau-Nachricht eingetragen ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungTypeType
     */
    public function getAnhangOderVerlinkung()
    {
        return $this->anhangOderVerlinkung;
    }

    /**
     * Sets a new anhangOderVerlinkung
     *
     * Eine Instanz dieses Elements enthält passende Metadaten zur Bauvorlage bzw. sonstigen Anlage, je nachdem ob diese (i) als Anhang mit dieser XBau-Nachricht übermittelt wird oder ob sie (ii) über einen Link zugänglich gemacht wird, der in diese XBau-Nachricht eingetragen ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungTypeType $anhangOderVerlinkung
     * @return self
     */
    public function setAnhangOderVerlinkung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungTypeType $anhangOderVerlinkung = null)
    {
        $this->anhangOderVerlinkung = $anhangOderVerlinkung;
        return $this;
    }
}

