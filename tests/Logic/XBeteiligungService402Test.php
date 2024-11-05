<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

class XBeteiligungService402Test extends XBeteiligungServiceTest
{
    public function testPlanung2BeteiligungBeteiligungNeu0402(): void
    {
        foreach ($this->testProcedures as $procedure) {
            $procedureXml = $this->sut->createProcedureUpdate402FromObject($procedure);
            $this->validateProcedureXML($procedureXml);
        }
    }

    public function testPlanung2BeteiligungRaumordnungNeu0302(): void
    {
        foreach ($this->testProcedures as $procedure) {
            $procedureXml = $this->sut->createXMLFor302($procedure);
            $this->validateProcedureXML($procedureXml);
        }
    }
}
