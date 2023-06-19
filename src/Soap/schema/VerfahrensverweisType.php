<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing VerfahrensverweisType
 *
 *
 * XSD Type: Verfahrensverweis
 */
class VerfahrensverweisType
{
    /**
     * @var string $planID
     */
    private $planID = null;

    /**
     * @var string $planname
     */
    private $planname = null;

    /**
     * @var string $arbeitstitel
     */
    private $arbeitstitel = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypType $verfahrenstyp
     */
    private $verfahrenstyp = null;

    /**
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType $verfahrensart
     */
    private $verfahrensart = null;

    /**
     * @var string $vfdbID
     */
    private $vfdbID = null;

    /**
     * Gets as planID
     *
     * @return string
     */
    public function getPlanID()
    {
        return $this->planID;
    }

    /**
     * Sets a new planID
     *
     * @param string $planID
     * @return self
     */
    public function setPlanID($planID)
    {
        $this->planID = $planID;
        return $this;
    }

    /**
     * Gets as planname
     *
     * @return string
     */
    public function getPlanname()
    {
        return $this->planname;
    }

    /**
     * Sets a new planname
     *
     * @param string $planname
     * @return self
     */
    public function setPlanname($planname)
    {
        $this->planname = $planname;
        return $this;
    }

    /**
     * Gets as arbeitstitel
     *
     * @return string
     */
    public function getArbeitstitel()
    {
        return $this->arbeitstitel;
    }

    /**
     * Sets a new arbeitstitel
     *
     * @param string $arbeitstitel
     * @return self
     */
    public function setArbeitstitel($arbeitstitel)
    {
        $this->arbeitstitel = $arbeitstitel;
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
    public function setVerfahrenstyp(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrenstypType $verfahrenstyp)
    {
        $this->verfahrenstyp = $verfahrenstyp;
        return $this;
    }

    /**
     * Gets as verfahrensart
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType
     */
    public function getVerfahrensart()
    {
        return $this->verfahrensart;
    }

    /**
     * Sets a new verfahrensart
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType $verfahrensart
     * @return self
     */
    public function setVerfahrensart(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensartType $verfahrensart)
    {
        $this->verfahrensart = $verfahrensart;
        return $this;
    }

    /**
     * Gets as vfdbID
     *
     * @return string
     */
    public function getVfdbID()
    {
        return $this->vfdbID;
    }

    /**
     * Sets a new vfdbID
     *
     * @param string $vfdbID
     * @return self
     */
    public function setVfdbID($vfdbID)
    {
        $this->vfdbID = $vfdbID;
        return $this;
    }
}

