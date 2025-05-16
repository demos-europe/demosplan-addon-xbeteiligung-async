<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified;

/**
 * Class representing NachrichtG2GTypeType
 *
 * Basistyp für alle Nachrichten zwischen Behörden und anderen öffentlichen Stellen (government-to-government).
 * XSD Type: Nachricht.G2GType
 */
class NachrichtG2GTypeType
{
    /**
     * In diesem Attribut ist der Name des Produktes (der Software) einzutragen, mit dem die Nachricht erstellt worden ist.
     *
     * @var string $produkt
     */
    private $produkt = null;

    /**
     * In diesem Attribut wird der Name der Organisation / Firma übermittelt, die für das Produkt (die Software) verantwortlich ist, mit dem die Nachricht erstellt wurde.
     *
     * @var string $produkthersteller
     */
    private $produkthersteller = null;

    /**
     * In diesem Attribut werden ergänzende Hinweise zu dem Produkt eingetragen. Dies sind Angaben, die für eine möglichst präzise Identifikation im Fehlerfall hilfreich sind, wie zum Beispiel Version und Patchlevel.
     *
     * @var string $produktversion
     */
    private $produktversion = null;

    /**
     * In diesem Attribut wird der Name des XÖV-Standards angegeben, aus dem die Nachricht stammt.
     *
     * @var string $standard
     */
    private $standard = null;

    /**
     * Ist dieses Attribut vorhanden, handelt es sich aus Sicht des Autors um eine Testnachricht, die nicht im normalen Produktivbetrieb verarbeitet werden darf. Autor und Leser können bilateral weitere Absprachen über den konkreten Inhalt des Attributs treffen.
     *
     * @var string $test
     */
    private $test = null;

    /**
     * In diesem Attribut wird die Version des XÖV-Standards eingetragen, aus dem die Nachricht stammt.
     *
     * @var string $version
     */
    private $version = null;

    /**
     * Nachrichtenkopf für Nachrichten zwischen Behörden und anderen (öffentlichen) Stellen. Der Nachrichtenkopf umfasst Angaben zur eindeutigen Identifikation des Autors und des Lesers der Nachricht sowie der Nachricht selbst.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType $nachrichtenkopfG2g
     */
    private $nachrichtenkopfG2g = null;

    /**
     * Gets as produkt
     *
     * In diesem Attribut ist der Name des Produktes (der Software) einzutragen, mit dem die Nachricht erstellt worden ist.
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
     * In diesem Attribut ist der Name des Produktes (der Software) einzutragen, mit dem die Nachricht erstellt worden ist.
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
     * In diesem Attribut wird der Name der Organisation / Firma übermittelt, die für das Produkt (die Software) verantwortlich ist, mit dem die Nachricht erstellt wurde.
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
     * In diesem Attribut wird der Name der Organisation / Firma übermittelt, die für das Produkt (die Software) verantwortlich ist, mit dem die Nachricht erstellt wurde.
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
     * In diesem Attribut werden ergänzende Hinweise zu dem Produkt eingetragen. Dies sind Angaben, die für eine möglichst präzise Identifikation im Fehlerfall hilfreich sind, wie zum Beispiel Version und Patchlevel.
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
     * In diesem Attribut werden ergänzende Hinweise zu dem Produkt eingetragen. Dies sind Angaben, die für eine möglichst präzise Identifikation im Fehlerfall hilfreich sind, wie zum Beispiel Version und Patchlevel.
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
     * In diesem Attribut wird der Name des XÖV-Standards angegeben, aus dem die Nachricht stammt.
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
     * In diesem Attribut wird der Name des XÖV-Standards angegeben, aus dem die Nachricht stammt.
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
     * Ist dieses Attribut vorhanden, handelt es sich aus Sicht des Autors um eine Testnachricht, die nicht im normalen Produktivbetrieb verarbeitet werden darf. Autor und Leser können bilateral weitere Absprachen über den konkreten Inhalt des Attributs treffen.
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
     * Ist dieses Attribut vorhanden, handelt es sich aus Sicht des Autors um eine Testnachricht, die nicht im normalen Produktivbetrieb verarbeitet werden darf. Autor und Leser können bilateral weitere Absprachen über den konkreten Inhalt des Attributs treffen.
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
     * In diesem Attribut wird die Version des XÖV-Standards eingetragen, aus dem die Nachricht stammt.
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
     * In diesem Attribut wird die Version des XÖV-Standards eingetragen, aus dem die Nachricht stammt.
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
     * Gets as nachrichtenkopfG2g
     *
     * Nachrichtenkopf für Nachrichten zwischen Behörden und anderen (öffentlichen) Stellen. Der Nachrichtenkopf umfasst Angaben zur eindeutigen Identifikation des Autors und des Lesers der Nachricht sowie der Nachricht selbst.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType
     */
    public function getNachrichtenkopfG2g()
    {
        return $this->nachrichtenkopfG2g;
    }

    /**
     * Sets a new nachrichtenkopfG2g
     *
     * Nachrichtenkopf für Nachrichten zwischen Behörden und anderen (öffentlichen) Stellen. Der Nachrichtenkopf umfasst Angaben zur eindeutigen Identifikation des Autors und des Lesers der Nachricht sowie der Nachricht selbst.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType $nachrichtenkopfG2g
     * @return self
     */
    public function setNachrichtenkopfG2g(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\NachrichtenkopfG2GTypeType $nachrichtenkopfG2g)
    {
        $this->nachrichtenkopfG2g = $nachrichtenkopfG2g;
        return $this;
    }
}

