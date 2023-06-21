<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing IdentifikationTypeType
 *
 * Unter "Identifikation" werden die Informationen zusammengefasst, die die eindeutige Identifikation von Objekten in einem fachlichen Kontext erlauben.
 * XSD Type: IdentifikationType
 */
class IdentifikationTypeType
{
    /**
     * Die ID sichert die eindeutige Identifikation von Objekten in einem fachlichen Kontext. Anmerkung: Hier geht es ausschließlich um fachliche Identifikationen wie Steuernummer, Krankenverischerungsnummer, Personalausweisnummer ...
     *
     * @var string $id
     */
    private $id = null;

    /**
     * Die "beschreibung" dient der näheren Charakterisierung des fachlichen Kontext der Identifikation.
     *
     * @var string $beschreibung
     */
    private $beschreibung = null;

    /**
     * Angaben zum Gültigkeitszeitraum einer Identifikationsnummer.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $gueltigkeit
     */
    private $gueltigkeit = null;

    /**
     * Gets as id
     *
     * Die ID sichert die eindeutige Identifikation von Objekten in einem fachlichen Kontext. Anmerkung: Hier geht es ausschließlich um fachliche Identifikationen wie Steuernummer, Krankenverischerungsnummer, Personalausweisnummer ...
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
     * Die ID sichert die eindeutige Identifikation von Objekten in einem fachlichen Kontext. Anmerkung: Hier geht es ausschließlich um fachliche Identifikationen wie Steuernummer, Krankenverischerungsnummer, Personalausweisnummer ...
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
     * Gets as beschreibung
     *
     * Die "beschreibung" dient der näheren Charakterisierung des fachlichen Kontext der Identifikation.
     *
     * @return string
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Sets a new beschreibung
     *
     * Die "beschreibung" dient der näheren Charakterisierung des fachlichen Kontext der Identifikation.
     *
     * @param string $beschreibung
     * @return self
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
        return $this;
    }

    /**
     * Gets as gueltigkeit
     *
     * Angaben zum Gültigkeitszeitraum einer Identifikationsnummer.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType
     */
    public function getGueltigkeit()
    {
        return $this->gueltigkeit;
    }

    /**
     * Sets a new gueltigkeit
     *
     * Angaben zum Gültigkeitszeitraum einer Identifikationsnummer.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $gueltigkeit
     * @return self
     */
    public function setGueltigkeit(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumTypeType $gueltigkeit = null)
    {
        $this->gueltigkeit = $gueltigkeit;
        return $this;
    }
}

