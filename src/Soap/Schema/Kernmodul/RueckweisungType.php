<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

/**
 * Class representing RueckweisungType
 *
 * Dieser Typ nimmt Angaben zu Art und Ort eines Fehlers auf, der zur Rückweisung der Nachricht geführt hat.
 * XSD Type: Rueckweisung
 */
class RueckweisungType
{
    /**
     * Jede Instanz dieses Elements enthält Informationen zu einem Fehler bzw. einem Mangel, der in Bezug auf die erhaltene Nachricht identifiziert wurde.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\RueckweisungsgrundType[] $rueckweisungsgrund
     */
    private $rueckweisungsgrund = [
        
    ];

    /**
     * Identifikationsmerkmale der zurückgewiesenen XBau-Nachricht, die der Autor der Rückweisung zu einem Zeitpunkt in der Vergangenheit empfangen hat.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachrichtTypeType $idNachricht
     */
    private $idNachricht = null;

    /**
     * Dieses Element bildet die zurückgewiesene Fachnachricht ohne Attachments im Binärformat ab..
     *
     * @var string $nachricht
     */
    private $nachricht = null;

    /**
     * Adds as rueckweisungsgrund
     *
     * Jede Instanz dieses Elements enthält Informationen zu einem Fehler bzw. einem Mangel, der in Bezug auf die erhaltene Nachricht identifiziert wurde.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\RueckweisungsgrundType $rueckweisungsgrund
     */
    public function addToRueckweisungsgrund(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\RueckweisungsgrundType $rueckweisungsgrund)
    {
        $this->rueckweisungsgrund[] = $rueckweisungsgrund;
        return $this;
    }

    /**
     * isset rueckweisungsgrund
     *
     * Jede Instanz dieses Elements enthält Informationen zu einem Fehler bzw. einem Mangel, der in Bezug auf die erhaltene Nachricht identifiziert wurde.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetRueckweisungsgrund($index)
    {
        return isset($this->rueckweisungsgrund[$index]);
    }

    /**
     * unset rueckweisungsgrund
     *
     * Jede Instanz dieses Elements enthält Informationen zu einem Fehler bzw. einem Mangel, der in Bezug auf die erhaltene Nachricht identifiziert wurde.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetRueckweisungsgrund($index)
    {
        unset($this->rueckweisungsgrund[$index]);
    }

    /**
     * Gets as rueckweisungsgrund
     *
     * Jede Instanz dieses Elements enthält Informationen zu einem Fehler bzw. einem Mangel, der in Bezug auf die erhaltene Nachricht identifiziert wurde.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\RueckweisungsgrundType[]
     */
    public function getRueckweisungsgrund()
    {
        return $this->rueckweisungsgrund;
    }

    /**
     * Sets a new rueckweisungsgrund
     *
     * Jede Instanz dieses Elements enthält Informationen zu einem Fehler bzw. einem Mangel, der in Bezug auf die erhaltene Nachricht identifiziert wurde.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\RueckweisungsgrundType[] $rueckweisungsgrund
     * @return self
     */
    public function setRueckweisungsgrund(array $rueckweisungsgrund)
    {
        $this->rueckweisungsgrund = $rueckweisungsgrund;
        return $this;
    }

    /**
     * Gets as idNachricht
     *
     * Identifikationsmerkmale der zurückgewiesenen XBau-Nachricht, die der Autor der Rückweisung zu einem Zeitpunkt in der Vergangenheit empfangen hat.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachrichtTypeType
     */
    public function getIdNachricht()
    {
        return $this->idNachricht;
    }

    /**
     * Sets a new idNachricht
     *
     * Identifikationsmerkmale der zurückgewiesenen XBau-Nachricht, die der Autor der Rückweisung zu einem Zeitpunkt in der Vergangenheit empfangen hat.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachrichtTypeType $idNachricht
     * @return self
     */
    public function setIdNachricht(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachrichtTypeType $idNachricht)
    {
        $this->idNachricht = $idNachricht;
        return $this;
    }

    /**
     * Gets as nachricht
     *
     * Dieses Element bildet die zurückgewiesene Fachnachricht ohne Attachments im Binärformat ab..
     *
     * @return string
     */
    public function getNachricht()
    {
        return $this->nachricht;
    }

    /**
     * Sets a new nachricht
     *
     * Dieses Element bildet die zurückgewiesene Fachnachricht ohne Attachments im Binärformat ab..
     *
     * @param string $nachricht
     * @return self
     */
    public function setNachricht($nachricht)
    {
        $this->nachricht = $nachricht;
        return $this;
    }
}

