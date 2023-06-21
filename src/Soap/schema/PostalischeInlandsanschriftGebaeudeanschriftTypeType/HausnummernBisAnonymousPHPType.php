<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType;

/**
 * Class representing HausnummernBisAnonymousPHPType
 */
class HausnummernBisAnonymousPHPType
{
    /**
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element hausnummer übermittelt.
     *
     * @var string $hausnummerBis
     */
    private $hausnummerBis = null;

    /**
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element hausnummerbuchstabezusatzziffer übermittelt.
     *
     * @var string $hausnummerbuchstabezusatzzifferBis
     */
    private $hausnummerbuchstabezusatzzifferBis = null;

    /**
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element teilnummerderhausnummer übermittelt.
     *
     * @var string $teilnummerderhausnummerBis
     */
    private $teilnummerderhausnummerBis = null;

    /**
     * Gets as hausnummerBis
     *
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element hausnummer übermittelt.
     *
     * @return string
     */
    public function getHausnummerBis()
    {
        return $this->hausnummerBis;
    }

    /**
     * Sets a new hausnummerBis
     *
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element hausnummer übermittelt.
     *
     * @param string $hausnummerBis
     * @return self
     */
    public function setHausnummerBis($hausnummerBis)
    {
        $this->hausnummerBis = $hausnummerBis;
        return $this;
    }

    /**
     * Gets as hausnummerbuchstabezusatzzifferBis
     *
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element hausnummerbuchstabezusatzziffer übermittelt.
     *
     * @return string
     */
    public function getHausnummerbuchstabezusatzzifferBis()
    {
        return $this->hausnummerbuchstabezusatzzifferBis;
    }

    /**
     * Sets a new hausnummerbuchstabezusatzzifferBis
     *
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element hausnummerbuchstabezusatzziffer übermittelt.
     *
     * @param string $hausnummerbuchstabezusatzzifferBis
     * @return self
     */
    public function setHausnummerbuchstabezusatzzifferBis($hausnummerbuchstabezusatzzifferBis)
    {
        $this->hausnummerbuchstabezusatzzifferBis = $hausnummerbuchstabezusatzzifferBis;
        return $this;
    }

    /**
     * Gets as teilnummerderhausnummerBis
     *
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element teilnummerderhausnummer übermittelt.
     *
     * @return string
     */
    public function getTeilnummerderhausnummerBis()
    {
        return $this->teilnummerderhausnummerBis;
    }

    /**
     * Sets a new teilnummerderhausnummerBis
     *
     * Soll ein Hausnummernbereich übermittelt werden, so ist hier das Ende dieses Bereichs zu übermitteln. Der Anfang des Bereichs wird in dem Element teilnummerderhausnummer übermittelt.
     *
     * @param string $teilnummerderhausnummerBis
     * @return self
     */
    public function setTeilnummerderhausnummerBis($teilnummerderhausnummerBis)
    {
        $this->teilnummerderhausnummerBis = $teilnummerderhausnummerBis;
        return $this;
    }
}

