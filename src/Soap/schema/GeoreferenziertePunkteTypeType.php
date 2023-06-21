<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing GeoreferenziertePunkteTypeType
 *
 * In eine Instanz diesen Typs werden die Geodaten eines Vorhabens oder einer sonstigen Entität als Punkte eingetragen.
 * XSD Type: GeoreferenziertePunkteType
 */
class GeoreferenziertePunkteTypeType
{
    /**
     * In diesem Element lassen sich georeferenzierte Daten zur punktförmigen Elementen übermitteln, z. B. die Lage von Schaltkästen, Schächten oder Baugruben.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType\PunktAnonymousPHPType[] $punkt
     */
    private $punkt = [
        
    ];

    /**
     * Hier können ergänzend Erläuterungen zu den georeferenzierten Daten gegeben werden.
     *
     * @var string $erlaeuterung
     */
    private $erlaeuterung = null;

    /**
     * Adds as punkt
     *
     * In diesem Element lassen sich georeferenzierte Daten zur punktförmigen Elementen übermitteln, z. B. die Lage von Schaltkästen, Schächten oder Baugruben.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType\PunktAnonymousPHPType $punkt
     */
    public function addToPunkt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType\PunktAnonymousPHPType $punkt)
    {
        $this->punkt[] = $punkt;
        return $this;
    }

    /**
     * isset punkt
     *
     * In diesem Element lassen sich georeferenzierte Daten zur punktförmigen Elementen übermitteln, z. B. die Lage von Schaltkästen, Schächten oder Baugruben.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetPunkt($index)
    {
        return isset($this->punkt[$index]);
    }

    /**
     * unset punkt
     *
     * In diesem Element lassen sich georeferenzierte Daten zur punktförmigen Elementen übermitteln, z. B. die Lage von Schaltkästen, Schächten oder Baugruben.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetPunkt($index)
    {
        unset($this->punkt[$index]);
    }

    /**
     * Gets as punkt
     *
     * In diesem Element lassen sich georeferenzierte Daten zur punktförmigen Elementen übermitteln, z. B. die Lage von Schaltkästen, Schächten oder Baugruben.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType\PunktAnonymousPHPType[]
     */
    public function getPunkt()
    {
        return $this->punkt;
    }

    /**
     * Sets a new punkt
     *
     * In diesem Element lassen sich georeferenzierte Daten zur punktförmigen Elementen übermitteln, z. B. die Lage von Schaltkästen, Schächten oder Baugruben.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\GeoreferenziertePunkteTypeType\PunktAnonymousPHPType[] $punkt
     * @return self
     */
    public function setPunkt(array $punkt)
    {
        $this->punkt = $punkt;
        return $this;
    }

    /**
     * Gets as erlaeuterung
     *
     * Hier können ergänzend Erläuterungen zu den georeferenzierten Daten gegeben werden.
     *
     * @return string
     */
    public function getErlaeuterung()
    {
        return $this->erlaeuterung;
    }

    /**
     * Sets a new erlaeuterung
     *
     * Hier können ergänzend Erläuterungen zu den georeferenzierten Daten gegeben werden.
     *
     * @param string $erlaeuterung
     * @return self
     */
    public function setErlaeuterung($erlaeuterung)
    {
        $this->erlaeuterung = $erlaeuterung;
        return $this;
    }
}

