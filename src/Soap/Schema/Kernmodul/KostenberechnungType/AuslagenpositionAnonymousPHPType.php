<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KostenberechnungType;

/**
 * Class representing AuslagenpositionAnonymousPHPType
 */
class AuslagenpositionAnonymousPHPType
{
    /**
     * Hier sind die Daten zu einer Position der Auslage einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\PositionType $position
     */
    private $position = null;

    /**
     * Gets as position
     *
     * Hier sind die Daten zu einer Position der Auslage einzutragen.
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
     * Hier sind die Daten zu einer Position der Auslage einzutragen.
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

