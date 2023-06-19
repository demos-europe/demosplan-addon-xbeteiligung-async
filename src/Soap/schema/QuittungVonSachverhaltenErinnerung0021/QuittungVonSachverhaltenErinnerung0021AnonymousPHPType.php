<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungVonSachverhaltenErinnerung0021;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GQuittungVonSachverhaltenType;

/**
 * Class representing QuittungVonSachverhaltenErinnerung0021AnonymousPHPType
 */
class QuittungVonSachverhaltenErinnerung0021AnonymousPHPType extends NachrichtG2GQuittungVonSachverhaltenType
{
    /**
     * Mit diesem Element wird die Ursprungsnachricht referenziert, die quittungsrelevante Inhalte enthielt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $ursprungsnachricht
     */
    private $ursprungsnachricht = null;

    /**
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt, dessen Quittung erwartet wird.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungType $quittung
     */
    private $quittung = null;

    /**
     * Mit diesem Element wird die Anzahl der übermittelten Erinnerungsnachrichten zur selben Ursprungsnachricht und demselben Sachverhalt übermittelt. In die erste Erinnerungsnachricht wird dementsprechend der Wert 1 eingetragen. Bei weiteren Erinnerungen wird der Wert jeweils um 1 hochgezählt.
     *
     * @var int $erinnerungsstufe
     */
    private $erinnerungsstufe = null;

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
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt, dessen Quittung erwartet wird.
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
     * Mit diesem Element werden die Informationen zum quittungsrelevanten Sachverhalt übermittelt, dessen Quittung erwartet wird.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungType $quittung
     * @return self
     */
    public function setQuittung(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\QuittungType $quittung)
    {
        $this->quittung = $quittung;
        return $this;
    }

    /**
     * Gets as erinnerungsstufe
     *
     * Mit diesem Element wird die Anzahl der übermittelten Erinnerungsnachrichten zur selben Ursprungsnachricht und demselben Sachverhalt übermittelt. In die erste Erinnerungsnachricht wird dementsprechend der Wert 1 eingetragen. Bei weiteren Erinnerungen wird der Wert jeweils um 1 hochgezählt.
     *
     * @return int
     */
    public function getErinnerungsstufe()
    {
        return $this->erinnerungsstufe;
    }

    /**
     * Sets a new erinnerungsstufe
     *
     * Mit diesem Element wird die Anzahl der übermittelten Erinnerungsnachrichten zur selben Ursprungsnachricht und demselben Sachverhalt übermittelt. In die erste Erinnerungsnachricht wird dementsprechend der Wert 1 eingetragen. Bei weiteren Erinnerungen wird der Wert jeweils um 1 hochgezählt.
     *
     * @param int $erinnerungsstufe
     * @return self
     */
    public function setErinnerungsstufe($erinnerungsstufe)
    {
        $this->erinnerungsstufe = $erinnerungsstufe;
        return $this;
    }
}

