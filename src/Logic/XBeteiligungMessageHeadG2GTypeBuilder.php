<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;


use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKommunikationKanalTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerzeichnisdienstTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfG2GType;

class XBeteiligungMessageHeadG2GTypeBuilder
{
    /**
     * @var NachrichtenkopfG2GType
     */
    protected NachrichtenkopfG2GType $head;

    public function __construct()
    {
        $this->head = new NachrichtenkopfG2GType();
        $messageIdentification = new IdentifikationNachrichtType();
        $code = new CodeType();
        $messageIdentification->setNachrichtentyp($code);
        $messageIdentification->setErstellungszeitpunkt(new DateTime());
        $this->head->setIdentifikationNachricht($messageIdentification);

        // Reader
        $reader = new BehoerdeTypeType();
        $this->head->setLeser($reader);
        $reader->setKennung('');
        $reader->setName('');
        $codeBehoerdenkennung = new CodeVerzeichnisdienstTypeType();
        $codeBehoerdenkennung->setListVersionID('');
        $codeBehoerdenkennung->setListURI('');
        $reader->setVerzeichnisdienst($codeBehoerdenkennung);
        $readerErreichbarkeit = new KommunikationTypeType();
        $readerErreichbarkeitChannel = new CodeKommunikationKanalTypeType();
        $readerErreichbarkeit->setKanal($readerErreichbarkeitChannel);
        $this->head->getLeser()?->addToErreichbarkeit($readerErreichbarkeit);

        // Author
        $author = new BehoerdeTypeType();
        $this->head->setAutor($author);
        $author->setKennung('');
        $author->setName('');
        $authorCodePraefix = new CodeVerzeichnisdienstTypeType();
        $authorCodePraefix->setListURI(null);
        $authorCodePraefix->setListVersionID('');
        $author->setVerzeichnisdienst($authorCodePraefix);
        $authorErreichbarkeit = new KommunikationTypeType();
        $author->addToErreichbarkeit($authorErreichbarkeit);
        $authorCodeErreichbarkeit = new CodeKommunikationKanalTypeType();
        $authorErreichbarkeit->setKanal($authorCodeErreichbarkeit);
        $this->head->getAutor()?->addToErreichbarkeit($authorErreichbarkeit);
    }

    /**
     * @return NachrichtenkopfG2GType
     */
    public function build(): NachrichtenkopfG2GType
    {
        return $this->head;
    }

    /**
     * @param DateTime $time
     *
     * @return $this
     */
    public function setCreationTime(DateTime $time): static
    {
        $this->head->getIdentifikationNachricht()?->setErstellungszeitpunkt($time);

        return $this;
    }

    /**
     * @param string $messageTypeCode
     *
     * @return $this
     */
    public function setMessageIdentificationTypeCode(string $messageTypeCode): static
    {
        $this->head->getIdentifikationNachricht()?->getNachrichtentyp()?->setCode($messageTypeCode);

        return $this;
    }

    /**
     * @param $uuid
     *
     * @return $this
     */
    public function setMessageIdentificationUUID($uuid): static
    {
        $this->head->getIdentifikationNachricht()?->setNachrichtenUUID($uuid);

        return $this;
    }

    /**
     * @param $code
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixCode($code, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setCode($code);

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixName($name, string $agentType): static
    {
        $this->getAgent($agentType)->setName($name);

        return $this;
    }

    /**
     * @param $code
     * @param int $index
     *
     * @return $this
     */
    public function setAgentContactChannelCode($code, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->getKanal()?->setCode($code);

        return $this;
    }

    /**
     * @param $listVersion
     * @param int $index
     *
     * @return $this
     */
    public function setAgentContactChannelListVersion($listVersion, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->getKanal()?->setListVersionID($listVersion);

        return $this;
    }

    /**
     * @param $label
     * @param int $index
     *
     * @return $this
     */
    public function setAgentContactLabel($label, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->setKennung($label);

        return $this;
    }

    /**
     * @param $versionId
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixListVersionId($versionId, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setListVersionID($versionId);

        return $this;
    }

    /**
     * @param $uri
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelListURI($uri, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setListURI($uri);

        return $this;
    }

    /**
     * @param $addition
     * @param int $index
     *
     * @return $this
     */
    public function setAgentAddition($addition, string $agentType, int $index = 0): static
    {
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->setZusatz($addition);

        return $this;
    }

    /**
     * Initializes new Author CommmunicationType.
     *
     * @param $index
     */
    private function newAgentKommunikationType($index, string $agentType): void
    {
        $authorCommunicationTypes = $this->getAgent($agentType)->getErreichbarkeit();
        $communicationType = new KommunikationTypeType();
        $communicationType->setKanal(new CodeKommunikationKanalTypeType());
        $authorCommunicationTypes[$index] = $communicationType;
        $this->getAgent($agentType)->setErreichbarkeit($authorCommunicationTypes);
    }

    /**
     * @return BehoerdeTypeType|mixed
     */
    private function getAgent(string $agentType): mixed
    {
        return 'reader' === $agentType ? $this->head->getLeser() : $this->head->getAutor();
    }

}
