<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung;

/**
 * Class representing RolleType
 *
 * Dieser Typ bezeichnet die Rolle eines am Vorhaben beteiligten Akteurs.
 * XSD Type: Rolle
 */
class RolleType
{
    /**
     * Angabe der Rolle des Beteiligten.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeRolleType $code
     */
    private $code = null;

    /**
     * Freitextangabe für die Rolle des Beteiligten.
     *
     * @var string $nichtgelisteterWert
     */
    private $nichtgelisteterWert = null;

    /**
     * Gets as code
     *
     * Angabe der Rolle des Beteiligten.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeRolleType
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * Angabe der Rolle des Beteiligten.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeRolleType $code
     * @return self
     */
    public function setCode(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeRolleType $code = null)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as nichtgelisteterWert
     *
     * Freitextangabe für die Rolle des Beteiligten.
     *
     * @return string
     */
    public function getNichtgelisteterWert()
    {
        return $this->nichtgelisteterWert;
    }

    /**
     * Sets a new nichtgelisteterWert
     *
     * Freitextangabe für die Rolle des Beteiligten.
     *
     * @param string $nichtgelisteterWert
     * @return self
     */
    public function setNichtgelisteterWert($nichtgelisteterWert)
    {
        $this->nichtgelisteterWert = $nichtgelisteterWert;
        return $this;
    }
}

