<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing VerfahrensteilschrittTypeType
 *
 *
 * XSD Type: VerfahrensteilschrittType
 */
class VerfahrensteilschrittTypeType
{
    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensteilschrittTypeType $verfahrensteilschrittcode
     */
    private $verfahrensteilschrittcode = null;

    /**
     * @var int $mindestdauer
     */
    private $mindestdauer = null;

    /**
     * @var int $maxdauer
     */
    private $maxdauer = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\UnterverfahrensteilschrittTypeType[] $unterverfahrensteilschritte
     */
    private $unterverfahrensteilschritte = [
        
    ];

    /**
     * Gets as verfahrensteilschrittcode
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensteilschrittTypeType
     */
    public function getVerfahrensteilschrittcode()
    {
        return $this->verfahrensteilschrittcode;
    }

    /**
     * Sets a new verfahrensteilschrittcode
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensteilschrittTypeType $verfahrensteilschrittcode
     * @return self
     */
    public function setVerfahrensteilschrittcode(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensteilschrittTypeType $verfahrensteilschrittcode = null)
    {
        $this->verfahrensteilschrittcode = $verfahrensteilschrittcode;
        return $this;
    }

    /**
     * Gets as mindestdauer
     *
     * @return int
     */
    public function getMindestdauer()
    {
        return $this->mindestdauer;
    }

    /**
     * Sets a new mindestdauer
     *
     * @param int $mindestdauer
     * @return self
     */
    public function setMindestdauer($mindestdauer)
    {
        $this->mindestdauer = $mindestdauer;
        return $this;
    }

    /**
     * Gets as maxdauer
     *
     * @return int
     */
    public function getMaxdauer()
    {
        return $this->maxdauer;
    }

    /**
     * Sets a new maxdauer
     *
     * @param int $maxdauer
     * @return self
     */
    public function setMaxdauer($maxdauer)
    {
        $this->maxdauer = $maxdauer;
        return $this;
    }

    /**
     * Adds as unterverfahrensteilschritte
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\UnterverfahrensteilschrittTypeType $unterverfahrensteilschritte
     */
    public function addToUnterverfahrensteilschritte(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\UnterverfahrensteilschrittTypeType $unterverfahrensteilschritte)
    {
        $this->unterverfahrensteilschritte[] = $unterverfahrensteilschritte;
        return $this;
    }

    /**
     * isset unterverfahrensteilschritte
     *
     * @param int|string $index
     * @return bool
     */
    public function issetUnterverfahrensteilschritte($index)
    {
        return isset($this->unterverfahrensteilschritte[$index]);
    }

    /**
     * unset unterverfahrensteilschritte
     *
     * @param int|string $index
     * @return void
     */
    public function unsetUnterverfahrensteilschritte($index)
    {
        unset($this->unterverfahrensteilschritte[$index]);
    }

    /**
     * Gets as unterverfahrensteilschritte
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\UnterverfahrensteilschrittTypeType[]
     */
    public function getUnterverfahrensteilschritte()
    {
        return $this->unterverfahrensteilschritte;
    }

    /**
     * Sets a new unterverfahrensteilschritte
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\UnterverfahrensteilschrittTypeType[] $unterverfahrensteilschritte
     * @return self
     */
    public function setUnterverfahrensteilschritte(array $unterverfahrensteilschritte = null)
    {
        $this->unterverfahrensteilschritte = $unterverfahrensteilschritte;
        return $this;
    }
}

