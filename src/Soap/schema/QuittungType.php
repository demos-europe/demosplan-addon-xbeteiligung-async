<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing QuittungType
 *
 * Dieser Datentyp umfasst alle Informationen, die zur Quittung eines Sachverhalts erforderlich sind.
 * XSD Type: Quittung
 */
class QuittungType
{
    /**
     * Mit diesem Element werden die Informationen übermittelt, mit denen der Einzelfall in der ursprünglichen Nachricht bzw. Sammelnachricht eindeutig identifiziert werden kann.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $ursprungsereignis
     */
    private $ursprungsereignis = null;

    /**
     * Mit diesem Element wird der quittungsrelevante Sachverhalt in der Form eines Codes übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltType $sachverhalt
     */
    private $sachverhalt = null;

    /**
     * Gets as ursprungsereignis
     *
     * Mit diesem Element werden die Informationen übermittelt, mit denen der Einzelfall in der ursprünglichen Nachricht bzw. Sammelnachricht eindeutig identifiziert werden kann.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType
     */
    public function getUrsprungsereignis()
    {
        return $this->ursprungsereignis;
    }

    /**
     * Sets a new ursprungsereignis
     *
     * Mit diesem Element werden die Informationen übermittelt, mit denen der Einzelfall in der ursprünglichen Nachricht bzw. Sammelnachricht eindeutig identifiziert werden kann.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $ursprungsereignis
     * @return self
     */
    public function setUrsprungsereignis(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisType $ursprungsereignis)
    {
        $this->ursprungsereignis = $ursprungsereignis;
        return $this;
    }

    /**
     * Gets as sachverhalt
     *
     * Mit diesem Element wird der quittungsrelevante Sachverhalt in der Form eines Codes übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltType
     */
    public function getSachverhalt()
    {
        return $this->sachverhalt;
    }

    /**
     * Sets a new sachverhalt
     *
     * Mit diesem Element wird der quittungsrelevante Sachverhalt in der Form eines Codes übermittelt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltType $sachverhalt
     * @return self
     */
    public function setSachverhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltType $sachverhalt)
    {
        $this->sachverhalt = $sachverhalt;
        return $this;
    }
}

