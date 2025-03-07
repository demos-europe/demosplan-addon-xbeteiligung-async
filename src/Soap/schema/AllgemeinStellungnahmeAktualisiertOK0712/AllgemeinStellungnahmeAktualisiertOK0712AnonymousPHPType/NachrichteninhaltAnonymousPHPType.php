<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinStellungnahmeAktualisiertOK0712\AllgemeinStellungnahmeAktualisiertOK0712AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * Die Vorgangs-ID der Nachrichten 0201, 0301 oder 0401 und der sich darauf beziehenden Nachrichten aus der Beteiligung.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Die Stellungnahme-ID, die in der Nachricht 0702 von der beteiligten Stelle übermittelt wurde.
     *
     * @var string $stellungnahmeID
     */
    private $stellungnahmeID = null;

    /**
     * Gets as vorgangsID
     *
     * Die Vorgangs-ID der Nachrichten 0201, 0301 oder 0401 und der sich darauf beziehenden Nachrichten aus der Beteiligung.
     *
     * @return string
     */
    public function getVorgangsID()
    {
        return $this->vorgangsID;
    }

    /**
     * Sets a new vorgangsID
     *
     * Die Vorgangs-ID der Nachrichten 0201, 0301 oder 0401 und der sich darauf beziehenden Nachrichten aus der Beteiligung.
     *
     * @param string $vorgangsID
     * @return self
     */
    public function setVorgangsID($vorgangsID)
    {
        $this->vorgangsID = $vorgangsID;
        return $this;
    }

    /**
     * Gets as stellungnahmeID
     *
     * Die Stellungnahme-ID, die in der Nachricht 0702 von der beteiligten Stelle übermittelt wurde.
     *
     * @return string
     */
    public function getStellungnahmeID()
    {
        return $this->stellungnahmeID;
    }

    /**
     * Sets a new stellungnahmeID
     *
     * Die Stellungnahme-ID, die in der Nachricht 0702 von der beteiligten Stelle übermittelt wurde.
     *
     * @param string $stellungnahmeID
     * @return self
     */
    public function setStellungnahmeID($stellungnahmeID)
    {
        $this->stellungnahmeID = $stellungnahmeID;
        return $this;
    }
}

