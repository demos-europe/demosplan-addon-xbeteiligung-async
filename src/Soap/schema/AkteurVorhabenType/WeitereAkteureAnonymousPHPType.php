<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType;

/**
 * Class representing WeitereAkteureAnonymousPHPType
 */
class WeitereAkteureAnonymousPHPType
{
    /**
     * Daten des Beteilgten.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $akteur
     */
    private $akteur = null;

    /**
     * Hier ist die Rolle des Beteiligten anzugeben.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleType $rolle
     */
    private $rolle = null;

    /**
     * Gets as akteur
     *
     * Daten des Beteilgten.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $akteur
     * @return self
     */
    public function setAkteur(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $akteur)
    {
        $this->akteur = $akteur;
        return $this;
    }

    /**
     * Gets as rolle
     *
     * Hier ist die Rolle des Beteiligten anzugeben.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleType $rolle
     * @return self
     */
    public function setRolle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurRolleType $rolle)
    {
        $this->rolle = $rolle;
        return $this;
    }
}

