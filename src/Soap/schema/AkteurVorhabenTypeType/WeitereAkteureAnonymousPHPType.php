<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenTypeType;

/**
 * Class representing WeitereAkteureAnonymousPHPType
 */
class WeitereAkteureAnonymousPHPType
{
    /**
     * Daten des Beteilgten.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $akteur
     */
    private $akteur = null;

    /**
     * Hier ist die Rolle des Beteiligten anzugeben.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleTypeType $rolle
     */
    private $rolle = null;

    /**
     * Gets as akteur
     *
     * Daten des Beteilgten.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType
     */
    public function getAkteur()
    {
        return $this->akteur;
    }

    /**
     * Sets a new akteur
     *
     * Daten des Beteilgten.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $akteur
     * @return self
     */
    public function setAkteur(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $akteur)
    {
        $this->akteur = $akteur;
        return $this;
    }

    /**
     * Gets as rolle
     *
     * Hier ist die Rolle des Beteiligten anzugeben.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleTypeType
     */
    public function getRolle()
    {
        return $this->rolle;
    }

    /**
     * Sets a new rolle
     *
     * Hier ist die Rolle des Beteiligten anzugeben.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleTypeType $rolle
     * @return self
     */
    public function setRolle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleTypeType $rolle)
    {
        $this->rolle = $rolle;
        return $this;
    }
}

