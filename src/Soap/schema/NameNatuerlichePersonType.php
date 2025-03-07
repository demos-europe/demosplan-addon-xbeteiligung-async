<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NameNatuerlichePersonType
 *
 * Der Name einer Person ist eine Benennung dieser Person, die dazu dient, diese Person von anderen Personen zu unterscheiden.
 * XSD Type: NameNatuerlichePerson
 */
class NameNatuerlichePersonType
{
    /**
     * Ein Titel wird häufig im Zusammenhang mit Namen verwendet, ist aber kein orginärer Bestandteil des Namens. Im Unterschied dazu gehören Adelstitel zum Familiennamen und sind daher in diesem Verständnis kein Titel. Zu den Titeln zählen beispielsweise akademische Grade, Dienst- und Amtsbezeichnungen oder militärische Ränge. Es können auch Titel übermittelt werden, die keine Titel im Sinne des Meldewesens sind. Beispiel: Dr.
     *
     * @var string $titel
     */
    private $titel = null;

    /**
     * Die Anrede ist der Namenszusatz (auch eine Anrede ohne Namen nur mit Titel ist eine Anrede) bei der Anrede (mündlich oder schriftlich) oder bei einem Anruf (fernmündlich) an eine Person oder Personengruppe. Anmerkung: Die komplette Anrede einer Person kann in einem Feld übermittelt werden. Beispiel: Herr, Frau, Herr Staatssekretär, Frau Bundeskanzlerin, Herr Botschafter, Eure Eminenz
     *
     * @var string $anrede
     */
    private $anrede = null;

    /**
     * Der Familienname ist der aktuelle Nachname einer Person und Ausdruck einer bestimmten Familienzugehörigkeit dieser Person.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType $familienname
     */
    private $familienname = null;

    /**
     * Der Vorname ist der Name bzw. der Teil des Namens, der nicht die Zugehörigkeit zu einer Familie ausdrückt, sondern das Individuum innerhalb der Familie bezeichnet und dazu dient, es von anderen Familienmitgliedern zu unterscheiden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType $vorname
     */
    private $vorname = null;

    /**
     * Gets as titel
     *
     * Ein Titel wird häufig im Zusammenhang mit Namen verwendet, ist aber kein orginärer Bestandteil des Namens. Im Unterschied dazu gehören Adelstitel zum Familiennamen und sind daher in diesem Verständnis kein Titel. Zu den Titeln zählen beispielsweise akademische Grade, Dienst- und Amtsbezeichnungen oder militärische Ränge. Es können auch Titel übermittelt werden, die keine Titel im Sinne des Meldewesens sind. Beispiel: Dr.
     *
     * @return string
     */
    public function getTitel()
    {
        return $this->titel;
    }

    /**
     * Sets a new titel
     *
     * Ein Titel wird häufig im Zusammenhang mit Namen verwendet, ist aber kein orginärer Bestandteil des Namens. Im Unterschied dazu gehören Adelstitel zum Familiennamen und sind daher in diesem Verständnis kein Titel. Zu den Titeln zählen beispielsweise akademische Grade, Dienst- und Amtsbezeichnungen oder militärische Ränge. Es können auch Titel übermittelt werden, die keine Titel im Sinne des Meldewesens sind. Beispiel: Dr.
     *
     * @param string $titel
     * @return self
     */
    public function setTitel($titel)
    {
        $this->titel = $titel;
        return $this;
    }

    /**
     * Gets as anrede
     *
     * Die Anrede ist der Namenszusatz (auch eine Anrede ohne Namen nur mit Titel ist eine Anrede) bei der Anrede (mündlich oder schriftlich) oder bei einem Anruf (fernmündlich) an eine Person oder Personengruppe. Anmerkung: Die komplette Anrede einer Person kann in einem Feld übermittelt werden. Beispiel: Herr, Frau, Herr Staatssekretär, Frau Bundeskanzlerin, Herr Botschafter, Eure Eminenz
     *
     * @return string
     */
    public function getAnrede()
    {
        return $this->anrede;
    }

    /**
     * Sets a new anrede
     *
     * Die Anrede ist der Namenszusatz (auch eine Anrede ohne Namen nur mit Titel ist eine Anrede) bei der Anrede (mündlich oder schriftlich) oder bei einem Anruf (fernmündlich) an eine Person oder Personengruppe. Anmerkung: Die komplette Anrede einer Person kann in einem Feld übermittelt werden. Beispiel: Herr, Frau, Herr Staatssekretär, Frau Bundeskanzlerin, Herr Botschafter, Eure Eminenz
     *
     * @param string $anrede
     * @return self
     */
    public function setAnrede($anrede)
    {
        $this->anrede = $anrede;
        return $this;
    }

    /**
     * Gets as familienname
     *
     * Der Familienname ist der aktuelle Nachname einer Person und Ausdruck einer bestimmten Familienzugehörigkeit dieser Person.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType
     */
    public function getFamilienname()
    {
        return $this->familienname;
    }

    /**
     * Sets a new familienname
     *
     * Der Familienname ist der aktuelle Nachname einer Person und Ausdruck einer bestimmten Familienzugehörigkeit dieser Person.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType $familienname
     * @return self
     */
    public function setFamilienname(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType $familienname)
    {
        $this->familienname = $familienname;
        return $this;
    }

    /**
     * Gets as vorname
     *
     * Der Vorname ist der Name bzw. der Teil des Namens, der nicht die Zugehörigkeit zu einer Familie ausdrückt, sondern das Individuum innerhalb der Familie bezeichnet und dazu dient, es von anderen Familienmitgliedern zu unterscheiden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType
     */
    public function getVorname()
    {
        return $this->vorname;
    }

    /**
     * Sets a new vorname
     *
     * Der Vorname ist der Name bzw. der Teil des Namens, der nicht die Zugehörigkeit zu einer Familie ausdrückt, sondern das Individuum innerhalb der Familie bezeichnet und dazu dient, es von anderen Familienmitgliedern zu unterscheiden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType $vorname
     * @return self
     */
    public function setVorname(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AllgemeinerNameType $vorname)
    {
        $this->vorname = $vorname;
        return $this;
    }
}

