<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing VerfahrenType
 *
 * Element zur Spezifizierung eines Verfahrens.
 * XSD Type: Verfahren
 */
class VerfahrenType
{
    /**
     * @var string $verfahrenstypID
     */
    private $verfahrenstypID = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypType $verfahrenstyp
     */
    private $verfahrenstyp = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType[] $verfahrensschritt
     */
    private $verfahrensschritt = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittType[] $verfahrensteilschritt
     */
    private $verfahrensteilschritt = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType[] $unterverfahrensteilschritt
     */
    private $unterverfahrensteilschritt = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType[] $verfahrensart
     */
    private $verfahrensart = [
        
    ];

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitType $zustaendigkeit
     */
    private $zustaendigkeit = null;

    /**
     * @var string $aktuelleMitteilung
     */
    private $aktuelleMitteilung = null;

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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypType
     */
    public function getVerfahrenstyp()
    {
        return $this->verfahrenstyp;
    }

    /**
     * Sets a new verfahrenstyp
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypType $verfahrenstyp
     * @return self
     */
    public function setVerfahrenstyp(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypType $verfahrenstyp = null)
    {
        $this->verfahrenstyp = $verfahrenstyp;
        return $this;
    }

    /**
     * Adds as verfahrensschritt
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschritt
     */
    public function addToVerfahrensschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType $verfahrensschritt)
    {
        $this->verfahrensschritt[] = $verfahrensschritt;
        return $this;
    }

    /**
     * isset verfahrensschritt
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVerfahrensschritt($index)
    {
        return isset($this->verfahrensschritt[$index]);
    }

    /**
     * unset verfahrensschritt
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVerfahrensschritt($index)
    {
        unset($this->verfahrensschritt[$index]);
    }

    /**
     * Gets as verfahrensschritt
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType[]
     */
    public function getVerfahrensschritt()
    {
        return $this->verfahrensschritt;
    }

    /**
     * Sets a new verfahrensschritt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType[] $verfahrensschritt
     * @return self
     */
    public function setVerfahrensschritt(array $verfahrensschritt = null)
    {
        $this->verfahrensschritt = $verfahrensschritt;
        return $this;
    }

    /**
     * Adds as verfahrensteilschritt
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittType $verfahrensteilschritt
     */
    public function addToVerfahrensteilschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittType $verfahrensteilschritt)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittType[]
     */
    public function getVerfahrensteilschritt()
    {
        return $this->verfahrensteilschritt;
    }

    /**
     * Sets a new verfahrensteilschritt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\VerfahrensteilschrittType[] $verfahrensteilschritt
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType $unterverfahrensteilschritt
     */
    public function addToUnterverfahrensteilschritt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType $unterverfahrensteilschritt)
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
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType[]
     */
    public function getUnterverfahrensteilschritt()
    {
        return $this->unterverfahrensteilschritt;
    }

    /**
     * Sets a new unterverfahrensteilschritt
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeUnterverfahrensteilschrittType[] $unterverfahrensteilschritt
     * @return self
     */
    public function setUnterverfahrensteilschritt(array $unterverfahrensteilschritt = null)
    {
        $this->unterverfahrensteilschritt = $unterverfahrensteilschritt;
        return $this;
    }

    /**
     * Adds as verfahrensart
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType $verfahrensart
     */
    public function addToVerfahrensart(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType $verfahrensart)
    {
        $this->verfahrensart[] = $verfahrensart;
        return $this;
    }

    /**
     * isset verfahrensart
     *
     * @param int|string $index
     * @return bool
     */
    public function issetVerfahrensart($index)
    {
        return isset($this->verfahrensart[$index]);
    }

    /**
     * unset verfahrensart
     *
     * @param int|string $index
     * @return void
     */
    public function unsetVerfahrensart($index)
    {
        unset($this->verfahrensart[$index]);
    }

    /**
     * Gets as verfahrensart
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType[]
     */
    public function getVerfahrensart()
    {
        return $this->verfahrensart;
    }

    /**
     * Sets a new verfahrensart
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType[] $verfahrensart
     * @return self
     */
    public function setVerfahrensart(array $verfahrensart = null)
    {
        $this->verfahrensart = $verfahrensart;
        return $this;
    }

    /**
     * Gets as zustaendigkeit
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitType
     */
    public function getZustaendigkeit()
    {
        return $this->zustaendigkeit;
    }

    /**
     * Sets a new zustaendigkeit
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitType $zustaendigkeit
     * @return self
     */
    public function setZustaendigkeit(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeZustaendigkeitType $zustaendigkeit)
    {
        $this->zustaendigkeit = $zustaendigkeit;
        return $this;
    }

    /**
     * Gets as aktuelleMitteilung
     *
     * @return string
     */
    public function getAktuelleMitteilung()
    {
        return $this->aktuelleMitteilung;
    }

    /**
     * Sets a new aktuelleMitteilung
     *
     * @param string $aktuelleMitteilung
     * @return self
     */
    public function setAktuelleMitteilung($aktuelleMitteilung)
    {
        $this->aktuelleMitteilung = $aktuelleMitteilung;
        return $this;
    }
}

