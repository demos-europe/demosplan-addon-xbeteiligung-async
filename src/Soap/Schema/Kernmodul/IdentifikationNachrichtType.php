<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Unqualified\IdentifikationNachrichtTypeType;

/**
 * Class representing IdentifikationNachrichtType
 *
 * Identifkationsmerkmale zu einer Nachricht. Dieser Typ kann im ID-Block zu einer Nachricht verwendet werden oder in einem Abschnitt, der auf eine Nachricht referenziert.
 * XSD Type: Identifikation.Nachricht
 */
class IdentifikationNachrichtType extends IdentifikationNachrichtTypeType
{
    /**
     * Hier wird der Universally Unique Identifier (UUID) der Nachricht mitgeteilt, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht ist weltweit eindeutig. So wird es möglich, Nachrichten hersteller- und anwendungsübergreifend eindeutig zu identifizieren. Für jede Nachricht muss eine neue UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht (bspw. nach Erhalt einer RTS-Nachricht) die UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss die UUID nicht angepasst werden.
     *
     * @var string $nachrichtenUUID
     */
    private $nachrichtenUUID = null;

    /**
     * Die eindeutige Identifizierungsnummer für einen Nachrichtentyp. Der konkret zu verwendende Datentyp für die Übermittlung des Schlüsselwertes wird durch den Fachstandard auf Schemaebene festgelegt.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeXBauKernmodulNachrichtenType $nachrichtentyp
     */
    private $nachrichtentyp = null;

    /**
     * Der Zeitpunkt, an dem die Nachricht erstellt wurde. Dieses Feld wird durch das Fachverfahren beim Erstellen der Nachricht gefüllt. Hier ist explizit nicht der Sende- und Empfangszeitpunkt festgehalten, denn die können in der Regel der Transportschicht entnommen werden.
     *
     * @var \DateTime $erstellungszeitpunkt
     */
    private $erstellungszeitpunkt = null;

    /**
     * Gets as nachrichtenUUID
     *
     * Hier wird der Universally Unique Identifier (UUID) der Nachricht mitgeteilt, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht ist weltweit eindeutig. So wird es möglich, Nachrichten hersteller- und anwendungsübergreifend eindeutig zu identifizieren. Für jede Nachricht muss eine neue UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht (bspw. nach Erhalt einer RTS-Nachricht) die UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss die UUID nicht angepasst werden.
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
     * Hier wird der Universally Unique Identifier (UUID) der Nachricht mitgeteilt, der das primäre Identifikationsmerkmal einer Nachricht darstellt. Der UUID der Nachricht ist weltweit eindeutig. So wird es möglich, Nachrichten hersteller- und anwendungsübergreifend eindeutig zu identifizieren. Für jede Nachricht muss eine neue UUID erzeugt werden, um eine eindeutige Identifikation der Nachricht sicherzustellen. Insbesondere ist es nicht zulässig, in einer korrigierten Nachricht (bspw. nach Erhalt einer RTS-Nachricht) die UUID der ursprünglichen Nachricht wiederzuverwenden. Sofern eine einmal erzeugte Nachricht ein weiteres Mal gesendet werden soll (bspw. aufgrund von Problemen beim Nachrichtentransport), muss die UUID nicht angepasst werden.
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
     * Die eindeutige Identifizierungsnummer für einen Nachrichtentyp. Der konkret zu verwendende Datentyp für die Übermittlung des Schlüsselwertes wird durch den Fachstandard auf Schemaebene festgelegt.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeXBauKernmodulNachrichtenType
     */
    public function getNachrichtentyp()
    {
        return $this->nachrichtentyp;
    }

    /**
     * Sets a new nachrichtentyp
     *
     * Die eindeutige Identifizierungsnummer für einen Nachrichtentyp. Der konkret zu verwendende Datentyp für die Übermittlung des Schlüsselwertes wird durch den Fachstandard auf Schemaebene festgelegt.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeXBauKernmodulNachrichtenType $nachrichtentyp
     * @return self
     */
    public function setNachrichtentyp(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeXBauKernmodulNachrichtenType $nachrichtentyp)
    {
        $this->nachrichtentyp = $nachrichtentyp;
        return $this;
    }

    /**
     * Gets as erstellungszeitpunkt
     *
     * Der Zeitpunkt, an dem die Nachricht erstellt wurde. Dieses Feld wird durch das Fachverfahren beim Erstellen der Nachricht gefüllt. Hier ist explizit nicht der Sende- und Empfangszeitpunkt festgehalten, denn die können in der Regel der Transportschicht entnommen werden.
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
     * Der Zeitpunkt, an dem die Nachricht erstellt wurde. Dieses Feld wird durch das Fachverfahren beim Erstellen der Nachricht gefüllt. Hier ist explizit nicht der Sende- und Empfangszeitpunkt festgehalten, denn die können in der Regel der Transportschicht entnommen werden.
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

