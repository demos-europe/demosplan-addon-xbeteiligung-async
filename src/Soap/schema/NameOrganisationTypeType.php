<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NameOrganisationTypeType
 *
 * "NameOrganisation" fasst die Angaben zum Namen einer Organisation zusammen.
 * XSD Type: NameOrganisationType
 */
class NameOrganisationTypeType
{
    /**
     * Offizieller Name einer Organisation. Entspricht bei registrierten Organisationen dem im Register eingetragenen Namen.
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Kurzbezeichnung des Namen einer Organisation.
     *
     * @var string $kurzbezeichnung
     */
    private $kurzbezeichnung = null;

    /**
     * Gets as name
     *
     * Offizieller Name einer Organisation. Entspricht bei registrierten Organisationen dem im Register eingetragenen Namen.
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
     * Offizieller Name einer Organisation. Entspricht bei registrierten Organisationen dem im Register eingetragenen Namen.
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
     * Gets as kurzbezeichnung
     *
     * Kurzbezeichnung des Namen einer Organisation.
     *
     * @return string
     */
    public function getKurzbezeichnung()
    {
        return $this->kurzbezeichnung;
    }

    /**
     * Sets a new kurzbezeichnung
     *
     * Kurzbezeichnung des Namen einer Organisation.
     *
     * @param string $kurzbezeichnung
     * @return self
     */
    public function setKurzbezeichnung($kurzbezeichnung)
    {
        $this->kurzbezeichnung = $kurzbezeichnung;
        return $this;
    }
}

