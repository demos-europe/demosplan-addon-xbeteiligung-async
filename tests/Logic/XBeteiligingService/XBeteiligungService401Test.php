<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligingService;

class XBeteiligungService401Test extends XBeteiligungServiceTest
{

    public function testPlanung2BeteiligungBeteiligungNeu0401(): void
    {
        $procedureXml = $this->sut->createProcedureNew401FromObject($this->testProcedure);
        echo $procedureXml; // todo: remove me when Im working again :)
        $this->validateProcedureXML($procedureXml);
    }

    public function testPlanung2BeteiligungBeteiligungNeu0301(): void
    {
        $procedureXml = $this->sut->createXMLFor301($this->testProcedure);
        $this->validateProcedureXML($procedureXml);
    }

    public function testPlanung2BeteiligungBeteiligungNeu0401NoBBox(): void
    {
        $procedureXml = $this->sut->createProcedureNew401FromObject($this->testProcedureWithoutBBox);
        $this->validateProcedureXML($procedureXml);
    }
}
