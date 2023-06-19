<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing TeilbekanntesDatumType
 *
 * Mit diesem Datentyp kann entweder ein vollständig bekanntes oder ein teilweise bekanntes Datum übermittelt werden. Die Angabe einer Zeitzone ist in keinem Fall möglich.
 * XSD Type: TeilbekanntesDatum
 */
class TeilbekanntesDatumType
{
    /**
     * Angabe eines vollständigen Datums
     *
     * @var \DateTime $jahrMonatTag
     */
    private $jahrMonatTag = null;

    /**
     * Angabe eines Datums mit Jahr und Monat
     *
     * @var int $jahrMonat
     */
    private $jahrMonat = null;

    /**
     * Angabe eines Datums durch eine Jahresangabe
     *
     * @var int $jahr
     */
    private $jahr = null;

    /**
     * Gets as jahrMonatTag
     *
     * Angabe eines vollständigen Datums
     *
     * @return \DateTime
     */
    public function getJahrMonatTag()
    {
        return $this->jahrMonatTag;
    }

    /**
     * Sets a new jahrMonatTag
     *
     * Angabe eines vollständigen Datums
     *
     * @param \DateTime $jahrMonatTag
     * @return self
     */
    public function setJahrMonatTag(\DateTime $jahrMonatTag = null)
    {
        $this->jahrMonatTag = $jahrMonatTag;
        return $this;
    }

    /**
     * Gets as jahrMonat
     *
     * Angabe eines Datums mit Jahr und Monat
     *
     * @return int
     */
    public function getJahrMonat()
    {
        return $this->jahrMonat;
    }

    /**
     * Sets a new jahrMonat
     *
     * Angabe eines Datums mit Jahr und Monat
     *
     * @param int $jahrMonat
     * @return self
     */
    public function setJahrMonat($jahrMonat)
    {
        $this->jahrMonat = $jahrMonat;
        return $this;
    }

    /**
     * Gets as jahr
     *
     * Angabe eines Datums durch eine Jahresangabe
     *
     * @return int
     */
    public function getJahr()
    {
        return $this->jahr;
    }

    /**
     * Sets a new jahr
     *
     * Angabe eines Datums durch eine Jahresangabe
     *
     * @param int $jahr
     * @return self
     */
    public function setJahr($jahr)
    {
        $this->jahr = $jahr;
        return $this;
    }
}

