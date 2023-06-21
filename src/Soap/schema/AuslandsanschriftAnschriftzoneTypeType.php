<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AuslandsanschriftAnschriftzoneTypeType
 *
 * Dieser Datentyp enthält die für die Zustellung im Ausland erforderlichen Angaben zur Anschriftzone, außer der Angabe des Staates. Diese Angaben beinhalten alle zur Adressierung erforderlichen Angaben inkl. des Namens der Person und bestehen aus bis zu fünf Zeilen. Diese Zeilen sind beginnend mit der Nummer 4 lückenlos zu durchzunummerieren.
 * XSD Type: Auslandsanschrift.AnschriftzoneType
 */
class AuslandsanschriftAnschriftzoneTypeType
{
    /**
     * Hier sind pro Zeile der Anschriftzone jeweils der eigentliche inhalt und die zeilennummer zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType[] $zeileAnschrift
     */
    private $zeileAnschrift = [
        
    ];

    /**
     * Adds as zeileAnschrift
     *
     * Hier sind pro Zeile der Anschriftzone jeweils der eigentliche inhalt und die zeilennummer zu übermitteln.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType $zeileAnschrift
     */
    public function addToZeileAnschrift(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType $zeileAnschrift)
    {
        $this->zeileAnschrift[] = $zeileAnschrift;
        return $this;
    }

    /**
     * isset zeileAnschrift
     *
     * Hier sind pro Zeile der Anschriftzone jeweils der eigentliche inhalt und die zeilennummer zu übermitteln.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetZeileAnschrift($index)
    {
        return isset($this->zeileAnschrift[$index]);
    }

    /**
     * unset zeileAnschrift
     *
     * Hier sind pro Zeile der Anschriftzone jeweils der eigentliche inhalt und die zeilennummer zu übermitteln.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetZeileAnschrift($index)
    {
        unset($this->zeileAnschrift[$index]);
    }

    /**
     * Gets as zeileAnschrift
     *
     * Hier sind pro Zeile der Anschriftzone jeweils der eigentliche inhalt und die zeilennummer zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType[]
     */
    public function getZeileAnschrift()
    {
        return $this->zeileAnschrift;
    }

    /**
     * Sets a new zeileAnschrift
     *
     * Hier sind pro Zeile der Anschriftzone jeweils der eigentliche inhalt und die zeilennummer zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType[] $zeileAnschrift
     * @return self
     */
    public function setZeileAnschrift(array $zeileAnschrift)
    {
        $this->zeileAnschrift = $zeileAnschrift;
        return $this;
    }
}

