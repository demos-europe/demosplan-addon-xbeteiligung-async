<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenEingangsbestaetigung1120;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;

/**
 * Class representing ProzessnachrichtenEingangsbestaetigung1120AnonymousPHPType
 */
class ProzessnachrichtenEingangsbestaetigung1120AnonymousPHPType extends NachrichtG2GTypeType
{
    /**
     * Dieses Element enthält, um Bezug zu nehmen, die Vorgangsnummer bzw. die Referenz des Antrags (Zeichen des Antragstellers). Außerdem enthält es - mandatorisch - eine Referenzierung auf die Nachricht, durch die der Antrag (modifizierter Antrag) bzw. die Anzeige eingereicht worden ist, deren Empfang bestätigt wird.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug
     */
    private $bezug = null;

    /**
     * Hier kann die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) ergänzend erläuternden Text in die Nachricht einfügen.
     *
     * @var string[] $information
     */
    private $information = null;

    /**
     * Gets as bezug
     *
     * Dieses Element enthält, um Bezug zu nehmen, die Vorgangsnummer bzw. die Referenz des Antrags (Zeichen des Antragstellers). Außerdem enthält es - mandatorisch - eine Referenzierung auf die Nachricht, durch die der Antrag (modifizierter Antrag) bzw. die Anzeige eingereicht worden ist, deren Empfang bestätigt wird.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType
     */
    public function getBezug()
    {
        return $this->bezug;
    }

    /**
     * Sets a new bezug
     *
     * Dieses Element enthält, um Bezug zu nehmen, die Vorgangsnummer bzw. die Referenz des Antrags (Zeichen des Antragstellers). Außerdem enthält es - mandatorisch - eine Referenzierung auf die Nachricht, durch die der Antrag (modifizierter Antrag) bzw. die Anzeige eingereicht worden ist, deren Empfang bestätigt wird.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug
     * @return self
     */
    public function setBezug(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugTypeType $bezug)
    {
        $this->bezug = $bezug;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * Hier kann die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) ergänzend erläuternden Text in die Nachricht einfügen.
     *
     * @return self
     * @param string $textabsatz
     */
    public function addToInformation($textabsatz)
    {
        $this->information[] = $textabsatz;
        return $this;
    }

    /**
     * isset information
     *
     * Hier kann die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) ergänzend erläuternden Text in die Nachricht einfügen.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetInformation($index)
    {
        return isset($this->information[$index]);
    }

    /**
     * unset information
     *
     * Hier kann die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) ergänzend erläuternden Text in die Nachricht einfügen.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetInformation($index)
    {
        unset($this->information[$index]);
    }

    /**
     * Gets as information
     *
     * Hier kann die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) ergänzend erläuternden Text in die Nachricht einfügen.
     *
     * @return string[]
     */
    public function getInformation()
    {
        return $this->information;
    }

    /**
     * Sets a new information
     *
     * Hier kann die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) ergänzend erläuternden Text in die Nachricht einfügen.
     *
     * @param string[] $information
     * @return self
     */
    public function setInformation(array $information = null)
    {
        $this->information = $information;
        return $this;
    }
}

