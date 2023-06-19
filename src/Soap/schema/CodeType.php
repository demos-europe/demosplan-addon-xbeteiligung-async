<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema;

/**
 * Class representing CodeType
 *
 * Der XÖV-Datentyp Code ermöglicht die Übermittlung von Werten, so genannter Codes, aus vordefinierten Codelisten. Eine Codeliste ist eine Liste von Codes und der Beschreibung ihrer jeweiligen Bedeutung. Eine entscheidende Eigenschaft des Datentyps ist die Möglichkeit auf differenzierte Weise Bezug zu Codelisten zu nehmen (Code-Typ 1 bis 4). In jedem Fall erlauben die übermittelten Daten eine eindeutige Identifizierung der zugrundeliegenden Codeliste.
 * XSD Type: Code
 */
class CodeType
{
    /**
     * Mit diesem XML-Attribut wird die Kennung der Codeliste übermittelt, in deren Kontext der jeweilige Code zu interpretieren ist. Die Kennung identifiziert die Codeliste, nicht jedoch deren Version eindeutig. Wird bereits im Rahmen des XÖV-Standards eine Kennung vorgegeben (es handelt sich in diesem Fall um einen Code-Typ 1, 2 oder 3) darf auf eine nochmalige Angabe der Kennung bei der Übermittlung eines Codes verzichtet werden. Aus diesem Grund ist das XML-Attribut listURI zunächst als optional deklariert.
     *
     * @var string $listURI
     */
    private $listURI = null;

    /**
     * Die konkrete Version der zu nutzenden Codeliste wird mit diesem XML-Attribut übertragen. Analog zum listURI ist die Bestimmung der Version einer Codeliste bei der Übertragung eines Codes zwingend. Die Version kann jedoch ebenfalls bereits im XÖV-Standard festgelegt werden (es handelt sich in diesem Fall um einen Code-Typ 1 oder 2).
     *
     * @var string $listVersionID
     */
    private $listVersionID = null;

    /**
     * In diesem XML-Element wird der Code einer Codeliste übermittelt.
     *
     * @var string $code
     */
    private $code = null;

    /**
     * Mit diesem optionalen XML-Element kann die Beschreibung des Codes, wie in der jeweiligen Beschreibungsspalte der Codeliste vorgegeben, übermittelt werden.
     *
     * @var string $name
     */
    private $name = null;

    /**
     * Gets as listURI
     *
     * Mit diesem XML-Attribut wird die Kennung der Codeliste übermittelt, in deren Kontext der jeweilige Code zu interpretieren ist. Die Kennung identifiziert die Codeliste, nicht jedoch deren Version eindeutig. Wird bereits im Rahmen des XÖV-Standards eine Kennung vorgegeben (es handelt sich in diesem Fall um einen Code-Typ 1, 2 oder 3) darf auf eine nochmalige Angabe der Kennung bei der Übermittlung eines Codes verzichtet werden. Aus diesem Grund ist das XML-Attribut listURI zunächst als optional deklariert.
     *
     * @return string
     */
    public function getListURI()
    {
        return $this->listURI;
    }

    /**
     * Sets a new listURI
     *
     * Mit diesem XML-Attribut wird die Kennung der Codeliste übermittelt, in deren Kontext der jeweilige Code zu interpretieren ist. Die Kennung identifiziert die Codeliste, nicht jedoch deren Version eindeutig. Wird bereits im Rahmen des XÖV-Standards eine Kennung vorgegeben (es handelt sich in diesem Fall um einen Code-Typ 1, 2 oder 3) darf auf eine nochmalige Angabe der Kennung bei der Übermittlung eines Codes verzichtet werden. Aus diesem Grund ist das XML-Attribut listURI zunächst als optional deklariert.
     *
     * @param string $listURI
     * @return self
     */
    public function setListURI($listURI)
    {
        $this->listURI = $listURI;
        return $this;
    }

    /**
     * Gets as listVersionID
     *
     * Die konkrete Version der zu nutzenden Codeliste wird mit diesem XML-Attribut übertragen. Analog zum listURI ist die Bestimmung der Version einer Codeliste bei der Übertragung eines Codes zwingend. Die Version kann jedoch ebenfalls bereits im XÖV-Standard festgelegt werden (es handelt sich in diesem Fall um einen Code-Typ 1 oder 2).
     *
     * @return string
     */
    public function getListVersionID()
    {
        return $this->listVersionID;
    }

    /**
     * Sets a new listVersionID
     *
     * Die konkrete Version der zu nutzenden Codeliste wird mit diesem XML-Attribut übertragen. Analog zum listURI ist die Bestimmung der Version einer Codeliste bei der Übertragung eines Codes zwingend. Die Version kann jedoch ebenfalls bereits im XÖV-Standard festgelegt werden (es handelt sich in diesem Fall um einen Code-Typ 1 oder 2).
     *
     * @param string $listVersionID
     * @return self
     */
    public function setListVersionID($listVersionID)
    {
        $this->listVersionID = $listVersionID;
        return $this;
    }

    /**
     * Gets as code
     *
     * In diesem XML-Element wird der Code einer Codeliste übermittelt.
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets a new code
     *
     * In diesem XML-Element wird der Code einer Codeliste übermittelt.
     *
     * @param string $code
     * @return self
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * Gets as name
     *
     * Mit diesem optionalen XML-Element kann die Beschreibung des Codes, wie in der jeweiligen Beschreibungsspalte der Codeliste vorgegeben, übermittelt werden.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets a new name
     *
     * Mit diesem optionalen XML-Element kann die Beschreibung des Codes, wie in der jeweiligen Beschreibungsspalte der Codeliste vorgegeben, übermittelt werden.
     *
     * @param string $name
     * @return self
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }
}

