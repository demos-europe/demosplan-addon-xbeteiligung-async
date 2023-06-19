<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing AllgemeinerNameType
 *
 * Dieser Datentyp repräsentiert die gemeinsamen Eigenschaften von Vor- und Familiennamen nach deutschem Personenstandsrecht. Der Vor- oder Familienname wird in Form einer Zeichenkette in dem Kindelement name übermittelt, in der eventuell vorhandene und als Namenszusätze bekannte Bestandteile nicht gesondert ausgezeichnet oder abgetrennt werden. Die Modellierung von AllgemeinerName als Choice-Struktur erlaubt es, die Sonderfälle eines zu Recht fehlenden Vornamens oder Familiennamens zu übermitteln. Sofern bei einem ausländischen Namen kein Vorname gemäß deutscher Systematik vorhanden ist, bzw. der Familienname eines Kindes zu übermitteln ist, welches verstorben ist, ohne einen Familiennamen erhalten zu haben, wird statt des Kindelements name das Kindelement nichtVorhanden mit dem Wert true übermittelt.
 * XSD Type: AllgemeinerName
 */
class AllgemeinerNameType
{
    /**
     * Der Name ist der eigentliche Familien- oder Vorname als Zeichenkette. Nachnamen, z.B. mit Adelstiteln bzw. ausländische Nachnamen werden als ein Name übermittelt und nicht in verschiedene Bestandteile aufgeteilt.
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Dieses Kindelement ist immer dann anstelle des Kindelements name zu verwenden, wenn ein Vor- oder Familienname einer Person zurecht nicht vorhanden ist.
     *
     * @var bool $nichtVorhanden
     */
    private $nichtVorhanden = null;

    /**
     * Gets as name
     *
     * Der Name ist der eigentliche Familien- oder Vorname als Zeichenkette. Nachnamen, z.B. mit Adelstiteln bzw. ausländische Nachnamen werden als ein Name übermittelt und nicht in verschiedene Bestandteile aufgeteilt.
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
     * Der Name ist der eigentliche Familien- oder Vorname als Zeichenkette. Nachnamen, z.B. mit Adelstiteln bzw. ausländische Nachnamen werden als ein Name übermittelt und nicht in verschiedene Bestandteile aufgeteilt.
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
     * Dieses Kindelement ist immer dann anstelle des Kindelements name zu verwenden, wenn ein Vor- oder Familienname einer Person zurecht nicht vorhanden ist.
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
     * Dieses Kindelement ist immer dann anstelle des Kindelements name zu verwenden, wenn ein Vor- oder Familienname einer Person zurecht nicht vorhanden ist.
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

