<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\MessageFactory\MessageComponentsBuilders;

use DemosEurope\DemosplanAddon\Contracts\Entities\AddressInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\VerfasserType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class VerfasserBuilderTest extends TestCase
{
    private VerfasserBuilder $verfasserBuilder;
    private MockObject|StatementCreated $statementCreated;
    private MockObject|UserInterface $user;
    private MockObject|StatementMetaInterface $meta;
    private MockObject|AddressInterface $address;

    protected function setUp(): void
    {
        $this->verfasserBuilder = new VerfasserBuilder();
        $this->statementCreated = $this->createMock(StatementCreated::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->meta = $this->createMock(StatementMetaInterface::class);
        $this->address = $this->createMock(AddressInterface::class);
    }

    private function setupBasicMocks(): void
    {
        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn($this->user);
    }

    private function setupUserWithName(string $firstName, string $lastName, string $title = ''): void
    {
        $this->user->method('getFirstname')->willReturn($firstName);
        $this->user->method('getLastname')->willReturn($lastName);
        $this->user->method('getTitle')->willReturn($title);
        $this->user->method('getAddress')->willReturn($this->address);
    }

    private function setupAddressData(string $street = '', string $houseNumber = '', string $postalCode = '', string $city = ''): void
    {
        $this->address->method('getStreet')->willReturn($street);
        $this->address->method('getHouseNumber')->willReturn($houseNumber);
        $this->address->method('getPostalCode')->willReturn($postalCode);
        $this->address->method('getCity')->willReturn($city);
    }

    private function setupEmptyAddressMocks(): void
    {
        $this->setupAddressData();
    }

    private function setupMetaAddressData(string $street = '', string $houseNumber = '', string $postalCode = '', string $city = ''): void
    {
        $this->meta->method('getOrgaStreet')->willReturn($street);
        $this->meta->method('getHouseNumber')->willReturn($houseNumber);
        $this->meta->method('getOrgaPostalCode')->willReturn($postalCode);
        $this->meta->method('getOrgaCity')->willReturn($city);
    }

    private function setupEmptyMetaAddressMocks(): void
    {
        $this->setupMetaAddressData();
    }

    private function assertBasicVerfasser(VerfasserType $verfasser): void
    {
        $this->assertInstanceOf(VerfasserType::class, $verfasser);
    }

    private function callPrivateBuildVerfasser(StatementCreated $statementCreated): VerfasserType
    {
        $reflection = new ReflectionClass($this->verfasserBuilder);
        $method = $reflection->getMethod('buildVerfasser');
        $method->setAccessible(true);

        return $method->invoke($this->verfasserBuilder, $statementCreated);
    }

    public function testBuildVerfasserWithPrivatePerson(): void
    {
        $this->meta->method('getOrgaName')->willReturn('Privatperson');
        $this->meta->method('getAuthorName')->willReturn('John Doe');
        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn(null);

        $verfasser = $this->callPrivateBuildVerfasser($this->statementCreated);

        $this->assertBasicVerfasser($verfasser);
        $this->assertTrue($verfasser->getPrivatperson());
        $this->assertNull($verfasser->getOrganisation());
    }

    public function testBuildVerfasserWithOrganisation(): void
    {
        $orgaName = 'Test Organization';

        $this->meta->method('getOrgaName')->willReturn($orgaName);
        $this->meta->method('getAuthorName')->willReturn('John Doe');
        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn(null);

        $verfasser = $this->callPrivateBuildVerfasser($this->statementCreated);

        $this->assertBasicVerfasser($verfasser);
        $this->assertNull($verfasser->getPrivatperson());
        $this->assertNotNull($verfasser->getOrganisation());
        $this->assertEquals($orgaName, $verfasser->getOrganisation()->getName());
    }

    public function testBuildVerfasserWithUserData(): void
    {
        $this->setupUserWithName('John', 'Doe');
        $this->setupAddressData('Main Street', '123', '12345', 'Test City');
        $this->meta->method('getOrgaName')->willReturn('Test Org');
        $this->setupBasicMocks();

        $verfasser = $this->callPrivateBuildVerfasser($this->statementCreated);

        $this->assertBasicVerfasser($verfasser);
        $this->assertEquals('John', $verfasser->getName()->getVorname()->getName());
        $this->assertEquals('Doe', $verfasser->getName()->getFamilienname()->getName());
        $this->assertNotNull($verfasser->getAnschrift());
        $this->assertEquals('Main Street', $verfasser->getAnschrift()->getStrasse());
        $this->assertEquals('123', $verfasser->getAnschrift()->getHausnummer());
        $this->assertEquals('12345', $verfasser->getAnschrift()->getPostfach());
        $this->assertEquals('Test City', $verfasser->getAnschrift()->getOrt());
    }

    public function testBuildVerfasserWithPrivatePersonUser(): void
    {
        $this->user->method('getFirstname')->willReturn('Privatperson');
        $this->user->method('getAddress')->willReturn($this->address);
        $this->setupEmptyAddressMocks();

        $this->meta->method('getOrgaName')->willReturn('Privatperson');
        $this->meta->method('getAuthorName')->willReturn('Anonymous User');
        $this->setupMetaAddressData('Meta Street', '456', '54321', 'Meta City');
        $this->setupBasicMocks();

        $verfasser = $this->callPrivateBuildVerfasser($this->statementCreated);

        $this->assertBasicVerfasser($verfasser);
        $this->assertTrue($verfasser->getPrivatperson());
        $this->assertEquals('Anonymous User', $verfasser->getName()->getVorname()->getName());
        $this->assertEquals('Anonymous User', $verfasser->getName()->getFamilienname()->getName());
        $this->assertNotNull($verfasser->getAnschrift());
        $this->assertEquals('Meta Street', $verfasser->getAnschrift()->getStrasse());
        $this->assertEquals('456', $verfasser->getAnschrift()->getHausnummer());
        $this->assertEquals('54321', $verfasser->getAnschrift()->getPostfach());
        $this->assertEquals('Meta City', $verfasser->getAnschrift()->getOrt());
    }

    public function testBuildVerfasserWithoutAddressData(): void
    {
        $this->setupUserWithName('John', 'Doe');
        $this->setupEmptyAddressMocks();
        $this->meta->method('getOrgaName')->willReturn('Test Org');
        $this->setupEmptyMetaAddressMocks();
        $this->setupBasicMocks();

        $verfasser = $this->callPrivateBuildVerfasser($this->statementCreated);

        $this->assertBasicVerfasser($verfasser);
        $this->assertNull($verfasser->getAnschrift());
        $this->assertNull($verfasser->getName()->getTitel());
    }

    public function testBuildVerfasserWithPartialAddressData(): void
    {
        $this->setupUserWithName('John', 'Doe');
        $this->setupAddressData('Main Street', '', '', 'Test City');
        $this->meta->method('getOrgaName')->willReturn('Test Org');
        $this->setupBasicMocks();

        $verfasser = $this->callPrivateBuildVerfasser($this->statementCreated);

        $this->assertBasicVerfasser($verfasser);
        $this->assertNotNull($verfasser->getAnschrift());
        $this->assertEquals('Main Street', $verfasser->getAnschrift()->getStrasse());
        $this->assertEquals('Test City', $verfasser->getAnschrift()->getOrt());
        $this->assertNull($verfasser->getAnschrift()->getHausnummer());
        $this->assertNull($verfasser->getAnschrift()->getPostfach());
    }

    public function testBuildVerfasserWithMetaDataOnly(): void
    {
        $this->meta->method('getOrgaName')->willReturn('Meta Organization');
        $this->meta->method('getAuthorName')->willReturn('Meta Author');
        $this->setupMetaAddressData('Meta Street', '789', '67890', 'Meta City');

        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn(null);

        $verfasser = $this->callPrivateBuildVerfasser($this->statementCreated);

        $this->assertBasicVerfasser($verfasser);
        $this->assertNull($verfasser->getPrivatperson());
        $this->assertNotNull($verfasser->getOrganisation());
        $this->assertEquals('Meta Organization', $verfasser->getOrganisation()->getName());
        $this->assertNull($verfasser->getName()->getVorname());
        $this->assertEquals('Meta Author', $verfasser->getName()->getFamilienname()->getName());
        $this->assertNotNull($verfasser->getAnschrift());
        $this->assertEquals('Meta Street', $verfasser->getAnschrift()->getStrasse());
        $this->assertEquals('789', $verfasser->getAnschrift()->getHausnummer());
        $this->assertEquals('67890', $verfasser->getAnschrift()->getPostfach());
        $this->assertEquals('Meta City', $verfasser->getAnschrift()->getOrt());
    }
}
