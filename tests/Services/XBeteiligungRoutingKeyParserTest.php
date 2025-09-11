<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Services;

use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungRoutingKeyParser;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\RoutingKeyComponents;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class XBeteiligungRoutingKeyParserTest extends TestCase
{
    private XBeteiligungRoutingKeyParser $parser;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->parser = new XBeteiligungRoutingKeyParser($this->logger);
    }

    /**
     * @dataProvider validRoutingKeyProvider
     */
    public function testParseRoutingKey(
        string $routingKey,
        string $expectedMandant,
        string $expectedDirection,
        string $expectedDvdvOrg1,
        string $expectedAgsCode1,
        string $expectedDvdvOrg2,
        string $expectedAgsCode2,
        string $expectedMessageIdentifier
    ): void {
        $result = $this->parser->parseRoutingKey($routingKey);

        self::assertSame($expectedMandant, $result->mandant);
        self::assertSame($expectedDirection, $result->direction);
        self::assertSame($expectedDvdvOrg1, $result->dvdvOrg1);
        self::assertSame($expectedAgsCode1, $result->agsCode1);
        self::assertSame($expectedDvdvOrg2, $result->dvdvOrg2);
        self::assertSame($expectedAgsCode2, $result->agsCode2);
        self::assertSame($expectedMessageIdentifier, $result->messageIdentifier);
    }

    public static function validRoutingKeyProvider(): array
    {
        return [
            'incoming kommunal message' => [
                'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401',
                'nrw', 'cockpit', 'bap', '02.05.00200099', 'bdp', '02.05.00200099', 'kommunal.initiieren.0401'
            ],
            'outgoing kommunal response' => [
                'bau.beteiligung.bdp.02.05.00200099.bap.02.05.00200099.kommunal.Initiieren.OK.0411',
                'bau', 'beteiligung', 'bdp', '02.05.00200099', 'bap', '02.05.00200099', 'kommunal.Initiieren.OK.0411'
            ],
            'different federal states' => [
                'by.cockpit.bap.09.77.00200099.bdp.09.77.00200099.kommunal.initiieren.0401',
                'by', 'cockpit', 'bap', '09.77.00200099', 'bdp', '09.77.00200099', 'kommunal.initiieren.0401'
            ]
        ];
    }

    /**
     * @dataProvider invalidRoutingKeyProvider
     */
    public function testParseRoutingKeyThrowsException(string $invalidRoutingKey, string $expectedExceptionMessage): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($expectedExceptionMessage);

        $this->parser->parseRoutingKey($invalidRoutingKey);
    }

    public static function invalidRoutingKeyProvider(): array
    {
        return [
            'too few parts' => [
                'nrw.cockpit.bap',
                'Invalid routing key format. Expected at least 7 parts, got 3'
            ],
            'invalid direction' => [
                'nrw.invalid.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401',
                "Invalid direction 'invalid'. Must be 'cockpit' or 'beteiligung'"
            ],
            'invalid AGS format (too few parts)' => [
                'nrw.cockpit.bap.02.05.bdp.02.05.00200099.kommunal.initiieren.0401',
                "Invalid agsCode1 format '02.05.bdp'. All parts must be numeric"
            ],
            'non-numeric AGS parts' => [
                'nrw.cockpit.bap.02.XX.00200099.bdp.02.05.00200099.kommunal.initiieren.0401',
                "Invalid agsCode1 format '02.XX.00200099'. All parts must be numeric"
            ]
        ];
    }

    public function testGetSenderAgsFromRoutingKey(): void
    {
        $routingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';

        $result = $this->parser->getSenderAgsFromRoutingKey($routingKey);

        // For incoming messages (cockpit), sender is agsCode1
        self::assertSame('02.05.00200099', $result);
    }

    public function testExtractFederalStateCodeFromRoutingKey(): void
    {
        $routingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';

        $result = $this->parser->extractFederalStateCodeFromRoutingKey($routingKey);

        self::assertSame('05', $result);
    }

    public function testExtractFederalStateCodeFromRoutingKeyWithDifferentStates(): void
    {
        $testCases = [
            ['by.cockpit.bap.09.77.00200099.bdp.09.77.00200099.kommunal.initiieren.0401', '77'], // Bayern
            ['sh.cockpit.bap.01.01.00200099.bdp.01.01.00200099.kommunal.initiieren.0401', '01'], // Schleswig-Holstein
            ['be.cockpit.bap.11.00.00200099.bdp.11.00.00200099.kommunal.initiieren.0401', '00']  // Berlin
        ];

        foreach ($testCases as [$routingKey, $expectedFederalState]) {
            $result = $this->parser->extractFederalStateCodeFromRoutingKey($routingKey);
            self::assertSame($expectedFederalState, $result, "Failed for routing key: $routingKey");
        }
    }

    public function testExtractFederalStateCodeFromRoutingKeyThrowsExceptionForInvalidAgs(): void
    {
        $routingKey = 'nrw.cockpit.bap.02.bdp.02.05.00200099.kommunal.initiieren.0401';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Invalid agsCode1 format '02.bdp.02'. All parts must be numeric");

        $this->parser->extractFederalStateCodeFromRoutingKey($routingKey);
    }

    public function testRoutingKeyComponentsGetters(): void
    {
        $routingKey = 'nrw.cockpit.bap.01.05.00200099.bdp.02.07.00300088.kommunal.initiieren.0401';
        $components = $this->parser->parseRoutingKey($routingKey);

        // Test getSenderAgs() - for incoming (cockpit), sender is agsCode1
        self::assertSame('01.05.00200099', $components->getSenderAgs());

        // Test getReceiverAgs() - for incoming (cockpit), receiver is agsCode2
        self::assertSame('02.07.00300088', $components->getReceiverAgs());

        // Test isIncoming() - should be true for cockpit direction
        self::assertTrue($components->isIncoming());
    }

    public function testOutgoingRoutingKeyComponents(): void
    {
        $routingKey = 'bau.beteiligung.bdp.02.05.00200099.bap.01.07.00400099.kommunal.Initiieren.OK.0411';
        $components = $this->parser->parseRoutingKey($routingKey);

        // For outgoing messages (beteiligung), sender is agsCode2
        self::assertSame('01.07.00400099', $components->getSenderAgs());

        // For outgoing messages (beteiligung), receiver is agsCode1
        self::assertSame('02.05.00200099', $components->getReceiverAgs());

        // Test isIncoming() - should be false for beteiligung direction
        self::assertFalse($components->isIncoming());
    }
}
