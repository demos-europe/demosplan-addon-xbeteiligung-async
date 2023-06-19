<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ProzessnachrichtenQuittierungRuecknahme1131;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;

/**
 * Class representing ProzessnachrichtenQuittierungRuecknahme1131AnonymousPHPType
 */
class ProzessnachrichtenQuittierungRuecknahme1131AnonymousPHPType extends NachrichtG2GType
{
    /**
     * In dieses Element sind die Referenzen von Antragsteller und ggf. Behörde einzutragen. Es ist die Nachricht des Antragstellers zu referenzieren, mittels derer er über seine Absicht informiert hat, den Antrag zurückzunehmen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugType $bezug
     */
    private $bezug = null;

    /**
     * Falls die Rücknahme des Antrags durch die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) akzeptiert wurde, wird in diesem Element "true" übermittelt. Andernfalls wird "false" übermittelt. Dann ist eine Rücknahme des Antrags nicht mehr möglich, weil bereits ein Bescheid erteilt wurde, der dem Antragsteller auf dem vorgesehenen Wege zugehen wird oder bereits zugegangen ist.
     *
     * @var bool $ruecknahmeAkzeptiert
     */
    private $ruecknahmeAkzeptiert = null;

    /**
     * Hier kann optional eine Begründung oder Erläuterung der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingetragen werden.
     *
     * @var string[] $information
     */
    private $information = null;

    /**
     * Gets as bezug
     *
     * In dieses Element sind die Referenzen von Antragsteller und ggf. Behörde einzutragen. Es ist die Nachricht des Antragstellers zu referenzieren, mittels derer er über seine Absicht informiert hat, den Antrag zurückzunehmen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugType
     */
    public function getBezug()
    {
        return $this->bezug;
    }

    /**
     * Sets a new bezug
     *
     * In dieses Element sind die Referenzen von Antragsteller und ggf. Behörde einzutragen. Es ist die Nachricht des Antragstellers zu referenzieren, mittels derer er über seine Absicht informiert hat, den Antrag zurückzunehmen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugType $bezug
     * @return self
     */
    public function setBezug(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BezugType $bezug)
    {
        $this->bezug = $bezug;
        return $this;
    }

    /**
     * Gets as ruecknahmeAkzeptiert
     *
     * Falls die Rücknahme des Antrags durch die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) akzeptiert wurde, wird in diesem Element "true" übermittelt. Andernfalls wird "false" übermittelt. Dann ist eine Rücknahme des Antrags nicht mehr möglich, weil bereits ein Bescheid erteilt wurde, der dem Antragsteller auf dem vorgesehenen Wege zugehen wird oder bereits zugegangen ist.
     *
     * @return bool
     */
    public function getRuecknahmeAkzeptiert()
    {
        return $this->ruecknahmeAkzeptiert;
    }

    /**
     * Sets a new ruecknahmeAkzeptiert
     *
     * Falls die Rücknahme des Antrags durch die Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) akzeptiert wurde, wird in diesem Element "true" übermittelt. Andernfalls wird "false" übermittelt. Dann ist eine Rücknahme des Antrags nicht mehr möglich, weil bereits ein Bescheid erteilt wurde, der dem Antragsteller auf dem vorgesehenen Wege zugehen wird oder bereits zugegangen ist.
     *
     * @param bool $ruecknahmeAkzeptiert
     * @return self
     */
    public function setRuecknahmeAkzeptiert($ruecknahmeAkzeptiert)
    {
        $this->ruecknahmeAkzeptiert = $ruecknahmeAkzeptiert;
        return $this;
    }

    /**
     * Adds as textabsatz
     *
     * Hier kann optional eine Begründung oder Erläuterung der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingetragen werden.
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
     * Hier kann optional eine Begründung oder Erläuterung der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingetragen werden.
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
     * Hier kann optional eine Begründung oder Erläuterung der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingetragen werden.
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
     * Hier kann optional eine Begründung oder Erläuterung der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingetragen werden.
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
     * Hier kann optional eine Begründung oder Erläuterung der Bauaufsichtsbehörde (bzw. Aufsichtsbehörde für den Breitbandausbau) eingetragen werden.
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

