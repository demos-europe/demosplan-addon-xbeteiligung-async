<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing OrganisationType
 *
 * Mit diesem Datentyp können Informationen zu einer Organisation üermittelt werden.
 * XSD Type: Organisation
 */
class OrganisationType
{
    /**
     * Hier ist der Name der Organisation zu übermitteln.
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Hier kann der Name der Organisationseinheit (z. B. Abteilung, Referat) übermittelt werden.
     *
     * @var string $organisationseinheit
     */
    private $organisationseinheit = null;

    /**
     * Gets as name
     *
     * Hier ist der Name der Organisation zu übermitteln.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Hier ist der Name der Organisation zu übermitteln.
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as organisationseinheit
     *
     * Hier kann der Name der Organisationseinheit (z. B. Abteilung, Referat) übermittelt werden.
     *
     * @return string
     */
    public function getOrganisationseinheit()
    {
        return $this->organisationseinheit;
    }

    /**
     * Sets a new organisationseinheit
     *
     * Hier kann der Name der Organisationseinheit (z. B. Abteilung, Referat) übermittelt werden.
     *
     * @param string $organisationseinheit
     * @return self
     */
    public function setOrganisationseinheit($organisationseinheit)
    {
        $this->organisationseinheit = $organisationseinheit;
        return $this;
    }
}

