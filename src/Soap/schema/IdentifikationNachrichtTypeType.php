<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing IdentifikationNachrichtTypeType
 *
 * Dieser Typ enthält die für die Identifikation einer Nachricht erforderlichen Informationen. Er kann verwendet werden, um Identifizierungsmerkmale zu setzen, auf die sich Leser oder Empfänger beziehen kann (Verwendung im Nachrichtenkopf) oder sich auf Identifizierungsmerkmale einer übermittelten Nachricht zu beziehen (Verwendung im Nachrichteninhalt von Reaktions- oder RtS-Nachrichten). Darüber hinaus enthält der Typ den Erstellungszeitpunkt.
 * XSD Type: Identifikation.NachrichtType
 */
class IdentifikationNachrichtTypeType
{
    /**
     * Hier wird der Universally Unique Identifier (UUID) der Nachricht mitgeteilt, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht ist weltweit eindeutig. So wird es möglich, Nachrichten hersteller- und anwendungsübergreifend eindeutig zu identifizieren. Für jede Nachricht muss ein neuer UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht (bspw. nach Erhalt einer RTS-Nachricht) den UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss der UUID nicht angepasst werden.
     *
     * @var string $nachrichtenUUID
     */
    private $nachrichtenUUID = null;

    /**
     * Die eindeutige Identifizierungsnummer für einen Nachrichtentyp. Der konkret zu verwendende Datentyp für die Übermittlung des Schlüsselwertes wird durch das xinneres-fachmodul auf Schemaebene festgelegt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType $nachrichtentyp
     */
    private $nachrichtentyp = null;

    /**
     * Der Zeitpunkt, an dem die Nachricht erstellt wurde. Dieses Feld wird durch das Fachverfahren beim Erstellen der Nachricht gefüllt. Hier ist explizit nicht der Sende- und Empfangszeitpunkt festgehalten, denn die können in der Regel der Transportschicht entnommen werden. Der Erstellungszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln.
     *
     * @var \DateTime $erstellungszeitpunkt
     */
    private $erstellungszeitpunkt = null;

    /**
     * Gets as nachrichtenUUID
     *
     * Hier wird der Universally Unique Identifier (UUID) der Nachricht mitgeteilt, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht ist weltweit eindeutig. So wird es möglich, Nachrichten hersteller- und anwendungsübergreifend eindeutig zu identifizieren. Für jede Nachricht muss ein neuer UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht (bspw. nach Erhalt einer RTS-Nachricht) den UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss der UUID nicht angepasst werden.
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
     * Hier wird der Universally Unique Identifier (UUID) der Nachricht mitgeteilt, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht ist weltweit eindeutig. So wird es möglich, Nachrichten hersteller- und anwendungsübergreifend eindeutig zu identifizieren. Für jede Nachricht muss ein neuer UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht (bspw. nach Erhalt einer RTS-Nachricht) den UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss der UUID nicht angepasst werden.
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
     * Die eindeutige Identifizierungsnummer für einen Nachrichtentyp. Der konkret zu verwendende Datentyp für die Übermittlung des Schlüsselwertes wird durch das xinneres-fachmodul auf Schemaebene festgelegt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType
     */
    public function getNachrichtentyp()
    {
        return $this->nachrichtentyp;
    }

    /**
     * Sets a new nachrichtentyp
     *
     * Die eindeutige Identifizierungsnummer für einen Nachrichtentyp. Der konkret zu verwendende Datentyp für die Übermittlung des Schlüsselwertes wird durch das xinneres-fachmodul auf Schemaebene festgelegt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType $nachrichtentyp
     * @return self
     */
    public function setNachrichtentyp(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType $nachrichtentyp)
    {
        $this->nachrichtentyp = $nachrichtentyp;
        return $this;
    }

    /**
     * Gets as erstellungszeitpunkt
     *
     * Der Zeitpunkt, an dem die Nachricht erstellt wurde. Dieses Feld wird durch das Fachverfahren beim Erstellen der Nachricht gefüllt. Hier ist explizit nicht der Sende- und Empfangszeitpunkt festgehalten, denn die können in der Regel der Transportschicht entnommen werden. Der Erstellungszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln.
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
     * Der Zeitpunkt, an dem die Nachricht erstellt wurde. Dieses Feld wird durch das Fachverfahren beim Erstellen der Nachricht gefüllt. Hier ist explizit nicht der Sende- und Empfangszeitpunkt festgehalten, denn die können in der Regel der Transportschicht entnommen werden. Der Erstellungszeitpunkt muss neben einer Angabe zum Datum eine zeitliche Information beinhalten. Diese ist mit einer Genauigkeit auf Ebene von Millisekunden und der Angabe zur Zeitzone zu übermitteln.
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

