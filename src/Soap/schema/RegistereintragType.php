<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RegistereintragType
 *
 * Mit diesem Typ werden Angaben zum Registereintrag einer natürlichen oder juristischen Person oder Personengesellschaft übermittelt.
 * XSD Type: Registereintrag
 */
class RegistereintragType
{
    /**
     * Nummer der Eintragung im Handels-, Genossenschafts- oder Vereinsregister.
     *
     * @var string $eintragungNr
     */
    private $eintragungNr = null;

    /**
     * Schlüssel des zuständigen Registergerichts.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeRegistergerichtType $gerichtSchluessel
     */
    private $gerichtSchluessel = null;

    /**
     * Gets as eintragungNr
     *
     * Nummer der Eintragung im Handels-, Genossenschafts- oder Vereinsregister.
     *
     * @return string
     */
    public function getEintragungNr()
    {
        return $this->eintragungNr;
    }

    /**
     * Sets a new eintragungNr
     *
     * Nummer der Eintragung im Handels-, Genossenschafts- oder Vereinsregister.
     *
     * @param string $eintragungNr
     * @return self
     */
    public function setEintragungNr($eintragungNr)
    {
        $this->eintragungNr = $eintragungNr;
        return $this;
    }

    /**
     * Gets as gerichtSchluessel
     *
     * Schlüssel des zuständigen Registergerichts.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeRegistergerichtType
     */
    public function getGerichtSchluessel()
    {
        return $this->gerichtSchluessel;
    }

    /**
     * Sets a new gerichtSchluessel
     *
     * Schlüssel des zuständigen Registergerichts.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeRegistergerichtType $gerichtSchluessel
     * @return self
     */
    public function setGerichtSchluessel(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeRegistergerichtType $gerichtSchluessel)
    {
        $this->gerichtSchluessel = $gerichtSchluessel;
        return $this;
    }
}

