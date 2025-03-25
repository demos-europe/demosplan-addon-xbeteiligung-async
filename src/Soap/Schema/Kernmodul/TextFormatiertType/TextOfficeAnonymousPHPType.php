<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\TextFormatiertType;

/**
 * Class representing TextOfficeAnonymousPHPType
 */
class TextOfficeAnonymousPHPType
{
    /**
     * Falls dieses Element genutzt wird, ist derselbe Inhalt wie im Element text einzutragen, nur wird hier ein XML-basiertes Office-Format verwendet, um den Text technisch zu repräsentieren. Dafür ist eines der beiden XML-basierten Office-Formate ODF (= OASIS Open Document Format) und OOXML (= Office Open XML, entwickelt von Microsoft) zu verwenden. Die Verwertung dieses Elements, insoweit in einer Nachrichteninstanz instanziiert, steht im Belieben des Lesers der Nachricht.
     *
     * @var string $textOfficeXML
     */
    private $textOfficeXML = null;

    /**
     * ODF und OOXML sind Rahmenformate, die diverse Subtypen zulassen. Dieses Element steuert die mimeType-Angabe bei, die aussagt, welches Format für die Office-Darstellung im Element textOffice instanziiert ist.
     *
     * @var \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeTextOfficeMimeTypeType $textOfficeMimeType
     */
    private $textOfficeMimeType = null;

    /**
     * Gets as textOfficeXML
     *
     * Falls dieses Element genutzt wird, ist derselbe Inhalt wie im Element text einzutragen, nur wird hier ein XML-basiertes Office-Format verwendet, um den Text technisch zu repräsentieren. Dafür ist eines der beiden XML-basierten Office-Formate ODF (= OASIS Open Document Format) und OOXML (= Office Open XML, entwickelt von Microsoft) zu verwenden. Die Verwertung dieses Elements, insoweit in einer Nachrichteninstanz instanziiert, steht im Belieben des Lesers der Nachricht.
     *
     * @return string
     */
    public function getTextOfficeXML()
    {
        return $this->textOfficeXML;
    }

    /**
     * Sets a new textOfficeXML
     *
     * Falls dieses Element genutzt wird, ist derselbe Inhalt wie im Element text einzutragen, nur wird hier ein XML-basiertes Office-Format verwendet, um den Text technisch zu repräsentieren. Dafür ist eines der beiden XML-basierten Office-Formate ODF (= OASIS Open Document Format) und OOXML (= Office Open XML, entwickelt von Microsoft) zu verwenden. Die Verwertung dieses Elements, insoweit in einer Nachrichteninstanz instanziiert, steht im Belieben des Lesers der Nachricht.
     *
     * @param string $textOfficeXML
     * @return self
     */
    public function setTextOfficeXML($textOfficeXML)
    {
        $this->textOfficeXML = $textOfficeXML;
        return $this;
    }

    /**
     * Gets as textOfficeMimeType
     *
     * ODF und OOXML sind Rahmenformate, die diverse Subtypen zulassen. Dieses Element steuert die mimeType-Angabe bei, die aussagt, welches Format für die Office-Darstellung im Element textOffice instanziiert ist.
     *
     * @return \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeTextOfficeMimeTypeType
     */
    public function getTextOfficeMimeType()
    {
        return $this->textOfficeMimeType;
    }

    /**
     * Sets a new textOfficeMimeType
     *
     * ODF und OOXML sind Rahmenformate, die diverse Subtypen zulassen. Dieses Element steuert die mimeType-Angabe bei, die aussagt, welches Format für die Office-Darstellung im Element textOffice instanziiert ist.
     *
     * @param \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeTextOfficeMimeTypeType $textOfficeMimeType
     * @return self
     */
    public function setTextOfficeMimeType(\DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeTextOfficeMimeTypeType $textOfficeMimeType)
    {
        $this->textOfficeMimeType = $textOfficeMimeType;
        return $this;
    }
}

