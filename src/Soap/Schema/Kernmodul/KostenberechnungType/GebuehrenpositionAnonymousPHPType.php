<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KostenberechnungType;

/**
 * Class representing GebuehrenpositionAnonymousPHPType
 */
class GebuehrenpositionAnonymousPHPType
{
    /**
     * Haushaltsstelle der eine Gebühr zugeordnet wird
     *
     * @var string $gebuehrenordnung
     */
    private $gebuehrenordnung = null;

    /**
     * Haushaltsstelle der eine Gebühr zugeordnet wird
     *
     * @var string $gebuehrentatbestand
     */
    private $gebuehrentatbestand = null;

    /**
     * Hier sind die Daten zu einer Position des Gebührenbescheids einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\PositionType $position
     */
    private $position = null;

    /**
     * Gets as gebuehrenordnung
     *
     * Haushaltsstelle der eine Gebühr zugeordnet wird
     *
     * @return string
     */
    public function getGebuehrenordnung()
    {
        return $this->gebuehrenordnung;
    }

    /**
     * Sets a new gebuehrenordnung
     *
     * Haushaltsstelle der eine Gebühr zugeordnet wird
     *
     * @param string $gebuehrenordnung
     * @return self
     */
    public function setGebuehrenordnung($gebuehrenordnung)
    {
        $this->gebuehrenordnung = $gebuehrenordnung;
        return $this;
    }

    /**
     * Gets as gebuehrentatbestand
     *
     * Haushaltsstelle der eine Gebühr zugeordnet wird
     *
     * @return string
     */
    public function getGebuehrentatbestand()
    {
        return $this->gebuehrentatbestand;
    }

    /**
     * Sets a new gebuehrentatbestand
     *
     * Haushaltsstelle der eine Gebühr zugeordnet wird
     *
     * @param string $gebuehrentatbestand
     * @return self
     */
    public function setGebuehrentatbestand($gebuehrentatbestand)
    {
        $this->gebuehrentatbestand = $gebuehrentatbestand;
        return $this;
    }

    /**
     * Gets as position
     *
     * Hier sind die Daten zu einer Position des Gebührenbescheids einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\PositionType
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Sets a new position
     *
     * Hier sind die Daten zu einer Position des Gebührenbescheids einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\PositionType $position
     * @return self
     */
    public function setPosition(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\PositionType $position)
    {
        $this->position = $position;
        return $this;
    }
}

