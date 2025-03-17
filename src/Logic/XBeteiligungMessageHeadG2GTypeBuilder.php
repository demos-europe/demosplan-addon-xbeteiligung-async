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
        $reader->setKennung('');
        $reader->setName('');
        $codeVerzeichnisdienst = new CodeVerzeichnisdienstTypeType();
        $codeVerzeichnisdienst->setCode('');
        $codeVerzeichnisdienst->setName('');
        $codeVerzeichnisdienst->setListURI('');
        $codeVerzeichnisdienst->setListVersionID('');
        $reader->setVerzeichnisdienst($codeVerzeichnisdienst);
        $erreichbarkeitLeser1 = new Erreichbarkeit();
        $erreichbarkeitLeser2 = new Erreichbarkeit();
        $codeKommunikationKanal = new CodeKommunikationKanalTypeType();
        $codeKommunikationKanal->setCode('');
        $codeKommunikationKanal->setName('');
        $codeKommunikationKanal->setListURI('');
        $codeKommunikationKanal->setListVersionID('');
        $erreichbarkeitLeser1->setKanal($codeKommunikationKanal);
        $erreichbarkeitLeser1->setKennung('');
        $erreichbarkeitLeser2->setKanal($codeKommunikationKanal);
        $erreichbarkeitLeser2->setKennung('');
        $reader->setErreichbarkeit([$erreichbarkeitLeser1, $erreichbarkeitLeser2]);
        $this->head->setLeser($reader);

        // Author
        $author = new Autor();
        $author->setKennung('');
        $author->setName('');
        $codeVerzeichnisdienst = new CodeVerzeichnisdienstTypeType();
        $codeVerzeichnisdienst->setCode('');
        $codeVerzeichnisdienst->setName('');
        $codeVerzeichnisdienst->setListURI('');
        $codeVerzeichnisdienst->setListVersionID('');
        $author->setVerzeichnisdienst($codeVerzeichnisdienst);

        $erreichbarkeitAuthor1 = new Erreichbarkeit();
        $erreichbarkeitAuthor2 = new Erreichbarkeit();
        $codeKommunikationKanal = new CodeKommunikationKanalTypeType();
        $codeKommunikationKanal->setCode('');
        $codeKommunikationKanal->setName('');
        $codeKommunikationKanal->setListURI('');
        $codeKommunikationKanal->setListVersionID('');
        $erreichbarkeitAuthor1->setKanal($codeKommunikationKanal);
        $erreichbarkeitAuthor1->setKennung('');
        $erreichbarkeitAuthor2->setKanal($codeKommunikationKanal);
        $erreichbarkeitAuthor2->setKennung('');
        $author->setErreichbarkeit([$erreichbarkeitAuthor1, $erreichbarkeitAuthor2]);
        $this->head->setAutor($author);
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
     * @param $uri
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixListURI($uri, string $agentType): static
    {
        // todo: check if not longer needed (dont exist in Leser/Author)
        $this->getAgent($agentType)->getBehoerdenkennung()?->getPraefix()?->setListURI($uri);

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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
        $this->getAgent($agentType)->getBehoerdenkennung()?->getKennung()?->setCode($code);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactChannelListURI(string $uri, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)?->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)?->getErreichbarkeit()[$index]->getKanal()?->setListURI($uri);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactChannelListVersionID(string $id, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)?->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)?->getErreichbarkeit()[$index]->getKanal()?->setCode($id);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactChannelCode(string $code, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)?->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)?->getErreichbarkeit()[$index]->getKanal()?->setCode($code);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactChannelName(string $name, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)?->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)?->getErreichbarkeit()[$index]->getKanal()?->setName($name);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentContactLabel(string $label, string $agentType, int $index = 0): static
    {
        if (!isset($this->getAgent($agentType)?->getErreichbarkeit()[$index])) {
            $this->newAgentKommunikationType($index, $agentType);
        }
        $this->getAgent($agentType)?->getErreichbarkeit()[$index]->setKennung($label);

        return $this;
    }

    /**
     * @return $this
     */
    public function setMessageIdentificationListURI(string $uri): static
    {
        $this->head->getIdentifikationNachricht()?->getNachrichtentyp()?->setListURI($uri);

        return $this;
    }

    /**
     * @param string $id
     *
     * @return $this
     */
    public function setMessageIdenfificationListVersionId(string $id): static
    {
        $this->head->getIdentifikationNachricht()?->getNachrichtentyp()?->setCode($id);

        return $this;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setAgentAddressBuildingNumber($number, string $agentType): static
    {
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setWohnort($municipal);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAgencyName(string $name, string $agentType): static
    {
        $this->getAgent($agentType)?->setName($name);

        return $this;
    }

    /**
     * @param $letter
     *
     * @return $this
     */
    public function setAgentAddressBuildingAdditionalLetter($letter, string $agentType): static
    {
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setZusatzangaben($info);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAgencyIdentificationPrefixListVersionId(string $versionId, string $agentType): static
    {
        $this->getAgent($agentType)?->getVerzeichnisdienst()?->setListVersionID($versionId);

        return $this;
    }

    /**
     * @param $versionId
     *
     * @return $this
     */
    public function setAgentAgencyIdentificationLabelListVersionID($versionId, string $agentType): static
    {
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
        $this->getAgent($agentType)->getBehoerdenkennung()?->getKennung()?->setName($name);

        return $this;
    }

    /**
     * @return $this
     */
    public function setAgentAddition(string $addition, string $agentType, int $index = 0): static
    {
        $this->getAgent($agentType)?->getErreichbarkeit()[$index]->setZusatz($addition);

        return $this;
    }

    /**
     * @param $number
     *
     * @return $this
     */
    public function setAgentAddressBuildingNumberBis($number, string $agentType): static
    {
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->getHausnummernBis()?->setTeilnummerderhausnummerBis($number);

        return $this;
    }

    private function newAgentKommunikationType(int $index, string $agentType): void
    {
        $erreichbarkeitList = $this->getAgent($agentType)?->getErreichbarkeit();
        $erreichbarkeit = new Erreichbarkeit();
        $codeKommunikationKanal = new CodeKommunikationKanalTypeType();
        $erreichbarkeit->setKanal($codeKommunikationKanal);
        $erreichbarkeitList[$index] = $erreichbarkeit;
        $this->getAgent($agentType)?->setErreichbarkeit($erreichbarkeitList);
    }

    private function getAgent(string $agentType): null|Leser|Autor
    {
        return 'reader' === $agentType ? $this->head->getLeser() : $this->head->getAutor();
    }

    /**
     * @return $this
     */
    public function setAgentApartmentOwner(string $owner, string $agentType): static
    {
        // todo: check if not longer needed (dont exist in Leser/Author)
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
        // todo: check if not longer needed (dont exist in Leser/Author)
        $this->getAgent($agentType)->getAnschrift()?->getGebaeude()?->setWohnortFruehererGemeindename($corporation);

        return $this;
    }
}
