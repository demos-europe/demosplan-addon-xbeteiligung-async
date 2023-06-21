<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing NachrichtB2GTypeType
 *
 * Nachrichtenstruktur für die Kommunikation: Unternehmen ("Business") an Behörde ("Government").
 * XSD Type: Nachricht.B2GType
 */
class NachrichtB2GTypeType
{
    /**
     * In diesem Attribut ist der Name des Produktes (der Software) einzutragen, mit dem diese Nachricht erstellt worden ist.
     *
     * @var string $produkt
     */
    private $produkt = null;

    /**
     * In diesem Attribut ist der Name der Firma oder der Organisation einzutragen, die für das DV-Verfahren verantwortlich ist, mit dem diese Nachricht erstellt worden ist.
     *
     * @var string $produkthersteller
     */
    private $produkthersteller = null;

    /**
     * In diesem Attribut sollen ergänzende Hinweise zu dem Produkt, mit dem diese Nachricht erstellt worden ist, eingetragen werden. Dies sind Angaben, die für eine möglichst präzise Identifikation im Fehlerfall hilfreich sind, wie zum Beispiel Version und Patchlevel.
     *
     * @var string $produktversion
     */
    private $produktversion = null;

    /**
     * In diesem Attribut wird der Name des Standards übermittelt, aus dem die Nachricht stammt. Der Name des Standards wird durch den Fachstandard als fixed-Value auf Schemaebene festgelegt (z. B. XPersonenstand).
     *
     * @var string $standard
     */
    private $standard = null;

    /**
     * Dieses Attribut ist optional. Ist es vorhanden, so sagt dies aus, dass es sich um eine Nachricht handelt, die (aus Sicht des Senders der Nachricht) nicht im normalen Produktivbetrieb behandelt werden soll. Über den Inhalt des Attributes wird nichts weiter ausgesagt, dies kann bilateral zwischen den Kommunikationspartnern vereinbart werden.
     *
     * @var string $test
     */
    private $test = null;

    /**
     * In diesem Attribut wird die Version des Standards übermittelt, aus dem die Nachricht stammt. Die Versionsbezeichnung wird durch den Fachstandard als fixed-Value auf Schemaebene festgelegt (z. B. 1.5.0).
     *
     * @var string $version
     */
    private $version = null;

    /**
     * Dieses Element enthält die Kopfinformationen zu Nachrichten des vorliegenden Typs.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfB2GTypeType $nachrichtenkopfB2G
     */
    private $nachrichtenkopfB2G = null;

    /**
     * Gets as produkt
     *
     * In diesem Attribut ist der Name des Produktes (der Software) einzutragen, mit dem diese Nachricht erstellt worden ist.
     *
     * @return string
     */
    public function getProdukt()
    {
        return $this->produkt;
    }

    /**
     * Sets a new produkt
     *
     * In diesem Attribut ist der Name des Produktes (der Software) einzutragen, mit dem diese Nachricht erstellt worden ist.
     *
     * @param string $produkt
     * @return self
     */
    public function setProdukt($produkt)
    {
        $this->produkt = $produkt;
        return $this;
    }

    /**
     * Gets as produkthersteller
     *
     * In diesem Attribut ist der Name der Firma oder der Organisation einzutragen, die für das DV-Verfahren verantwortlich ist, mit dem diese Nachricht erstellt worden ist.
     *
     * @return string
     */
    public function getProdukthersteller()
    {
        return $this->produkthersteller;
    }

    /**
     * Sets a new produkthersteller
     *
     * In diesem Attribut ist der Name der Firma oder der Organisation einzutragen, die für das DV-Verfahren verantwortlich ist, mit dem diese Nachricht erstellt worden ist.
     *
     * @param string $produkthersteller
     * @return self
     */
    public function setProdukthersteller($produkthersteller)
    {
        $this->produkthersteller = $produkthersteller;
        return $this;
    }

