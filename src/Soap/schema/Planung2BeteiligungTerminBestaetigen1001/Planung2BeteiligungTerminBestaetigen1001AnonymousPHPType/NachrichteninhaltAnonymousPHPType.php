<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungTerminBestaetigen1001\Planung2BeteiligungTerminBestaetigen1001AnonymousPHPType;

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
     * Hier kann die ID des Planverfahrens
     *
     * @var string $planID
     */
    private $planID = null;

    /**
     * @var \DateTime $datumStartBeteiligung
     */
    private $datumStartBeteiligung = null;

    /**
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
     * Gets as planID
     *
     * Hier kann die ID des Planverfahrens
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
     * Hier kann die ID des Planverfahrens
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
     * @return \DateTime
     */
    public function getDatumStartBeteiligung()
    {
        return $this->datumStartBeteiligung;
    }

    /**
     * Sets a new datumStartBeteiligung
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
     * @return \DateTime
     */
    public function getDatumVeroeffentlichungBeteiligung()
    {
        return $this->datumVeroeffentlichungBeteiligung;
    }

    /**
     * Sets a new datumVeroeffentlichungBeteiligung
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

