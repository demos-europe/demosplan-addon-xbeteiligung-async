<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenRueckweisungG2G1100;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing ProzessnachrichtenRueckweisungG2G1100AnonymousPHPType
 */
class ProzessnachrichtenRueckweisungG2G1100AnonymousPHPType extends NachrichtG2GType
{
    /**
     * Mit diesem Element wird der Fehler einer gegebenenen Nachricht benannt, der zu der vorliegenden Rückweisung geführt hat.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungType $rueckweisungDaten
     */
    private $rueckweisungDaten = null;

    /**
     * Gets as rueckweisungDaten
     *
     * Mit diesem Element wird der Fehler einer gegebenenen Nachricht benannt, der zu der vorliegenden Rückweisung geführt hat.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungType
     */
    public function getRueckweisungDaten()
    {
        return $this->rueckweisungDaten;
    }

    /**
     * Sets a new rueckweisungDaten
     *
     * Mit diesem Element wird der Fehler einer gegebenenen Nachricht benannt, der zu der vorliegenden Rückweisung geführt hat.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungType $rueckweisungDaten
     * @return self
     */
    public function setRueckweisungDaten(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungType $rueckweisungDaten)
    {
        $this->rueckweisungDaten = $rueckweisungDaten;
        return $this;
    }
}

