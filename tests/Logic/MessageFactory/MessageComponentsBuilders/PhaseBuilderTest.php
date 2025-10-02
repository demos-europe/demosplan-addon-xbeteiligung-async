<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\MessageFactory\MessageComponentsBuilders;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\ProjectPrefixNotFoundException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders\PhaseBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionClass;

class PhaseBuilderTest extends TestCase
{
    private PhaseBuilder $phaseBuilder;
    private MockObject|PermissionEvaluatorInterface $permissionEvaluator;
    private MockObject|LoggerInterface $logger;
    private MockObject|GlobalConfigInterface $globalConfig;
    private MockObject|StatementCreated $statementCreated;
    private MockObject|StellungnahmeType $statement;

    protected function setUp(): void
    {
        $this->permissionEvaluator = $this->createMock(PermissionEvaluatorInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->globalConfig = $this->createMock(GlobalConfigInterface::class);
        $this->statementCreated = $this->createMock(StatementCreated::class);
        $this->statement = $this->createMock(StellungnahmeType::class);

        $this->phaseBuilder = new PhaseBuilder(
            $this->permissionEvaluator,
            $this->logger,
            $this->globalConfig
        );
    }

    private function setupStatementCreatedMocks(string $phase = 'evaluating', string $publicStatement = StatementInterface::EXTERNAL): void
    {
        $this->statementCreated->method('getPhase')->willReturn($phase);
        $this->statementCreated->method('getPublicStatement')->willReturn($publicStatement);
    }

    private function setupGlobalConfigMocks(string $internalPhaseName = 'Internal Phase', string $externalPhaseName = 'External Phase'): void
    {
        $this->globalConfig->method('getPhaseNameWithPriorityInternal')->willReturn($internalPhaseName);
        $this->globalConfig->method('getPhaseNameWithPriorityExternal')->willReturn($externalPhaseName);
    }

    private function callPrivateGetPhaseName(StatementCreated $statementCreated): string
    {
        $reflection = new ReflectionClass($this->phaseBuilder);
        $method = $reflection->getMethod('getPhaseName');
        $method->setAccessible(true);

        return $method->invoke($this->phaseBuilder, $statementCreated);
    }

    private function callPrivateCreatePhaseType(): object
    {
        $reflection = new ReflectionClass($this->phaseBuilder);
        $method = $reflection->getMethod('createPhaseType');
        $method->setAccessible(true);

        return $method->invoke($this->phaseBuilder);
    }

    public function testSetVerfahrenschrittWithKommunalPermission(): void
    {
        $this->setupStatementCreatedMocks();
        $this->setupGlobalConfigMocks();
        $this->permissionEvaluator->method('isPermissionEnabled')
            ->willReturnCallback(function($permission) {
                if ($permission->getPermissionName() === 'feature_procedure_message_kom_create') return true;
                return false;
            });

        $this->statement->expects($this->once())->method('setVerfahrensschrittKommunal');
        $this->statement->expects($this->never())->method('setVerfahrensschrittRaumordnung');
        $this->statement->expects($this->never())->method('setVerfahrensschrittPlanfeststellung');

        $this->phaseBuilder->setVerfahrenschritt($this->statementCreated, $this->statement);
    }

    public function testSetVerfahrenschrittWithRaumordnungPermission(): void
    {
        $this->setupStatementCreatedMocks();
        $this->setupGlobalConfigMocks();
        $this->permissionEvaluator->method('isPermissionEnabled')
            ->willReturnCallback(function($permission) {
                if ($permission->getPermissionName() === 'feature_procedure_message_rog_create') return true;
                return false;
            });

        $this->statement->expects($this->never())->method('setVerfahrensschrittKommunal');
        $this->statement->expects($this->once())->method('setVerfahrensschrittRaumordnung');
        $this->statement->expects($this->never())->method('setVerfahrensschrittPlanfeststellung');

        $this->phaseBuilder->setVerfahrenschritt($this->statementCreated, $this->statement);
    }

    public function testSetVerfahrenschrittWithPlanfeststellungPermission(): void
    {
        $this->setupStatementCreatedMocks();
        $this->setupGlobalConfigMocks();
        $this->permissionEvaluator->method('isPermissionEnabled')
            ->willReturnCallback(function($permission) {
                if ($permission->getPermissionName() === 'feature_procedure_message_pln_create') return true;
                return false;
            });

        $this->statement->expects($this->never())->method('setVerfahrensschrittKommunal');
        $this->statement->expects($this->never())->method('setVerfahrensschrittRaumordnung');
        $this->statement->expects($this->once())->method('setVerfahrensschrittPlanfeststellung');

        $this->phaseBuilder->setVerfahrenschritt($this->statementCreated, $this->statement);
    }

    public function testSetVerfahrenschrittThrowsExceptionWhenNoPermission(): void
    {
        $this->setupStatementCreatedMocks();
        $this->permissionEvaluator->method('isPermissionEnabled')->willReturn(false);
        $this->logger->expects($this->once())->method('error');

        $this->expectException(ProjectPrefixNotFoundException::class);
        $this->expectExceptionMessage('No valid procedure type found.');

        $this->phaseBuilder->setVerfahrenschritt($this->statementCreated, $this->statement);
    }

    public function testGetPhaseNameReturnsInternalPhaseNameForInternalStatement(): void
    {
        $this->setupStatementCreatedMocks('evaluating', StatementInterface::INTERNAL);
        $this->setupGlobalConfigMocks('Internal Evaluating Phase', 'External Evaluating Phase');

        $result = $this->callPrivateGetPhaseName($this->statementCreated);

        $this->assertEquals('Internal Evaluating Phase', $result);
    }

    public function testGetPhaseNameReturnsExternalPhaseNameForExternalStatement(): void
    {
        $this->setupStatementCreatedMocks('evaluating', StatementInterface::EXTERNAL);
        $this->setupGlobalConfigMocks('Internal Evaluating Phase', 'External Evaluating Phase');

        $result = $this->callPrivateGetPhaseName($this->statementCreated);

        $this->assertEquals('External Evaluating Phase', $result);
    }

    public function testCreatePhaseTypeReturnsKommunalType(): void
    {
        $this->permissionEvaluator->method('isPermissionEnabled')
            ->willReturnCallback(function($permission) {
                if ($permission->getPermissionName() === 'feature_procedure_message_kom_create') return true;
                return false;
            });

        $result = $this->callPrivateCreatePhaseType();

        $this->assertInstanceOf(CodeVerfahrensschrittKommunalType::class, $result);
    }

    public function testCreatePhaseTypeReturnsRaumordnungType(): void
    {
        $this->permissionEvaluator->method('isPermissionEnabled')
            ->willReturnCallback(function($permission) {
                if ($permission->getPermissionName() === 'feature_procedure_message_rog_create') return true;
                return false;
            });

        $result = $this->callPrivateCreatePhaseType();

        $this->assertInstanceOf(CodeVerfahrensschrittRaumordnungType::class, $result);
    }

    public function testCreatePhaseTypeReturnsPlanfeststellungType(): void
    {
        $this->permissionEvaluator->method('isPermissionEnabled')
            ->willReturnCallback(function($permission) {
                if ($permission->getPermissionName() === 'feature_procedure_message_pln_create') return true;
                return false;
            });

        $result = $this->callPrivateCreatePhaseType();

        $this->assertInstanceOf(CodeVerfahrensschrittPlanfeststellungType::class, $result);
    }

    public function testCreatePhaseTypeThrowsExceptionWhenNoPermission(): void
    {
        $this->permissionEvaluator->method('isPermissionEnabled')->willReturn(false);
        $this->logger->expects($this->once())->method('error');

        $this->expectException(ProjectPrefixNotFoundException::class);

        $this->callPrivateCreatePhaseType();
    }

    public function testSetVerfahrensteilschrittSetsCorrectValues(): void
    {
        $this->setupStatementCreatedMocks();
        $this->setupGlobalConfigMocks('Internal Phase', 'External Phase');

        $this->statement->expects($this->once())
            ->method('setVerfahrensteilschritt')
            ->with($this->callback(function ($verfahrensteilschritt) {
                return $verfahrensteilschritt instanceof \DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensteilschrittType
                    && $verfahrensteilschritt->getCode() === '0815'
                    && $verfahrensteilschritt->getName() === 'External Phase'
                    && $verfahrensteilschritt->getListVersionID() === '3';
            }));

        $this->phaseBuilder->setVerfahrensteilschritt($this->statementCreated, $this->statement);
    }

    public function testSetVerfahrensteilschrittUsesInternalPhaseNameForInternalStatement(): void
    {
        $this->setupStatementCreatedMocks('evaluating', StatementInterface::INTERNAL);
        $this->setupGlobalConfigMocks('Internal Phase', 'External Phase');

        $this->statement->expects($this->once())
            ->method('setVerfahrensteilschritt')
            ->with($this->callback(function ($verfahrensteilschritt) {
                return $verfahrensteilschritt->getName() === 'Internal Phase';
            }));

        $this->phaseBuilder->setVerfahrensteilschritt($this->statementCreated, $this->statement);
    }
}