<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenEinstellenDokumente1150;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing ProzessnachrichtenEinstellenDokumente1150AnonymousPHPType
 */
class ProzessnachrichtenEinstellenDokumente1150AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Hier können Eintragungen vorgenommen werden, falls sich das Dokument einem vorhandenen Vorgang zuordnen lässt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug
     */
    private $bezug = null;

    /**
     * Mit diesem Element kann ein Ablageort innerhalb der jeweiligen Struktur des Portals bzw. der Plattform für das Dokument angegeben werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAblageortTypeType $ablageort
     */
    private $ablageort = null;

    /**
     * In diesem Element werden die Metadaten zur Anlage übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Gets as bezug
     *
     * Hier können Eintragungen vorgenommen werden, falls sich das Dokument einem vorhandenen Vorgang zuordnen lässt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType
     */
    public function getBezug()
    {
        return $this->bezug;
    }

    /**
     * Sets a new bezug
     *
     * Hier können Eintragungen vorgenommen werden, falls sich das Dokument einem vorhandenen Vorgang zuordnen lässt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug
     * @return self
     */
    public function setBezug(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugErweitertTypeType $bezug)
    {
        $this->bezug = $bezug;
        return $this;
    }

    /**
     * Gets as ablageort
     *
     * Mit diesem Element kann ein Ablageort innerhalb der jeweiligen Struktur des Portals bzw. der Plattform für das Dokument angegeben werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAblageortTypeType
     */
    public function getAblageort()
    {
        return $this->ablageort;
    }

    /**
     * Sets a new ablageort
     *
     * Mit diesem Element kann ein Ablageort innerhalb der jeweiligen Struktur des Portals bzw. der Plattform für das Dokument angegeben werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAblageortTypeType $ablageort
     * @return self
     */
    public function setAblageort(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeAblageortTypeType $ablageort = null)
    {
        $this->ablageort = $ablageort;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * In diesem Element werden die Metadaten zur Anlage übermittelt.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType $anlage
     */
    public function addToAnlagen(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType $anlage)
    {
        $this->anlagen[] = $anlage;
        return $this;
    }

    /**
     * isset anlagen
     *
     * In diesem Element werden die Metadaten zur Anlage übermittelt.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnlagen($index)
    {
        return isset($this->anlagen[$index]);
    }

    /**
     * unset anlagen
     *
     * In diesem Element werden die Metadaten zur Anlage übermittelt.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnlagen($index)
    {
        unset($this->anlagen[$index]);
    }

    /**
     * Gets as anlagen
     *
     * In diesem Element werden die Metadaten zur Anlage übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[]
     */
    public function getAnlagen()
    {
        return $this->anlagen;
    }

    /**
     * Sets a new anlagen
     *
     * In diesem Element werden die Metadaten zur Anlage übermittelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageDirektTypeType[] $anlagen
     * @return self
     */
    public function setAnlagen(array $anlagen)
    {
        $this->anlagen = $anlagen;
        return $this;
    }
}

