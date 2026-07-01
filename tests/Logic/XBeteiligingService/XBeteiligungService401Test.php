<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligingService;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;

class XBeteiligungService401Test extends XBeteiligungServiceTest
{

    public function testPlanung2BeteiligungBeteiligungNeu0401(): void
    {
        $procedureXml = $this->sut->createProcedureNew401FromObject($this->testProcedure);
        $this->validateProcedureXML($procedureXml, KommunalInitiieren0401::class);
    }

    public function testPlanung2BeteiligungBeteiligungNeu0301(): void
    {
        $procedureXml = $this->sut->createXMLFor301($this->testProcedure);
        $this->validateProcedureXML($procedureXml, RaumordnungInitiieren0301::class);
    }

    public function testPlanung2BeteiligungBeteiligungNeu0401NoBBox(): void
    {
        $procedureXml = $this->sut->createProcedureNew401FromObject($this->testProcedureWithoutBBox);
        $this->validateProcedureXML($procedureXml, KommunalInitiieren0401::class);
    }

    public function testPlanfeststellungNeu0201(): void
    {
        $procedureXml = $this->sut->createXMLFor201($this->testProcedure);

        $this->validateProcedureXML($procedureXml, PlanfeststellungInitiieren0201::class);
    }

    public function testEnDashInNameAndDescriptionIsSanitizedToHyphen(): void
    {
        $enDash = "\u{2013}";
        $procedure = $this->getTestProcedure(
            $this->getTestProcedureSettings(),
            'Marsbasis Alpha '.$enDash.' Süd',
            'Trasse Krater Alpha '.$enDash.' Düne Beta '.$enDash.' Marsbasis Süd'
        );

        $procedureXml = $this->sut->createProcedureNew401FromObject($procedure);

        // The XML must validate against the XBeteiligung XSD; an en-dash would violate the String.Latin pattern.
        $this->validateProcedureXML($procedureXml, KommunalInitiieren0401::class);
        // The en-dash must have been replaced by a hyphen-minus in both planname and beschreibungPlanungsanlass.
        self::assertStringNotContainsString($enDash, $procedureXml);
        self::assertStringContainsString('Krater Alpha - Düne Beta - Marsbasis Süd', $procedureXml);
    }
}
