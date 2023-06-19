<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungType;

/**
 * Class representing LinklisteAnonymousPHPType
 */
class LinklisteAnonymousPHPType
{
    /**
     * Jedes instanziierte Element steht für einen Link auf eine Webseite oder Ressource.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType[] $link
     */
    private $link = [
        
    ];

    /**
     * Adds as link
     *
     * Jedes instanziierte Element steht für einen Link auf eine Webseite oder Ressource.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType $link
     */
    public function addToLink(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType $link)
    {
        $this->link[] = $link;
        return $this;
    }

    /**
     * isset link
     *
     * Jedes instanziierte Element steht für einen Link auf eine Webseite oder Ressource.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLink($index)
    {
        return isset($this->link[$index]);
    }

    /**
     * unset link
     *
     * Jedes instanziierte Element steht für einen Link auf eine Webseite oder Ressource.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLink($index)
    {
        unset($this->link[$index]);
    }

    /**
     * Gets as link
     *
     * Jedes instanziierte Element steht für einen Link auf eine Webseite oder Ressource.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType[]
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Sets a new link
     *
     * Jedes instanziierte Element steht für einen Link auf eine Webseite oder Ressource.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType[] $link
     * @return self
     */
    public function setLink(array $link = null)
    {
        $this->link = $link;
        return $this;
    }
}

