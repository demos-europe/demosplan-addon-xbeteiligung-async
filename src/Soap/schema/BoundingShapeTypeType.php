<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BoundingShapeTypeType
 *
 *
 * XSD Type: BoundingShapeType
 */
class BoundingShapeTypeType
{
    /**
     * @var string $nilReason
     */
    private $nilReason = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Envelope $envelope
     */
    private $envelope = null;

    /**
     * Gets as nilReason
     *
     * @return string
     */
    public function getNilReason()
    {
        return $this->nilReason;
    }

    /**
     * Sets a new nilReason
     *
     * @param string $nilReason
     * @return self
     */
    public function setNilReason($nilReason)
    {
        $this->nilReason = $nilReason;
        return $this;
    }

    /**
     * Gets as envelope
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Envelope
     */
    public function getEnvelope()
    {
        return $this->envelope;
    }

    /**
     * Sets a new envelope
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Envelope $envelope
     * @return self
     */
    public function setEnvelope(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Envelope $envelope)
    {
        $this->envelope = $envelope;
        return $this;
    }
}

