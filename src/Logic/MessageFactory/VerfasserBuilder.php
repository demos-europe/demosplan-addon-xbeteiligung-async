<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AllgemeinerNameType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeErreichbarkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\KommunikationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameNatuerlichePersonType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\OrganisationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\VerfasserType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;

class VerfasserBuilder
{
    private const PRIVATE_PERSON = 'Privatperson';

    public function buildVerfasser(StatementCreated $statementCreated): VerfasserType
    {
        $verfasser = new VerfasserType();

        $this->setVerfasserType($verfasser, $statementCreated);
        $this->setPersonalDetails($verfasser, $statementCreated);
        $this->setAddress($verfasser, $statementCreated);

        return $verfasser;
    }

    private function setVerfasserType(VerfasserType $verfasser, StatementCreated $statementCreated): void
    {
        if ($this->getTypeOfPerson($statementCreated)) {
            $verfasser->setPrivatperson(true);
        } else {
            $organisation = new OrganisationType();
            $organisation->setName($statementCreated->getMeta()->getOrgaName());
            $verfasser->setOrganisation($organisation);
        }
    }

    private function setPersonalDetails(VerfasserType $verfasser, StatementCreated $statementCreated): void
    {
        $user = $statementCreated->getUser();
        $naturalPerson = new NameNatuerlichePersonType();

        if (null !== $user && self::PRIVATE_PERSON !== $user->getFirstname()) {
            $this->setPersonalDetailsFromUser($naturalPerson, $user);
        } else {
            $this->setPersonalDetailsFromMeta($naturalPerson, $statementCreated);
        }

        $verfasser->setName($naturalPerson);
    }

    private function setPersonalDetailsFromUser(NameNatuerlichePersonType $naturalPerson, $user): void
    {
        if (!empty($user->getTitle())) {
            $naturalPerson->setTitel($user->getTitle());
        }

        if (!empty($user->getGender())) {
            $naturalPerson->setAnrede($user->getGender());
        }

        $firstName = $this->createAllgemeinerName($user->getFirstname());
        $lastName = $this->createAllgemeinerName($user->getLastname());

        $naturalPerson->setVorname($firstName);
        $naturalPerson->setFamilienname($lastName);
    }

    private function setPersonalDetailsFromMeta(NameNatuerlichePersonType $naturalPerson, StatementCreated $statementCreated): void
    {
        $authorName = $statementCreated->getMeta()->getAuthorName();
        $firstName = $this->createAllgemeinerName($authorName);
        $lastName = $this->createAllgemeinerName($authorName);

        $naturalPerson->setVorname($firstName);
        $naturalPerson->setFamilienname($lastName);
    }

    private function createAllgemeinerName(string $name): AllgemeinerNameType
    {
        $nameType = new AllgemeinerNameType();
        $nameType->setName($name);

        return $nameType;
    }

    private function setAddress(VerfasserType $verfasser, StatementCreated $statementCreated): void
    {
        $user = $statementCreated->getUser();

        if (null !== $user && self::PRIVATE_PERSON !== $user->getFirstname()) {
            $this->setAddressFromUser($verfasser, $user);
        } else {
            $this->setAddressFromMeta($verfasser, $statementCreated->getMeta());
        }
    }

    private function setAddressFromUser(VerfasserType $verfasser, $user): void
    {
        $address = new AnschriftType();
        $hasAddressData = false;
        $userAddress = $user->getAddress();

        if (!empty($userAddress->getStreet())) {
            $address->setStrasse($userAddress->getStreet());
            $hasAddressData = true;
        }

        if (!empty($userAddress->getHouseNumber())) {
            $address->setHausnummer($userAddress->getHouseNumber());
            $hasAddressData = true;
        }

        if (!empty($userAddress->getPostalCode())) {
            $address->setPostfach($userAddress->getPostalCode());
            $hasAddressData = true;
        }

        if (!empty($userAddress->getCity())) {
            $address->setOrt($userAddress->getCity());
            $hasAddressData = true;
        }

        if ($hasAddressData) {
            $verfasser->setAnschrift($address);
        }
    }

    private function setAddressFromMeta(VerfasserType $verfasser, $meta): void
    {
        $address = new AnschriftType();
        $hasAddressData = false;

        if (!empty($meta->getOrgaStreet())) {
            $address->setStrasse($meta->getOrgaStreet());
            $hasAddressData = true;
        }

        if (!empty($meta->getHouseNumber())) {
            $address->setHausnummer($meta->getHouseNumber());
            $hasAddressData = true;
        }

        if (!empty($meta->getOrgaPostalCode())) {
            $address->setPostfach($meta->getOrgaPostalCode());
            $hasAddressData = true;
        }

        if (!empty($meta->getOrgaCity())) {
            $address->setOrt($meta->getOrgaCity());
            $hasAddressData = true;
        }

        if ($hasAddressData) {
            $verfasser->setAnschrift($address);
        }
    }

    private function getTypeOfPerson(StatementCreated $statementCreated): bool
    {
        return self::PRIVATE_PERSON === $statementCreated->getMeta()->getOrgaName();
    }
}
