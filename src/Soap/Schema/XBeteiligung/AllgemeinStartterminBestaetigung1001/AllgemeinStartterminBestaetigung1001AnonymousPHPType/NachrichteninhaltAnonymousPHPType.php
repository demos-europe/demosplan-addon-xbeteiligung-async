<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStartterminBestaetigung1001\AllgemeinStartterminBestaetigung1001AnonymousPHPType;

/**
 * Class representing NachrichteninhaltAnonymousPHPType
 */
class NachrichteninhaltAnonymousPHPType
{
    /**
     * ID des Beteiligungsverfahrens, die mit der Nachricht 0401 als "vorgangsID" übermittelt wurde.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Die Vorgangs-ID der Nachrichten 0201, 0301 oder 0401 und der sich darauf beziehenden Nachrichten aus der Beteiligung.
     *
     * @var string $vorgangsID
     */
    private $vorgangsID = null;

    /**
     * Hier kann die ID des Planverfahrens übermittelt werden.
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * Hier ist das Datum zu übermitteln, an dem das Beteiligungsverfahren startet.
     *
     * @var \DateTime $datumStartBeteiligung
     */
    private $datumStartBeteiligung = null;

    /**
     * Hier ist das Datum zu übermitteln, an dem das Beteiligungsverfahren veröffentlicht werden soll.
     *
     * @var \DateTime $datumVeroeffentlichungBeteiligung
     */
    private $datumVeroeffentlichungBeteiligung = null;

    /**
     * Gets as beteiligungsID
     *
     * ID des Beteiligungsverfahrens, die mit der Nachricht 0401 als "vorgangsID" übermittelt wurde.
     *
     * @return string
     */
    public function getBeteiligungsID()
    {
        return $this->beteiligungsID;
    }

    /**
     * Sets a new beteiligungsID
     *
     * ID des Beteiligungsverfahrens, die mit der Nachricht 0401 als "vorgangsID" übermittelt wurde.
     *
     * @param string $beteiligungsID
     * @return self
     */
    public function setBeteiligungsID($beteiligungsID)
    {
        $this->beteiligungsID = $beteiligungsID;
        return $this;
    }

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
     * Gets as planID
     *
     * Hier kann die ID des Planverfahrens übermittelt werden.
     *
     * @return string
     */
    public function getPlanID()
    {
        return $this->planID;
    }

    /**
     * Sets a new planID
     *
     * Hier kann die ID des Planverfahrens übermittelt werden.
     *
     * @param string $planID
     * @return self
     */
    public function setPlanID($planID)
    {
        $this->planID = $planID;
        return $this;
    }

    /**
     * Gets as datumStartBeteiligung
     *
     * Hier ist das Datum zu übermitteln, an dem das Beteiligungsverfahren startet.
     *
     * @return \DateTime
     */
    public function getDatumStartBeteiligung()
    {
        return $this->datumStartBeteiligung;
    }

    /**
     * Sets a new datumStartBeteiligung
     *
     * Hier ist das Datum zu übermitteln, an dem das Beteiligungsverfahren startet.
     *
     * @param \DateTime $datumStartBeteiligung
     * @return self
     */
    public function setDatumStartBeteiligung(\DateTime $datumStartBeteiligung)
    {
        $this->datumStartBeteiligung = $datumStartBeteiligung;
        return $this;
    }

    /**
     * Gets as datumVeroeffentlichungBeteiligung
     *
     * Hier ist das Datum zu übermitteln, an dem das Beteiligungsverfahren veröffentlicht werden soll.
     *
     * @return \DateTime
     */
    public function getDatumVeroeffentlichungBeteiligung()
    {
        return $this->datumVeroeffentlichungBeteiligung;
    }

    /**
     * Sets a new datumVeroeffentlichungBeteiligung
     *
     * Hier ist das Datum zu übermitteln, an dem das Beteiligungsverfahren veröffentlicht werden soll.
     *
     * @param \DateTime $datumVeroeffentlichungBeteiligung
     * @return self
     */
    public function setDatumVeroeffentlichungBeteiligung(?\DateTime $datumVeroeffentlichungBeteiligung = null)
    {
        $this->datumVeroeffentlichungBeteiligung = $datumVeroeffentlichungBeteiligung;
        return $this;
    }
}

