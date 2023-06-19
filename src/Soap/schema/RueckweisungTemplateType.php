<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing RueckweisungTemplateType
 *
 * Dieser Typ realisiert die abstrakte Oberklasse aller Rückweisungscontainer.
 * XSD Type: Rueckweisung.Template
 */
class RueckweisungTemplateType
{
    /**
     * Dieses Kindelement ist nur dann zu übermitteln, wenn die Nachricht nicht von dem ursprünglich adressierten Leser zurückgesandt wird, sondern von einer anderen Stelle (zum Beispiel einer Clearingstelle, die im Auftrag der ursprünglich adressierten Behörde eine Prüfung eingehender Nachrichten nach formalen Kriterien durchführt). Wird dieses Element nicht übermittelt, ist die rückweisende Stelle der zurückgewiesenen Nachricht (Kindelement nachricht) zu entnehmen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisendeStelleType $rueckweisendeStelle
     */
    private $rueckweisendeStelle = null;

    /**
     * In diesem Element werden die Gründe mitgeteilt, aufgrund derer die Nachricht zurückgesandt wird. Generell ist so vorzugehen, dass die Gründe für die Rückweisung so präzise und vollständig wie möglich bezeichnet werden, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType[] $rueckweisungsgrund
     */
    private $rueckweisungsgrund = [
        
    ];

    /**
     * In diesem Element sind Informationen zu übermitteln, die bei dem Empfang einer als fehlerhaft betrachteten Nachricht möglicherweise der Transportebene entnommen werden konnten. Diese Angaben können gemacht werden, um dem Leser oder Empfänger einer Rücksendenachricht die Identifikation der als fehlerhaft betrachteten und nunmehr zurückgesandten Nachricht zu erleichtern. Alle Kindelemente dieses Elementes sind optional, da keine verbindlichen Anforderungen an das Transportprotokoll gestellt werden können.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTransportinformationenType $transportinformationen
     */
    private $transportinformationen = null;

    /**
     * Mit diesem Element werden die nicht verarbeitbaren Einzelfälle aus der zurückgewiesenen (Sammel-)Nachricht kenntlich gemacht. Für jeden nicht verarbeitbaren Einzelfall sind neben den Identifikationsdaten die Gründe mitzuteilen, aufgrund derer der Einzelfall nicht verarbeitet werden konnte. Diese Gründe sind so präzise und vollständig wie möglich zu bezeichnen, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungEinzelfallType[] $nichtVerarbeitbarerEinzelfall
     */
    private $nichtVerarbeitbarerEinzelfall = [
        
    ];

    /**
     * In diesem Kindelement wird die die Rückweisung auslösende fachliche Nachricht identifiziert. Sofern die Ursprungsnachricht schemakonform ist, muss das Element idNachricht übermittelt werden. Ist die Ursprungsnachricht nicht schemakonform kann die Übermittlung des Elements entfallen.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $idNachricht
     */
    private $idNachricht = null;

    /**
     * Dieses Element enthält die aus den genannten Gründen zurückgewiesene ursprüngliche Nachricht. Um technische Probleme mit verschachtelten XML-Dokumenten zu vermeiden, ist der zurückgewiesene Inhalt immer base64-codiert zurückzusenden. Sollte es sich bei der ursprünglichen Nachricht um eine Sammelnachricht gehandelt haben, müssen die Einzelfälle, die korrekt verarbeitet wurden, aus der hier übermittelten Nachricht entfernt worden sein. Die hier übermittelte Nachricht enthält also nur Einzelfälle, die nicht verarbeitet worden sind. Für jeden nicht verarbeiteten Einzelfall ist ein Element nichtVerarbeitbarerEinzelfall mit einer entsprechenden begruendung zu übermitteln.
     *
     * @var string $nachricht
     */
    private $nachricht = null;

