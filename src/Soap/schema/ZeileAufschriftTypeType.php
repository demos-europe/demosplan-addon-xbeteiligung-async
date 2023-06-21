<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing ZeileAufschriftTypeType
 *
 * Dieser Datentyp repräsentiert eine Zeile einer Aufschrift gemäß DIN 5008 und besteht aus dem Inhalt der Zeile sowie der Angabe der Zeilennummer in der Aufschrift. Sofern dieser Datentyp für die Übermittlung einer Zeile der Anschriftzone verwendet wird, sind ausschließlich die Zeilennummern 4 bis 8 zu verwenden. Zeilen der Vermerkzone werden in dem Datentyp Auslandsanschrift.Druckbild nicht übermittelt. Sofern in einem xinneres-fachmodul der Bedarf besteht, kann der Datentyp aber im Rahmen der Einbindung in das xinneres-fachmodul um weitere ZeileAufschrift-Kindelemente für die Vermerkzone ergänzt werden. Für diese Zeilen sind ausschließlich die Zeilennummern 1 bis 3 zu verwenden.
 * XSD Type: ZeileAufschriftType
 */
class ZeileAufschriftTypeType
{
    /**
     * Die Angabe, in welcher Zeile der Inhalt in der Aufschrift auftauchen soll.
     *
     * @var int $zeilennummer
     */
    private $zeilennummer = null;

    /**
     * Hier ist der Inhalt der Aufschriftzeile anzugeben.
     *
     * @var string $inhalt
     */
    private $inhalt = null;

    /**
     * Gets as zeilennummer
     *
     * Die Angabe, in welcher Zeile der Inhalt in der Aufschrift auftauchen soll.
     *
     * @return int
     */
    public function getZeilennummer()
    {
        return $this->zeilennummer;
    }

    /**
     * Sets a new zeilennummer
     *
     * Die Angabe, in welcher Zeile der Inhalt in der Aufschrift auftauchen soll.
     *
     * @param int $zeilennummer
     * @return self
     */
    public function setZeilennummer($zeilennummer)
    {
        $this->zeilennummer = $zeilennummer;
        return $this;
    }

    /**
     * Gets as inhalt
     *
     * Hier ist der Inhalt der Aufschriftzeile anzugeben.
     *
     * @return string
     */
    public function getInhalt()
    {
        return $this->inhalt;
    }

    /**
     * Sets a new inhalt
     *
     * Hier ist der Inhalt der Aufschriftzeile anzugeben.
     *
     * @param string $inhalt
     * @return self
     */
    public function setInhalt($inhalt)
    {
        $this->inhalt = $inhalt;
        return $this;
    }
}

