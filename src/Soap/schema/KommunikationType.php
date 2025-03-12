<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing KommunikationType
 *
 * "Kommunikation" fasst Angaben zur Erreichbarkeit über elektronische Kommunikationskanäle (z.B. Telefon, Fax, E-Mail) zusammen.
 * XSD Type: Kommunikation
 */
class KommunikationType
{
    /**
     * Der "kanal" gibt an, über welchen Kommunikationskanal eine Erreichbarkeit besteht (Telefon, E-Mail usw.).
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitType $kanal
     */
    private $kanal = null;

    /**
     * Die "kennung" beinhaltet die konkreten Angaben zur Erreichbarkeit über einen Kommunikationskanal, d.h. die Telefonnummer, Faxnummer, E-Mail-Adresse oder dergleichen. Die Kennung soll strukturiert erfasst werden. Beispiele: +49 30 12345-67 (internationale Rufnummer nach DIN 5008) 030 12345-67 ( nationale Rufnummern nach DIN 5008) +49 89 1234567 (internationale Rufnummer nach E.123) (089) 123456) (nationale Rufnummer nach E.123) tel:+49-30-1234567 (Uniform Resource Identifier nach RFC 3966)
     *
     * @var string $kennung
     */
    private $kennung = null;

    /**
     * Mit der Komponente "istDienstlich" kann angegeben werden, ob es sich um dienstliche oder private Kommunikationsdaten handelt.
     *
     * @var bool $istDienstlich
     */
    private $istDienstlich = null;

    /**
     * Im "zusatz" können zusätzliche freie Angaben zur Erreichbarkeit über einen Kommunikationskanal gemacht werden. Beispiel: erreichbar tagsüber zwischen 9 und 16 Uhr
     *
     * @var string $zusatz
     */
    private $zusatz = null;

    /**
     * Gets as kanal
     *
     * Der "kanal" gibt an, über welchen Kommunikationskanal eine Erreichbarkeit besteht (Telefon, E-Mail usw.).
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitType
     */
    public function getKanal()
    {
        return $this->kanal;
    }

    /**
     * Sets a new kanal
     *
     * Der "kanal" gibt an, über welchen Kommunikationskanal eine Erreichbarkeit besteht (Telefon, E-Mail usw.).
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitType $kanal
     * @return self
     */
    public function setKanal(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitType $kanal = null)
    {
        $this->kanal = $kanal;
        return $this;
    }

    /**
     * Gets as kennung
     *
     * Die "kennung" beinhaltet die konkreten Angaben zur Erreichbarkeit über einen Kommunikationskanal, d.h. die Telefonnummer, Faxnummer, E-Mail-Adresse oder dergleichen. Die Kennung soll strukturiert erfasst werden. Beispiele: +49 30 12345-67 (internationale Rufnummer nach DIN 5008) 030 12345-67 ( nationale Rufnummern nach DIN 5008) +49 89 1234567 (internationale Rufnummer nach E.123) (089) 123456) (nationale Rufnummer nach E.123) tel:+49-30-1234567 (Uniform Resource Identifier nach RFC 3966)
     *
     * @return string
     */
    public function getKennung()
    {
        return $this->kennung;
    }

    /**
     * Sets a new kennung
     *
     * Die "kennung" beinhaltet die konkreten Angaben zur Erreichbarkeit über einen Kommunikationskanal, d.h. die Telefonnummer, Faxnummer, E-Mail-Adresse oder dergleichen. Die Kennung soll strukturiert erfasst werden. Beispiele: +49 30 12345-67 (internationale Rufnummer nach DIN 5008) 030 12345-67 ( nationale Rufnummern nach DIN 5008) +49 89 1234567 (internationale Rufnummer nach E.123) (089) 123456) (nationale Rufnummer nach E.123) tel:+49-30-1234567 (Uniform Resource Identifier nach RFC 3966)
     *
     * @param string $kennung
     * @return self
     */
    public function setKennung($kennung)
    {
        $this->kennung = $kennung;
        return $this;
    }

    /**
     * Gets as istDienstlich
     *
     * Mit der Komponente "istDienstlich" kann angegeben werden, ob es sich um dienstliche oder private Kommunikationsdaten handelt.
     *
     * @return bool
     */
    public function getIstDienstlich()
    {
        return $this->istDienstlich;
    }

    /**
     * Sets a new istDienstlich
     *
     * Mit der Komponente "istDienstlich" kann angegeben werden, ob es sich um dienstliche oder private Kommunikationsdaten handelt.
     *
     * @param bool $istDienstlich
     * @return self
     */
    public function setIstDienstlich($istDienstlich)
    {
        $this->istDienstlich = $istDienstlich;
        return $this;
    }

    /**
     * Gets as zusatz
     *
     * Im "zusatz" können zusätzliche freie Angaben zur Erreichbarkeit über einen Kommunikationskanal gemacht werden. Beispiel: erreichbar tagsüber zwischen 9 und 16 Uhr
     *
     * @return string
     */
    public function getZusatz()
    {
        return $this->zusatz;
    }

    /**
     * Sets a new zusatz
     *
     * Im "zusatz" können zusätzliche freie Angaben zur Erreichbarkeit über einen Kommunikationskanal gemacht werden. Beispiel: erreichbar tagsüber zwischen 9 und 16 Uhr
     *
     * @param string $zusatz
     * @return self
     */
    public function setZusatz($zusatz)
    {
        $this->zusatz = $zusatz;
        return $this;
    }
}

