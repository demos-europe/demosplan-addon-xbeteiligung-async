<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligingService;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInitiieren0301;

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
}
