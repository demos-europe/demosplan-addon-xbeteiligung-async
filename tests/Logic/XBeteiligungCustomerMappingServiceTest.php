<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CustomerServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungCustomerMappingService;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class XBeteiligungCustomerMappingServiceTest extends TestCase
{
    private const HAMBURG_AGS_CODE = '020200000099';
    private const INVALID_LENGTH_ERROR_MESSAGE = 'XöV-Kennung-Code must be at least 4 characters long';
    private const INVALID_FEDERAL_STATE_ERROR_MESSAGE = 'No subdomain mapping found for federal state code: 99';

    private XBeteiligungCustomerMappingService $sut;
    private CustomerServiceInterface|MockObject $customerService;
    private LoggerInterface|MockObject $logger;

    protected function setUp(): void
    {
        $this->customerService = $this->createMock(CustomerServiceInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sut = new XBeteiligungCustomerMappingService($this->customerService, $this->logger);
    }

    /**
     * @dataProvider validAgsToCustomerMappingProvider
     */
    public function testMapAgsToCustomerSubdomain(string $agsCode, string $expectedSubdomain): void
    {
        $result = $this->sut->mapAgsToCustomerSubdomain($agsCode);

        self::assertSame($expectedSubdomain, $result);
    }

    public static function validAgsToCustomerMappingProvider(): array
    {
        return [
            'Schleswig-Holstein' => ['020100000099', 'sh'],
            'Hamburg' => [self::HAMBURG_AGS_CODE, 'hh'],
            'Niedersachsen' => ['020300000099', 'ni'],
            'Bremen' => ['020400000099', 'hb'],
            'Nordrhein-Westfalen' => ['020500000099', 'nw'],
            'Hessen' => ['020600000099', 'he'],
            'Rheinland-Pfalz' => ['020700000099', 'rp'],
            'Baden-Württemberg' => ['020800000099', 'bw'],
            'Bayern' => ['020900000099', 'by'],
            'Saarland' => ['021000000099', 'sl'],
            'Berlin' => ['021100000099', 'be'],
            'Brandenburg' => ['021200000099', 'bb'],
            'Mecklenburg-Vorpommern' => ['021300000099', 'mv'],
            'Sachsen' => ['021400000099', 'sn'],
            'Sachsen-Anhalt' => ['021500000099', 'st'],
            'Thüringen' => ['021600000099', 'th'],
        ];
    }

    /**
     * @dataProvider federalStateExtractionProvider
     */
    public function testExtractFederalStateCode(string $agsCode, string $expectedFederalState): void
    {
        $result = $this->sut->extractFederalStateCode($agsCode);

        self::assertSame($expectedFederalState, $result);
    }

    public static function federalStateExtractionProvider(): array
    {
        return [
            'Hamburg full code' => [self::HAMBURG_AGS_CODE, '02'],
            'Bayern full code' => ['020900000099', '09'],
            'Berlin full code' => ['021100000099', '11'],
            'Short code still works' => ['0205', '05'],
            'Minimum required digits' => ['0216', '16'],
        ];
    }

    public function testGetCustomerByAgsCodeSuccess(): void
    {
        $agsCode = self::HAMBURG_AGS_CODE;
        $expectedSubdomain = 'hh';

        $customerMock = $this->createMock(CustomerInterface::class);
        $customerMock->method('getId')->willReturn('customer-hh-id');

        $this->customerService
            ->expects($this->once())
            ->method('findCustomerBySubdomain')
            ->with($expectedSubdomain)
            ->willReturn($customerMock);

        $result = $this->sut->getCustomerByAgsCode($agsCode);

        self::assertSame($customerMock, $result);
    }

    public function testGetCustomerByAgsCodeCustomerNotFound(): void
    {
        $agsCode = self::HAMBURG_AGS_CODE;
        $expectedSubdomain = 'hh';

        $exception = new \Exception('Customer not found');

        $this->customerService
            ->expects($this->once())
            ->method('findCustomerBySubdomain')
            ->with($expectedSubdomain)
            ->willThrowException($exception);

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Customer not found');

        $this->sut->getCustomerByAgsCode($agsCode);
    }

    /**
     * @dataProvider invalidAgsCodeProvider
     */
    public function testMapAgsToCustomerSubdomainInvalidAgsCode(string $invalidAgsCode, string $expectedExceptionMessage): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->sut->mapAgsToCustomerSubdomain($invalidAgsCode);
    }

    public static function invalidAgsCodeProvider(): array
    {
        return [
            'Too short' => ['01', self::INVALID_LENGTH_ERROR_MESSAGE],
            'Three digits' => ['012', self::INVALID_LENGTH_ERROR_MESSAGE],
            'Empty string' => ['', self::INVALID_LENGTH_ERROR_MESSAGE],
            'Invalid federal state' => ['029900000099', self::INVALID_FEDERAL_STATE_ERROR_MESSAGE],
        ];
    }

    public function testGetCustomerByAgsCodeInvalidCode(): void
    {
        $invalidAgsCode = '029900000099';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::INVALID_FEDERAL_STATE_ERROR_MESSAGE);

        $this->sut->getCustomerByAgsCode($invalidAgsCode);
    }

    public function testExtractFederalStateCodeSuccess(): void
    {
        // Should not throw exception for valid codes
        self::assertSame('02', $this->sut->extractFederalStateCode(self::HAMBURG_AGS_CODE));
        self::assertSame('01', $this->sut->extractFederalStateCode('0201'));
        self::assertSame('16', $this->sut->extractFederalStateCode('021600000099'));
    }

    public function testExtractFederalStateCodeFailure(): void
    {
        // Test only the length validation in extractFederalStateCode
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(self::INVALID_LENGTH_ERROR_MESSAGE);

        $this->sut->extractFederalStateCode('01');
    }
}
