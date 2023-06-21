<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing PostalischeInlandsanschriftGebaeudeanschriftTypeType
 *
 * Dieser Datentyp beinhaltet die Angaben für die Adressierung im Inland, soweit es sich um eine Gebäudeanschrift (und nicht um eine Postfachanschrift) handelt.
 * XSD Type: PostalischeInlandsanschrift.GebaeudeanschriftType
 */
class PostalischeInlandsanschriftGebaeudeanschriftTypeType extends PostalischeInlandsanschriftBasisTypeType
{
    /**
     * Falls ein Hausnummernbereich mitzuteilen ist, muss dieses Element übermittelt werden. Die hier übermittelten Kindelemente enthalten jeweils den Endwert einer Bereichsangabe. Zu einem Hausnummernbereich gehören ein Anfang und ein Ende. Der Anfang wird definiert in den Kindelementen hausnummer, hausnummerbuchstabezusatzziffer und teilnummerderhausnummer. Das Ende wird definiert in den korrespondierenden Kindelementen von hausnummern.bis. Für den Hausnummernbereich 16 - 18 würde hausnummer mit dem Wert 16 und hausnummern.bis/hausnummer.bis mit dem Wert 18 übermittelt. Für den Hausnummernbereich 16a - c würde hausnummer mit dem Wert 16, hausnummerbuchstabezusatzziffer mit dem Wert a und hausnummern.bis/hausnummerbuchstabezusatzziffer.bis mit dem Wert c übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType\HausnummernBisAnonymousPHPType $hausnummernBis
     */
    private $hausnummernBis = null;

    /**
     * Gets as hausnummernBis
     *
     * Falls ein Hausnummernbereich mitzuteilen ist, muss dieses Element übermittelt werden. Die hier übermittelten Kindelemente enthalten jeweils den Endwert einer Bereichsangabe. Zu einem Hausnummernbereich gehören ein Anfang und ein Ende. Der Anfang wird definiert in den Kindelementen hausnummer, hausnummerbuchstabezusatzziffer und teilnummerderhausnummer. Das Ende wird definiert in den korrespondierenden Kindelementen von hausnummern.bis. Für den Hausnummernbereich 16 - 18 würde hausnummer mit dem Wert 16 und hausnummern.bis/hausnummer.bis mit dem Wert 18 übermittelt. Für den Hausnummernbereich 16a - c würde hausnummer mit dem Wert 16, hausnummerbuchstabezusatzziffer mit dem Wert a und hausnummern.bis/hausnummerbuchstabezusatzziffer.bis mit dem Wert c übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType\HausnummernBisAnonymousPHPType
     */
    public function getHausnummernBis()
    {
        return $this->hausnummernBis;
    }

    /**
     * Sets a new hausnummernBis
     *
     * Falls ein Hausnummernbereich mitzuteilen ist, muss dieses Element übermittelt werden. Die hier übermittelten Kindelemente enthalten jeweils den Endwert einer Bereichsangabe. Zu einem Hausnummernbereich gehören ein Anfang und ein Ende. Der Anfang wird definiert in den Kindelementen hausnummer, hausnummerbuchstabezusatzziffer und teilnummerderhausnummer. Das Ende wird definiert in den korrespondierenden Kindelementen von hausnummern.bis. Für den Hausnummernbereich 16 - 18 würde hausnummer mit dem Wert 16 und hausnummern.bis/hausnummer.bis mit dem Wert 18 übermittelt. Für den Hausnummernbereich 16a - c würde hausnummer mit dem Wert 16, hausnummerbuchstabezusatzziffer mit dem Wert a und hausnummern.bis/hausnummerbuchstabezusatzziffer.bis mit dem Wert c übermittelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType\HausnummernBisAnonymousPHPType $hausnummernBis
     * @return self
     */
    public function setHausnummernBis(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType\HausnummernBisAnonymousPHPType $hausnummernBis = null)
    {
        $this->hausnummernBis = $hausnummernBis;
        return $this;
    }
}

