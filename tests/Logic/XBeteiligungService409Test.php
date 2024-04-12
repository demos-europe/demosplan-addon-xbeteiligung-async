<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

class XBeteiligungService409Test extends XBeteiligungServiceTest
{
    public function testPlanung2BeteiligungBeteiligungNeu0409(): void
    {
        $procedureXml = $this->sut->createProcedureDeleted409FromObject($this->testProcedure->getId());
        $this->validateProcedureXML($procedureXml);
    }

    public function testPlanung2BeteiligungRaumordnungLoeschen0309(): void
    {
        $procedureXml = $this->sut->createXMLFor309($this->testProcedure->getId());
        $this->validateProcedureXML($procedureXml);
    }
}
