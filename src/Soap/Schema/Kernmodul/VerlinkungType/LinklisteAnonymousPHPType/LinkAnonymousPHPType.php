<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\VerlinkungType\LinklisteAnonymousPHPType;

/**
 * Class representing LinkAnonymousPHPType
 */
class LinkAnonymousPHPType
{
    /**
     * Hier ist die URL einer Webseite oder Ressource anzugeben.
     *
     * @var string $url
     */
    private $url = null;

    /**
     * Hier ist die zur URL passende Beschreibung der Webseite oder Ressource einzutragen.
     *
     * @var string $beschreibung
     */
    private $beschreibung = null;

    /**
     * Gets as url
     *
     * Hier ist die URL einer Webseite oder Ressource anzugeben.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Sets a new url
     *
     * Hier ist die URL einer Webseite oder Ressource anzugeben.
     *
     * @param string $url
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * Gets as beschreibung
     *
     * Hier ist die zur URL passende Beschreibung der Webseite oder Ressource einzutragen.
     *
     * @return string
     */
    public function getBeschreibung()
    {
        return $this->beschreibung;
    }

    /**
     * Sets a new beschreibung
     *
     * Hier ist die zur URL passende Beschreibung der Webseite oder Ressource einzutragen.
     *
     * @param string $beschreibung
     * @return self
     */
    public function setBeschreibung($beschreibung)
    {
        $this->beschreibung = $beschreibung;
        return $this;
    }
}

