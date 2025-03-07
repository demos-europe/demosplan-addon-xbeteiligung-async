<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PositionType
 *
 * Dieser Typ entält die Daten zu einer Position des Gebührenbescheids.
 * XSD Type: Position
 */
class PositionType
{
    /**
     * Hier ist eine Begründung bzw. eine Herleitung (Bsp. Stückpreis mal Anzahl; Bsp. Rahmen mit Bezug auf Aufwand; Bsp. Grund bzw. Gegenstand der Auslage ) zur Position einzutragen.
     *
     * @var string $erlaeuterung
     */
    private $erlaeuterung = null;

    /**
     * Hier ist der Betrag in Eurocent einzutragen.
     *
     * @var int $betrag
     */
    private $betrag = null;

    /**
     * Gets as erlaeuterung
     *
     * Hier ist eine Begründung bzw. eine Herleitung (Bsp. Stückpreis mal Anzahl; Bsp. Rahmen mit Bezug auf Aufwand; Bsp. Grund bzw. Gegenstand der Auslage ) zur Position einzutragen.
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
     * Hier ist eine Begründung bzw. eine Herleitung (Bsp. Stückpreis mal Anzahl; Bsp. Rahmen mit Bezug auf Aufwand; Bsp. Grund bzw. Gegenstand der Auslage ) zur Position einzutragen.
     *
     * @param string $erlaeuterung
     * @return self
     */
    public function setErlaeuterung($erlaeuterung)
    {
        $this->erlaeuterung = $erlaeuterung;
        return $this;
    }

    /**
     * Gets as betrag
     *
     * Hier ist der Betrag in Eurocent einzutragen.
     *
     * @return int
     */
    public function getBetrag()
    {
        return $this->betrag;
    }

    /**
     * Sets a new betrag
     *
     * Hier ist der Betrag in Eurocent einzutragen.
     *
     * @param int $betrag
     * @return self
     */
    public function setBetrag($betrag)
    {
        $this->betrag = $betrag;
        return $this;
    }
}

