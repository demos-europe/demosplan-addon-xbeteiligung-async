<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AmBauBeteiligtePersonTypeType
 *
 * Dieser Typ fasst die Merkmale zusammen, die typischerweise zu am Bau beteiligten natürlichen Personen genannt werden bzw. zu nennen sind.
 * XSD Type: AmBauBeteiligtePersonType
 */
class AmBauBeteiligtePersonTypeType
{
    /**
     * In dieses Element sind die Angaben zu den Namen der Person einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $name
     */
    private $name = null;

    /**
     * In dieses Objekt sind die Angaben zur Anschrift der Person einzutragen. Welche Anschrift dafür auszuwählen ist, ist nicht festgelegt. Wichtig ist, dass es eine zustellfähige Anschrift sein muss (für den Zweck der verbindlichen Zustellung).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType $anschrift
     */
    private $anschrift = null;

    /**
     * In dieses Objekt sind Angaben einzutragen, die benötigt werden, um mit der Person zu kommunizieren (z. B. per Brief oder per Telefon). Diese Daten dienen der persönlichen Erreichbarkeit; die Unterscheidung, ob es sich um eine private oder eine geschäftliche E-Mail-Adresse handelt, ist nicht erheblich.).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $kommunikation
     */
    private $kommunikation = [
        
    ];

    /**
     * Gets as name
     *
     * In dieses Element sind die Angaben zu den Namen der Person einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * In dieses Element sind die Angaben zu den Namen der Person einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $name
     * @return self
     */
    public function setName(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as anschrift
     *
     * In dieses Objekt sind die Angaben zur Anschrift der Person einzutragen. Welche Anschrift dafür auszuwählen ist, ist nicht festgelegt. Wichtig ist, dass es eine zustellfähige Anschrift sein muss (für den Zweck der verbindlichen Zustellung).
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType
     */
    public function getAnschrift()
    {
        return $this->anschrift;
    }

    /**
     * Sets a new anschrift
     *
     * In dieses Objekt sind die Angaben zur Anschrift der Person einzutragen. Welche Anschrift dafür auszuwählen ist, ist nicht festgelegt. Wichtig ist, dass es eine zustellfähige Anschrift sein muss (für den Zweck der verbindlichen Zustellung).
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType $anschrift
     * @return self
     */
    public function setAnschrift(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType $anschrift)
    {
        $this->anschrift = $anschrift;
        return $this;
    }

    /**
     * Adds as kommunikation
     *
     * In dieses Objekt sind Angaben einzutragen, die benötigt werden, um mit der Person zu kommunizieren (z. B. per Brief oder per Telefon). Diese Daten dienen der persönlichen Erreichbarkeit; die Unterscheidung, ob es sich um eine private oder eine geschäftliche E-Mail-Adresse handelt, ist nicht erheblich.).
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType $kommunikation
     */
    public function addToKommunikation(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType $kommunikation)
    {
        $this->kommunikation[] = $kommunikation;
        return $this;
    }

    /**
     * isset kommunikation
     *
     * In dieses Objekt sind Angaben einzutragen, die benötigt werden, um mit der Person zu kommunizieren (z. B. per Brief oder per Telefon). Diese Daten dienen der persönlichen Erreichbarkeit; die Unterscheidung, ob es sich um eine private oder eine geschäftliche E-Mail-Adresse handelt, ist nicht erheblich.).
     *
     * @param int|string $index
     * @return bool
     */
    public function issetKommunikation($index)
    {
        return isset($this->kommunikation[$index]);
    }

    /**
     * unset kommunikation
     *
     * In dieses Objekt sind Angaben einzutragen, die benötigt werden, um mit der Person zu kommunizieren (z. B. per Brief oder per Telefon). Diese Daten dienen der persönlichen Erreichbarkeit; die Unterscheidung, ob es sich um eine private oder eine geschäftliche E-Mail-Adresse handelt, ist nicht erheblich.).
     *
     * @param int|string $index
     * @return void
     */
    public function unsetKommunikation($index)
    {
        unset($this->kommunikation[$index]);
    }

    /**
     * Gets as kommunikation
     *
     * In dieses Objekt sind Angaben einzutragen, die benötigt werden, um mit der Person zu kommunizieren (z. B. per Brief oder per Telefon). Diese Daten dienen der persönlichen Erreichbarkeit; die Unterscheidung, ob es sich um eine private oder eine geschäftliche E-Mail-Adresse handelt, ist nicht erheblich.).
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[]
     */
    public function getKommunikation()
    {
        return $this->kommunikation;
    }

    /**
     * Sets a new kommunikation
     *
     * In dieses Objekt sind Angaben einzutragen, die benötigt werden, um mit der Person zu kommunizieren (z. B. per Brief oder per Telefon). Diese Daten dienen der persönlichen Erreichbarkeit; die Unterscheidung, ob es sich um eine private oder eine geschäftliche E-Mail-Adresse handelt, ist nicht erheblich.).
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $kommunikation
     * @return self
     */
    public function setKommunikation(array $kommunikation = null)
    {
        $this->kommunikation = $kommunikation;
        return $this;
    }
}

