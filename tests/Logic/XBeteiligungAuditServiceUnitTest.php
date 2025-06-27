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

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungMessageAudit;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungMessageAuditRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/**
 * Basic unit tests for XBeteiligungAuditService
 */
class XBeteiligungAuditServiceUnitTest extends TestCase
{
    private MockObject $auditRepository;
    private MockObject $logger;
    private XBeteiligungAuditService $service;

    protected function setUp(): void
    {
        $this->auditRepository = $this->createMock(XBeteiligungMessageAuditRepository::class);
        $this->logger = $this->createMock(LoggerInterface::class);

        $this->service = new XBeteiligungAuditService(
            $this->auditRepository,
            $this->logger
        );
    }

    public function testConstants(): void
    {
        self::assertSame('received', XBeteiligungAuditService::DIRECTION_RECEIVED);
        self::assertSame('sent', XBeteiligungAuditService::DIRECTION_SENT);
        self::assertSame('cockpit', XBeteiligungAuditService::TARGET_SYSTEM_COCKPIT);
        self::assertSame('k3', XBeteiligungAuditService::TARGET_SYSTEM_K3);
        self::assertSame('pending', XBeteiligungAuditService::STATUS_PENDING);
        self::assertSame('processed', XBeteiligungAuditService::STATUS_PROCESSED);
        self::assertSame('sent', XBeteiligungAuditService::STATUS_SENT);
        self::assertSame('failed', XBeteiligungAuditService::STATUS_FAILED);
    }

    public function testAuditReceivedMessage(): void
    {
        $this->auditRepository->expects($this->once())
            ->method('save')
            ->with(self::isInstanceOf(XBeteiligungMessageAudit::class));

        $this->logger->expects($this->once())
            ->method('info');

        $result = $this->service->auditReceivedMessage(
            '<xml>content</xml>',
            'kommunal.Initiieren.0401',
            'plan-123'
        );

        self::assertInstanceOf(XBeteiligungMessageAudit::class, $result);
        self::assertSame('received', $result->getDirection());
        self::assertSame('cockpit', $result->getTargetSystem());
        self::assertSame('kommunal.Initiieren.0401', $result->getMessageType());
        self::assertSame('<xml>content</xml>', $result->getMessageContent());
        self::assertSame('plan-123', $result->getPlanId());
    }

    public function testAuditSentMessage(): void
    {
        $this->auditRepository->expects($this->once())
            ->method('save')
            ->with(self::isInstanceOf(XBeteiligungMessageAudit::class));

        $this->logger->expects($this->once())
            ->method('info');

        $result = $this->service->auditSentMessage(
            '<xml>statement</xml>',
            'allgemein.stellungnahme.Neuabgegeben.0701',
            'proc-456',
            'plan-123',
            'resp-789',
            'stmt-abc'
        );

        self::assertInstanceOf(XBeteiligungMessageAudit::class, $result);
        self::assertSame('sent', $result->getDirection());
        self::assertSame('cockpit', $result->getTargetSystem());
        self::assertSame('pending', $result->getStatus());
        self::assertSame('stmt-abc', $result->getStatementId());
    }

    public function testAuditK3Message(): void
    {
        $this->auditRepository->expects($this->once())
            ->method('save')
            ->with(self::isInstanceOf(XBeteiligungMessageAudit::class));

        $this->logger->expects($this->once())
            ->method('info');

        $result = $this->service->auditK3Message(
            '<xml>k3 content</xml>',
            'kommunal.Initiieren.0401',
            'proc-456',
            'plan-123'
        );

        self::assertInstanceOf(XBeteiligungMessageAudit::class, $result);
        self::assertSame('sent', $result->getDirection());
        self::assertSame('k3', $result->getTargetSystem());
        self::assertSame('pending', $result->getStatus());
    }

    public function testMarkAsProcessed(): void
    {
        $audit = new XBeteiligungMessageAudit();

        $this->auditRepository->expects($this->once())
            ->method('get')
            ->with('audit-123')
            ->willReturn($audit);

        $this->auditRepository->expects($this->once())
            ->method('save')
            ->with($audit);

        $this->logger->expects($this->once())
            ->method('info');

        $this->service->markAsProcessed('audit-123', 'proc-456');

        self::assertSame('processed', $audit->getStatus());
        self::assertSame('proc-456', $audit->getProcedureId());
        self::assertNotNull($audit->getProcessedAt());
    }

    public function testMarkAsProcessedAuditNotFound(): void
    {
        $this->auditRepository->expects($this->once())
            ->method('get')
            ->with('audit-404')
            ->willReturn(null);

        $this->auditRepository->expects($this->never())
            ->method('save');

        $this->logger->expects($this->once())
            ->method('warning');

        $this->service->markAsProcessed('audit-404');
    }

    public function testFindAuditRecordsByProcedureAndTargetSystem(): void
    {
        $expectedAudits = [new XBeteiligungMessageAudit()];

        $this->auditRepository->expects($this->once())
            ->method('findByProcedureIdAndTargetSystem')
            ->with('proc-123', 'k3')
            ->willReturn($expectedAudits);

        $result = $this->service->findAuditRecordsByProcedureAndTargetSystem('proc-123', 'k3');
        self::assertSame($expectedAudits, $result);
    }

    public function testFindOriginalIncoming401Message(): void
    {
        $audit401Cockpit = new XBeteiligungMessageAudit();
        $audit401Cockpit->setDirection('received')
            ->setTargetSystem('cockpit')
            ->setMessageType('kommunal.Initiieren.0401')
            ->setProcedureId('proc-123');

        $audit401K3 = new XBeteiligungMessageAudit();
        $audit401K3->setDirection('sent')
            ->setTargetSystem('k3')
            ->setMessageType('kommunal.Initiieren.0401')
            ->setProcedureId('proc-123');

        $audits = [$audit401Cockpit, $audit401K3];

        $this->auditRepository->expects($this->once())
            ->method('findByProcedureIdAndTargetSystem')
            ->with('proc-123', 'cockpit')
            ->willReturn($audits);

        $result = $this->service->findOriginalIncoming401Message('proc-123');
        self::assertSame($audit401Cockpit, $result);
    }

    public function testFindOriginalIncoming401MessageNotFound(): void
    {
        $auditOther = new XBeteiligungMessageAudit();
        $auditOther->setDirection('sent')
            ->setTargetSystem('cockpit')
            ->setMessageType('kommunal.Initiieren.OK.0411')
            ->setProcedureId('proc-123');

        $audits = [$auditOther];

        $this->auditRepository->expects($this->once())
            ->method('findByProcedureIdAndTargetSystem')
            ->with('proc-123', 'cockpit')
            ->willReturn($audits);

        $result = $this->service->findOriginalIncoming401Message('proc-123');
        self::assertNull($result);
    }
}
