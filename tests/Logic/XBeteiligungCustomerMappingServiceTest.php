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
            'Schleswig-Holstein' => ['02011000000', 'sh'],  // 02 01 1000000
            'Hamburg' => ['02020000000', 'hh'],             // 02 02 0000000
            'Niedersachsen' => ['02031000000', 'ni'],       // 02 03 1000000
            'Bremen' => ['02040000000', 'hb'],              // 02 04 0000000
            'Nordrhein-Westfalen' => ['02051000000', 'nw'], // 02 05 1000000
            'Hessen' => ['02061000000', 'he'],              // 02 06 1000000
            'Rheinland-Pfalz' => ['02071000000', 'rp'],     // 02 07 1000000
            'Baden-Württemberg' => ['02081000000', 'bw'],   // 02 08 1000000
            'Bayern' => ['02091000000', 'by'],              // 02 09 1000000
            'Saarland' => ['02101000000', 'sl'],            // 02 10 1000000
            'Berlin' => ['02111000000', 'be'],              // 02 11 1000000
            'Brandenburg' => ['02121000000', 'bb'],         // 02 12 1000000
            'Mecklenburg-Vorpommern' => ['02131000000', 'mv'], // 02 13 1000000
            'Sachsen' => ['02141000000', 'sn'],             // 02 14 1000000
            'Sachsen-Anhalt' => ['02151000000', 'st'],      // 02 15 1000000
            'Thüringen' => ['02161000000', 'th'],           // 02 16 1000000
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
            'Hamburg full code' => ['02020000000', '02'],    // 02 02 0000000 -> 02
            'Bayern full code' => ['02091000000', '09'],     // 02 09 1000000 -> 09
            'Berlin full code' => ['02111000000', '11'],     // 02 11 1000000 -> 11
            'Short code still works' => ['0205', '05'],      // 02 05 -> 05
            'Minimum required digits' => ['0216', '16'],     // 02 16 -> 16
        ];
    }

    public function testGetCustomerByAgsCodeSuccess(): void
    {
        $agsCode = '02020000000'; // Hamburg (02 02 0000000)
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
        $agsCode = '02020000000'; // Hamburg (02 02 0000000)
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
            'Too short' => ['01', 'XöV-Kennung-Code must be at least 4 characters long'],
            'Three digits' => ['012', 'XöV-Kennung-Code must be at least 4 characters long'],
            'Empty string' => ['', 'XöV-Kennung-Code must be at least 4 characters long'],
            'Invalid federal state' => ['99001000000', 'No subdomain mapping found for federal state code: 00'],
            'Zero federal state' => ['00001000000', 'No subdomain mapping found for federal state code: 00'],
            'High federal state' => ['17001000000', 'No subdomain mapping found for federal state code: 00'],
        ];
    }

    public function testGetCustomerByAgsCodeInvalidCode(): void
    {
        $invalidAgsCode = '99001000000';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('No subdomain mapping found for federal state code: 00');

        $this->sut->getCustomerByAgsCode($invalidAgsCode);
    }

    public function testExtractFederalStateCodeSuccess(): void
    {
        // Should not throw exception for valid codes
        self::assertSame('02', $this->sut->extractFederalStateCode('02020000000'));
        self::assertSame('01', $this->sut->extractFederalStateCode('0201'));
        self::assertSame('16', $this->sut->extractFederalStateCode('02161000000'));
    }

    public function testExtractFederalStateCodeFailure(): void
    {
        // Test only the length validation in extractFederalStateCode
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('XöV-Kennung-Code must be at least 4 characters long');

        $this->sut->extractFederalStateCode('01');
    }
}