    /**
     * Gets as rueckweisendeStelle
     *
     * Dieses Kindelement ist nur dann zu übermitteln, wenn die Nachricht nicht von dem ursprünglich adressierten Leser zurückgesandt wird, sondern von einer anderen Stelle (zum Beispiel einer Clearingstelle, die im Auftrag der ursprünglich adressierten Behörde eine Prüfung eingehender Nachrichten nach formalen Kriterien durchführt). Wird dieses Element nicht übermittelt, ist die rückweisende Stelle der zurückgewiesenen Nachricht (Kindelement nachricht) zu entnehmen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisendeStelleType
     */
    public function getRueckweisendeStelle()
    {
        return $this->rueckweisendeStelle;
    }

    /**
     * Sets a new rueckweisendeStelle
     *
     * Dieses Kindelement ist nur dann zu übermitteln, wenn die Nachricht nicht von dem ursprünglich adressierten Leser zurückgesandt wird, sondern von einer anderen Stelle (zum Beispiel einer Clearingstelle, die im Auftrag der ursprünglich adressierten Behörde eine Prüfung eingehender Nachrichten nach formalen Kriterien durchführt). Wird dieses Element nicht übermittelt, ist die rückweisende Stelle der zurückgewiesenen Nachricht (Kindelement nachricht) zu entnehmen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisendeStelleType $rueckweisendeStelle
     * @return self
     */
    public function setRueckweisendeStelle(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisendeStelleType $rueckweisendeStelle = null)
    {
        $this->rueckweisendeStelle = $rueckweisendeStelle;
        return $this;
    }

    /**
     * Adds as rueckweisungsgrund
     *
     * In diesem Element werden die Gründe mitgeteilt, aufgrund derer die Nachricht zurückgesandt wird. Generell ist so vorzugehen, dass die Gründe für die Rückweisung so präzise und vollständig wie möglich bezeichnet werden, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType $rueckweisungsgrund
     */
    public function addToRueckweisungsgrund(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType $rueckweisungsgrund)
    {
        $this->rueckweisungsgrund[] = $rueckweisungsgrund;
        return $this;
    }

    /**
     * isset rueckweisungsgrund
     *
     * In diesem Element werden die Gründe mitgeteilt, aufgrund derer die Nachricht zurückgesandt wird. Generell ist so vorzugehen, dass die Gründe für die Rückweisung so präzise und vollständig wie möglich bezeichnet werden, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetRueckweisungsgrund($index)
    {
        return isset($this->rueckweisungsgrund[$index]);
    }

    /**
     * unset rueckweisungsgrund
     *
     * In diesem Element werden die Gründe mitgeteilt, aufgrund derer die Nachricht zurückgesandt wird. Generell ist so vorzugehen, dass die Gründe für die Rückweisung so präzise und vollständig wie möglich bezeichnet werden, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetRueckweisungsgrund($index)
    {
        unset($this->rueckweisungsgrund[$index]);
    }

    /**
     * Gets as rueckweisungsgrund
     *
     * In diesem Element werden die Gründe mitgeteilt, aufgrund derer die Nachricht zurückgesandt wird. Generell ist so vorzugehen, dass die Gründe für die Rückweisung so präzise und vollständig wie möglich bezeichnet werden, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType[]
     */
    public function getRueckweisungsgrund()
    {
        return $this->rueckweisungsgrund;
    }

    /**
     * Sets a new rueckweisungsgrund
     *
     * In diesem Element werden die Gründe mitgeteilt, aufgrund derer die Nachricht zurückgesandt wird. Generell ist so vorzugehen, dass die Gründe für die Rückweisung so präzise und vollständig wie möglich bezeichnet werden, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungRueckweisungsgrundType[] $rueckweisungsgrund
     * @return self
     */
    public function setRueckweisungsgrund(array $rueckweisungsgrund)
    {
        $this->rueckweisungsgrund = $rueckweisungsgrund;
        return $this;
    }

    /**
     * Gets as transportinformationen
     *
     * In diesem Element sind Informationen zu übermitteln, die bei dem Empfang einer als fehlerhaft betrachteten Nachricht möglicherweise der Transportebene entnommen werden konnten. Diese Angaben können gemacht werden, um dem Leser oder Empfänger einer Rücksendenachricht die Identifikation der als fehlerhaft betrachteten und nunmehr zurückgesandten Nachricht zu erleichtern. Alle Kindelemente dieses Elementes sind optional, da keine verbindlichen Anforderungen an das Transportprotokoll gestellt werden können.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTransportinformationenType
     */
    public function getTransportinformationen()
    {
        return $this->transportinformationen;
    }

