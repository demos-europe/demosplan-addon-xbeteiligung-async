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

    public function testMarkK3MessageAsDelivered(): void
    {
        $audit = new XBeteiligungMessageAudit();
        $audit->setDirection('sent')
            ->setTargetSystem('k3')
            ->setMessageType('kommunal.Initiieren.0401')
            ->setStatus('pending');

        $this->auditRepository->expects($this->exactly(2))
            ->method('get')
            ->with('audit-123')
            ->willReturn($audit);

        $this->auditRepository->expects($this->once())
            ->method('save')
            ->with($audit);

        $result = $this->service->markK3MessageAsDelivered('audit-123');
        self::assertTrue($result);
        self::assertSame('sent', $audit->getStatus());
    }

    public function testMarkK3MessageAsDeliveredAlreadyDelivered(): void
    {
        $audit = new XBeteiligungMessageAudit();
        $audit->setDirection('sent')
            ->setTargetSystem('k3')
            ->setMessageType('kommunal.Initiieren.0401')
            ->setStatus('sent');

        $this->auditRepository->expects($this->once())
            ->method('get')
            ->with('audit-123')
            ->willReturn($audit);

        $this->auditRepository->expects($this->never())
            ->method('save');

        $result = $this->service->markK3MessageAsDelivered('audit-123');
        self::assertTrue($result);
    }

    public function testMarkK3MessageAsDeliveredNotFound(): void
    {
        $this->auditRepository->expects($this->once())
            ->method('get')
            ->with('audit-404')
            ->willReturn(null);

        $result = $this->service->markK3MessageAsDelivered('audit-404');
        self::assertFalse($result);
    }

    public function testMarkK3MessageAsDeliveredWrongTargetSystem(): void
    {
        $audit = new XBeteiligungMessageAudit();
        $audit->setDirection('sent')
            ->setTargetSystem('cockpit')
            ->setMessageType('kommunal.Initiieren.0401')
            ->setStatus('pending');

        $this->auditRepository->expects($this->once())
            ->method('get')
            ->with('audit-123')
            ->willReturn($audit);

        $result = $this->service->markK3MessageAsDelivered('audit-123');
        self::assertFalse($result);
    }

    public function testMarkK3MessageAsDeliveredWrongDirection(): void
    {
        $audit = new XBeteiligungMessageAudit();
        $audit->setDirection('received')
            ->setTargetSystem('k3')
            ->setMessageType('kommunal.Initiieren.0401')
            ->setStatus('pending');

        $this->auditRepository->expects($this->once())
            ->method('get')
            ->with('audit-123')
            ->willReturn($audit);

        $result = $this->service->markK3MessageAsDelivered('audit-123');
        self::assertFalse($result);
    }
}
