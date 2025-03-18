<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligingService;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungLoeschen0309;

class XBeteiligungService409Test extends XBeteiligungServiceTest
{
    public function testPlanung2BeteiligungBeteiligungNeu0409(): void
    {
        $procedureXml = $this->sut->createProcedureDeleted409FromObject($this->testProcedure->getId());
        $this->validateProcedureXML($procedureXml, KommunalLoeschen0409::class);
    }

    public function testPlanung2BeteiligungRaumordnungLoeschen0309(): void
    {
        $procedureXml = $this->sut->createXMLFor309($this->testProcedure->getId());
        $this->validateProcedureXML($procedureXml, RaumordnungLoeschen0309::class);
    }
}
