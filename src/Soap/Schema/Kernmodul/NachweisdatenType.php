<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing NachweisdatenType
 *
 * Dieser Typ bildet Nachweisdaten zu behördlichen Vorgängen ab.
 * XSD Type: Nachweisdaten
 */
class NachweisdatenType
{
    /**
     * In dieses Element ist die zuständige Behörde einzutragen.
     *
     * @var string $behoerde
     */
    private $behoerde = null;

    /**
     * In dieses Element ist das Datum einzutragen.
     *
     * @var \DateTime $datum
     */
    private $datum = null;

    /**
     * In dieses Element ist das Aktenzeichen einzutragen.
     *
     * @var string $aktenzeichen
     */
    private $aktenzeichen = null;

    /**
     * Gets as behoerde
     *
     * In dieses Element ist die zuständige Behörde einzutragen.
     *
     * @return string
     */
    public function getBehoerde()
    {
        return $this->behoerde;
    }

    /**
     * Sets a new behoerde
     *
     * In dieses Element ist die zuständige Behörde einzutragen.
     *
     * @param string $behoerde
     * @return self
     */
    public function setBehoerde($behoerde)
    {
        $this->behoerde = $behoerde;
        return $this;
    }

    /**
     * Gets as datum
     *
     * In dieses Element ist das Datum einzutragen.
     *
     * @return \DateTime
     */
    public function getDatum()
    {
        return $this->datum;
    }

    /**
     * Sets a new datum
     *
     * In dieses Element ist das Datum einzutragen.
     *
     * @param \DateTime $datum
     * @return self
     */
    public function setDatum(?\DateTime $datum = null)
    {
        $this->datum = $datum;
        return $this;
    }

    /**
     * Gets as aktenzeichen
     *
     * In dieses Element ist das Aktenzeichen einzutragen.
     *
     * @return string
     */
    public function getAktenzeichen()
    {
        return $this->aktenzeichen;
    }

    /**
     * Sets a new aktenzeichen
     *
     * In dieses Element ist das Aktenzeichen einzutragen.
     *
     * @param string $aktenzeichen
     * @return self
     */
    public function setAktenzeichen($aktenzeichen)
    {
        $this->aktenzeichen = $aktenzeichen;
        return $this;
    }
}

