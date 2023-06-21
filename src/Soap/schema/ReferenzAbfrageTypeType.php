<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing ReferenzAbfrageTypeType
 *
 * Dieser Typ deckt die Identifizierung einer Abfrage ab.
 * XSD Type: ReferenzAbfrageType
 */
class ReferenzAbfrageTypeType
{
    /**
     * Maschinell erzeugter Identifier, der die Anfrage (nicht die Anfragenachricht) eindeutig kennzeichnet.
     *
     * @var string $referenzAbfrage
     */
    private $referenzAbfrage = null;

    /**
     * Gets as referenzAbfrage
     *
     * Maschinell erzeugter Identifier, der die Anfrage (nicht die Anfragenachricht) eindeutig kennzeichnet.
     *
     * @return string
     */
    public function getReferenzAbfrage()
    {
        return $this->referenzAbfrage;
    }

    /**
     * Sets a new referenzAbfrage
     *
     * Maschinell erzeugter Identifier, der die Anfrage (nicht die Anfragenachricht) eindeutig kennzeichnet.
     *
     * @param string $referenzAbfrage
     * @return self
     */
    public function setReferenzAbfrage($referenzAbfrage)
    {
        $this->referenzAbfrage = $referenzAbfrage;
        return $this;
    }
}

