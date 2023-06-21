<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing ReferenzAntragsserviceTypeType
 *
 * Dieser Typ deckt Daten ab, durch die ein Antragsservice einen Antrags- (oder Anzeigen-)vorgang eindeutig kennzeichnet.
 * XSD Type: ReferenzAntragsserviceType
 */
class ReferenzAntragsserviceTypeType
{
    /**
     * Initial eingetragener, i.d.R. maschinell erzeugter Identifier, der für den Antragsvorgang steht (nicht für die Antragsnachricht). Dieser Identifier geht mit den Antragsnachrichten der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) zu und schafft dieser die Möglichkeit, in ihren Reaktionsnachrichten darauf zu referenzieren.
     *
     * @var string $referenzAntragsservice
     */
    private $referenzAntragsservice = null;

    /**
     * Gets as referenzAntragsservice
     *
     * Initial eingetragener, i.d.R. maschinell erzeugter Identifier, der für den Antragsvorgang steht (nicht für die Antragsnachricht). Dieser Identifier geht mit den Antragsnachrichten der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) zu und schafft dieser die Möglichkeit, in ihren Reaktionsnachrichten darauf zu referenzieren.
     *
     * @return string
     */
    public function getReferenzAntragsservice()
    {
        return $this->referenzAntragsservice;
    }

    /**
     * Sets a new referenzAntragsservice
     *
     * Initial eingetragener, i.d.R. maschinell erzeugter Identifier, der für den Antragsvorgang steht (nicht für die Antragsnachricht). Dieser Identifier geht mit den Antragsnachrichten der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) zu und schafft dieser die Möglichkeit, in ihren Reaktionsnachrichten darauf zu referenzieren.
     *
     * @param string $referenzAntragsservice
     * @return self
     */
    public function setReferenzAntragsservice($referenzAntragsservice)
    {
        $this->referenzAntragsservice = $referenzAntragsservice;
        return $this;
    }
}

