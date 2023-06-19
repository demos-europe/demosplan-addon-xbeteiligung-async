<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AnlagenType
 *
 * Dieser Typ dient dazu, die Metadaten zu Verfahrensunterlagen aufzunehmen, die einer XBeteiligungs-Kommunikation als Anlagen beigefügt sind.
 * XSD Type: Anlagen
 */
class AnlagenType
{
    /**
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType[] $anlage
     */
    private $anlage = [
        
    ];

    /**
     * Adds as anlage
     *
     * Jede Instanz dieses Elements steht für eine Anlage (Primärdokument) zur übermittelten Fachnachricht.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType $anlage
     */
    public function addToAnlage(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType $anlage)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType[]
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType[] $anlage
     * @return self
     */
    public function setAnlage(array $anlage)
    {
        $this->anlage = $anlage;
        return $this;
    }
}

