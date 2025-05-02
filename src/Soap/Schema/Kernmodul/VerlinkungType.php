<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing VerlinkungType
 *
 * Dieser Typ gestattet es, Webressourcen anzugeben für ggf. nötige Reaktionen des Lesers auf die vorliegende Nachricht.
 * XSD Type: Verlinkung
 */
class VerlinkungType
{
    /**
     * Dieses Element steht für eine Liste von verlinkten Angeboten, die vom Autor der Nachricht bereitgestellt werden.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType[] $linkliste
     */
    private $linkliste = null;

    /**
     * Falls dieses Elements instanziiert ist, gibt der Autor dadurch an, dass auf ein eigenes Angebot verzichtet wird und dass stattdessen auf eine ggf. bereitgestellte zentrale bzw. einheitlich vorgegebene Möglichkeit verwiesen wird.
     *
     * @var bool $default
     */
    private $default = null;

    /**
     * Falls dieses Element instanziiert ist, ist das das Zeichen, dass weder ein eigenes Angebot bereitgestellt wird, noch auf eine zentrale Lösung verwiesen wird.
     *
     * @var bool $noreply
     */
    private $noreply = null;

    /**
     * Adds as link
     *
     * Dieses Element steht für eine Liste von verlinkten Angeboten, die vom Autor der Nachricht bereitgestellt werden.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType $link
     */
    public function addToLinkliste(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType $link)
    {
        $this->linkliste[] = $link;
        return $this;
    }

    /**
     * isset linkliste
     *
     * Dieses Element steht für eine Liste von verlinkten Angeboten, die vom Autor der Nachricht bereitgestellt werden.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetLinkliste($index)
    {
        return isset($this->linkliste[$index]);
    }

    /**
     * unset linkliste
     *
     * Dieses Element steht für eine Liste von verlinkten Angeboten, die vom Autor der Nachricht bereitgestellt werden.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetLinkliste($index)
    {
        unset($this->linkliste[$index]);
    }

    /**
     * Gets as linkliste
     *
     * Dieses Element steht für eine Liste von verlinkten Angeboten, die vom Autor der Nachricht bereitgestellt werden.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType[]
     */
    public function getLinkliste()
    {
        return $this->linkliste;
    }

    /**
     * Sets a new linkliste
     *
     * Dieses Element steht für eine Liste von verlinkten Angeboten, die vom Autor der Nachricht bereitgestellt werden.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\VerlinkungType\LinklisteAnonymousPHPType\LinkAnonymousPHPType[] $linkliste
     * @return self
     */
    public function setLinkliste(array $linkliste = null)
    {
        $this->linkliste = $linkliste;
        return $this;
    }

    /**
     * Gets as default
     *
     * Falls dieses Elements instanziiert ist, gibt der Autor dadurch an, dass auf ein eigenes Angebot verzichtet wird und dass stattdessen auf eine ggf. bereitgestellte zentrale bzw. einheitlich vorgegebene Möglichkeit verwiesen wird.
     *
     * @return bool
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Sets a new default
     *
     * Falls dieses Elements instanziiert ist, gibt der Autor dadurch an, dass auf ein eigenes Angebot verzichtet wird und dass stattdessen auf eine ggf. bereitgestellte zentrale bzw. einheitlich vorgegebene Möglichkeit verwiesen wird.
     *
     * @param bool $default
     * @return self
     */
    public function setDefault($default)
    {
        $this->default = $default;
        return $this;
    }

    /**
     * Gets as noreply
     *
     * Falls dieses Element instanziiert ist, ist das das Zeichen, dass weder ein eigenes Angebot bereitgestellt wird, noch auf eine zentrale Lösung verwiesen wird.
     *
     * @return bool
     */
    public function getNoreply()
    {
        return $this->noreply;
    }

    /**
     * Sets a new noreply
     *
     * Falls dieses Element instanziiert ist, ist das das Zeichen, dass weder ein eigenes Angebot bereitgestellt wird, noch auf eine zentrale Lösung verwiesen wird.
     *
     * @param bool $noreply
     * @return self
     */
    public function setNoreply($noreply)
    {
        $this->noreply = $noreply;
        return $this;
    }
}

