<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing OrganisationTypeType
 *
 * Eine Organisation ist eine Vereinigung mehrerer natürlicher oder juristischer Personen bzw. eine rechtsfähige Personengesellschaft zu einem gemeinsamen Zweck, z.B. im wirtschaftlichen, gemeinnützigen, religiösen, öffentlichen oder politischen Bereich.
 * XSD Type: OrganisationType
 */
class OrganisationTypeType
{
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
     * Unter "kommunikation" werden Angaben zur Erreichbarkeit einer Organisation über elektronische Kommunikationskanäle zusammengefasst.
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
     * Angaben zu einer Person, die operativ für das Bauvorhaben zuständig ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $ansprechpartner
     */
    private $ansprechpartner = null;

    /**
     * Angaben zum Registereintrag einer natürlichen oder juristischen Person oder Personengesellschaft.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RegistereintragTypeType $registereintrag
     */
    private $registereintrag = null;

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
     * Unter "kommunikation" werden Angaben zur Erreichbarkeit einer Organisation über elektronische Kommunikationskanäle zusammengefasst.
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
     * Unter "kommunikation" werden Angaben zur Erreichbarkeit einer Organisation über elektronische Kommunikationskanäle zusammengefasst.
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
     * Unter "kommunikation" werden Angaben zur Erreichbarkeit einer Organisation über elektronische Kommunikationskanäle zusammengefasst.
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
     * Unter "kommunikation" werden Angaben zur Erreichbarkeit einer Organisation über elektronische Kommunikationskanäle zusammengefasst.
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
     * Unter "kommunikation" werden Angaben zur Erreichbarkeit einer Organisation über elektronische Kommunikationskanäle zusammengefasst.
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
     * Gets as ansprechpartner
     *
     * Angaben zu einer Person, die operativ für das Bauvorhaben zuständig ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType
     */
    public function getAnsprechpartner()
    {
        return $this->ansprechpartner;
    }

    /**
     * Sets a new ansprechpartner
     *
     * Angaben zu einer Person, die operativ für das Bauvorhaben zuständig ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $ansprechpartner
     * @return self
     */
    public function setAnsprechpartner(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameNatuerlichePersonTypeType $ansprechpartner = null)
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

