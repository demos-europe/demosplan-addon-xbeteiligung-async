<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedurePhaseDefinitionServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungPhaseDefinitionCodeMapping;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper\ProcedurePhaseDefinitionCodeResolver;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeMappingRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProcedurePhaseDefinitionCodeResolverTest extends TestCase
{
    private ProcedurePhaseDefinitionCodeResolver $sut;
    private XBeteiligungPhaseDefinitionCodeMappingRepository&MockObject $mappingRepository;
    private ProcedurePhaseDefinitionServiceInterface&MockObject $phaseDefinitionService;
    private LoggerInterface&MockObject $logger;
    private CustomerInterface&MockObject $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mappingRepository = $this->createMock(XBeteiligungPhaseDefinitionCodeMappingRepository::class);
        $this->phaseDefinitionService = $this->createMock(ProcedurePhaseDefinitionServiceInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->customer = $this->createMock(CustomerInterface::class);
        $this->customer->method('getId')->willReturn('customer-1');

        $this->sut = new ProcedurePhaseDefinitionCodeResolver(
            $this->mappingRepository,
            $this->phaseDefinitionService,
            $this->logger,
        );
    }

    private function createMapping(string $audience, ?string $customerId): XBeteiligungPhaseDefinitionCodeMapping&MockObject
    {
        $otherCustomer = null;
        if (null !== $customerId) {
            $otherCustomer = $this->createMock(CustomerInterface::class);
            $otherCustomer->method('getId')->willReturn($customerId);
        }

        $phaseDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $phaseDefinition->method('getAudience')->willReturn($audience);
        $phaseDefinition->method('getCustomer')->willReturn($otherCustomer);

        $mapping = $this->createMock(XBeteiligungPhaseDefinitionCodeMapping::class);
        $mapping->method('getPhaseDefinition')->willReturn($phaseDefinition);

        return $mapping;
    }

    public function testNullCodeFallsBackToInitialDefinition(): void
    {
        // Arrange
        $initialDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $this->phaseDefinitionService->expects(self::once())
            ->method('findInitialDefinition')
            ->with(StatementInterface::EXTERNAL, $this->customer)
            ->willReturn($initialDefinition);
        $this->mappingRepository->expects(self::never())->method('findByXBeteiligungStandardCode');

        // Act
        $result = $this->sut->resolve(StatementInterface::EXTERNAL, null, $this->customer);

        // Assert
        self::assertSame($initialDefinition, $result);
    }

    public function testEmptyCodeFallsBackToInitialDefinition(): void
    {
        // Arrange
        $initialDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $this->phaseDefinitionService->method('findInitialDefinition')->willReturn($initialDefinition);
        $this->mappingRepository->expects(self::never())->method('findByXBeteiligungStandardCode');

        // Act
        $result = $this->sut->resolve(StatementInterface::EXTERNAL, '', $this->customer);

        // Assert
        self::assertSame($initialDefinition, $result);
    }

    public function testSingleMatchingMappingReturnsItsPhaseDefinition(): void
    {
        // Arrange
        $mapping = $this->createMapping(StatementInterface::EXTERNAL, 'customer-1');
        $this->mappingRepository->expects(self::once())
            ->method('findByXBeteiligungStandardCode')
            ->with('1000')
            ->willReturn([$mapping]);
        $this->phaseDefinitionService->expects(self::never())->method('findInitialDefinition');

        // Act
        $result = $this->sut->resolve(StatementInterface::EXTERNAL, '1000', $this->customer);

        // Assert
        self::assertSame($mapping->getPhaseDefinition(), $result);
    }

    public function testMappingForDifferentCustomerFallsBackToInitialDefinition(): void
    {
        // Arrange
        $mapping = $this->createMapping(StatementInterface::EXTERNAL, 'other-customer');
        $this->mappingRepository->method('findByXBeteiligungStandardCode')->willReturn([$mapping]);

        $initialDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $this->phaseDefinitionService->expects(self::once())
            ->method('findInitialDefinition')
            ->with(StatementInterface::EXTERNAL, $this->customer)
            ->willReturn($initialDefinition);

        // Act
        $result = $this->sut->resolve(StatementInterface::EXTERNAL, '1000', $this->customer);

        // Assert
        self::assertSame($initialDefinition, $result);
    }

    public function testMappingForDifferentAudienceFallsBackToInitialDefinition(): void
    {
        // Arrange
        $mapping = $this->createMapping(StatementInterface::INTERNAL, 'customer-1');
        $this->mappingRepository->method('findByXBeteiligungStandardCode')->willReturn([$mapping]);

        $initialDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $this->phaseDefinitionService->method('findInitialDefinition')->willReturn($initialDefinition);

        // Act
        $result = $this->sut->resolve(StatementInterface::EXTERNAL, '1000', $this->customer);

        // Assert
        self::assertSame($initialDefinition, $result);
    }

    public function testNoMappingFoundFallsBackToInitialDefinition(): void
    {
        // Arrange
        $this->mappingRepository->method('findByXBeteiligungStandardCode')->willReturn([]);

        $initialDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $this->phaseDefinitionService->method('findInitialDefinition')->willReturn($initialDefinition);

        // Act
        $result = $this->sut->resolve(StatementInterface::EXTERNAL, '1000', $this->customer);

        // Assert
        self::assertSame($initialDefinition, $result);
    }

    public function testAmbiguousMappingFallsBackToInitialDefinitionAndLogsWarning(): void
    {
        // Arrange
        $mappingA = $this->createMapping(StatementInterface::EXTERNAL, 'customer-1');
        $mappingB = $this->createMapping(StatementInterface::EXTERNAL, 'customer-1');
        $this->mappingRepository->method('findByXBeteiligungStandardCode')->willReturn([$mappingA, $mappingB]);

        $initialDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $this->phaseDefinitionService->method('findInitialDefinition')->willReturn($initialDefinition);
        $this->logger->expects(self::once())->method('warning');

        // Act
        $result = $this->sut->resolve(StatementInterface::EXTERNAL, '1000', $this->customer);

        // Assert
        self::assertSame($initialDefinition, $result);
    }
}