    /**
     * Sets a new transportinformationen
     *
     * In diesem Element sind Informationen zu übermitteln, die bei dem Empfang einer als fehlerhaft betrachteten Nachricht möglicherweise der Transportebene entnommen werden konnten. Diese Angaben können gemacht werden, um dem Leser oder Empfänger einer Rücksendenachricht die Identifikation der als fehlerhaft betrachteten und nunmehr zurückgesandten Nachricht zu erleichtern. Alle Kindelemente dieses Elementes sind optional, da keine verbindlichen Anforderungen an das Transportprotokoll gestellt werden können.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTransportinformationenType $transportinformationen
     * @return self
     */
    public function setTransportinformationen(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungTransportinformationenType $transportinformationen = null)
    {
        $this->transportinformationen = $transportinformationen;
        return $this;
    }

    /**
     * Adds as nichtVerarbeitbarerEinzelfall
     *
     * Mit diesem Element werden die nicht verarbeitbaren Einzelfälle aus der zurückgewiesenen (Sammel-)Nachricht kenntlich gemacht. Für jeden nicht verarbeitbaren Einzelfall sind neben den Identifikationsdaten die Gründe mitzuteilen, aufgrund derer der Einzelfall nicht verarbeitet werden konnte. Diese Gründe sind so präzise und vollständig wie möglich zu bezeichnen, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @return self
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungEinzelfallType $nichtVerarbeitbarerEinzelfall
     */
    public function addToNichtVerarbeitbarerEinzelfall(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungEinzelfallType $nichtVerarbeitbarerEinzelfall)
    {
        $this->nichtVerarbeitbarerEinzelfall[] = $nichtVerarbeitbarerEinzelfall;
        return $this;
    }

    /**
     * isset nichtVerarbeitbarerEinzelfall
     *
     * Mit diesem Element werden die nicht verarbeitbaren Einzelfälle aus der zurückgewiesenen (Sammel-)Nachricht kenntlich gemacht. Für jeden nicht verarbeitbaren Einzelfall sind neben den Identifikationsdaten die Gründe mitzuteilen, aufgrund derer der Einzelfall nicht verarbeitet werden konnte. Diese Gründe sind so präzise und vollständig wie möglich zu bezeichnen, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @param int|string $index
     * @return bool
     */
    public function issetNichtVerarbeitbarerEinzelfall($index)
    {
        return isset($this->nichtVerarbeitbarerEinzelfall[$index]);
    }

    /**
     * unset nichtVerarbeitbarerEinzelfall
     *
     * Mit diesem Element werden die nicht verarbeitbaren Einzelfälle aus der zurückgewiesenen (Sammel-)Nachricht kenntlich gemacht. Für jeden nicht verarbeitbaren Einzelfall sind neben den Identifikationsdaten die Gründe mitzuteilen, aufgrund derer der Einzelfall nicht verarbeitet werden konnte. Diese Gründe sind so präzise und vollständig wie möglich zu bezeichnen, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @param int|string $index
     * @return void
     */
    public function unsetNichtVerarbeitbarerEinzelfall($index)
    {
        unset($this->nichtVerarbeitbarerEinzelfall[$index]);
    }

    /**
     * Gets as nichtVerarbeitbarerEinzelfall
     *
     * Mit diesem Element werden die nicht verarbeitbaren Einzelfälle aus der zurückgewiesenen (Sammel-)Nachricht kenntlich gemacht. Für jeden nicht verarbeitbaren Einzelfall sind neben den Identifikationsdaten die Gründe mitzuteilen, aufgrund derer der Einzelfall nicht verarbeitet werden konnte. Diese Gründe sind so präzise und vollständig wie möglich zu bezeichnen, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungEinzelfallType[]
     */
    public function getNichtVerarbeitbarerEinzelfall()
    {
        return $this->nichtVerarbeitbarerEinzelfall;
    }

