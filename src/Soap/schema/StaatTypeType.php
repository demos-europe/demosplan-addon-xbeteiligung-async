<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing StaatTypeType
 *
 * Als Staat bezeichnet man eine politische Ordnung, die ein gemeinsames als Staatsgebiet abgegrenztes Territorium, ein dazugehöriges Staatsvolk und eine Machtausübung über dieses umfasst.
 * XSD Type: StaatType
 */
class StaatTypeType
{
    /**
     * Dieses verwendet einen Schlüssel zur Identifikation eines Staates.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStaatTypeType $staat
     */
    private $staat = null;

    /**
     * Gets as staat
     *
     * Dieses verwendet einen Schlüssel zur Identifikation eines Staates.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStaatTypeType
     */
    public function getStaat()
    {
        return $this->staat;
    }

    /**
     * Sets a new staat
     *
     * Dieses verwendet einen Schlüssel zur Identifikation eines Staates.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStaatTypeType $staat
     * @return self
     */
    public function setStaat(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeStaatTypeType $staat)
    {
        $this->staat = $staat;
        return $this;
    }
}

