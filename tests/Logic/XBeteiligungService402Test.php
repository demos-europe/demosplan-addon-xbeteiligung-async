<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

class XBeteiligungService402Test extends XBeteiligungServiceTest
{
    public function testPlanung2BeteiligungBeteiligungNeu0402(): void
    {
        $procedureXml = $this->sut->createProcedureUpdate402FromObject($this->testProcedure);
        $this->validateProcedureXML($procedureXml);
    }

    public function testPlanung2BeteiligungRaumordnungNeu0302(): void
    {
        $procedureXml = $this->sut->createXMLFor302($this->testProcedure);
        $this->validateProcedureXML($procedureXml);
    }
}