    /**
     * Sets a new nichtVerarbeitbarerEinzelfall
     *
     * Mit diesem Element werden die nicht verarbeitbaren Einzelfälle aus der zurückgewiesenen (Sammel-)Nachricht kenntlich gemacht. Für jeden nicht verarbeitbaren Einzelfall sind neben den Identifikationsdaten die Gründe mitzuteilen, aufgrund derer der Einzelfall nicht verarbeitet werden konnte. Diese Gründe sind so präzise und vollständig wie möglich zu bezeichnen, um eine schnelle Klärung des Sachverhalts zu ermöglichen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RueckweisungEinzelfallType[] $nichtVerarbeitbarerEinzelfall
     * @return self
     */
    public function setNichtVerarbeitbarerEinzelfall(array $nichtVerarbeitbarerEinzelfall = null)
    {
        $this->nichtVerarbeitbarerEinzelfall = $nichtVerarbeitbarerEinzelfall;
        return $this;
    }

    /**
     * Gets as idNachricht
     *
     * In diesem Kindelement wird die die Rückweisung auslösende fachliche Nachricht identifiziert. Sofern die Ursprungsnachricht schemakonform ist, muss das Element idNachricht übermittelt werden. Ist die Ursprungsnachricht nicht schemakonform kann die Übermittlung des Elements entfallen.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType
     */
    public function getIdNachricht()
    {
        return $this->idNachricht;
    }

    /**
     * Sets a new idNachricht
     *
     * In diesem Kindelement wird die die Rückweisung auslösende fachliche Nachricht identifiziert. Sofern die Ursprungsnachricht schemakonform ist, muss das Element idNachricht übermittelt werden. Ist die Ursprungsnachricht nicht schemakonform kann die Übermittlung des Elements entfallen.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $idNachricht
     * @return self
     */
    public function setIdNachricht(?\DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType $idNachricht = null)
    {
        $this->idNachricht = $idNachricht;
        return $this;
    }

    /**
     * Gets as nachricht
     *
     * Dieses Element enthält die aus den genannten Gründen zurückgewiesene ursprüngliche Nachricht. Um technische Probleme mit verschachtelten XML-Dokumenten zu vermeiden, ist der zurückgewiesene Inhalt immer base64-codiert zurückzusenden. Sollte es sich bei der ursprünglichen Nachricht um eine Sammelnachricht gehandelt haben, müssen die Einzelfälle, die korrekt verarbeitet wurden, aus der hier übermittelten Nachricht entfernt worden sein. Die hier übermittelte Nachricht enthält also nur Einzelfälle, die nicht verarbeitet worden sind. Für jeden nicht verarbeiteten Einzelfall ist ein Element nichtVerarbeitbarerEinzelfall mit einer entsprechenden begruendung zu übermitteln.
     *
     * @return string
     */
    public function getNachricht()
    {
        return $this->nachricht;
    }

    /**
     * Sets a new nachricht
     *
     * Dieses Element enthält die aus den genannten Gründen zurückgewiesene ursprüngliche Nachricht. Um technische Probleme mit verschachtelten XML-Dokumenten zu vermeiden, ist der zurückgewiesene Inhalt immer base64-codiert zurückzusenden. Sollte es sich bei der ursprünglichen Nachricht um eine Sammelnachricht gehandelt haben, müssen die Einzelfälle, die korrekt verarbeitet wurden, aus der hier übermittelten Nachricht entfernt worden sein. Die hier übermittelte Nachricht enthält also nur Einzelfälle, die nicht verarbeitet worden sind. Für jeden nicht verarbeiteten Einzelfall ist ein Element nichtVerarbeitbarerEinzelfall mit einer entsprechenden begruendung zu übermitteln.
     *
     * @param string $nachricht
     * @return self
     */
    public function setNachricht($nachricht)
    {
        $this->nachricht = $nachricht;
        return $this;
    }
}

