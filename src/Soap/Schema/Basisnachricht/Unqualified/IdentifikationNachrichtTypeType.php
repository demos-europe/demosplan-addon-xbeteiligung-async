<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified;

/**
 * Class representing IdentifikationNachrichtTypeType
 *
 * Dieser Typ enthält Angaben zur eindeutigen Identifikation einer Nachricht.
 * XSD Type: Identifikation.NachrichtType
 */
class IdentifikationNachrichtTypeType
{
    /**
     * Dieses Element enthält den „Universally Unique IDentifier (UUID)“ der Nachricht, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht wird entsprechend rfc4122 gebildet und ermöglicht Nachrichten hersteller- und anwendungsübergreifend weltweit eindeutig zu identifizieren. Für jede Nachricht muss ein neuer UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht den UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss der UUID nicht angepasst werden.
     *
     * @var string $nachrichtenUUID
     */
    private $nachrichtenUUID = null;

    /**
     * Dieses Element enthält eine eindeutige Kennzeichnung des Nachrichtentyps. Die Identifikation erfolgt über eine Codeliste des entsprechenden XÖV-Standards.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType $nachrichtentyp
     */
    private $nachrichtentyp = null;

    /**
     * Dieses Element enthält den Erstellungszeitpunkt der Nachricht - es enthält explizit nicht den Sende- und Empfangszeitpunkt. Der Erstellungszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln.
     *
     * @var \DateTime $erstellungszeitpunkt
     */
    private $erstellungszeitpunkt = null;

    /**
     * Gets as nachrichtenUUID
     *
     * Dieses Element enthält den „Universally Unique IDentifier (UUID)“ der Nachricht, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht wird entsprechend rfc4122 gebildet und ermöglicht Nachrichten hersteller- und anwendungsübergreifend weltweit eindeutig zu identifizieren. Für jede Nachricht muss ein neuer UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht den UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss der UUID nicht angepasst werden.
     *
     * @return string
     */
    public function getNachrichtenUUID()
    {
        return $this->nachrichtenUUID;
    }

    /**
     * Sets a new nachrichtenUUID
     *
     * Dieses Element enthält den „Universally Unique IDentifier (UUID)“ der Nachricht, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht wird entsprechend rfc4122 gebildet und ermöglicht Nachrichten hersteller- und anwendungsübergreifend weltweit eindeutig zu identifizieren. Für jede Nachricht muss ein neuer UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht den UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss der UUID nicht angepasst werden.
     *
     * @param string $nachrichtenUUID
     * @return self
     */
    public function setNachrichtenUUID($nachrichtenUUID)
    {
        $this->nachrichtenUUID = $nachrichtenUUID;
        return $this;
    }

    /**
     * Gets as nachrichtentyp
     *
     * Dieses Element enthält eine eindeutige Kennzeichnung des Nachrichtentyps. Die Identifikation erfolgt über eine Codeliste des entsprechenden XÖV-Standards.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType
     */
    public function getNachrichtentyp()
    {
        return $this->nachrichtentyp;
    }

    /**
     * Sets a new nachrichtentyp
     *
     * Dieses Element enthält eine eindeutige Kennzeichnung des Nachrichtentyps. Die Identifikation erfolgt über eine Codeliste des entsprechenden XÖV-Standards.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType $nachrichtentyp
     * @return self
     */
    public function setNachrichtentyp(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType $nachrichtentyp)
    {
        $this->nachrichtentyp = $nachrichtentyp;
        return $this;
    }

    /**
     * Gets as erstellungszeitpunkt
     *
     * Dieses Element enthält den Erstellungszeitpunkt der Nachricht - es enthält explizit nicht den Sende- und Empfangszeitpunkt. Der Erstellungszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln.
     *
     * @return \DateTime
     */
    public function getErstellungszeitpunkt()
    {
        return $this->erstellungszeitpunkt;
    }

    /**
     * Sets a new erstellungszeitpunkt
     *
     * Dieses Element enthält den Erstellungszeitpunkt der Nachricht - es enthält explizit nicht den Sende- und Empfangszeitpunkt. Der Erstellungszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln.
     *
     * @param \DateTime $erstellungszeitpunkt
     * @return self
     */
    public function setErstellungszeitpunkt(\DateTime $erstellungszeitpunkt)
    {
        $this->erstellungszeitpunkt = $erstellungszeitpunkt;
        return $this;
    }
}

