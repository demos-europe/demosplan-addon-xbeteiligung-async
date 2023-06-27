<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing OrganisationTypeType
 *
 * Eine Organisation ist eine Vereinigung mehrerer natürlicher oder juristischer Personen bzw. eine rechtsfähige Personengesellschaft zu einem gemeinsamen Zweck.
 * XSD Type: OrganisationType
 */
class OrganisationTypeType
{
    /**
     * Dieses Element kann genutzt werden, um die Organisation zu identifizieren. Es handelt es sich um eine technische ID, die es erlaubt den Datensatz für Änderungen deskriptiver Eigenschaften herauszugreifen. Dem Element ist der W3C-Datentyp ID (https://www.w3.org/TR/xmlschema11-2/#ID) zugeordnet, ein String-Derivat. Scope des hiermit eingeführten Identifikationsmechanismus: Der Scope ist gemäß der hier festgelegten Semantik nur der gegebene Vorgang. Dies bedeutet beispielsweise im Rahmen eines Antragsvorgangs: Der Antrag wird initial vom Online-Service ("Baugenehmigung Online") an die zuständige Bauaufsichtsbehörde übermittelt. In der XBau-Nachrichteninstanz werden die beteiligten Personen und Firmen identifiziert mittels vom Online-Service vergebener Identifier. Ab dem Zeitpunkt werden diese Identifikationsmittel im Nachrichtenaustausch im Rahmen des gegebenen Vorgangs in beide Richtungen weiterverwendet, um in der Kommunikation intersubjektiv nachvollziehbar zu referenzieren. Ob über diesen Scope hinausgehend identifiziert werden soll, ist Angelegenheit des Umsetzungsprojekts, XBau macht dazu keine Aussage.
     *
     * @var string $id
     */
    private $id = null;

    /**
     * Angaben zum offiziellen Namen der Organisation.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType $name
     */
    private $name = null;

    /**
     * Angaben zur Anschrift der Organisation, die über den Typ der Anschrift auf deren Verwendung als Postanschrift, Niederlassungsanschrift oder Gründungsanschrift verweist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType[] $anschrift
     */
    private $anschrift = [
        
    ];

    /**
     * Pro Instanz dieses Elements kann eine Angabe zur Erreichbarkeit der Organisation eingetragen werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $kommunikation
     */
    private $kommunikation = [
        
    ];

    /**
     * Angaben zu Personen, die autorisiert sind, für die Organisation zu sprechen. Dies kann z. B. ein Mitglied der Geschäftsleitung sein.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType[] $vertreter
     */
    private $vertreter = [
        
    ];

    /**
     * Pro Instanz dieses Elements kann eine Angabe zu einer Person als operativer Ansprechpartner für die Organisation übermittelt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType\AnsprechpartnerAnonymousPHPType[] $ansprechpartner
     */
    private $ansprechpartner = [
        
    ];

    /**
     * Angaben zum Registereintrag einer natürlichen oder juristischen Person oder Personengesellschaft.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RegistereintragTypeType $registereintrag
     */
    private $registereintrag = null;

