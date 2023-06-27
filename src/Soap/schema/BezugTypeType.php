<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing BezugTypeType
 *
 * Dieser Typ gestattet Eintragungen, um auf einen Antrag, einen Vorgang und ggf. auf eine Nachricht, die im Rahmen dieses Vorgangs gesendet worden ist, Bezug zu nehmen.
 * XSD Type: BezugType
 */
class BezugTypeType
{
    /**
     * Hier ist der Identifier des Antragsservice (der mit dem Antrag bzw. der Anzeige bei der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingegangen ist) oder der Abfrage (auf die die vorliegende Nachricht eine Antwort ist) anzugeben. Falls die vorliegende Nachricht an ein Online-Portal gerichtet ist, kann so der Projektraum bzw. Postkorb angesteuert werden.
     *
     * @var \Jms\Handler\GmlAnyTypeHandler $referenz
     */
    private $referenz = null;

    /**
     * Eine Instanz dieses Elements enthält, falls ein solches vergeben wurde, das Zeichen (Aktenzeichen) des von der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) geführten Vorgangs, innerhalb dessen diese Nachricht übermittelt wird.
     *
     * @var string $vorgang
     */
    private $vorgang = null;

    /**
     * Falls ein solcher Zusammenhang vorliegt, sind hier die Kennungen der Nachricht einzutragen, auf die sich diese Nachricht bezieht.
     *
     * @var \Jms\Handler\GmlAnyTypeHandler $bezugNachricht
     */
    private $bezugNachricht = null;

    /**
     * Gets as referenz
     *
     * Hier ist der Identifier des Antragsservice (der mit dem Antrag bzw. der Anzeige bei der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingegangen ist) oder der Abfrage (auf die die vorliegende Nachricht eine Antwort ist) anzugeben. Falls die vorliegende Nachricht an ein Online-Portal gerichtet ist, kann so der Projektraum bzw. Postkorb angesteuert werden.
     *
     * @return \Jms\Handler\GmlAnyTypeHandler
     */
    public function getReferenz()
    {
        return $this->referenz;
    }

    /**
     * Sets a new referenz
     *
     * Hier ist der Identifier des Antragsservice (der mit dem Antrag bzw. der Anzeige bei der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingegangen ist) oder der Abfrage (auf die die vorliegende Nachricht eine Antwort ist) anzugeben. Falls die vorliegende Nachricht an ein Online-Portal gerichtet ist, kann so der Projektraum bzw. Postkorb angesteuert werden.
     *
     * @param \Jms\Handler\GmlAnyTypeHandler $referenz
     * @return self
     */
    public function setReferenz(?\Jms\Handler\GmlAnyTypeHandler $referenz = null)
    {
        $this->referenz = $referenz;
        return $this;
    }

    /**
     * Gets as vorgang
     *
     * Eine Instanz dieses Elements enthält, falls ein solches vergeben wurde, das Zeichen (Aktenzeichen) des von der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) geführten Vorgangs, innerhalb dessen diese Nachricht übermittelt wird.
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
     * Eine Instanz dieses Elements enthält, falls ein solches vergeben wurde, das Zeichen (Aktenzeichen) des von der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) geführten Vorgangs, innerhalb dessen diese Nachricht übermittelt wird.
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
     * @return \Jms\Handler\GmlAnyTypeHandler
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
     * @param \Jms\Handler\GmlAnyTypeHandler $bezugNachricht
     * @return self
     */
    public function setBezugNachricht(?\Jms\Handler\GmlAnyTypeHandler $bezugNachricht = null)
    {
        $this->bezugNachricht = $bezugNachricht;
        return $this;
    }
}

