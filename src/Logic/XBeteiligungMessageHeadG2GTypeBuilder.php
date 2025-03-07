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
        $this->getAgent($agentType)->getBehoerdenkennung()?->getPraefix()?->setCode($code);

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixName($name, string $agentType): static
    {
        $this->getAgent($agentType)->getBehoerdenkennung()?->getPraefix()?->setName($name);

        return $this;
    }

    /**
     * @param $code
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelCode($code, string $agentType): static
    {
        $this->getAgent($agentType)->getBehoerdenkennung()?->getKennung()?->setCode($code);

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
     * @param $name
     * @param int $index
     *
     * @return $this
     */
    public function setAgentContactChannelName($name, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->getKanal()?->setName($name);

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
     * @param $number
     *
     * @return $this
     */
    public function setAgentAddressBuildingNumber($number, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setHausnummer($number);

        return $this;
    }

    /**
     * @param $zipcode
     *
     * @return $this
     */
    public function setAgentAddressBuildingZipcode($zipcode, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setPostleitzahl($zipcode);

        return $this;
    }

    /**
     * @param $street
     *
     * @return $this
     */
    public function setAgentAddressStreet($street, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setStrasse($street);

        return $this;
    }

    /**
     * @param $municipal
     *
     * @return $this
     */
    public function setAgentAddressMunicipal($municipal, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setWohnort($municipal);

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setAgentAgencyName($name, string $agentType): static
    {
        $this->getAgent($agentType)->setBehoerdenname($name);

        return $this;
    }

    /**
     * @param $letter
     *
     * @return $this
     */
    public function setAgentAddressBuildingAdditionalLetter($letter, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setHausnummerBuchstabeZusatzziffer($letter);

        return $this;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setAgentAddressBuildingApartmentNumber($number, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setTeilnummerDerHausnummer($number);

        return $this;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setAgentAddressBuildingFloorNumber($number, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setStockwerkswohnungsnummer($number);

        return $this;
    }

    /**
     * @param $info
     *
     * @return $this
     */
    public function setAgentAddressBuildingAdditionalInfo($info, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setZusatzangaben($info);

        return $this;
    }

    /**
     * @param $versionId
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixListVersionId($versionId, string $agentType): static
    {
        $this->getAgent($agentType)->getBehoerdenkennung()?->getPraefix()?->setCode($versionId);

        return $this;
    }

    /**
     * @param $versionId
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelListVersionID($versionId, string $agentType): static
    {
        $this->getAgent($agentType)->getBehoerdenkennung()?->getKennung()?->setCode($versionId);

        return $this;
    }

    /**
     * @param $uri
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelListURI($uri, string $agentType): static
    {
        $this->getAgent($agentType)->getBehoerdenkennung()?->getKennung()?->setCode($uri);

        return $this;
    }

    /**
     * @param $name
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelName($name, string $agentType): static
    {
        $this->getAgent($agentType)->getBehoerdenkennung()?->getKennung()?->setName($name);

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
     * @param $number
     *
     * @return $this
     */
    public function setAgentAddressBuildingNumberBis($number, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->getHausnummernBis()?->setHausnummerBis($number);

        return $this;
    }

    /**
     * @param $letter
     *
     * @return $this
     */
    public function setAgentAddressBuildingAdditionalLetterBis($letter, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->getHausnummernBis()?->setHausnummerbuchstabezusatzzifferBis($letter);

        return $this;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setAgentAddressBuildingApartmentNumberBis($number, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->getHausnummernBis()?->setTeilnummerderhausnummerBis($number);

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
        $communicationType->setKanal(new CodeErreichbarkeitTypeType());
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

    /**
     * @return $this
     */
    public function setAgentApartmentOwner(string $owner, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setWohnungsinhaber($owner);

        return $this;
    }

    /**
     * @param $corporation
     * @param string $agentType - reader|author
     *
     * @return $this
     */
    public function setAgentMunicipalPreviousCorporation($corporation, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setWohnortFruehererGemeindename($corporation);

        return $this;
    }
}
