<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligungService;

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use Exception;
use Psr\Log\LoggerInterface;

class XBeteiligungService202Test extends XBeteiligungServiceTest
{
    /**
     * Test basic parsing of 202 message.
     *
     * @throws Exception
     */
    public function testParsePlanfeststellungAktualisieren0202(): void
    {
        $xmlContent = file_get_contents(__DIR__ . '/../../res/example-202.xml');
        self::assertNotFalse($xmlContent, 'Failed to read example-202.xml');

        // Create real parser for this test
        $parser = new XBeteiligungIncomingMessageParser($this->createMock(LoggerInterface::class));

        $xmlObject = $parser->getXmlObject($xmlContent, '202');

        self::assertInstanceOf(PlanfeststellungAktualisieren0202::class, $xmlObject);

        $beteiligung = $xmlObject->getNachrichteninhalt()?->getBeteiligung();
        self::assertNotNull($beteiligung, 'Beteiligung should not be null');

        // Verify basic structure
        self::assertSame('planIDPlaceHolder', $beteiligung->getPlanID());
        self::assertSame('planNamePlaceHolder', $beteiligung->getPlanname());
        self::assertSame('ABCabcXYZxyz', $beteiligung->getArbeitstitel());

        // Verify public participation
        $publicParticipation = $beteiligung->getBeteiligungOeffentlichkeit();
        self::assertNotNull($publicParticipation);

        // Verify TOEB participation
        $toebParticipation = $beteiligung->getBeteiligungTOEB();
        self::assertNotNull($toebParticipation);

        // Verify attachments exist
        $publicAnlagen = $publicParticipation->getAnlagen();
        self::assertNotNull($publicAnlagen);

        $toebAnlagen = $toebParticipation->getAnlagen();
        self::assertNotNull($toebAnlagen);
    }
}
