<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing AmBauBeteiligtePersonType
 *
 * Dieser Typ fasst die Merkmale zusammen, die typischerweise zu am Bau beteiligten natürlichen Personen genannt werden bzw. zu nennen sind.
 * XSD Type: AmBauBeteiligtePerson
 */
class AmBauBeteiligtePersonType
{
    /**
     * Dieses Element kann genutzt werden, um die Person zu identifizieren. Es handelt es sich um eine technische ID, die es erlaubt einen Datensatz für Änderungen deskriptiver Eigenschaften herauszugreifen. Dem Element ist der W3C-Datentyp ID (https://www.w3.org/TR/xmlschema11-2/#ID) zugeordnet, ein String-Derivat. Scope des hiermit eingeführten Identifikationsmechanismus: Der Scope ist gemäß der hier festgelegten Semantik nur der gegebene Vorgang. Dies bedeutet beispielsweise im Rahmen eines Antragsvorgangs: Der Antrag wird initial vom Online-Service ("Baugenehmigung Online") an die zuständige Bauaufsichtsbehörde übermittelt. In der XBau-Nachrichteninstanz werden die beteiligten Personen und Firmen identifiziert mittels vom Online-Service vergebener Identifier. Ab dem Zeitpunkt werden diese Identifikationsmittel im Nachrichtenaustausch im Rahmen des gegebenen Vorgangs in beide Richtungen weiterverwendet, um in der Kommunikation intersubjektiv nachvollziehbar zu referenzieren. Ob über diesen Scope hinausgehend identifiziert werden soll, ist Angelegenheit des Umsetzungsprojekts, XBau macht dazu keine Aussage.
     *
     * @var string $id
     */
    private $id = null;

    /**
     * In dieses Element sind die Angaben zu den Namen der Person einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameNatuerlichePersonType $name
     */
    private $name = null;

    /**
     * In dieses Objekt sind die Angaben zur Anschrift der Person einzutragen. Welche Anschrift dafür auszuwählen ist, ist nicht festgelegt. Wichtig ist, dass es eine zustellfähige Anschrift sein muss (für den Zweck der verbindlichen Zustellung).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType $anschrift
     */
    private $anschrift = null;

    /**
     * In dieses Objekt sind Angaben einzutragen, die benötigt werden, um mit der Person zu kommunizieren (z. B. per Brief oder per Telefon). Diese Daten dienen der persönlichen Erreichbarkeit; die Unterscheidung, ob es sich um eine private oder eine geschäftliche E-Mail-Adresse handelt, ist nicht erheblich.).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType[] $kommunikation
     */
    private $kommunikation = [
        
    ];

    /**
     * Sofern mehrere natürliche Personen involviert sind, handelt es sich um eine Personengruppe, deren Vertreter mit diesem Element zu kennzeichnen ist.
     *
     * @var bool $istVertreter
     */
    private $istVertreter = null;

    /**
     * Gets as id
     *
     * Dieses Element kann genutzt werden, um die Person zu identifizieren. Es handelt es sich um eine technische ID, die es erlaubt einen Datensatz für Änderungen deskriptiver Eigenschaften herauszugreifen. Dem Element ist der W3C-Datentyp ID (https://www.w3.org/TR/xmlschema11-2/#ID) zugeordnet, ein String-Derivat. Scope des hiermit eingeführten Identifikationsmechanismus: Der Scope ist gemäß der hier festgelegten Semantik nur der gegebene Vorgang. Dies bedeutet beispielsweise im Rahmen eines Antragsvorgangs: Der Antrag wird initial vom Online-Service ("Baugenehmigung Online") an die zuständige Bauaufsichtsbehörde übermittelt. In der XBau-Nachrichteninstanz werden die beteiligten Personen und Firmen identifiziert mittels vom Online-Service vergebener Identifier. Ab dem Zeitpunkt werden diese Identifikationsmittel im Nachrichtenaustausch im Rahmen des gegebenen Vorgangs in beide Richtungen weiterverwendet, um in der Kommunikation intersubjektiv nachvollziehbar zu referenzieren. Ob über diesen Scope hinausgehend identifiziert werden soll, ist Angelegenheit des Umsetzungsprojekts, XBau macht dazu keine Aussage.
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets a new id
     *
     * Dieses Element kann genutzt werden, um die Person zu identifizieren. Es handelt es sich um eine technische ID, die es erlaubt einen Datensatz für Änderungen deskriptiver Eigenschaften herauszugreifen. Dem Element ist der W3C-Datentyp ID (https://www.w3.org/TR/xmlschema11-2/#ID) zugeordnet, ein String-Derivat. Scope des hiermit eingeführten Identifikationsmechanismus: Der Scope ist gemäß der hier festgelegten Semantik nur der gegebene Vorgang. Dies bedeutet beispielsweise im Rahmen eines Antragsvorgangs: Der Antrag wird initial vom Online-Service ("Baugenehmigung Online") an die zuständige Bauaufsichtsbehörde übermittelt. In der XBau-Nachrichteninstanz werden die beteiligten Personen und Firmen identifiziert mittels vom Online-Service vergebener Identifier. Ab dem Zeitpunkt werden diese Identifikationsmittel im Nachrichtenaustausch im Rahmen des gegebenen Vorgangs in beide Richtungen weiterverwendet, um in der Kommunikation intersubjektiv nachvollziehbar zu referenzieren. Ob über diesen Scope hinausgehend identifiziert werden soll, ist Angelegenheit des Umsetzungsprojekts, XBau macht dazu keine Aussage.
     *
     * @param string $id
     * @return self
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Gets as name
     *
     * In dieses Element sind die Angaben zu den Namen der Person einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameNatuerlichePersonType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameNatuerlichePersonType $name
     * @return self
     */
    public function setName(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameNatuerlichePersonType $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as anschrift
     *
     * In dieses Objekt sind die Angaben zur Anschrift der Person einzutragen. Welche Anschrift dafür auszuwählen ist, ist nicht festgelegt. Wichtig ist, dass es eine zustellfähige Anschrift sein muss (für den Zweck der verbindlichen Zustellung).
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType $anschrift
     * @return self
     */
    public function setAnschrift(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType $anschrift)
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType $kommunikation
     */
    public function addToKommunikation(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType $kommunikation)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType[]
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType[] $kommunikation
     * @return self
     */
    public function setKommunikation(?array $kommunikation = null)
    {
        $this->kommunikation = $kommunikation;
        return $this;
    }

    /**
     * Gets as istVertreter
     *
     * Sofern mehrere natürliche Personen involviert sind, handelt es sich um eine Personengruppe, deren Vertreter mit diesem Element zu kennzeichnen ist.
     *
     * @return bool
     */
    public function getIstVertreter()
    {
        return $this->istVertreter;
    }

    /**
     * Sets a new istVertreter
     *
     * Sofern mehrere natürliche Personen involviert sind, handelt es sich um eine Personengruppe, deren Vertreter mit diesem Element zu kennzeichnen ist.
     *
     * @param bool $istVertreter
     * @return self
     */
    public function setIstVertreter($istVertreter)
    {
        $this->istVertreter = $istVertreter;
        return $this;
    }
}

