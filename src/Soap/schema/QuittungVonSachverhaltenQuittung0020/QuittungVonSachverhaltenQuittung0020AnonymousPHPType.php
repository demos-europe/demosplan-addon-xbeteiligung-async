<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungVonSachverhaltenQuittung0020;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GQuittungVonSachverhaltenTypeType;

/**
 * Class representing QuittungVonSachverhaltenQuittung0020AnonymousPHPType
 */
class QuittungVonSachverhaltenQuittung0020AnonymousPHPType extends NachrichtG2GQuittungVonSachverhaltenTypeType
{
    /**
     * Mit diesem Element wird die Ursprungsnachricht referenziert, die quittungsrelevante Inhalte enthielt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4TypeType $ursprungsnachricht
     */
    private $ursprungsnachricht = null;

    /**
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungTypeType $quittung
     */
    private $quittung = null;

    /**
     * Gets as ursprungsnachricht
     *
     * Mit diesem Element wird die Ursprungsnachricht referenziert, die quittungsrelevante Inhalte enthielt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4TypeType
     */
    public function getUrsprungsnachricht()
    {
        return $this->ursprungsnachricht;
    }

    /**
     * Sets a new ursprungsnachricht
     *
     * Mit diesem Element wird die Ursprungsnachricht referenziert, die quittungsrelevante Inhalte enthielt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4TypeType $ursprungsnachricht
     * @return self
     */
    public function setUrsprungsnachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4TypeType $ursprungsnachricht)
    {
        $this->ursprungsnachricht = $ursprungsnachricht;
        return $this;
    }

    /**
     * Gets as quittung
     *
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungTypeType
     */
    public function getQuittung()
    {
        return $this->quittung;
    }

    /**
     * Sets a new quittung
     *
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungTypeType $quittung
     * @return self
     */
    public function setQuittung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungTypeType $quittung)
    {
        $this->quittung = $quittung;
        return $this;
    }
}

