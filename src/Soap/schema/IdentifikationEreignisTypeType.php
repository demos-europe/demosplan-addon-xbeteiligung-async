<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing IdentifikationEreignisTypeType
 *
 * Dieser Typ enthält Angaben, die ein Ereignis eindeutig identifizieren und es dem Leser einer Nachricht ermöglichen, die Reihenfolge von Ereignissen beim Autor nachzuvollziehen. Sofern dieses Element in einer Nachricht mit mehreren Datensätzen verwendet wird (Sammelnachricht), dient es der Identifikation des Einzelfalls. Es muss dann entsprechend für jeden Einzelfall in der Sammelnachricht übermittelt werden.
 * XSD Type: Identifikation.EreignisType
 */
class IdentifikationEreignisTypeType
{
    /**
     * Dieses Element kann verwendet werden, um beim Leser die ursprüngliche Chronologie der Ereignisse beim Autor der Nachricht zu rekonstruieren. Welcher Zeitpunkt hier zu übermitteln ist, wird durch die xinneres-fachmodule an den fachlichen Nutzungsstellen festgelegt. Der Ereigniszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln. Der hier übermittelte Zeitpunkt kann von dem Erstellungszeitpunkt der Nachricht, in der das Ereignis übermittelt wird, abweichen.
     *
     * @var \DateTime $ereignisZeitpunkt
     */
    private $ereignisZeitpunkt = null;

    /**
     * Mit diesem Element wird ein (Geschäfts-)Zeichen zu dem Ereignis übermittelt, das das Ereignis innerhalb einer Nachricht eindeutig identifiziert. Das (Geschäfts-)Zeichen kann durch den Autor der Nachricht beliebig gestaltet werden, es muss nur sichergestellt werden, dass ein Ereignis innerhalb einer Nachricht eindeutig identifiziert (nur relevant bei Sammelnachrichten) wird und dass der Autor einer Nachricht in der Lage ist, das übermittelte Ereignis mithilfe des (Geschäfts-)Zeichens und den identifizierenden Angaben zur Nachricht wieder aufzufinden. Ein solches Zeichen darf maximal 100 Zeichen umfassen. Außer den Zeichen A..Z, a..z sowie den Ziffern 0..9 sind maximal acht Sonderzeichen erlaubt. Umlaute und das ß gelten ebenfalls als Sonderzeichen.
     *
     * @var string $ereignisZeichen
     */
    private $ereignisZeichen = null;

    /**
     * Gets as ereignisZeitpunkt
     *
     * Dieses Element kann verwendet werden, um beim Leser die ursprüngliche Chronologie der Ereignisse beim Autor der Nachricht zu rekonstruieren. Welcher Zeitpunkt hier zu übermitteln ist, wird durch die xinneres-fachmodule an den fachlichen Nutzungsstellen festgelegt. Der Ereigniszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln. Der hier übermittelte Zeitpunkt kann von dem Erstellungszeitpunkt der Nachricht, in der das Ereignis übermittelt wird, abweichen.
     *
     * @return \DateTime
     */
    public function getEreignisZeitpunkt()
    {
        return $this->ereignisZeitpunkt;
    }

    /**
     * Sets a new ereignisZeitpunkt
     *
     * Dieses Element kann verwendet werden, um beim Leser die ursprüngliche Chronologie der Ereignisse beim Autor der Nachricht zu rekonstruieren. Welcher Zeitpunkt hier zu übermitteln ist, wird durch die xinneres-fachmodule an den fachlichen Nutzungsstellen festgelegt. Der Ereigniszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln. Der hier übermittelte Zeitpunkt kann von dem Erstellungszeitpunkt der Nachricht, in der das Ereignis übermittelt wird, abweichen.
     *
     * @param \DateTime $ereignisZeitpunkt
     * @return self
     */
    public function setEreignisZeitpunkt(\DateTime $ereignisZeitpunkt)
    {
        $this->ereignisZeitpunkt = $ereignisZeitpunkt;
        return $this;
    }

    /**
     * Gets as ereignisZeichen
     *
     * Mit diesem Element wird ein (Geschäfts-)Zeichen zu dem Ereignis übermittelt, das das Ereignis innerhalb einer Nachricht eindeutig identifiziert. Das (Geschäfts-)Zeichen kann durch den Autor der Nachricht beliebig gestaltet werden, es muss nur sichergestellt werden, dass ein Ereignis innerhalb einer Nachricht eindeutig identifiziert (nur relevant bei Sammelnachrichten) wird und dass der Autor einer Nachricht in der Lage ist, das übermittelte Ereignis mithilfe des (Geschäfts-)Zeichens und den identifizierenden Angaben zur Nachricht wieder aufzufinden. Ein solches Zeichen darf maximal 100 Zeichen umfassen. Außer den Zeichen A..Z, a..z sowie den Ziffern 0..9 sind maximal acht Sonderzeichen erlaubt. Umlaute und das ß gelten ebenfalls als Sonderzeichen.
     *
     * @return string
     */
    public function getEreignisZeichen()
    {
        return $this->ereignisZeichen;
    }

    /**
     * Sets a new ereignisZeichen
     *
     * Mit diesem Element wird ein (Geschäfts-)Zeichen zu dem Ereignis übermittelt, das das Ereignis innerhalb einer Nachricht eindeutig identifiziert. Das (Geschäfts-)Zeichen kann durch den Autor der Nachricht beliebig gestaltet werden, es muss nur sichergestellt werden, dass ein Ereignis innerhalb einer Nachricht eindeutig identifiziert (nur relevant bei Sammelnachrichten) wird und dass der Autor einer Nachricht in der Lage ist, das übermittelte Ereignis mithilfe des (Geschäfts-)Zeichens und den identifizierenden Angaben zur Nachricht wieder aufzufinden. Ein solches Zeichen darf maximal 100 Zeichen umfassen. Außer den Zeichen A..Z, a..z sowie den Ziffern 0..9 sind maximal acht Sonderzeichen erlaubt. Umlaute und das ß gelten ebenfalls als Sonderzeichen.
     *
     * @param string $ereignisZeichen
     * @return self
     */
    public function setEreignisZeichen($ereignisZeichen)
    {
        $this->ereignisZeichen = $ereignisZeichen;
        return $this;
    }
}

