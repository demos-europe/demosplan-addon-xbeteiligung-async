<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing MetadatenAnlageLinkType
 *
 * Dieser Datentyp bildet die Metadaten zu einer Anlage ab. Er basiert auf dem Datentyp quotMetadatenAnlagequot aus dem XBau-Kernmodul. Mit diesem Datentyp ist allerdings nur die Übermittlung von Links auf Dokumente möglich.
 * XSD Type: MetadatenAnlageLink
 */
class MetadatenAnlageLinkType
{
    /**
     * Hier kann eine deskriptive Bezeichnung der Anlage übermittelt werden.
     *
     * @var string $bezeichnung
     */
    private $bezeichnung = null;

    /**
     * Hier kann eine Versionsnummer übermittelt werden. Sie dient dazu, Anlagen zu unterscheiden, die dieselbe Bezeichnung tragen.
     *
     * @var int $versionsnummer
     */
    private $versionsnummer = null;

    /**
     * Hier kann das Datum der Erstellung bzw. der letzten Bearbeitung dieser Anlage übermittelt werden.
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
     * Hier ist die URL zu übermitteln, unter der die Anlage abrufbar ist.
     *
     * @var string $link
     */
    private $link = null;

    /**
     * Gets as bezeichnung
     *
     * Hier kann eine deskriptive Bezeichnung der Anlage übermittelt werden.
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
     * Hier kann eine deskriptive Bezeichnung der Anlage übermittelt werden.
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
     * Hier kann eine Versionsnummer übermittelt werden. Sie dient dazu, Anlagen zu unterscheiden, die dieselbe Bezeichnung tragen.
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
     * Hier kann eine Versionsnummer übermittelt werden. Sie dient dazu, Anlagen zu unterscheiden, die dieselbe Bezeichnung tragen.
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
     * Hier kann das Datum der Erstellung bzw. der letzten Bearbeitung dieser Anlage übermittelt werden.
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
     * Hier kann das Datum der Erstellung bzw. der letzten Bearbeitung dieser Anlage übermittelt werden.
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
     * Gets as link
     *
     * Hier ist die URL zu übermitteln, unter der die Anlage abrufbar ist.
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets a new link
     *
     * Hier ist die URL zu übermitteln, unter der die Anlage abrufbar ist.
     *
     * @param string $link
     * @return self
     */
    public function setLink($link)
    {
        $this->link = $link;
        return $this;
    }
}

