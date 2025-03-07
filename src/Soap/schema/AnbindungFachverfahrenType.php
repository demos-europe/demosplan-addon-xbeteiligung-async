<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AnbindungFachverfahrenType
 *
 * Dieser Typ deckt Parameter ab, die angewendet werden können, um die behördliche Fachanwendung an das Antragsportal oder die Kollaborationsplattform anzubinden.
 * XSD Type: AnbindungFachverfahren
 */
class AnbindungFachverfahrenType
{
    /**
     * Hier ist der API-Key einzutragen. Der API-Key dient als zusätzliche Absicherung der Authenzität auf Ebene der Fachnachricht.
     *
     * @var string $apiKey
     */
    private $apiKey = null;

    /**
     * Über AnwendungsspezifischeErweiterung können über die in XBau konkret definierten Metadaten hinaus weiterführende Metadaten, z. B. fachspezifischer Natur, zu einem Informationsobjekt angegeben werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnwendungsspezifischeErweiterungXMLType $anwendungsspezifischeErweiterung
     */
    private $anwendungsspezifischeErweiterung = null;

    /**
     * Gets as apiKey
     *
     * Hier ist der API-Key einzutragen. Der API-Key dient als zusätzliche Absicherung der Authenzität auf Ebene der Fachnachricht.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Sets a new apiKey
     *
     * Hier ist der API-Key einzutragen. Der API-Key dient als zusätzliche Absicherung der Authenzität auf Ebene der Fachnachricht.
     *
     * @param string $apiKey
     * @return self
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * Gets as anwendungsspezifischeErweiterung
     *
     * Über AnwendungsspezifischeErweiterung können über die in XBau konkret definierten Metadaten hinaus weiterführende Metadaten, z. B. fachspezifischer Natur, zu einem Informationsobjekt angegeben werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnwendungsspezifischeErweiterungXMLType
     */
    public function getAnwendungsspezifischeErweiterung()
    {
        return $this->anwendungsspezifischeErweiterung;
    }

    /**
     * Sets a new anwendungsspezifischeErweiterung
     *
     * Über AnwendungsspezifischeErweiterung können über die in XBau konkret definierten Metadaten hinaus weiterführende Metadaten, z. B. fachspezifischer Natur, zu einem Informationsobjekt angegeben werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnwendungsspezifischeErweiterungXMLType $anwendungsspezifischeErweiterung
     * @return self
     */
    public function setAnwendungsspezifischeErweiterung(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnwendungsspezifischeErweiterungXMLType $anwendungsspezifischeErweiterung = null)
    {
        $this->anwendungsspezifischeErweiterung = $anwendungsspezifischeErweiterung;
        return $this;
    }
}

