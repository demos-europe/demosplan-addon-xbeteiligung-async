<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AuslandsanschriftDruckbildTypeType
 *
 * Dieser Datentyp repräsentiert das Druckbild einer Anschrift im Ausland, indem die Anschriftzone eines Brieffensters gemäß DIN 5008 abgebildet wird. Die Anschriftzone setzt sich aus einem Schlüsselwert für den Zielstaat und bis zu fünf weiteren Zeilen für die übrigen Adressierungsangaben zusammen. Angaben zur Vermerkzone können mit diesem Datentypen nicht abgebildet werden. Die ersten fünf Zeilen werden mit den Angaben zur Anschrift im Ausland gefüllt. Leerzeilen sind dabei nicht zulässig. Entsprechend DIN 5008 sind die Zeilen beginnend mit der Nummer 4 lückenlos durchzunummerieren und Zeilennummern nicht mehrfach zu verwenden. Der Ortsname in der Anschrift sollte in Großbuchstaben und in der Sprache des Zielstaates erfasst und übermittelt werden. Für die Erstellung des Druckbildes ist der übermittelte Schlüsselwert des Zielstaates in eine für die Zustellung geeignete Klartextform - d. h. in Großbuchstaben und in die deutsche Sprache - zu überführen.
 * XSD Type: Auslandsanschrift.DruckbildType
 */
class AuslandsanschriftDruckbildTypeType
{
    /**
     * Hier ist Staatenschlüssel des Zielstaats aus der aktuell gültigen Staats- und Gebietssystematik des Statistischen Bundesamtes zu übermitteln. Da mit dem Datentyp Auslandsanschrift.Druckbild nur Auslandsanschriften übermittelt werden dürfen, ist die Verwendung des Schlüssels 000 (Deutschland) nicht zulässig.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZielstaatTypeType $staat
     */
    private $staat = null;

    /**
     * Hier sind zeilenweise die für die Zustellung erforderlichen Angaben zur Anschriftzone zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType[] $anschriftzone
     */
    private $anschriftzone = null;

    /**
     * Gets as staat
     *
     * Hier ist Staatenschlüssel des Zielstaats aus der aktuell gültigen Staats- und Gebietssystematik des Statistischen Bundesamtes zu übermitteln. Da mit dem Datentyp Auslandsanschrift.Druckbild nur Auslandsanschriften übermittelt werden dürfen, ist die Verwendung des Schlüssels 000 (Deutschland) nicht zulässig.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZielstaatTypeType
     */
    public function getStaat()
    {
        return $this->staat;
    }

    /**
     * Sets a new staat
     *
     * Hier ist Staatenschlüssel des Zielstaats aus der aktuell gültigen Staats- und Gebietssystematik des Statistischen Bundesamtes zu übermitteln. Da mit dem Datentyp Auslandsanschrift.Druckbild nur Auslandsanschriften übermittelt werden dürfen, ist die Verwendung des Schlüssels 000 (Deutschland) nicht zulässig.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZielstaatTypeType $staat
     * @return self
     */
    public function setStaat(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZielstaatTypeType $staat)
    {
        $this->staat = $staat;
        return $this;
    }

    /**
     * Adds as zeileAnschrift
     *
     * Hier sind zeilenweise die für die Zustellung erforderlichen Angaben zur Anschriftzone zu übermitteln.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType $zeileAnschrift
     */
    public function addToAnschriftzone(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType $zeileAnschrift)
    {
        $this->anschriftzone[] = $zeileAnschrift;
        return $this;
    }

    /**
     * isset anschriftzone
     *
     * Hier sind zeilenweise die für die Zustellung erforderlichen Angaben zur Anschriftzone zu übermitteln.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnschriftzone($index)
    {
        return isset($this->anschriftzone[$index]);
    }

    /**
     * unset anschriftzone
     *
     * Hier sind zeilenweise die für die Zustellung erforderlichen Angaben zur Anschriftzone zu übermitteln.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnschriftzone($index)
    {
        unset($this->anschriftzone[$index]);
    }

    /**
     * Gets as anschriftzone
     *
     * Hier sind zeilenweise die für die Zustellung erforderlichen Angaben zur Anschriftzone zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType[]
     */
    public function getAnschriftzone()
    {
        return $this->anschriftzone;
    }

    /**
     * Sets a new anschriftzone
     *
     * Hier sind zeilenweise die für die Zustellung erforderlichen Angaben zur Anschriftzone zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeileAufschriftTypeType[] $anschriftzone
     * @return self
     */
    public function setAnschriftzone(array $anschriftzone = null)
    {
        $this->anschriftzone = $anschriftzone;
        return $this;
    }
}

