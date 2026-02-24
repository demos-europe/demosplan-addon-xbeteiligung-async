<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\MessageHandler\Incoming;

use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\PlanIdExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use Exception;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class PlanIdExtractorTest extends TestCase
{
    private PlanIdExtractor $sut;
    private MockObject|XBeteiligungIncomingMessageParser $incomingMessageParser;
    private MockObject|LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->incomingMessageParser = $this->createMock(XBeteiligungIncomingMessageParser::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sut = new PlanIdExtractor($this->incomingMessageParser, $this->logger);
    }

    public function testExtractFromMessageReturnsValidPlanId(): void
    {
        $messageXml = '<xml>sample message</xml>';
        $messageType = XBeteiligungMessageType::KOMMUNAL_INITIIEREN->value;
        $expectedPlanId = 'PLAN_12345';
        $xmlObject = $this->createMockXmlObjectWithPlanId($expectedPlanId);
        $this->incomingMessageParser
            ->expects($this->once())
            ->method('getXmlObject')
            ->with($messageXml, '401')
            ->willReturn($xmlObject);
        $result = $this->sut->extractFromMessage($messageXml, $messageType);
        static::assertSame($expectedPlanId, $result);
    }

    public function testExtractFromMessageReturnsNullWhenPlanIdNotFound(): void
    {
        $messageXml = '<xml>sample message</xml>';
        $messageType = XBeteiligungMessageType::KOMMUNAL_INITIIEREN->value;
        $xmlObject = $this->createMockXmlObjectWithPlanId(null);
        $this->incomingMessageParser
            ->expects($this->once())
            ->method('getXmlObject')
            ->with($messageXml, '401')
            ->willReturn($xmlObject);
        $result = $this->sut->extractFromMessage($messageXml, $messageType);
        static::assertNull($result);
    }

    public function testExtractFromMessageReturnsNullWhenMessageTypeInvalid(): void
    {
        $messageXml = '<xml>sample message</xml>';
        $messageType = 'invalid.message.type';
        $this->incomingMessageParser->expects($this->never())->method('getXmlObject');
        $result = $this->sut->extractFromMessage($messageXml, $messageType);
        static::assertNull($result);
    }

    public function testExtractFromMessageLogsWarningAndReturnsNullOnException(): void
    {
        $messageXml = '<xml>sample message</xml>';
        $messageType = XBeteiligungMessageType::KOMMUNAL_INITIIEREN->value;
        $exceptionMessage = 'XML parsing failed';
        $this->incomingMessageParser
            ->expects($this->once())
            ->method('getXmlObject')
            ->with($messageXml, '401')
            ->willThrowException(new Exception($exceptionMessage));
        $this->logger
            ->expects($this->once())
            ->method('warning')
            ->with(
                'Could not extract planId from message XML',
                [
                    'messageType' => $messageType,
                    'error' => $exceptionMessage
                ]
            );
        $result = $this->sut->extractFromMessage($messageXml, $messageType);
        static::assertNull($result);
    }


    #[DataProvider('messageTypeToCodeProvider')]
    public function testExtractFromMessageHandlesAllSupportedMessageTypes(
        string $messageType,
        string $expectedCode
    ): void {
        $messageXml = '<xml>sample message</xml>';
        $expectedPlanId = 'TEST_PLAN_ID';
        $xmlObject = $this->createMockXmlObjectWithPlanId($expectedPlanId);
        $this->incomingMessageParser
            ->expects($this->once())
            ->method('getXmlObject')
            ->with($messageXml, $expectedCode)
            ->willReturn($xmlObject);
        $result = $this->sut->extractFromMessage($messageXml, $messageType);
        static::assertSame($expectedPlanId, $result);
    }

    public static function messageTypeToCodeProvider(): array
    {
        return [
            'KOMMUNAL_INITIIEREN' => [XBeteiligungMessageType::KOMMUNAL_INITIIEREN->value, '401'],
            'KOMMUNAL_AKTUALISIEREN' => [XBeteiligungMessageType::KOMMUNAL_AKTUALISIEREN->value, '402'],
            'PLANFESTSTELLUNG_INITIIEREN' => [XBeteiligungMessageType::PLANFESTSTELLUNG_INITIIEREN->value, '0201'],
        ];
    }

    public function testExtractFromMessageReturnsNullForUnsupportedMessageTypes(): void
    {
        $messageXml = '<xml>sample message</xml>';
        $messageType = XBeteiligungMessageType::UNKNOWN->value;
        $this->incomingMessageParser->expects($this->never())->method('getXmlObject');
        $result = $this->sut->extractFromMessage($messageXml, $messageType);
        static::assertNull($result);
    }

    private function createMockXmlObjectWithPlanId(?string $planId): NachrichtG2GTypeType
    {
        $beteiligung = new class($planId) {
            public function __construct(private readonly ?string $planId) {}
            public function getPlanID(): ?string { return $this->planId; }
        };
        $nachrichteninhalt = new class($beteiligung) {
            public function __construct(private readonly object $beteiligung) {}
            public function getBeteiligung(): object { return $this->beteiligung; }
        };

        return new class($nachrichteninhalt) extends NachrichtG2GTypeType {
            public function __construct(private readonly object $nachrichteninhalt) {}
            public function getNachrichteninhalt(): object { return $this->nachrichteninhalt; }
        };
    }
}
