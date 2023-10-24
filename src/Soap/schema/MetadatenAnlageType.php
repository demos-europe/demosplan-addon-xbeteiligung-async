<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing MetadatenAnlageType
 *
 * Dieser Datentyp bildet die Metadaten zu einer Anlage ab. Er basiert auf der Modellierung des XBau-Kernmoduls 1.2, erlaubt aber im Gegensatz zu diesem auch die Übermittlung von Anhängen innerhalb der Fachnachricht als base64-codiertem Binary. Bei der Übermittlung innerhalb der Fachnachricht ist darauf zu achten, dass die Größe der Fachnachricht inkl. Dokument 128 MB nicht überschreitet.
 * XSD Type: MetadatenAnlage
 */
class MetadatenAnlageType
{
    /**
     * Hier ist eine deskriptive Bezeichnung der Anlage einzutragen.
     *
     * @var string $bezeichnung
     */
    private $bezeichnung = null;

    /**
     * Hier ist eine Versionsnummer einzutragen. Sie dient dazu, Anlagen zu unterscheiden, die dieselbe Bezeichnung tragen.
     *
     * @var int $versionsnummer
     */
    private $versionsnummer = null;

    /**
     * Hier ist das Datum der Erstellung bzw. der letzten Bearbeitung dieser Anlage einzutragen.
     *
     * @var \DateTime $datum
     */
    private $datum = null;

    /**
     * Hier ist, falls möglich die Dokumentenkategorie anzugeben.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensunterlagetypType $anlageart
     */
    private $anlageart = null;

    /**
     * Dieses Element nennt - analog zur Übermittlung von E-Mail-Anlagen - den MIME-Typ der angehängten oder verlinkten Anlage (z.B. text/xml, text/plain, application/gzip oder application/pdf). Die Angabe ist mandatorisch, weil eine für den Empfänger zur Verarbeitung der Daten notwendige Information.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType $mimeType
     */
    private $mimeType = null;

    /**
     * Dieses Elements enthält die Angabe, ob die Anlage (i) als Anhang mit dieser XBeteiligungs-Nachricht übermittelt wird, (ii) über einen Link zugänglich gemacht wird, der in diese XBeteiligungs-Nachricht eingetragen ist oder ob sie (iii) base64-codiert innerhalb dieser XBeteiligungs-Nachricht übermittelt wird.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungType $anhangOderVerlinkung
     */
    private $anhangOderVerlinkung = null;

    /**
     * Gets as bezeichnung
     *
     * Hier ist eine deskriptive Bezeichnung der Anlage einzutragen.
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
     * Hier ist eine deskriptive Bezeichnung der Anlage einzutragen.
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
     * Hier ist eine Versionsnummer einzutragen. Sie dient dazu, Anlagen zu unterscheiden, die dieselbe Bezeichnung tragen.
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
     * Hier ist eine Versionsnummer einzutragen. Sie dient dazu, Anlagen zu unterscheiden, die dieselbe Bezeichnung tragen.
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
     * Hier ist das Datum der Erstellung bzw. der letzten Bearbeitung dieser Anlage einzutragen.
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
     * Hier ist das Datum der Erstellung bzw. der letzten Bearbeitung dieser Anlage einzutragen.
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
     * Hier ist, falls möglich die Dokumentenkategorie anzugeben.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensunterlagetypType
     */
    public function getAnlageart()
    {
        return $this->anlageart;
    }

    /**
     * Sets a new anlageart
     *
     * Hier ist, falls möglich die Dokumentenkategorie anzugeben.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensunterlagetypType $anlageart
     * @return self
     */
    public function setAnlageart(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensunterlagetypType $anlageart = null)
    {
        $this->anlageart = $anlageart;
        return $this;
    }

    /**
     * Gets as mimeType
     *
     * Dieses Element nennt - analog zur Übermittlung von E-Mail-Anlagen - den MIME-Typ der angehängten oder verlinkten Anlage (z.B. text/xml, text/plain, application/gzip oder application/pdf). Die Angabe ist mandatorisch, weil eine für den Empfänger zur Verarbeitung der Daten notwendige Information.
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
     * Dieses Element nennt - analog zur Übermittlung von E-Mail-Anlagen - den MIME-Typ der angehängten oder verlinkten Anlage (z.B. text/xml, text/plain, application/gzip oder application/pdf). Die Angabe ist mandatorisch, weil eine für den Empfänger zur Verarbeitung der Daten notwendige Information.
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
     * Dieses Elements enthält die Angabe, ob die Anlage (i) als Anhang mit dieser XBeteiligungs-Nachricht übermittelt wird, (ii) über einen Link zugänglich gemacht wird, der in diese XBeteiligungs-Nachricht eingetragen ist oder ob sie (iii) base64-codiert innerhalb dieser XBeteiligungs-Nachricht übermittelt wird.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungType
     */
    public function getAnhangOderVerlinkung()
    {
        return $this->anhangOderVerlinkung;
    }

    /**
     * Sets a new anhangOderVerlinkung
     *
     * Dieses Elements enthält die Angabe, ob die Anlage (i) als Anhang mit dieser XBeteiligungs-Nachricht übermittelt wird, (ii) über einen Link zugänglich gemacht wird, der in diese XBeteiligungs-Nachricht eingetragen ist oder ob sie (iii) base64-codiert innerhalb dieser XBeteiligungs-Nachricht übermittelt wird.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungType $anhangOderVerlinkung
     * @return self
     */
    public function setAnhangOderVerlinkung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungType $anhangOderVerlinkung = null)
    {
        $this->anhangOderVerlinkung = $anhangOderVerlinkung;
        return $this;
    }
}

