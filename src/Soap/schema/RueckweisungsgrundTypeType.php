<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RueckweisungsgrundTypeType
 *
 * Dieser Typ nimmt Angaben zu Art und Ort eines Fehlers auf, der zur Rückweisung der Nachricht geführt hat.
 * XSD Type: RueckweisungsgrundType
 */
class RueckweisungsgrundTypeType
{
    /**
     * Mit diesem Element wird der Grund übermittelt, aus dem die Nachricht zurückgewiesen wird. Die im Rahmen der hier zu verwendenden Codeliste zur Verfügung gestellten Rückweisungsgründe sind kontextunabhängig und finden daher in allen Übermittlungskontexten Anwendung.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauFehlerkennzahlTypeType $grund
     */
    private $grund = null;

    /**
     * Hier steht ein ergänzender textueller Hinweis auf die Art des Fehlers, der zur Zurückweisung der Nachricht geführt hat. Sofern kein Text angegeben ist (das Element also nicht übermittelt wird), gilt allein die Erläuterung zur im Element grund stehenden Fehlerkennzahl.
     *
     * @var string $fehlertext
     */
    private $fehlertext = null;

    /**
     * Gets as grund
     *
     * Mit diesem Element wird der Grund übermittelt, aus dem die Nachricht zurückgewiesen wird. Die im Rahmen der hier zu verwendenden Codeliste zur Verfügung gestellten Rückweisungsgründe sind kontextunabhängig und finden daher in allen Übermittlungskontexten Anwendung.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauFehlerkennzahlTypeType
     */
    public function getGrund()
    {
        return $this->grund;
    }

    /**
     * Sets a new grund
     *
     * Mit diesem Element wird der Grund übermittelt, aus dem die Nachricht zurückgewiesen wird. Die im Rahmen der hier zu verwendenden Codeliste zur Verfügung gestellten Rückweisungsgründe sind kontextunabhängig und finden daher in allen Übermittlungskontexten Anwendung.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauFehlerkennzahlTypeType $grund
     * @return self
     */
    public function setGrund(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauFehlerkennzahlTypeType $grund)
    {
        $this->grund = $grund;
        return $this;
    }

    /**
     * Gets as fehlertext
     *
     * Hier steht ein ergänzender textueller Hinweis auf die Art des Fehlers, der zur Zurückweisung der Nachricht geführt hat. Sofern kein Text angegeben ist (das Element also nicht übermittelt wird), gilt allein die Erläuterung zur im Element grund stehenden Fehlerkennzahl.
     *
     * @return string
     */
    public function getFehlertext()
    {
        return $this->fehlertext;
    }

    /**
     * Sets a new fehlertext
     *
     * Hier steht ein ergänzender textueller Hinweis auf die Art des Fehlers, der zur Zurückweisung der Nachricht geführt hat. Sofern kein Text angegeben ist (das Element also nicht übermittelt wird), gilt allein die Erläuterung zur im Element grund stehenden Fehlerkennzahl.
     *
     * @param string $fehlertext
     * @return self
     */
    public function setFehlertext($fehlertext)
    {
        $this->fehlertext = $fehlertext;
        return $this;
    }
}

