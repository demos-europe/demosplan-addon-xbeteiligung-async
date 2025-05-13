<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing BeteiligungKommunalOeffentlichkeitType
 *
 * Dieser Datentyp enthält alle Informationen zu einem Beteiligungsverfahren im Rahmen der kommunalen Bauleitplanung.
 * XSD Type: BeteiligungKommunalOeffentlichkeit
 */
class BeteiligungKommunalOeffentlichkeitType
{
    /**
     * Hier muss eine Zeichenkette übermittelt werden, die zur Zuordnung von Stellungnahmen zu einzelnen Beteiligungsverfahren genutzt werden kann.
     *
     * @var string $beteiligungsID
     */
    private $beteiligungsID = null;

    /**
     * Hier ist die Durchgangsnummer des Verfahrens zu übermitteln.
     *
     * @var int $durchgang
     */
    private $durchgang = null;

    /**
     * Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
     *
     * @var \DateTime $bekanntmachung
     */
    private $bekanntmachung = null;

    /**
     * Hier können aktuelle Mitteilungen übermittelt werden.
     *
     * @var string[] $aktuelleMitteilung
     */
    private $aktuelleMitteilung = [
        
    ];

    /**
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $zeitraum
     */
    private $zeitraum = null;

    /**
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType[] $anlagen
     */
    private $anlagen = null;

    /**
     * Hier ist zu übermitteln, um welche Art der Öffentlichkeitsbeteiligung es sich handelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType $beteiligungKommunalOeffentlichkeitArt
     */
    private $beteiligungKommunalOeffentlichkeitArt = null;

    /**
     * Hier kann der Zeitraum (Beginn und Ende) übermittelt werden, für den die Dokumente zu einer Beteiligungsphase öffentlich bereitgestellt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $veroeffentlichungszeitraum
     */
    private $veroeffentlichungszeitraum = null;

    /**
     * Gets as beteiligungsID
     *
     * Hier muss eine Zeichenkette übermittelt werden, die zur Zuordnung von Stellungnahmen zu einzelnen Beteiligungsverfahren genutzt werden kann.
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
     * Hier muss eine Zeichenkette übermittelt werden, die zur Zuordnung von Stellungnahmen zu einzelnen Beteiligungsverfahren genutzt werden kann.
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
     * Gets as durchgang
     *
     * Hier ist die Durchgangsnummer des Verfahrens zu übermitteln.
     *
     * @return int
     */
    public function getDurchgang()
    {
        return $this->durchgang;
    }

    /**
     * Sets a new durchgang
     *
     * Hier ist die Durchgangsnummer des Verfahrens zu übermitteln.
     *
     * @param int $durchgang
     * @return self
     */
    public function setDurchgang($durchgang)
    {
        $this->durchgang = $durchgang;
        return $this;
    }

    /**
     * Gets as bekanntmachung
     *
     * Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
     *
     * @return \DateTime
     */
    public function getBekanntmachung()
    {
        return $this->bekanntmachung;
    }

    /**
     * Sets a new bekanntmachung
     *
     * Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
     *
     * @param \DateTime $bekanntmachung
     * @return self
     */
    public function setBekanntmachung(\DateTime $bekanntmachung)
    {
        $this->bekanntmachung = $bekanntmachung;
        return $this;
    }

    /**
     * Adds as aktuelleMitteilung
     *
     * Hier können aktuelle Mitteilungen übermittelt werden.
     *
     * @return self
     * @param string $aktuelleMitteilung
     */
    public function addToAktuelleMitteilung($aktuelleMitteilung)
    {
        $this->aktuelleMitteilung[] = $aktuelleMitteilung;
        return $this;
    }

