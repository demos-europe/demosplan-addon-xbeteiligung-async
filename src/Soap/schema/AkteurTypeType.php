<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AkteurTypeType
 *
 * Durch den Typ Akteur wird eine Differenzierung zwischen natürlichen Einzelpersonen, Personengruppen und juristischen Personen definiert. Im Namen einer Firma handelt eine natürliche Person als Vertreter. Bei mehreren Personen (z.B. Eigentümergemeinschaften) wird jede Person (juristisch oder natürlich) als Akteur definiert. Eine Person dieser Gruppe kann als Vertreter definiert werden.
 * XSD Type: AkteurType
 */
class AkteurTypeType
{
    /**
     * In dieses Element sind die Angaben zu der natürlichen Person einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AmBauBeteiligtePersonTypeType $natuerlichePerson
     */
    private $natuerlichePerson = null;

    /**
     * In dieses Element sind die Angaben zu der Organisation einzutragen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $organisation
     */
    private $organisation = null;

    /**
     * Gets as natuerlichePerson
     *
     * In dieses Element sind die Angaben zu der natürlichen Person einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AmBauBeteiligtePersonTypeType
     */
    public function getNatuerlichePerson()
    {
        return $this->natuerlichePerson;
    }

    /**
     * Sets a new natuerlichePerson
     *
     * In dieses Element sind die Angaben zu der natürlichen Person einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AmBauBeteiligtePersonTypeType $natuerlichePerson
     * @return self
     */
    public function setNatuerlichePerson(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AmBauBeteiligtePersonTypeType $natuerlichePerson = null)
    {
        $this->natuerlichePerson = $natuerlichePerson;
        return $this;
    }

    /**
     * Gets as organisation
     *
     * In dieses Element sind die Angaben zu der Organisation einzutragen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * Sets a new organisation
     *
     * In dieses Element sind die Angaben zu der Organisation einzutragen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $organisation
     * @return self
     */
    public function setOrganisation(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType $organisation = null)
    {
        $this->organisation = $organisation;
        return $this;
    }
}

