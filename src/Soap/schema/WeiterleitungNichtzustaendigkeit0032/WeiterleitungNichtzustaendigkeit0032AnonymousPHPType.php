<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitungNichtzustaendigkeit0032;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GWeiterleitungType;

/**
 * Class representing WeiterleitungNichtzustaendigkeit0032AnonymousPHPType
 */
class WeiterleitungNichtzustaendigkeit0032AnonymousPHPType extends NachrichtG2GWeiterleitungType
{
    /**
     * In diesem Element werden Informationen zu den Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Die Behörde, die die Nachricht über die ungeklärte Zuständigkeit an die den Prozess auslösende Behörde erstellt, ist nicht als weiterleitende Stelle zu übermitteln.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleType[] $weiterleitendeStelle
     */
    private $weiterleitendeStelle = [
        
    ];

    /**
     * In diesem Kindelement wird die den Prozess auslösende fachliche Nachricht identifiziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $zustaendigkeitUngeklaertNachricht
     */
    private $zustaendigkeitUngeklaertNachricht = null;

    /**
     * In diesem Kindelement werden Angaben zur Identifizierung des Geschäftsvorfalls übermittelt, der den Prozess ausgelöst hat. Das Kindelement ist nur dann zu befüllen, wenn die den Prozess auslösende fachliche Nachricht identifizierende Angaben zu dem Geschäftsvorfall enthält.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $zustaendigkeitUngeklaertEreignis
     */
    private $zustaendigkeitUngeklaertEreignis = null;

    /**
     * In diesem Kindelement können Bemerkungen zu der weitergeleiteten Nachricht übermittelt werden.
     *
     * @var string $bemerkung
     */
    private $bemerkung = null;

    /**
     * Adds as weiterleitendeStelle
     *
     * In diesem Element werden Informationen zu den Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Die Behörde, die die Nachricht über die ungeklärte Zuständigkeit an die den Prozess auslösende Behörde erstellt, ist nicht als weiterleitende Stelle zu übermitteln.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleType $weiterleitendeStelle
     */
    public function addToWeiterleitendeStelle(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleType $weiterleitendeStelle)
    {
        $this->weiterleitendeStelle[] = $weiterleitendeStelle;
        return $this;
    }

    /**
     * isset weiterleitendeStelle
     *
     * In diesem Element werden Informationen zu den Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Die Behörde, die die Nachricht über die ungeklärte Zuständigkeit an die den Prozess auslösende Behörde erstellt, ist nicht als weiterleitende Stelle zu übermitteln.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWeiterleitendeStelle($index)
    {
        return isset($this->weiterleitendeStelle[$index]);
    }

    /**
     * unset weiterleitendeStelle
     *
     * In diesem Element werden Informationen zu den Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Die Behörde, die die Nachricht über die ungeklärte Zuständigkeit an die den Prozess auslösende Behörde erstellt, ist nicht als weiterleitende Stelle zu übermitteln.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWeiterleitendeStelle($index)
    {
        unset($this->weiterleitendeStelle[$index]);
    }

    /**
     * Gets as weiterleitendeStelle
     *
     * In diesem Element werden Informationen zu den Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Die Behörde, die die Nachricht über die ungeklärte Zuständigkeit an die den Prozess auslösende Behörde erstellt, ist nicht als weiterleitende Stelle zu übermitteln.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleType[]
     */
    public function getWeiterleitendeStelle()
    {
        return $this->weiterleitendeStelle;
    }

    /**
     * Sets a new weiterleitendeStelle
     *
     * In diesem Element werden Informationen zu den Behörden übermittelt, die im Laufe des Prozesses die fachliche Nachricht weitergeleitet haben. Die Behörde, die die Nachricht über die ungeklärte Zuständigkeit an die den Prozess auslösende Behörde erstellt, ist nicht als weiterleitende Stelle zu übermitteln.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\WeiterleitendeStelleType[] $weiterleitendeStelle
     * @return self
     */
    public function setWeiterleitendeStelle(array $weiterleitendeStelle = null)
    {
        $this->weiterleitendeStelle = $weiterleitendeStelle;
        return $this;
    }

    /**
     * Gets as zustaendigkeitUngeklaertNachricht
     *
     * In diesem Kindelement wird die den Prozess auslösende fachliche Nachricht identifiziert.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type
     */
    public function getZustaendigkeitUngeklaertNachricht()
    {
        return $this->zustaendigkeitUngeklaertNachricht;
    }

    /**
     * Sets a new zustaendigkeitUngeklaertNachricht
     *
     * In diesem Kindelement wird die den Prozess auslösende fachliche Nachricht identifiziert.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $zustaendigkeitUngeklaertNachricht
     * @return self
     */
    public function setZustaendigkeitUngeklaertNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTyp4Type $zustaendigkeitUngeklaertNachricht)
    {
        $this->zustaendigkeitUngeklaertNachricht = $zustaendigkeitUngeklaertNachricht;
        return $this;
    }

    /**
     * Gets as zustaendigkeitUngeklaertEreignis
     *
     * In diesem Kindelement werden Angaben zur Identifizierung des Geschäftsvorfalls übermittelt, der den Prozess ausgelöst hat. Das Kindelement ist nur dann zu befüllen, wenn die den Prozess auslösende fachliche Nachricht identifizierende Angaben zu dem Geschäftsvorfall enthält.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType
     */
    public function getZustaendigkeitUngeklaertEreignis()
    {
        return $this->zustaendigkeitUngeklaertEreignis;
    }

    /**
     * Sets a new zustaendigkeitUngeklaertEreignis
     *
     * In diesem Kindelement werden Angaben zur Identifizierung des Geschäftsvorfalls übermittelt, der den Prozess ausgelöst hat. Das Kindelement ist nur dann zu befüllen, wenn die den Prozess auslösende fachliche Nachricht identifizierende Angaben zu dem Geschäftsvorfall enthält.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $zustaendigkeitUngeklaertEreignis
     * @return self
     */
    public function setZustaendigkeitUngeklaertEreignis(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $zustaendigkeitUngeklaertEreignis = null)
    {
        $this->zustaendigkeitUngeklaertEreignis = $zustaendigkeitUngeklaertEreignis;
        return $this;
    }

    /**
     * Gets as bemerkung
     *
     * In diesem Kindelement können Bemerkungen zu der weitergeleiteten Nachricht übermittelt werden.
     *
     * @return string
     */
    public function getBemerkung()
    {
        return $this->bemerkung;
    }

    /**
     * Sets a new bemerkung
     *
     * In diesem Kindelement können Bemerkungen zu der weitergeleiteten Nachricht übermittelt werden.
     *
     * @param string $bemerkung
     * @return self
     */
    public function setBemerkung($bemerkung)
    {
        $this->bemerkung = $bemerkung;
        return $this;
    }
}