    /**
     * isset aktuelleMitteilung
     *
     * Hier können aktuelle Mitteilungen übermittelt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAktuelleMitteilung($index)
    {
        return isset($this->aktuelleMitteilung[$index]);
    }

    /**
     * unset aktuelleMitteilung
     *
     * Hier können aktuelle Mitteilungen übermittelt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAktuelleMitteilung($index)
    {
        unset($this->aktuelleMitteilung[$index]);
    }

    /**
     * Gets as aktuelleMitteilung
     *
     * Hier können aktuelle Mitteilungen übermittelt werden.
     *
     * @return string[]
     */
    public function getAktuelleMitteilung()
    {
        return $this->aktuelleMitteilung;
    }

    /**
     * Sets a new aktuelleMitteilung
     *
     * Hier können aktuelle Mitteilungen übermittelt werden.
     *
     * @param string[] $aktuelleMitteilung
     * @return self
     */
    public function setAktuelleMitteilung(array $aktuelleMitteilung = null)
    {
        $this->aktuelleMitteilung = $aktuelleMitteilung;
        return $this;
    }

    /**
     * Gets as zeitraum
     *
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType
     */
    public function getZeitraum()
    {
        return $this->zeitraum;
    }

    /**
     * Sets a new zeitraum
     *
     * Zeitraum (Beginn und Ende) einer Beteiligungsphase.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $zeitraum
     * @return self
     */
    public function setZeitraum(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $zeitraum)
    {
        $this->zeitraum = $zeitraum;
        return $this;
    }

    /**
     * Adds as anlage
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType $anlage
     */
    public function addToAnlagen(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType $anlage)
    {
        $this->anlagen[] = $anlage;
        return $this;
    }

    /**
     * isset anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAnlagen($index)
    {
        return isset($this->anlagen[$index]);
    }

    /**
     * unset anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAnlagen($index)
    {
        unset($this->anlagen[$index]);
    }

    /**
     * Gets as anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType[]
     */
    public function getAnlagen()
    {
        return $this->anlagen;
    }

    /**
     * Sets a new anlagen
     *
     * Die für die Beteiligung erforderlichen Anlagen und Verfahrensunterlagen werden in diesem Element referenziert.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType[] $anlagen
     * @return self
     */
    public function setAnlagen(array $anlagen = null)
    {
        $this->anlagen = $anlagen;
        return $this;
    }

    /**
     * Gets as beteiligungKommunalOeffentlichkeitArt
     *
     * Hier ist zu übermitteln, um welche Art der Öffentlichkeitsbeteiligung es sich handelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType
     */
    public function getBeteiligungKommunalOeffentlichkeitArt()
    {
        return $this->beteiligungKommunalOeffentlichkeitArt;
    }

    /**
     * Sets a new beteiligungKommunalOeffentlichkeitArt
     *
     * Hier ist zu übermitteln, um welche Art der Öffentlichkeitsbeteiligung es sich handelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType $beteiligungKommunalOeffentlichkeitArt
     * @return self
     */
    public function setBeteiligungKommunalOeffentlichkeitArt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType $beteiligungKommunalOeffentlichkeitArt)
    {
        $this->beteiligungKommunalOeffentlichkeitArt = $beteiligungKommunalOeffentlichkeitArt;
        return $this;
    }

    /**
     * Gets as veroeffentlichungszeitraum
     *
     * Hier kann der Zeitraum (Beginn und Ende) übermittelt werden, für den die Dokumente zu einer Beteiligungsphase öffentlich bereitgestellt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType
     */
    public function getVeroeffentlichungszeitraum()
    {
        return $this->veroeffentlichungszeitraum;
    }

    /**
     * Sets a new veroeffentlichungszeitraum
     *
     * Hier kann der Zeitraum (Beginn und Ende) übermittelt werden, für den die Dokumente zu einer Beteiligungsphase öffentlich bereitgestellt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $veroeffentlichungszeitraum
     * @return self
     */
    public function setVeroeffentlichungszeitraum(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType $veroeffentlichungszeitraum = null)
    {
        $this->veroeffentlichungszeitraum = $veroeffentlichungszeitraum;
        return $this;
    }
}

