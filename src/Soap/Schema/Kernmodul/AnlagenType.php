<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing AnlagenType
 *
 * Dieser Typ dient dazu, die Metadaten zu Dokumenten aufzunehmen, die einer XBau-Kommunikation (z.B. Antragstellung) als Anlagen beigefügt sind. Als Anlagen sind in erster Linie Bauvorlagen zu nennen, in vielen Fällen zusätzlich weitere Anlagen (wie Nachweise und ergänzende Dokumentationen) und sonstiges Schriftgut.
 * XSD Type: Anlagen
 */
class AnlagenType
{
    /**
     * Jede Instanz dieses Elements steht für eine Anlage (Bauvorlage, Primärdokument, sonstige Anlage) zum vorliegenden Antrag bzw. zur übermittelten XBau-Fachnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType[] $anlage
     */
    private $anlage = [
        
    ];

    /**
     * Adds as anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Bauvorlage, Primärdokument, sonstige Anlage) zum vorliegenden Antrag bzw. zur übermittelten XBau-Fachnachricht.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType $anlage
     */
    public function addToAnlage(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType $anlage)
    {
        $this->anlage[] = $anlage;
        return $this;
    }

    /**
     * isset anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Bauvorlage, Primärdokument, sonstige Anlage) zum vorliegenden Antrag bzw. zur übermittelten XBau-Fachnachricht.
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
     * Jede Instanz dieses Elements steht für eine Anlage (Bauvorlage, Primärdokument, sonstige Anlage) zum vorliegenden Antrag bzw. zur übermittelten XBau-Fachnachricht.
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
     * Jede Instanz dieses Elements steht für eine Anlage (Bauvorlage, Primärdokument, sonstige Anlage) zum vorliegenden Antrag bzw. zur übermittelten XBau-Fachnachricht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType[]
     */
    public function getAnlage()
    {
        return $this->anlage;
    }

    /**
     * Sets a new anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Bauvorlage, Primärdokument, sonstige Anlage) zum vorliegenden Antrag bzw. zur übermittelten XBau-Fachnachricht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnlageDirektType[] $anlage
     * @return self
     */
    public function setAnlage(array $anlage)
    {
        $this->anlage = $anlage;
        return $this;
    }
}

