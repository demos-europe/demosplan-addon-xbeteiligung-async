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
use Exception;
use InvalidArgumentException;
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
     * Test successful mapping of federal state code to customer.
     */
    public function testGetCustomerByFederalStateCodeSuccess(): void
    {
        $customerMock = $this->createMock(CustomerInterface::class);
        $customerMock->method('getId')->willReturn('customer-123');

        $this->customerService
            ->expects($this->once())
            ->method('findCustomerBySubdomain')
            ->with('hh')
            ->willReturn($customerMock);

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with(
                'Successfully mapped federal state code to customer',
                [
                    'federal_state_code' => '02',
                    'subdomain' => 'hh',
                    'customer_id' => 'customer-123'
                ]
            );

        $result = $this->sut->getCustomerByFederalStateCode('02');

        self::assertSame($customerMock, $result);
    }

    /**
     * Test all valid federal state codes are mapped correctly.
     */
    public function testGetCustomerByFederalStateCodeAllValidCodes(): void
    {
        $validMappings = [
            '01' => 'sh', // Schleswig-Holstein
            '02' => 'hh', // Hamburg
            '03' => 'ni', // Niedersachsen
            '04' => 'hb', // Bremen
            '05' => 'nw', // Nordrhein-Westfalen
            '06' => 'he', // Hessen
            '07' => 'rp', // Rheinland-Pfalz
            '08' => 'bw', // Baden-Württemberg
            '09' => 'by', // Bayern
            '10' => 'sl', // Saarland
            '11' => 'be', // Berlin
            '12' => 'bb', // Brandenburg
            '13' => 'mv', // Mecklenburg-Vorpommern
            '14' => 'sn', // Sachsen
            '15' => 'st', // Sachsen-Anhalt
            '16' => 'th', // Thüringen
        ];

        $customerMock = $this->createMock(CustomerInterface::class);
        $customerMock->method('getId')->willReturn('test-customer-id');

        // Use any() for flexible mock matching since some subdomains are called multiple times
        $this->customerService
            ->method('findCustomerBySubdomain')
            ->willReturn($customerMock);

        $this->logger
            ->method('info');

        foreach ($validMappings as $federalStateCode => $expectedSubdomain) {
            $result = $this->sut->getCustomerByFederalStateCode((string) $federalStateCode);
            self::assertSame($customerMock, $result);
        }

        // Verify that the service was called the expected number of times
        $this->addToAssertionCount(count($validMappings));
    }

    /**
     * Test exception thrown for invalid federal state code.
     */
    public function testGetCustomerByFederalStateCodeInvalidCode(): void
    {
        $this->customerService
            ->expects($this->never())
            ->method('findCustomerBySubdomain');

        $this->logger
            ->expects($this->never())
            ->method('info');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('No subdomain mapping found for federal state code: 17');

        $this->sut->getCustomerByFederalStateCode('17');
    }

    /**
     * Test various invalid federal state codes.
     *
     * @dataProvider invalidFederalStateCodeProvider
     */
    public function testGetCustomerByFederalStateCodeVariousInvalidCodes(string $invalidCode): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("No subdomain mapping found for federal state code: {$invalidCode}");

        $this->sut->getCustomerByFederalStateCode($invalidCode);
    }

    /**
     * Data provider for invalid federal state codes.
     */
    public static function invalidFederalStateCodeProvider(): array
    {
        return [
            'zero_padding' => ['00'],
            'out_of_range_high' => ['17'],
            'much_too_high' => ['20'],
            'way_too_high' => ['50'],
            'non_numeric' => ['XX'],
            'empty_string' => [''],
            'single_digit' => ['1'],
            'three_digits' => ['001'],
        ];
    }

    /**
     * Test exception propagation when customer service throws exception.
     */
    public function testGetCustomerByFederalStateCodeCustomerServiceException(): void
    {
        $serviceException = new Exception('Customer not found in database');

        $this->customerService
            ->expects($this->once())
            ->method('findCustomerBySubdomain')
            ->with('hh')
            ->willThrowException($serviceException);

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with(
                'Customer not found for federal state code',
                [
                    'federal_state_code' => '02',
                    'subdomain' => 'hh',
                    'error' => 'Customer not found in database'
                ]
            );

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Customer not found in database');

        $this->sut->getCustomerByFederalStateCode('02');
    }

    /**
     * Test logging behavior for different scenarios.
     */
    public function testLoggingBehavior(): void
    {
        // Test success logging
        $customerMock = $this->createMock(CustomerInterface::class);
        $customerMock->method('getId')->willReturn('customer-456');

        $this->customerService
            ->method('findCustomerBySubdomain')
            ->willReturn($customerMock);

        $this->logger
            ->expects($this->once())
            ->method('info')
            ->with(
                'Successfully mapped federal state code to customer',
                self::callback(fn ($context)
                => isset($context['federal_state_code'], $context['subdomain'], $context['customer_id'])
                    && '09' === $context['federal_state_code']
                    && 'by' === $context['subdomain']
                    && 'customer-456' === $context['customer_id']
                )
            );

        $result = $this->sut->getCustomerByFederalStateCode('09');
        self::assertSame($customerMock, $result);
    }

    /**
     * Test error logging when customer service fails.
     */
    public function testErrorLoggingOnFailure(): void
    {
        $serviceException = new Exception('Database connection failed');

        $this->customerService
            ->method('findCustomerBySubdomain')
            ->willThrowException($serviceException);

        $this->logger
            ->expects($this->once())
            ->method('error')
            ->with(
                'Customer not found for federal state code',
                self::callback(fn ($context)
                => isset($context['federal_state_code'], $context['subdomain'], $context['error'])
                    && '06' === $context['federal_state_code']
                    && 'he' === $context['subdomain']
                    && 'Database connection failed' === $context['error']
                )
            );

        $this->expectException(Exception::class);

        $this->sut->getCustomerByFederalStateCode('06');
    }
}
