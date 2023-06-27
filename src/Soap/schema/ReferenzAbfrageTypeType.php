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
     * @var \Jms\Handler\GmlAnyTypeHandler $referenzAbfrage
     */
    private $referenzAbfrage = null;

    /**
     * Gets as referenzAbfrage
     *
     * Maschinell erzeugter Identifier, der die Anfrage (nicht die Anfragenachricht) eindeutig kennzeichnet.
     *
     * @return \Jms\Handler\GmlAnyTypeHandler
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
     * @param \Jms\Handler\GmlAnyTypeHandler $referenzAbfrage
     * @return self
     */
    public function setReferenzAbfrage(\Jms\Handler\GmlAnyTypeHandler $referenzAbfrage)
    {
        $this->referenzAbfrage = $referenzAbfrage;
        return $this;
    }
}

