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

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungOutgoingRoutingKeyBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Services\XBeteiligungRoutingKeyParser;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class XBeteiligungOutgoingRoutingKeyBuilderTest extends TestCase
{
    private XBeteiligungOutgoingRoutingKeyBuilder $builder;
    private XBeteiligungConfiguration $config;
    private XBeteiligungRoutingKeyParser $routingKeyParser;
    private LoggerInterface $logger;

    protected function setUp(): void
    {
        $this->config = new XBeteiligungConfiguration(
            true,           // rabbitMqEnabled
            30,             // requestTimeout
            300,            // communicationDelay
            'Kommunal',     // procedureMessageType
            true,           // auditEnabled
            'bap',          // xoevAddressPrefixCockpit
            10,             // maxMessagesPerCycle
            5,              // consumerTimeout
            'Test Procedure Type' // procedureTypeName
        );

        $this->logger = $this->createMock(LoggerInterface::class);
        $this->routingKeyParser = new XBeteiligungRoutingKeyParser($this->logger);

        $this->builder = new XBeteiligungOutgoingRoutingKeyBuilder(
            $this->config,
            $this->routingKeyParser,
            $this->logger
        );
    }

    /**
     * @dataProvider incomingToOutgoingRoutingKeyProvider
     */
    public function testBuildFromIncomingRoutingKey(
        string $incomingRoutingKey,
        string $outgoingMessageIdentifier,
        string $expectedOutgoingRoutingKey
    ): void {
        $result = $this->builder->buildFromIncomingRoutingKey($incomingRoutingKey, $outgoingMessageIdentifier);

        self::assertSame($expectedOutgoingRoutingKey, $result);
    }

    public static function incomingToOutgoingRoutingKeyProvider(): array
    {
        return [
            'kommunal initiieren to OK response' => [
                'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401',
                'kommunal.Initiieren.OK.0411',
                'bau.beteiligung.bdp.02.05.00200099.bap.02.05.00200099.kommunal.Initiieren.OK.0411'
            ],
            'different AGS codes' => [
                'by.cockpit.bap.09.77.00200099.bdp.01.05.00300088.kommunal.initiieren.0401',
                'kommunal.Initiieren.OK.0411',
                'bau.beteiligung.bdp.01.05.00300088.bap.09.77.00200099.kommunal.Initiieren.OK.0411'
            ],
            'error response' => [
                'sh.cockpit.bap.01.01.00200099.bdp.02.05.00200099.kommunal.initiieren.0401',
                'kommunal.Initiieren.NOK.0421',
                'bau.beteiligung.bdp.02.05.00200099.bap.01.01.00200099.kommunal.Initiieren.NOK.0421'
            ],
            'statement message' => [
                'hh.cockpit.bap.02.00.00200099.bdp.02.05.00200099.kommunal.initiieren.0401',
                'allgemein.stellungnahme.neuabgegeben.0701',
                'bau.beteiligung.bdp.02.05.00200099.bap.02.00.00200099.allgemein.stellungnahme.neuabgegeben.0701'
            ]
        ];
    }

    public function testBuildFromIncomingRoutingKeyFlipsDirectionAndOrganizations(): void
    {
        $incomingRoutingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';
        $outgoingMessageIdentifier = 'kommunal.Initiieren.OK.0411';

        $result = $this->builder->buildFromIncomingRoutingKey($incomingRoutingKey, $outgoingMessageIdentifier);

        // Parse the result to verify structure
        $outgoingComponents = $this->routingKeyParser->parseRoutingKey($result);

        // Verify direction is flipped from 'cockpit' to 'beteiligung'
        self::assertSame('beteiligung', $outgoingComponents->direction);

        // Verify organization flip: bap (cockpit) -> bdp (demosplan) as sender
        self::assertSame('bdp', $outgoingComponents->dvdvOrg1);
        self::assertSame('bap', $outgoingComponents->dvdvOrg2);

        // Verify AGS codes are flipped (sender becomes receiver and vice versa)
        $incomingComponents = $this->routingKeyParser->parseRoutingKey($incomingRoutingKey);
        self::assertSame($incomingComponents->agsCode2, $outgoingComponents->agsCode1); // demosplan AGS
        self::assertSame($incomingComponents->agsCode1, $outgoingComponents->agsCode2); // cockpit AGS
    }

    public function testBuildFromIncomingRoutingKeyUsesConfiguredProjectTypePrefix(): void
    {
        $incomingRoutingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';
        $outgoingMessageIdentifier = 'kommunal.Initiieren.OK.0411';

        $result = $this->builder->buildFromIncomingRoutingKey($incomingRoutingKey, $outgoingMessageIdentifier);

        // Should start with configured project type prefix 'bau'
        self::assertStringStartsWith('bau.', $result);
    }

    public function testBuildFromIncomingRoutingKeyThrowsExceptionForOutgoingRoutingKey(): void
    {
        $outgoingRoutingKey = 'bau.beteiligung.bdp.02.05.00200099.bap.02.05.00200099.kommunal.Initiieren.OK.0411';
        $outgoingMessageIdentifier = 'kommunal.Initiieren.OK.0411';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected incoming routing key (direction=cockpit), got: beteiligung');

        $this->builder->buildFromIncomingRoutingKey($outgoingRoutingKey, $outgoingMessageIdentifier);
    }

    public function testBuildFromIncomingRoutingKeyLogsDebugAndInfo(): void
    {
        $incomingRoutingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';
        $outgoingMessageIdentifier = 'kommunal.Initiieren.OK.0411';

        // Expect at least one debug log (RoutingKeyParser also logs)
        $this->logger->expects($this->atLeastOnce())
            ->method('debug');

        // Expect info log at end from the OutgoingRoutingKeyBuilder
        $this->logger->expects($this->once())
            ->method('info')
            ->with('Built outgoing routing key',
                static::callback(
                    static fn (array $context)
                    => isset(
                        $context['incomingRoutingKey'],
                            $context['outgoingRoutingKey'],
                            $context['outgoingMessageIdentifier']
                        ) &&
                   $context['incomingRoutingKey'] === $incomingRoutingKey &&
                   $context['outgoingMessageIdentifier'] === $outgoingMessageIdentifier &&
                   str_starts_with($context['outgoingRoutingKey'], 'bau.beteiligung.')));

        $this->builder->buildFromIncomingRoutingKey($incomingRoutingKey, $outgoingMessageIdentifier);
    }

    public function testBuildFromIncomingRoutingKeyWithDifferentConfigurations(): void
    {
        // Create builder with different configuration (raumordnung type gives 'rog' prefix)
        $customConfig = new XBeteiligungConfiguration(
            true,           // rabbitMqEnabled
            30,             // requestTimeout
            300,            // communicationDelay
            'Raumordnung',  // procedureMessageType (gives 'rog' prefix)
            true,           // auditEnabled
            'custom_cockpit', // xoevAddressPrefixCockpit
            10,             // maxMessagesPerCycle
            5,              // consumerTimeout
            'Test Procedure Type' // procedureTypeName
        );

        $customBuilder = new XBeteiligungOutgoingRoutingKeyBuilder(
            $customConfig,
            $this->routingKeyParser,
            $this->logger
        );

        $incomingRoutingKey = 'nrw.cockpit.custom_cockpit.02.05.00200099.custom_kommunal.02.05.00200099.kommunal.initiieren.0401';
        $outgoingMessageIdentifier = 'kommunal.Initiieren.OK.0411';

        $result = $customBuilder->buildFromIncomingRoutingKey($incomingRoutingKey, $outgoingMessageIdentifier);

        self::assertSame(
            'rog.beteiligung.rog.02.05.00200099.custom_cockpit.02.05.00200099.kommunal.Initiieren.OK.0411',
            $result
        );
    }

    public function testBuildFromIncomingRoutingKeyPreservesMessageIdentifier(): void
    {
        $incomingRoutingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';
        $customMessageIdentifier = 'custom.message.type.9999';

        $result = $this->builder->buildFromIncomingRoutingKey($incomingRoutingKey, $customMessageIdentifier);

        self::assertStringEndsWith($customMessageIdentifier, $result);
    }

    /**
     * Test edge cases and validation
     */
    public function testBuildFromIncomingRoutingKeyValidatesParameters(): void
    {
        $validIncomingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';

        // This should work fine
        $result = $this->builder->buildFromIncomingRoutingKey($validIncomingKey, 'test.message.0000');
        self::assertNotEmpty($result);
    }

    public function testBuildOutgoingKeyStructure(): void
    {
        $incomingRoutingKey = 'nrw.cockpit.bap.01.05.00100099.bdp.02.07.00200088.kommunal.initiieren.0401';
        $outgoingMessageIdentifier = 'kommunal.Initiieren.OK.0411';

        $result = $this->builder->buildFromIncomingRoutingKey($incomingRoutingKey, $outgoingMessageIdentifier);

        // Expected structure: bau.beteiligung.bdp.02.07.00200088.bap.01.05.00100099.kommunal.Initiieren.OK.0411
        $parts = explode('.', $result);

        self::assertCount(14, $parts); // Should have 14 parts total (including .OK.0411)
        self::assertSame('bau', $parts[0]); // beteiligung variant
        self::assertSame('beteiligung', $parts[1]); // direction
        self::assertSame('bdp', $parts[2]); // sender DVDV org (demosplan)
        self::assertSame('02', $parts[3]); // sender AGS part 1
        self::assertSame('07', $parts[4]); // sender AGS part 2
        self::assertSame('00200088', $parts[5]); // sender AGS part 3
        self::assertSame('bap', $parts[6]); // receiver DVDV org (cockpit)
        self::assertSame('01', $parts[7]); // receiver AGS part 1
        self::assertSame('05', $parts[8]); // receiver AGS part 2
        self::assertSame('00100099', $parts[9]); // receiver AGS part 3
        // parts[10-13] are the message identifier
        self::assertSame('kommunal', $parts[10]);
        self::assertSame('Initiieren', $parts[11]);
        self::assertSame('OK', $parts[12]);
        self::assertSame('0411', $parts[13]);
    }
}
