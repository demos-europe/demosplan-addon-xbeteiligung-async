<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders;

use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AllgemeinerNameType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\AnschriftType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameNatuerlichePersonType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\OrganisationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\VerfasserType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;

class VerfasserBuilder
{
    private const PRIVATE_PERSON = 'Privatperson';

    public function setVerfasser(StatementCreated $statementCreated, StellungnahmeType $statement): void
    {
        $verfasser = $this->buildVerfasser($statementCreated);
        $statement->setVerfasser($verfasser);
    }

    private function buildVerfasser(StatementCreated $statementCreated): VerfasserType
    {
        $verfasser = new VerfasserType();

        $this->setVerfasserType($verfasser, $statementCreated);
        $this->setPersonalDetails($verfasser, $statementCreated);
        $this->setAddress($verfasser, $statementCreated);

        return $verfasser;
    }

    private function setVerfasserType(VerfasserType $verfasser, StatementCreated $statementCreated): void
    {
        if ($this->isPrivatePerson($statementCreated)) {
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

    private function setPersonalDetailsFromUser(NameNatuerlichePersonType $naturalPerson, UserInterface $user): void
    {
        if (!empty($user->getTitle())) {
            $naturalPerson->setTitel($user->getTitle());
        }

        $firstName = $this->createAllgemeinerName($user->getFirstname());
        $lastName = $this->createAllgemeinerName($user->getLastname());

        $naturalPerson->setVorname($firstName);
        $naturalPerson->setFamilienname($lastName);
    }

    private function setPersonalDetailsFromMeta(NameNatuerlichePersonType $naturalPerson, StatementCreated $statementCreated): void
    {
        $authorName = $statementCreated->getMeta()->getAuthorName();
        $lastName = $this->createAllgemeinerName($authorName);
        $vorname = $this->createAllgemeinerName('');

        $naturalPerson->setFamilienname($lastName);
        $naturalPerson->setVorname($vorname);
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

    private function setAddressFromUser(VerfasserType $verfasser, UserInterface $user): void
    {
        $address = new AnschriftType();
        $hasAddressData = false;
        $userAddress = $user->getAddress();

        if (null !== $userAddress)
        {
            if ('' !== $userAddress->getStreet()) {
                $address->setStrasse($userAddress->getStreet());
                $hasAddressData = true;
            }

            if ('' !== $userAddress->getHouseNumber()) {
                $address->setHausnummer($userAddress->getHouseNumber());
                $hasAddressData = true;
            }

            if ('' !== $userAddress->getPostalcode()) {
                $address->setPostfach($userAddress->getPostalcode());
                $hasAddressData = true;
            }

            if ('' !== $userAddress->getCity()) {
                $address->setOrt($userAddress->getCity());
                $hasAddressData = true;
            }
        }

        if ($hasAddressData) {
            $verfasser->setAnschrift($address);
        }
    }

    private function setAddressFromMeta(VerfasserType $verfasser, StatementMetaInterface $meta): void
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

    public function isPrivatePerson(StatementCreated $statementCreated): bool
    {
        return self::PRIVATE_PERSON === $statementCreated->getMeta()->getOrgaName();
    }
}

