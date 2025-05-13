<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing AnlagenLinkType
 *
 * Dieser Typ dient dazu, die Metadaten zu Verfahrensunterlagen aufzunehmen, die einer XBeteiligungs-Kommunikation als Anlagen beigefügt sind. Die Übermittlung von Dokumenten ist nicht möglich.
 * XSD Type: AnlagenLink
 */
class AnlagenLinkType
{
    /**
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType[] $anlage
     */
    private $anlage = [
        
    ];

    /**
     * Adds as anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType $anlage
     */
    public function addToAnlage(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType $anlage)
    {
        $this->anlage[] = $anlage;
        return $this;
    }

    /**
     * isset anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnlage($index)
    {
        return isset($this->anlage[$index]);
    }

    /**
     * unset anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnlage($index)
    {
        unset($this->anlage[$index]);
    }

    /**
     * Gets as anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType[]
     */
    public function getAnlage()
    {
        return $this->anlage;
    }

    /**
     * Sets a new anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageLinkType[] $anlage
     * @return self
     */
    public function setAnlage(array $anlage)
    {
        $this->anlage = $anlage;
        return $this;
    }
}

