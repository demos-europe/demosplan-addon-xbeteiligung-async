<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing VerfahrenTypeType
 *
 * Element zur Spezifizierung eines Verfahrens.
 * XSD Type: VerfahrenType
 */
class VerfahrenTypeType
{
    /**
     * @var string $verfahrenstypID
     */
    private $verfahrenstypID = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypTypeType $verfahrenstyp
     */
    private $verfahrenstyp = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittTypeType[] $verfahrensteilschritt
     */
    private $verfahrensteilschritt = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType[] $unterverfahrensteilschritt
     */
    private $unterverfahrensteilschritt = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitTypeType $zustaendigkeit
     */
    private $zustaendigkeit = null;

    /**
     * @var string[] $aktuelleMitteilung
     */
    private $aktuelleMitteilung = [
        
    ];

    /**
     * Gets as verfahrenstypID
     *
     * @return string
     */
    public function getVerfahrenstypID()
    {
        return $this->verfahrenstypID;
    }

    /**
     * Sets a new verfahrenstypID
     *
     * @param string $verfahrenstypID
     * @return self
     */
    public function setVerfahrenstypID($verfahrenstypID)
    {
        $this->verfahrenstypID = $verfahrenstypID;
        return $this;
    }

    /**
     * Gets as verfahrenstyp
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypTypeType
     */
    public function getVerfahrenstyp()
    {
        return $this->verfahrenstyp;
    }

    /**
     * Sets a new verfahrenstyp
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypTypeType $verfahrenstyp
     * @return self
     */
    public function setVerfahrenstyp(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypTypeType $verfahrenstyp = null)
    {
        $this->verfahrenstyp = $verfahrenstyp;
        return $this;
    }

    /**
     * Adds as verfahrensteilschritt
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittTypeType $verfahrensteilschritt
     */
    public function addToVerfahrensteilschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittTypeType $verfahrensteilschritt)
    {
        $this->verfahrensteilschritt[] = $verfahrensteilschritt;
        return $this;
    }

    /**
     * isset verfahrensteilschritt
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVerfahrensteilschritt($index)
    {
        return isset($this->verfahrensteilschritt[$index]);
    }

    /**
     * unset verfahrensteilschritt
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVerfahrensteilschritt($index)
    {
        unset($this->verfahrensteilschritt[$index]);
    }

    /**
     * Gets as verfahrensteilschritt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittTypeType[]
     */
    public function getVerfahrensteilschritt()
    {
        return $this->verfahrensteilschritt;
    }

    /**
     * Sets a new verfahrensteilschritt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittTypeType[] $verfahrensteilschritt
     * @return self
     */
    public function setVerfahrensteilschritt(array $verfahrensteilschritt = null)
    {
        $this->verfahrensteilschritt = $verfahrensteilschritt;
        return $this;
    }

    /**
     * Adds as unterverfahrensteilschritt
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType $unterverfahrensteilschritt
     */
    public function addToUnterverfahrensteilschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType $unterverfahrensteilschritt)
    {
        $this->unterverfahrensteilschritt[] = $unterverfahrensteilschritt;
        return $this;
    }

    /**
     * isset unterverfahrensteilschritt
     *
     * @param int|string $index
     * @return bool
     */
    public function issetUnterverfahrensteilschritt($index)
    {
        return isset($this->unterverfahrensteilschritt[$index]);
    }

    /**
     * unset unterverfahrensteilschritt
     *
     * @param int|string $index
     * @return void
     */
    public function unsetUnterverfahrensteilschritt($index)
    {
        unset($this->unterverfahrensteilschritt[$index]);
    }

    /**
     * Gets as unterverfahrensteilschritt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType[]
     */
    public function getUnterverfahrensteilschritt()
    {
        return $this->unterverfahrensteilschritt;
    }

    /**
     * Sets a new unterverfahrensteilschritt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittTypeType[] $unterverfahrensteilschritt
     * @return self
     */
    public function setUnterverfahrensteilschritt(array $unterverfahrensteilschritt = null)
    {
        $this->unterverfahrensteilschritt = $unterverfahrensteilschritt;
        return $this;
    }

    /**
     * Gets as zustaendigkeit
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitTypeType
     */
    public function getZustaendigkeit()
    {
        return $this->zustaendigkeit;
    }

    /**
     * Sets a new zustaendigkeit
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitTypeType $zustaendigkeit
     * @return self
     */
    public function setZustaendigkeit(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitTypeType $zustaendigkeit)
    {
        $this->zustaendigkeit = $zustaendigkeit;
        return $this;
    }

    /**
     * Adds as aktuelleMitteilung
     *
     * @return self
     * @param string $aktuelleMitteilung
     */
    public function addToAktuelleMitteilung($aktuelleMitteilung)
    {
        $this->aktuelleMitteilung[] = $aktuelleMitteilung;
        return $this;
    }

    /**
     * isset aktuelleMitteilung
     *
     * @param int|string $index
     * @return bool
     */
    public function issetAktuelleMitteilung($index)
    {
        return isset($this->aktuelleMitteilung[$index]);
    }

    /**
     * unset aktuelleMitteilung
     *
     * @param int|string $index
     * @return void
     */
    public function unsetAktuelleMitteilung($index)
    {
        unset($this->aktuelleMitteilung[$index]);
    }

    /**
     * Gets as aktuelleMitteilung
     *
     * @return string[]
     */
    public function getAktuelleMitteilung()
    {
        return $this->aktuelleMitteilung;
    }

    /**
     * Sets a new aktuelleMitteilung
     *
     * @param string[] $aktuelleMitteilung
     * @return self
     */
    public function setAktuelleMitteilung(array $aktuelleMitteilung = null)
    {
        $this->aktuelleMitteilung = $aktuelleMitteilung;
        return $this;
    }
}