    /**
     * Gets as produktversion
     *
     * In diesem Attribut sollen ergänzende Hinweise zu dem Produkt, mit dem diese Nachricht erstellt worden ist, eingetragen werden. Dies sind Angaben, die für eine möglichst präzise Identifikation im Fehlerfall hilfreich sind, wie zum Beispiel Version und Patchlevel.
     *
     * @return string
     */
    public function getProduktversion()
    {
        return $this->produktversion;
    }

    /**
     * Sets a new produktversion
     *
     * In diesem Attribut sollen ergänzende Hinweise zu dem Produkt, mit dem diese Nachricht erstellt worden ist, eingetragen werden. Dies sind Angaben, die für eine möglichst präzise Identifikation im Fehlerfall hilfreich sind, wie zum Beispiel Version und Patchlevel.
     *
     * @param string $produktversion
     * @return self
     */
    public function setProduktversion($produktversion)
    {
        $this->produktversion = $produktversion;
        return $this;
    }

    /**
     * Gets as standard
     *
     * In diesem Attribut wird der Name des Standards übermittelt, aus dem die Nachricht stammt. Der Name des Standards wird durch den Fachstandard als fixed-Value auf Schemaebene festgelegt (z. B. XPersonenstand).
     *
     * @return string
     */
    public function getStandard()
    {
        return $this->standard;
    }

    /**
     * Sets a new standard
     *
     * In diesem Attribut wird der Name des Standards übermittelt, aus dem die Nachricht stammt. Der Name des Standards wird durch den Fachstandard als fixed-Value auf Schemaebene festgelegt (z. B. XPersonenstand).
     *
     * @param string $standard
     * @return self
     */
    public function setStandard($standard)
    {
        $this->standard = $standard;
        return $this;
    }

    /**
     * Gets as test
     *
     * Dieses Attribut ist optional. Ist es vorhanden, so sagt dies aus, dass es sich um eine Nachricht handelt, die (aus Sicht des Senders der Nachricht) nicht im normalen Produktivbetrieb behandelt werden soll. Über den Inhalt des Attributes wird nichts weiter ausgesagt, dies kann bilateral zwischen den Kommunikationspartnern vereinbart werden.
     *
     * @return string
     */
    public function getTest()
    {
        return $this->test;
    }

    /**
     * Sets a new test
     *
     * Dieses Attribut ist optional. Ist es vorhanden, so sagt dies aus, dass es sich um eine Nachricht handelt, die (aus Sicht des Senders der Nachricht) nicht im normalen Produktivbetrieb behandelt werden soll. Über den Inhalt des Attributes wird nichts weiter ausgesagt, dies kann bilateral zwischen den Kommunikationspartnern vereinbart werden.
     *
     * @param string $test
     * @return self
     */
    public function setTest($test)
    {
        $this->test = $test;
        return $this;
    }

    /**
     * Gets as version
     *
     * In diesem Attribut wird die Version des Standards übermittelt, aus dem die Nachricht stammt. Die Versionsbezeichnung wird durch den Fachstandard als fixed-Value auf Schemaebene festgelegt (z. B. 1.5.0).
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Sets a new version
     *
     * In diesem Attribut wird die Version des Standards übermittelt, aus dem die Nachricht stammt. Die Versionsbezeichnung wird durch den Fachstandard als fixed-Value auf Schemaebene festgelegt (z. B. 1.5.0).
     *
     * @param string $version
     * @return self
     */
    public function setVersion($version)
    {
        $this->version = $version;
        return $this;
    }

    /**
     * Gets as nachrichtenkopfB2G
     *
     * Dieses Element enthält die Kopfinformationen zu Nachrichten des vorliegenden Typs.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfB2GTypeType
     */
    public function getNachrichtenkopfB2G()
    {
        return $this->nachrichtenkopfB2G;
    }

    /**
     * Sets a new nachrichtenkopfB2G
     *
     * Dieses Element enthält die Kopfinformationen zu Nachrichten des vorliegenden Typs.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfB2GTypeType $nachrichtenkopfB2G
     * @return self
     */
    public function setNachrichtenkopfB2G(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfB2GTypeType $nachrichtenkopfB2G)
    {
        $this->nachrichtenkopfB2G = $nachrichtenkopfB2G;
        return $this;
    }
}

