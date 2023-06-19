<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AkteurVorhabenType
 *
 * Der Datentyp benennt Akteure des Vorhabens, für das eine Beteiligung initiert wird.
 * XSD Type: AkteurVorhaben
 */
class AkteurVorhabenType
{
    /**
     * Hier wird Behörde oder Stelle genannt, die für das Verfahren rechtlich verantwortlich ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $veranlasser
     */
    private $veranlasser = null;

    /**
     * Dieser Abschnitt fasst die Daten zu einem weiteren Akteur zusammen. Für jeden Akteur ist ein Element zu instanziieren.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType\WeitereAkteureAnonymousPHPType[] $weitereAkteure
     */
    private $weitereAkteure = [
        
    ];

    /**
     * Gets as veranlasser
     *
     * Hier wird Behörde oder Stelle genannt, die für das Verfahren rechtlich verantwortlich ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType
     */
    public function getVeranlasser()
    {
        return $this->veranlasser;
    }

    /**
     * Sets a new veranlasser
     *
     * Hier wird Behörde oder Stelle genannt, die für das Verfahren rechtlich verantwortlich ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $veranlasser
     * @return self
     */
    public function setVeranlasser(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType $veranlasser)
    {
        $this->veranlasser = $veranlasser;
        return $this;
    }

    /**
     * Adds as weitereAkteure
     *
     * Dieser Abschnitt fasst die Daten zu einem weiteren Akteur zusammen. Für jeden Akteur ist ein Element zu instanziieren.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType\WeitereAkteureAnonymousPHPType $weitereAkteure
     */
    public function addToWeitereAkteure(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType\WeitereAkteureAnonymousPHPType $weitereAkteure)
    {
        $this->weitereAkteure[] = $weitereAkteure;
        return $this;
    }

    /**
     * isset weitereAkteure
     *
     * Dieser Abschnitt fasst die Daten zu einem weiteren Akteur zusammen. Für jeden Akteur ist ein Element zu instanziieren.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetWeitereAkteure($index)
    {
        return isset($this->weitereAkteure[$index]);
    }

    /**
     * unset weitereAkteure
     *
     * Dieser Abschnitt fasst die Daten zu einem weiteren Akteur zusammen. Für jeden Akteur ist ein Element zu instanziieren.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetWeitereAkteure($index)
    {
        unset($this->weitereAkteure[$index]);
    }

    /**
     * Gets as weitereAkteure
     *
     * Dieser Abschnitt fasst die Daten zu einem weiteren Akteur zusammen. Für jeden Akteur ist ein Element zu instanziieren.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType\WeitereAkteureAnonymousPHPType[]
     */
    public function getWeitereAkteure()
    {
        return $this->weitereAkteure;
    }

    /**
     * Sets a new weitereAkteure
     *
     * Dieser Abschnitt fasst die Daten zu einem weiteren Akteur zusammen. Für jeden Akteur ist ein Element zu instanziieren.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType\WeitereAkteureAnonymousPHPType[] $weitereAkteure
     * @return self
     */
    public function setWeitereAkteure(array $weitereAkteure = null)
    {
        $this->weitereAkteure = $weitereAkteure;
        return $this;
    }
}

