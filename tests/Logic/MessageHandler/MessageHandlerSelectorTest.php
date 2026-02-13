<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\MessageHandler;

use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\MessageHandlerSelector;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\GenericProcedureMessageHandler;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\GenericStatementResponseHandler;
use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class MessageHandlerSelectorTest extends TestCase
{
    private MessageHandlerSelector $sut;
    private MockObject|GenericProcedureMessageHandler $procedureMessageHandler;
    private MockObject|GenericStatementResponseHandler $statementResponseHandler;

    protected function setUp(): void
    {
        parent::setUp();
        $this->procedureMessageHandler = $this->createMock(GenericProcedureMessageHandler::class);
        $this->statementResponseHandler = $this->createMock(GenericStatementResponseHandler::class);
        $this->sut = new MessageHandlerSelector($this->procedureMessageHandler, $this->statementResponseHandler);
    }

    public function testGetHandlerForKommunalInitiierenReturnsGenericProcedureMessageHandler(): void
    {
        $messageType = XBeteiligungMessageType::KOMMUNAL_INITIIEREN->value;
        $result = $this->sut->getHandlerForMessageType($messageType);
        static::assertSame($this->procedureMessageHandler, $result);
    }

    /*
     * Disable test in the mean time while we do not support this message type
     public function testGetHandlerForKommunalAktualisierenReturnsGenericProcedureMessageHandler(): void
    {
        $messageType = XBeteiligungMessageType::KOMMUNAL_AKTUALISIEREN->value;
        $result = $this->sut->getHandlerForMessageType($messageType);
        static::assertSame($this->procedureMessageHandler, $result);
    }*/

    public function testGetHandlerForPlanfeststellungInitiierenReturnsGenericProcedureMessageHandler(): void
    {
        $messageType = XBeteiligungMessageType::PLANFESTSTELLUNG_INITIIEREN->value;
        $result = $this->sut->getHandlerForMessageType($messageType);
        static::assertSame($this->procedureMessageHandler, $result);
    }

    public function testGetHandlerForStellungnahmeOkReturnsGenericStatementResponseHandler(): void
    {
        $messageType = XBeteiligungMessageType::STELLUNGNAHME_OK->value;
        $result = $this->sut->getHandlerForMessageType($messageType);
        static::assertSame($this->statementResponseHandler, $result);
    }

    public function testGetHandlerForStellungnahmeNokReturnsGenericStatementResponseHandler(): void
    {
        $messageType = XBeteiligungMessageType::STELLUNGNAHME_NOK->value;
        $result = $this->sut->getHandlerForMessageType($messageType);
        static::assertSame($this->statementResponseHandler, $result);
    }


    /**
     * @dataProvider unsupportedMessageTypesProvider
     */
    public function testGetHandlerThrowsExceptionForUnsupportedTypes(string $messageType): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->sut->getHandlerForMessageType($messageType);
    }

    public static function unsupportedMessageTypesProvider(): array
    {
        return [
            'invalid message type' => ['invalid.message.type'],
            'KOMMUNAL_LOESCHEN' => [XBeteiligungMessageType::KOMMUNAL_LOESCHEN->value],
            'RAUMORDNUNG_INITIIEREN' => [XBeteiligungMessageType::RAUMORDNUNG_INITIIEREN->value],
            'RAUMORDNUNG_AKTUALISIEREN' => [XBeteiligungMessageType::RAUMORDNUNG_AKTUALISIEREN->value],
            'RAUMORDNUNG_LOESCHEN' => [XBeteiligungMessageType::RAUMORDNUNG_LOESCHEN->value],
            'PLANFESTSTELLUNG_AKTUALISIEREN' => [XBeteiligungMessageType::PLANFESTSTELLUNG_AKTUALISIEREN->value],
            'PLANFESTSTELLUNG_LOESCHEN' => [XBeteiligungMessageType::PLANFESTSTELLUNG_LOESCHEN->value],
            'UNKNOWN' => [XBeteiligungMessageType::UNKNOWN->value],
            'UNKNOWN_RESPONSE' => [XBeteiligungMessageType::UNKNOWN_RESPONSE->value],
        ];
    }
}
