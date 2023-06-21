<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing QuittungTypeType
 *
 * Dieser Datentyp umfasst alle Informationen, die zur Quittung eines Sachverhalts erforderlich sind.
 * XSD Type: QuittungType
 */
class QuittungTypeType
{
    /**
     * Mit diesem Element werden die Informationen übermittelt, mit denen der Einzelfall in der ursprünglichen Nachricht bzw. Sammelnachricht eindeutig identifiziert werden kann.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisTypeType $ursprungsereignis
     */
    private $ursprungsereignis = null;

    /**
     * Mit diesem Element wird der quittungsrelevante Sachverhalt in der Form eines Codes übermittelt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltTypeType $sachverhalt
     */
    private $sachverhalt = null;

    /**
     * Gets as ursprungsereignis
     *
     * Mit diesem Element werden die Informationen übermittelt, mit denen der Einzelfall in der ursprünglichen Nachricht bzw. Sammelnachricht eindeutig identifiziert werden kann.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisTypeType $ursprungsereignis
     * @return self
     */
    public function setUrsprungsereignis(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationEreignisTypeType $ursprungsereignis)
    {
        $this->ursprungsereignis = $ursprungsereignis;
        return $this;
    }

    /**
     * Gets as sachverhalt
     *
     * Mit diesem Element wird der quittungsrelevante Sachverhalt in der Form eines Codes übermittelt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltTypeType
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
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltTypeType $sachverhalt
     * @return self
     */
    public function setSachverhalt(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeQuittungsrelevanterSachverhaltTypeType $sachverhalt)
    {
        $this->sachverhalt = $sachverhalt;
        return $this;
    }
}

