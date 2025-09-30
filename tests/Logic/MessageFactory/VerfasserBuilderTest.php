<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\MessageFactory;

use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\AddressInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\VerfasserType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

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

    public function testBuildVerfasserWithPrivatePerson(): void
    {
        $this->meta->method('getOrgaName')->willReturn('Privatperson');
        $this->meta->method('getAuthorName')->willReturn('John Doe');
        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn(null);

        $verfasser = $this->verfasserBuilder->buildVerfasser($this->statementCreated);

        $this->assertInstanceOf(VerfasserType::class, $verfasser);
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

        $verfasser = $this->verfasserBuilder->buildVerfasser($this->statementCreated);

        $this->assertInstanceOf(VerfasserType::class, $verfasser);
        $this->assertNull($verfasser->getPrivatperson());
        $this->assertNotNull($verfasser->getOrganisation());
        $this->assertEquals($orgaName, $verfasser->getOrganisation()->getName());
    }

    public function testBuildVerfasserWithUserData(): void
    {
        $this->user->method('getFirstname')->willReturn('John');
        $this->user->method('getLastname')->willReturn('Doe');
        $this->user->method('getAddress')->willReturn($this->address);

        $this->address->method('getStreet')->willReturn('Main Street');
        $this->address->method('getHouseNumber')->willReturn('123');
        $this->address->method('getPostalCode')->willReturn('12345');
        $this->address->method('getCity')->willReturn('Test City');

        $this->meta->method('getOrgaName')->willReturn('Test Org');
        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn($this->user);

        $verfasser = $this->verfasserBuilder->buildVerfasser($this->statementCreated);

        $this->assertInstanceOf(VerfasserType::class, $verfasser);
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
        $this->address->method('getStreet')->willReturn('');
        $this->address->method('getHouseNumber')->willReturn('');
        $this->address->method('getPostalCode')->willReturn('');
        $this->address->method('getCity')->willReturn('');

        $this->meta->method('getOrgaName')->willReturn('Privatperson');
        $this->meta->method('getAuthorName')->willReturn('Anonymous User');
        $this->meta->method('getOrgaStreet')->willReturn('Meta Street');
        $this->meta->method('getHouseNumber')->willReturn('456');
        $this->meta->method('getOrgaPostalCode')->willReturn('54321');
        $this->meta->method('getOrgaCity')->willReturn('Meta City');

        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn($this->user);

        $verfasser = $this->verfasserBuilder->buildVerfasser($this->statementCreated);

        $this->assertInstanceOf(VerfasserType::class, $verfasser);
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
        $this->user->method('getFirstname')->willReturn('John');
        $this->user->method('getLastname')->willReturn('Doe');
        $this->user->method('getAddress')->willReturn($this->address);

        $this->address->method('getStreet')->willReturn('');
        $this->address->method('getHouseNumber')->willReturn('');
        $this->address->method('getPostalCode')->willReturn('');
        $this->address->method('getCity')->willReturn('');

        $this->meta->method('getOrgaName')->willReturn('Test Org');
        $this->meta->method('getOrgaStreet')->willReturn('');
        $this->meta->method('getHouseNumber')->willReturn('');
        $this->meta->method('getOrgaPostalCode')->willReturn('');
        $this->meta->method('getOrgaCity')->willReturn('');

        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn($this->user);

        $verfasser = $this->verfasserBuilder->buildVerfasser($this->statementCreated);

        $this->assertInstanceOf(VerfasserType::class, $verfasser);
        $this->assertNull($verfasser->getAnschrift());
        $this->assertNull($verfasser->getName()->getTitel());
    }

    public function testBuildVerfasserWithPartialAddressData(): void
    {
        $this->user->method('getFirstname')->willReturn('John');
        $this->user->method('getLastname')->willReturn('Doe');
        $this->user->method('getAddress')->willReturn($this->address);

        $this->address->method('getStreet')->willReturn('Main Street');
        $this->address->method('getHouseNumber')->willReturn('');
        $this->address->method('getPostalCode')->willReturn('');
        $this->address->method('getCity')->willReturn('Test City');

        $this->meta->method('getOrgaName')->willReturn('Test Org');
        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn($this->user);

        $verfasser = $this->verfasserBuilder->buildVerfasser($this->statementCreated);

        $this->assertInstanceOf(VerfasserType::class, $verfasser);
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
        $this->meta->method('getOrgaStreet')->willReturn('Meta Street');
        $this->meta->method('getHouseNumber')->willReturn('789');
        $this->meta->method('getOrgaPostalCode')->willReturn('67890');
        $this->meta->method('getOrgaCity')->willReturn('Meta City');

        $this->statementCreated->method('getMeta')->willReturn($this->meta);
        $this->statementCreated->method('getUser')->willReturn(null);

        $verfasser = $this->verfasserBuilder->buildVerfasser($this->statementCreated);

        $this->assertInstanceOf(VerfasserType::class, $verfasser);
        $this->assertNull($verfasser->getPrivatperson());
        $this->assertNotNull($verfasser->getOrganisation());
        $this->assertEquals('Meta Organization', $verfasser->getOrganisation()->getName());
        $this->assertEquals('Meta Author', $verfasser->getName()->getVorname()->getName());
        $this->assertEquals('Meta Author', $verfasser->getName()->getFamilienname()->getName());
        $this->assertNotNull($verfasser->getAnschrift());
        $this->assertEquals('Meta Street', $verfasser->getAnschrift()->getStrasse());
        $this->assertEquals('789', $verfasser->getAnschrift()->getHausnummer());
        $this->assertEquals('67890', $verfasser->getAnschrift()->getPostfach());
        $this->assertEquals('Meta City', $verfasser->getAnschrift()->getOrt());
    }
}
