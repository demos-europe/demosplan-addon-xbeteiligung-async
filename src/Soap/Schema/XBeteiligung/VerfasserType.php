<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing VerfasserType
 *
 * Dieser Datentyp wird verwendet, um die Angaben zum Verfasser einer Stellungnahme zu übermitteln.
 * XSD Type: Verfasser
 */
class VerfasserType
{
    /**
     * Falls es sich beim Verfasser der Stellungnahme um eine Privatperson handelt, ist hier true zu übermitteln.
     *
     * @var bool $privatperson
     */
    private $privatperson = null;

    /**
     * Hier ist der Name der Person anzugeben, die die Stellungnahme als Privatperson oder im Namen einer Organisation verfasst.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameNatuerlichePersonType $name
     */
    private $name = null;

    /**
     * Wird die Stellungnahme für eine Organisation verfasst, so sind hier die Angaben der Organisation zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\OrganisationType $organisation
     */
    private $organisation = null;

    /**
     * Hier kann die Anschrift übermittelt werden, unter der der Verfasser erreichbar ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType $anschrift
     */
    private $anschrift = null;

    /**
     * Hier kann übermittelt werden, wie der Verfasser der Stellungnahme zu erreichen ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType[] $kommunikation
     */
    private $kommunikation = [
        
    ];

    /**
     * Gets as privatperson
     *
     * Falls es sich beim Verfasser der Stellungnahme um eine Privatperson handelt, ist hier true zu übermitteln.
     *
     * @return bool
     */
    public function getPrivatperson()
    {
        return $this->privatperson;
    }

    /**
     * Sets a new privatperson
     *
     * Falls es sich beim Verfasser der Stellungnahme um eine Privatperson handelt, ist hier true zu übermitteln.
     *
     * @param bool $privatperson
     * @return self
     */
    public function setPrivatperson($privatperson)
    {
        $this->privatperson = $privatperson;
        return $this;
    }

    /**
     * Gets as name
     *
     * Hier ist der Name der Person anzugeben, die die Stellungnahme als Privatperson oder im Namen einer Organisation verfasst.
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
     * Hier ist der Name der Person anzugeben, die die Stellungnahme als Privatperson oder im Namen einer Organisation verfasst.
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
     * Gets as organisation
     *
     * Wird die Stellungnahme für eine Organisation verfasst, so sind hier die Angaben der Organisation zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\OrganisationType
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Sets a new organisation
     *
     * Wird die Stellungnahme für eine Organisation verfasst, so sind hier die Angaben der Organisation zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\OrganisationType $organisation
     * @return self
     */
    public function setOrganisation(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\OrganisationType $organisation = null)
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * Gets as anschrift
     *
     * Hier kann die Anschrift übermittelt werden, unter der der Verfasser erreichbar ist.
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
     * Hier kann die Anschrift übermittelt werden, unter der der Verfasser erreichbar ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType $anschrift
     * @return self
     */
    public function setAnschrift(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType $anschrift = null)
    {
        $this->anschrift = $anschrift;
        return $this;
    }

    /**
     * Adds as kommunikation
     *
     * Hier kann übermittelt werden, wie der Verfasser der Stellungnahme zu erreichen ist.
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
     * Hier kann übermittelt werden, wie der Verfasser der Stellungnahme zu erreichen ist.
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
     * Hier kann übermittelt werden, wie der Verfasser der Stellungnahme zu erreichen ist.
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
     * Hier kann übermittelt werden, wie der Verfasser der Stellungnahme zu erreichen ist.
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
     * Hier kann übermittelt werden, wie der Verfasser der Stellungnahme zu erreichen ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType[] $kommunikation
     * @return self
     */
    public function setKommunikation(?array $kommunikation = null)
    {
        $this->kommunikation = $kommunikation;
        return $this;
    }
}

