<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing WeiterleitendeStelleType
 *
 * Dieser Datentyp enthält Informationen zu einer Stelle, die eine Nachricht weitergeleitet hat. Sollte eine Nachricht über mehrere Stellen weitergeleitet worden sein, kann anhand des Zeitpunktes der Weiterleitung die Reihenfolge bestimmt werden.
 * XSD Type: WeiterleitendeStelle
 */
class WeiterleitendeStelleType
{
    /**
     * Angaben zur weiterleitenden Stelle
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $stelle
     */
    private $stelle = null;

    /**
     * Hier wird das Datum und die Zeit übermittelt, an dem die im Kindelement stelle bezeichnete Stelle die Nachricht weitergeleitet hat. Der hier übermittelte Zeitpunkt entspricht dabei dem erstellungszeitpunkt der Weiterleitungsnachricht, mit der die Stelle die Weiterleitung vorgenommen hat.
     *
     * @var \DateTime $zeitpunkt
     */
    private $zeitpunkt = null;

    /**
     * Gets as stelle
     *
     * Angaben zur weiterleitenden Stelle
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType
     */
    public function getStelle()
    {
        return $this->stelle;
    }

    /**
     * Sets a new stelle
     *
     * Angaben zur weiterleitenden Stelle
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $stelle
     * @return self
     */
    public function setStelle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarType $stelle)
    {
        $this->stelle = $stelle;
        return $this;
    }

    /**
     * Gets as zeitpunkt
     *
     * Hier wird das Datum und die Zeit übermittelt, an dem die im Kindelement stelle bezeichnete Stelle die Nachricht weitergeleitet hat. Der hier übermittelte Zeitpunkt entspricht dabei dem erstellungszeitpunkt der Weiterleitungsnachricht, mit der die Stelle die Weiterleitung vorgenommen hat.
     *
     * @return \DateTime
     */
    public function getZeitpunkt()
    {
        return $this->zeitpunkt;
    }

    /**
     * Sets a new zeitpunkt
     *
     * Hier wird das Datum und die Zeit übermittelt, an dem die im Kindelement stelle bezeichnete Stelle die Nachricht weitergeleitet hat. Der hier übermittelte Zeitpunkt entspricht dabei dem erstellungszeitpunkt der Weiterleitungsnachricht, mit der die Stelle die Weiterleitung vorgenommen hat.
     *
     * @param \DateTime $zeitpunkt
     * @return self
     */
    public function setZeitpunkt(\DateTime $zeitpunkt)
    {
        $this->zeitpunkt = $zeitpunkt;
        return $this;
    }
}