    /**
     * Gets as id
     *
     * Dieses Element kann genutzt werden, um die Organisation zu identifizieren. Es handelt es sich um eine technische ID, die es erlaubt den Datensatz für Änderungen deskriptiver Eigenschaften herauszugreifen. Dem Element ist der W3C-Datentyp ID (https://www.w3.org/TR/xmlschema11-2/#ID) zugeordnet, ein String-Derivat. Scope des hiermit eingeführten Identifikationsmechanismus: Der Scope ist gemäß der hier festgelegten Semantik nur der gegebene Vorgang. Dies bedeutet beispielsweise im Rahmen eines Antragsvorgangs: Der Antrag wird initial vom Online-Service ("Baugenehmigung Online") an die zuständige Bauaufsichtsbehörde übermittelt. In der XBau-Nachrichteninstanz werden die beteiligten Personen und Firmen identifiziert mittels vom Online-Service vergebener Identifier. Ab dem Zeitpunkt werden diese Identifikationsmittel im Nachrichtenaustausch im Rahmen des gegebenen Vorgangs in beide Richtungen weiterverwendet, um in der Kommunikation intersubjektiv nachvollziehbar zu referenzieren. Ob über diesen Scope hinausgehend identifiziert werden soll, ist Angelegenheit des Umsetzungsprojekts, XBau macht dazu keine Aussage.
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
     * Dieses Element kann genutzt werden, um die Organisation zu identifizieren. Es handelt es sich um eine technische ID, die es erlaubt den Datensatz für Änderungen deskriptiver Eigenschaften herauszugreifen. Dem Element ist der W3C-Datentyp ID (https://www.w3.org/TR/xmlschema11-2/#ID) zugeordnet, ein String-Derivat. Scope des hiermit eingeführten Identifikationsmechanismus: Der Scope ist gemäß der hier festgelegten Semantik nur der gegebene Vorgang. Dies bedeutet beispielsweise im Rahmen eines Antragsvorgangs: Der Antrag wird initial vom Online-Service ("Baugenehmigung Online") an die zuständige Bauaufsichtsbehörde übermittelt. In der XBau-Nachrichteninstanz werden die beteiligten Personen und Firmen identifiziert mittels vom Online-Service vergebener Identifier. Ab dem Zeitpunkt werden diese Identifikationsmittel im Nachrichtenaustausch im Rahmen des gegebenen Vorgangs in beide Richtungen weiterverwendet, um in der Kommunikation intersubjektiv nachvollziehbar zu referenzieren. Ob über diesen Scope hinausgehend identifiziert werden soll, ist Angelegenheit des Umsetzungsprojekts, XBau macht dazu keine Aussage.
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
     * Angaben zum offiziellen Namen der Organisation.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Angaben zum offiziellen Namen der Organisation.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType $name
     * @return self
     */
    public function setName(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType $name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Adds as anschrift
     *
     * Angaben zur Anschrift der Organisation, die über den Typ der Anschrift auf deren Verwendung als Postanschrift, Niederlassungsanschrift oder Gründungsanschrift verweist.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType $anschrift
     */
    public function addToAnschrift(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType $anschrift)
    {
        $this->anschrift[] = $anschrift;
        return $this;
    }

    /**
     * isset anschrift
     *
     * Angaben zur Anschrift der Organisation, die über den Typ der Anschrift auf deren Verwendung als Postanschrift, Niederlassungsanschrift oder Gründungsanschrift verweist.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnschrift($index)
    {
        return isset($this->anschrift[$index]);
    }

    /**
     * unset anschrift
     *
     * Angaben zur Anschrift der Organisation, die über den Typ der Anschrift auf deren Verwendung als Postanschrift, Niederlassungsanschrift oder Gründungsanschrift verweist.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnschrift($index)
    {
        unset($this->anschrift[$index]);
    }

    /**
     * Gets as anschrift
     *
     * Angaben zur Anschrift der Organisation, die über den Typ der Anschrift auf deren Verwendung als Postanschrift, Niederlassungsanschrift oder Gründungsanschrift verweist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType[]
     */
    public function getAnschrift()
    {
        return $this->anschrift;
    }

    /**
     * Sets a new anschrift
     *
     * Angaben zur Anschrift der Organisation, die über den Typ der Anschrift auf deren Verwendung als Postanschrift, Niederlassungsanschrift oder Gründungsanschrift verweist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnschriftTypeType[] $anschrift
     * @return self
     */
    public function setAnschrift(array $anschrift = null)
    {
        $this->anschrift = $anschrift;
        return $this;
    }

    /**
     * Adds as kommunikation
     *
     * Pro Instanz dieses Elements kann eine Angabe zur Erreichbarkeit der Organisation eingetragen werden.
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
     * Pro Instanz dieses Elements kann eine Angabe zur Erreichbarkeit der Organisation eingetragen werden.
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
     * Pro Instanz dieses Elements kann eine Angabe zur Erreichbarkeit der Organisation eingetragen werden.
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
     * Pro Instanz dieses Elements kann eine Angabe zur Erreichbarkeit der Organisation eingetragen werden.
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
     * Pro Instanz dieses Elements kann eine Angabe zur Erreichbarkeit der Organisation eingetragen werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType[] $kommunikation
     * @return self
     */
    public function setKommunikation(array $kommunikation = null)
    {
        $this->kommunikation = $kommunikation;
        return $this;
    }

    /**
     * Adds as vertreter
     *
     * Angaben zu Personen, die autorisiert sind, für die Organisation zu sprechen. Dies kann z. B. ein Mitglied der Geschäftsleitung sein.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $vertreter
     */
    public function addToVertreter(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $vertreter)
    {
        $this->vertreter[] = $vertreter;
        return $this;
    }

    /**
     * isset vertreter
     *
     * Angaben zu Personen, die autorisiert sind, für die Organisation zu sprechen. Dies kann z. B. ein Mitglied der Geschäftsleitung sein.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVertreter($index)
    {
        return isset($this->vertreter[$index]);
    }

    /**
     * unset vertreter
     *
     * Angaben zu Personen, die autorisiert sind, für die Organisation zu sprechen. Dies kann z. B. ein Mitglied der Geschäftsleitung sein.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVertreter($index)
    {
        unset($this->vertreter[$index]);
    }

    /**
     * Gets as vertreter
     *
     * Angaben zu Personen, die autorisiert sind, für die Organisation zu sprechen. Dies kann z. B. ein Mitglied der Geschäftsleitung sein.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType[]
     */
    public function getVertreter()
    {
        return $this->vertreter;
    }

    /**
     * Sets a new vertreter
     *
     * Angaben zu Personen, die autorisiert sind, für die Organisation zu sprechen. Dies kann z. B. ein Mitglied der Geschäftsleitung sein.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType[] $vertreter
     * @return self
     */
    public function setVertreter(array $vertreter = null)
    {
        $this->vertreter = $vertreter;
        return $this;
    }

    /**
     * Adds as ansprechpartner
     *
     * Pro Instanz dieses Elements kann eine Angabe zu einer Person als operativer Ansprechpartner für die Organisation übermittelt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType\AnsprechpartnerAnonymousPHPType $ansprechpartner
     */
    public function addToAnsprechpartner(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType\AnsprechpartnerAnonymousPHPType $ansprechpartner)
    {
        $this->ansprechpartner[] = $ansprechpartner;
        return $this;
    }

    /**
     * isset ansprechpartner
     *
     * Pro Instanz dieses Elements kann eine Angabe zu einer Person als operativer Ansprechpartner für die Organisation übermittelt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnsprechpartner($index)
    {
        return isset($this->ansprechpartner[$index]);
    }

    /**
     * unset ansprechpartner
     *
     * Pro Instanz dieses Elements kann eine Angabe zu einer Person als operativer Ansprechpartner für die Organisation übermittelt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnsprechpartner($index)
    {
        unset($this->ansprechpartner[$index]);
    }

    /**
     * Gets as ansprechpartner
     *
     * Pro Instanz dieses Elements kann eine Angabe zu einer Person als operativer Ansprechpartner für die Organisation übermittelt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType\AnsprechpartnerAnonymousPHPType[]
     */
    public function getAnsprechpartner()
    {
        return $this->ansprechpartner;
    }

    /**
     * Sets a new ansprechpartner
     *
     * Pro Instanz dieses Elements kann eine Angabe zu einer Person als operativer Ansprechpartner für die Organisation übermittelt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType\AnsprechpartnerAnonymousPHPType[] $ansprechpartner
     * @return self
     */
    public function setAnsprechpartner(array $ansprechpartner = null)
    {
        $this->ansprechpartner = $ansprechpartner;
        return $this;
    }

    /**
     * Gets as registereintrag
     *
     * Angaben zum Registereintrag einer natürlichen oder juristischen Person oder Personengesellschaft.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RegistereintragTypeType
     */
    public function getRegistereintrag()
    {
        return $this->registereintrag;
    }

    /**
     * Sets a new registereintrag
     *
     * Angaben zum Registereintrag einer natürlichen oder juristischen Person oder Personengesellschaft.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RegistereintragTypeType $registereintrag
     * @return self
     */
    public function setRegistereintrag(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RegistereintragTypeType $registereintrag = null)
    {
        $this->registereintrag = $registereintrag;
        return $this;
    }
}

