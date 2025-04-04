<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;


use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\CodeVerzeichnisdienstTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Autor;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachricht;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Leser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtenkopfG2g;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\CodeKommunikationKanalTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Code\CodeType;

class XBeteiligungMessageHeadG2GTypeBuilder
{
    protected NachrichtenkopfG2g $head;

    public function __construct()
    {
        $this->head = new NachrichtenkopfG2g();
        $messageIdentification = new IdentifikationNachricht();
        $code = new CodeType();
        $messageIdentification->setNachrichtentyp($code);
        $messageIdentification->setErstellungszeitpunkt(new DateTime());
        $this->head->setIdentifikationNachricht($messageIdentification);

        // Reader
        $reader = new Leser();
        $this->head->setLeser($reader);
        $reader->setKennung('');
        $reader->setName('');
        $codeBehoerdenkennung = new CodeVerzeichnisdienstTypeType();
        $codeBehoerdenkennung->setListVersionID('');
        $codeBehoerdenkennung->setListURI('');
        $reader->setVerzeichnisdienst($codeBehoerdenkennung);
        $readerErreichbarkeit = new Erreichbarkeit();
        $readerErreichbarkeitChannel = new CodeKommunikationKanalTypeType();
        $readerErreichbarkeit->setKanal($readerErreichbarkeitChannel);
        $this->head->getLeser()?->addToErreichbarkeit($readerErreichbarkeit);

        // Author
        $author = new Autor();
        $this->head->setAutor($author);
        $author->setKennung('');
        $author->setName('');
        $authorCodePraefix = new CodeVerzeichnisdienstTypeType();
        $authorCodePraefix->setListURI(null);
        $authorCodePraefix->setListVersionID('');
        $author->setVerzeichnisdienst($authorCodePraefix);
        $authorErreichbarkeit = new Erreichbarkeit();
        $author->addToErreichbarkeit($authorErreichbarkeit);
        $authorCodeErreichbarkeit = new CodeKommunikationKanalTypeType();
        $authorErreichbarkeit->setKanal($authorCodeErreichbarkeit);
        $this->head->getAutor()?->addToErreichbarkeit($authorErreichbarkeit);
    }

    public function build(): NachrichtenkopfG2g
    {
        return $this->head;
    }

    /**
     * @return $this
     */
    public function setCreationTime(DateTime $time): static
    {
        $this->head->getIdentifikationNachricht()?->setErstellungszeitpunkt($time);

        return $this;
    }

    /**
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
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setName($name);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelCode(string $code, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setCode($code);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactChannelCode(string $code, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->getKanal()?->setCode($code);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactChannelName(string $name, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->getKanal()?->setName($name);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactLabel(string $label, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->setKennung($label);

        return $this;
    }

    /**
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixListVersionId(string $versionId, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setListVersionID($versionId);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelListVersionID(string $versionId, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setListVersionID($versionId);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelListURI(string $uri, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setListURI($uri);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelName(string $name, string $agentType): static
    {
        $this->getAgent($agentType)->getVerzeichnisdienst()?->setName($name);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAddition(string $addition, string $agentType, int $index = 0): static
    {
        $this->getAgent($agentType)->getErreichbarkeit()[$index]->setZusatz($addition);

        return $this;
    }

    /**
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
     * Initializes new Author Erreichbarkeit type.
     */
    private function newAgentKommunikationType(int $index, string $agentType): void
    {
        $authorerreichbarkeitTypes = $this->getAgent($agentType)->getErreichbarkeit();
        $erreichbarkeit = new Erreichbarkeit();
        $erreichbarkeit->setKanal(new CodeKommunikationKanalTypeType());
        $authorerreichbarkeitTypes[$index] = $erreichbarkeit;
        $this->getAgent($agentType)->setErreichbarkeit($authorerreichbarkeitTypes);
    }

    private function getAgent(string $agentType): Leser|Autor
    {
        return 'reader' === $agentType ? $this->head->getLeser() : $this->head->getAutor();
    }

    /**
     * TODO: Is this not needed anymore or how the implementation has to be changed?
     * @return $this
     */
    public function setAgentApartmentOwner(string $owner, string $agentType): static
    {
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setWohnungsinhaber($owner);

        return $this;
    }

    /**
     * TODO: Is this not needed anymore or how the implementation has to be changed?
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
