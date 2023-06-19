<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RueckweisungTransportinformationenType
 *
 * Mit diesem Typ können Angaben zu den Transportinformationen der zurückgewiesenen Nachricht übermittelt werden.
 * XSD Type: Rueckweisung.Transportinformationen
 */
class RueckweisungTransportinformationenType
{
    /**
     * Hier kann eine Identifikation der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich diese aus dem Transportprotokoll entnehmen lässt. Im Falle von OSCI-Transport wäre hier die messageID des Transportumschlages zu nutzen.
     *
     * @var string $nachrichtenId
     */
    private $nachrichtenId = null;

    /**
     * Hier kann der Inhalt der Betreff- oder Subject-Zeile der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich diese aus dem Transportprotokoll entnehmen lässt.
     *
     * @var string $betreff
     */
    private $betreff = null;

    /**
     * Hier kann der Zeitpunkt des Versands der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich dieser aus dem Transportprotokoll entnehmen lässt.
     *
     * @var \DateTime $sendezeitpunkt
     */
    private $sendezeitpunkt = null;

    /**
     * Hier können weitere Angaben gemacht werden, die dem Empfänger der aus den genannten Gründen zurückgewiesenen Nachricht helfen, diese in seinem Verfahren zu identifizieren.
     *
     * @var string $ergaenzendeHinweise
     */
    private $ergaenzendeHinweise = null;

    /**
     * Gets as nachrichtenId
     *
     * Hier kann eine Identifikation der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich diese aus dem Transportprotokoll entnehmen lässt. Im Falle von OSCI-Transport wäre hier die messageID des Transportumschlages zu nutzen.
     *
     * @return string
     */
    public function getNachrichtenId()
    {
        return $this->nachrichtenId;
    }

    /**
     * Sets a new nachrichtenId
     *
     * Hier kann eine Identifikation der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich diese aus dem Transportprotokoll entnehmen lässt. Im Falle von OSCI-Transport wäre hier die messageID des Transportumschlages zu nutzen.
     *
     * @param string $nachrichtenId
     * @return self
     */
    public function setNachrichtenId($nachrichtenId)
    {
        $this->nachrichtenId = $nachrichtenId;
        return $this;
    }

    /**
     * Gets as betreff
     *
     * Hier kann der Inhalt der Betreff- oder Subject-Zeile der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich diese aus dem Transportprotokoll entnehmen lässt.
     *
     * @return string
     */
    public function getBetreff()
    {
        return $this->betreff;
    }

    /**
     * Sets a new betreff
     *
     * Hier kann der Inhalt der Betreff- oder Subject-Zeile der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich diese aus dem Transportprotokoll entnehmen lässt.
     *
     * @param string $betreff
     * @return self
     */
    public function setBetreff($betreff)
    {
        $this->betreff = $betreff;
        return $this;
    }

    /**
     * Gets as sendezeitpunkt
     *
     * Hier kann der Zeitpunkt des Versands der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich dieser aus dem Transportprotokoll entnehmen lässt.
     *
     * @return \DateTime
     */
    public function getSendezeitpunkt()
    {
        return $this->sendezeitpunkt;
    }

    /**
     * Sets a new sendezeitpunkt
     *
     * Hier kann der Zeitpunkt des Versands der aus den genannten Gründen zurückgewiesenen Nachricht übermittelt werden, sofern sich dieser aus dem Transportprotokoll entnehmen lässt.
     *
     * @param \DateTime $sendezeitpunkt
     * @return self
     */
    public function setSendezeitpunkt(?\DateTime $sendezeitpunkt = null)
    {
        $this->sendezeitpunkt = $sendezeitpunkt;
        return $this;
    }

    /**
     * Gets as ergaenzendeHinweise
     *
     * Hier können weitere Angaben gemacht werden, die dem Empfänger der aus den genannten Gründen zurückgewiesenen Nachricht helfen, diese in seinem Verfahren zu identifizieren.
     *
     * @return string
     */
    public function getErgaenzendeHinweise()
    {
        return $this->ergaenzendeHinweise;
    }

    /**
     * Sets a new ergaenzendeHinweise
     *
     * Hier können weitere Angaben gemacht werden, die dem Empfänger der aus den genannten Gründen zurückgewiesenen Nachricht helfen, diese in seinem Verfahren zu identifizieren.
     *
     * @param string $ergaenzendeHinweise
     * @return self
     */
    public function setErgaenzendeHinweise($ergaenzendeHinweise)
    {
        $this->ergaenzendeHinweise = $ergaenzendeHinweise;
        return $this;
    }
}

