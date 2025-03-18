<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BezugType
 *
 * Dieser Typ gestattet Eintragungen, um auf einen Antrag, einen Vorgang und ggf. auf eine Nachricht, die im Rahmen dieses Vorgangs gesendet worden ist, Bezug zu nehmen.
 * XSD Type: Bezug
 */
class BezugType
{
    /**
     * Hier ist der Identifier des Antragsservice (der mit dem Antrag bzw. der Anzeige bei der Fachbehörde eingegangen ist) oder der Abfrage (auf die die vorliegende Nachricht eine Antwort ist) anzugeben. Falls die vorliegende Nachricht an ein Online-Portal gerichtet ist, kann so der Projektraum bzw. Postkorb angesteuert werden.
     *
     * @var string $referenz
     */
    private $referenz = null;

    /**
     * Eine Instanz dieses Elements enthält, falls ein solches vergeben wurde, das Zeichen (Aktenzeichen) des von der Fachbehörde geführten Vorgangs, innerhalb dessen diese Nachricht übermittelt wird.
     *
     * @var string $vorgang
     */
    private $vorgang = null;

    /**
     * Falls ein solcher Zusammenhang vorliegt, sind hier die Kennungen der Nachricht einzutragen, auf die sich diese Nachricht bezieht.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType $bezugNachricht
     */
    private $bezugNachricht = null;

    /**
     * Gets as referenz
     *
     * Hier ist der Identifier des Antragsservice (der mit dem Antrag bzw. der Anzeige bei der Fachbehörde eingegangen ist) oder der Abfrage (auf die die vorliegende Nachricht eine Antwort ist) anzugeben. Falls die vorliegende Nachricht an ein Online-Portal gerichtet ist, kann so der Projektraum bzw. Postkorb angesteuert werden.
     *
     * @return string
     */
    public function getReferenz()
    {
        return $this->referenz;
    }

    /**
     * Sets a new referenz
     *
     * Hier ist der Identifier des Antragsservice (der mit dem Antrag bzw. der Anzeige bei der Fachbehörde eingegangen ist) oder der Abfrage (auf die die vorliegende Nachricht eine Antwort ist) anzugeben. Falls die vorliegende Nachricht an ein Online-Portal gerichtet ist, kann so der Projektraum bzw. Postkorb angesteuert werden.
     *
     * @param string $referenz
     * @return self
     */
    public function setReferenz($referenz)
    {
        $this->referenz = $referenz;
        return $this;
    }

    /**
     * Gets as vorgang
     *
     * Eine Instanz dieses Elements enthält, falls ein solches vergeben wurde, das Zeichen (Aktenzeichen) des von der Fachbehörde geführten Vorgangs, innerhalb dessen diese Nachricht übermittelt wird.
     *
     * @return string
     */
    public function getVorgang()
    {
        return $this->vorgang;
    }

    /**
     * Sets a new vorgang
     *
     * Eine Instanz dieses Elements enthält, falls ein solches vergeben wurde, das Zeichen (Aktenzeichen) des von der Fachbehörde geführten Vorgangs, innerhalb dessen diese Nachricht übermittelt wird.
     *
     * @param string $vorgang
     * @return self
     */
    public function setVorgang($vorgang)
    {
        $this->vorgang = $vorgang;
        return $this;
    }

    /**
     * Gets as bezugNachricht
     *
     * Falls ein solcher Zusammenhang vorliegt, sind hier die Kennungen der Nachricht einzutragen, auf die sich diese Nachricht bezieht.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType
     */
    public function getBezugNachricht()
    {
        return $this->bezugNachricht;
    }

    /**
     * Sets a new bezugNachricht
     *
     * Falls ein solcher Zusammenhang vorliegt, sind hier die Kennungen der Nachricht einzutragen, auf die sich diese Nachricht bezieht.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType $bezugNachricht
     * @return self
     */
    public function setBezugNachricht(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType $bezugNachricht = null)
    {
        $this->bezugNachricht = $bezugNachricht;
        return $this;
    }
}

