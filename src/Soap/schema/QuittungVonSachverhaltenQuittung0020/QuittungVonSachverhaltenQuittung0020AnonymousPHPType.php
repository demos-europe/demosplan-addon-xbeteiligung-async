<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungVonSachverhaltenQuittung0020;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GQuittungVonSachverhaltenType;

/**
 * Class representing QuittungVonSachverhaltenQuittung0020AnonymousPHPType
 */
class QuittungVonSachverhaltenQuittung0020AnonymousPHPType extends NachrichtG2GQuittungVonSachverhaltenType
{
    /**
     * Mit diesem Element wird die Ursprungsnachricht referenziert, die quittungsrelevante Inhalte enthielt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $ursprungsnachricht
     */
    private $ursprungsnachricht = null;

    /**
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungType $quittung
     */
    private $quittung = null;

    /**
     * Gets as ursprungsnachricht
     *
     * Mit diesem Element wird die Ursprungsnachricht referenziert, die quittungsrelevante Inhalte enthielt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $ursprungsnachricht
     * @return self
     */
    public function setUrsprungsnachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $ursprungsnachricht)
    {
        $this->ursprungsnachricht = $ursprungsnachricht;
        return $this;
    }

    /**
     * Gets as quittung
     *
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungType $quittung
     * @return self
     */
    public function setQuittung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungType $quittung)
    {
        $this->quittung = $quittung;
        return $this;
    }
}

