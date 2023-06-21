<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing LichtbildTypeType
 *
 * Mit diesem Datentyp wird ein Lichtbild im Binärformat übermittelt, inklusive der Angabe des Bildformats als MIME-Type.
 * XSD Type: LichtbildType
 */
class LichtbildTypeType
{
    /**
     * @var string $__value
     */
    private $__value = null;

    /**
     * Mit diesem Attribut wird das Format des übermittelten Lichtbilds als MIME-Type übermittelt.
     *
     * @var string $mimeType
     */
    private $mimeType = null;

    /**
     * Construct
     *
     * @param string $value
     */
    public function __construct($value)
    {
        $this->value($value);
    }

    /**
     * Gets or sets the inner value
     *
     * @param string $value
     * @return string
     */
    public function value()
    {
        if ($args = func_get_args()) {
            $this->__value = $args[0];
        }
        return $this->__value;
    }

    /**
     * Gets a string value
     *
     * @return string
     */
    public function __toString()
    {
        return strval($this->__value);
    }

    /**
     * Gets as mimeType
     *
     * Mit diesem Attribut wird das Format des übermittelten Lichtbilds als MIME-Type übermittelt.
     *
     * @return string
     */
    public function getMimeType()
    {
        return $this->mimeType;
    }

    /**
     * Sets a new mimeType
     *
     * Mit diesem Attribut wird das Format des übermittelten Lichtbilds als MIME-Type übermittelt.
     *
     * @param string $mimeType
     * @return self
     */
    public function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
    }
}

