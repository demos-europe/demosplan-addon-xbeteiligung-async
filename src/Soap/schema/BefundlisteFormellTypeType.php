<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BefundlisteFormellTypeType
 *
 * Dieser Typ beinhaltet die Befunde der formellen Prüfung eines Antrags, einer Anzeige oder einer Erklärung. Für jeden Befund sind die entsprechenden Parameter gefüllt, die Inhalt und Bezug des Befundes für den Antragsteller bzw. Anfragenden nachvollziehbar machen.
 * XSD Type: BefundlisteFormellType
 */
class BefundlisteFormellTypeType
{
    /**
     * Dieses Element stellt genau einen Befund der Befundliste dar.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType[] $befund
     */
    private $befund = [
        
    ];

    /**
     * Adds as befund
     *
     * Dieses Element stellt genau einen Befund der Befundliste dar.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType $befund
     */
    public function addToBefund(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType $befund)
    {
        $this->befund[] = $befund;
        return $this;
    }

    /**
     * isset befund
     *
     * Dieses Element stellt genau einen Befund der Befundliste dar.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetBefund($index)
    {
        return isset($this->befund[$index]);
    }

    /**
     * unset befund
     *
     * Dieses Element stellt genau einen Befund der Befundliste dar.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetBefund($index)
    {
        unset($this->befund[$index]);
    }

    /**
     * Gets as befund
     *
     * Dieses Element stellt genau einen Befund der Befundliste dar.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType[]
     */
    public function getBefund()
    {
        return $this->befund;
    }

    /**
     * Sets a new befund
     *
     * Dieses Element stellt genau einen Befund der Befundliste dar.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BefundlisteFormellTypeType\BefundAnonymousPHPType[] $befund
     * @return self
     */
    public function setBefund(array $befund)
    {
        $this->befund = $befund;
        return $this;
    }
}

