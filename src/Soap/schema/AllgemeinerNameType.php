<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AllgemeinerNameType
 *
 * Der AllgemeineName dient der Darstellung von Vor- und Nachnamen und fasst deren gemeinsame Eigenschaften zusammen.
 * XSD Type: AllgemeinerName
 */
class AllgemeinerNameType
{
    /**
     * Die Komponente "name" ist der Familien- oder Vorname als Zeichenkette. Nachnamen, z.B. mit Adelstiteln bzw. ausländische Nachnamen werden als ein Name übermittelt und nicht in verschiedene Bestandteile aufgeteilt.
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Diese Komponente beinhaltet eine Feststellung (wahr oder falsch), ob zu Recht kein Name angegeben wurde. Ueber das Setzen auf TRUE, wird angezeigt, dass zurecht kein Name angegeben wurde.
     *
     * @var bool $nichtVorhanden
     */
    private $nichtVorhanden = null;

    /**
     * Gets as name
     *
     * Die Komponente "name" ist der Familien- oder Vorname als Zeichenkette. Nachnamen, z.B. mit Adelstiteln bzw. ausländische Nachnamen werden als ein Name übermittelt und nicht in verschiedene Bestandteile aufgeteilt.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Die Komponente "name" ist der Familien- oder Vorname als Zeichenkette. Nachnamen, z.B. mit Adelstiteln bzw. ausländische Nachnamen werden als ein Name übermittelt und nicht in verschiedene Bestandteile aufgeteilt.
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Gets as nichtVorhanden
     *
     * Diese Komponente beinhaltet eine Feststellung (wahr oder falsch), ob zu Recht kein Name angegeben wurde. Ueber das Setzen auf TRUE, wird angezeigt, dass zurecht kein Name angegeben wurde.
     *
     * @return bool
     */
    public function getNichtVorhanden()
    {
        return $this->nichtVorhanden;
    }

    /**
     * Sets a new nichtVorhanden
     *
     * Diese Komponente beinhaltet eine Feststellung (wahr oder falsch), ob zu Recht kein Name angegeben wurde. Ueber das Setzen auf TRUE, wird angezeigt, dass zurecht kein Name angegeben wurde.
     *
     * @param bool $nichtVorhanden
     * @return self
     */
    public function setNichtVorhanden($nichtVorhanden)
    {
        $this->nichtVorhanden = $nichtVorhanden;
        return $this;
    }
}

