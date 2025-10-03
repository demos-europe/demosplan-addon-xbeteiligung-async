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

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming\StatementIdExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenOK0711;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenNOK0721;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenOK0711\AllgemeinStellungnahmeNeuabgegebenOK0711AnonymousPHPType\NachrichteninhaltAnonymousPHPType as OkContentType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenNOK0721\AllgemeinStellungnahmeNeuabgegebenNOK0721AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NokContentType;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StatementIdExtractorTest extends TestCase
{
    private StatementIdExtractor $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $this->sut = new StatementIdExtractor();
    }

    public function testExtractFromXmlWithOK711ReturnsStatementIdWithoutPrefix(): void
    {
        $xmlObject = $this->createMockOK711('ID_12345');
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertSame('12345', $result);
    }

    public function testExtractFromXmlWithNOK721ReturnsStatementIdWithoutPrefix(): void
    {
        $xmlObject = $this->createMockNOK721('ID_67890');
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertSame('67890', $result);
    }

    public function testExtractFromXmlWithoutPrefixReturnsStatementIdUnchanged(): void
    {
        $xmlObject = $this->createMockOK711('12345');
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertSame('12345', $result);
    }

    public function testExtractFromXmlWithNullStatementIdReturnsNull(): void
    {
        $xmlObject = $this->createMockOK711(null);
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertNull($result);
    }

    public function testExtractFromXmlWithEmptyStringReturnsEmptyString(): void
    {
        $xmlObject = $this->createMockOK711('');
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertSame('', $result);
    }

    public function testExtractFromXmlWithMultiplePrefixesRemovesAll(): void
    {
        $xmlObject = $this->createMockOK711('ID_ID_12345');
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertSame('12345', $result);
    }

    public function testExtractFromXmlWithPrefixInMiddleRemovesAll(): void
    {
        $xmlObject = $this->createMockOK711('12345_ID_67890');
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertSame('12345_67890', $result);
    }

    /**
     * @dataProvider statementIdVariationsProvider
     */
    public function testExtractFromXmlHandlesVariousStatementIdFormats(
        string $inputId,
        ?string $expectedResult
    ): void {
        $xmlObject = $this->createMockOK711($inputId);
        $result = $this->sut->extractFromXml($xmlObject);
        static::assertSame($expectedResult, $result);
    }

    public static function statementIdVariationsProvider(): array
    {
        return [
            'normal ID with prefix' => ['ID_ABC123', 'ABC123'],
            'UUID with prefix' => ['ID_550e8400-e29b-41d4-a716-446655440000', '550e8400-e29b-41d4-a716-446655440000'],
            'numeric ID with prefix' => ['ID_999999', '999999'],
            'alphanumeric ID with prefix' => ['ID_TEST123ABC', 'TEST123ABC'],
            'ID without prefix' => ['PLAIN123', 'PLAIN123'],
            'only prefix' => ['ID_', ''],
            'prefix case sensitive' => ['id_123', 'id_123'], // should not remove lowercase
            'ID in middle' => ['ABC_ID_123', 'ABC_123'], // removes all ID_ occurrences
        ];
    }

    private function createMockOK711(?string $statementId): MockObject|AllgemeinStellungnahmeNeuabgegebenOK0711
    {
        $content = $this->createMock(OkContentType::class);
        $content->method('getStellungnahmeID')->willReturn($statementId);
        $xmlObject = $this->createMock(AllgemeinStellungnahmeNeuabgegebenOK0711::class);
        $xmlObject->method('getNachrichteninhalt')->willReturn($content);

        return $xmlObject;
    }

    private function createMockNOK721(?string $statementId): MockObject|AllgemeinStellungnahmeNeuabgegebenNOK0721
    {
        $content = $this->createMock(NokContentType::class);
        $content->method('getStellungnahmeID')->willReturn($statementId);
        $xmlObject = $this->createMock(AllgemeinStellungnahmeNeuabgegebenNOK0721::class);
        $xmlObject->method('getNachrichteninhalt')->willReturn($content);

        return $xmlObject;
    }
}
