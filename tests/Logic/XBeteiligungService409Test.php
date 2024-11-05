<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;

class XBeteiligungService409Test extends XBeteiligungServiceTest
{
    public function testPlanung2BeteiligungBeteiligungNeu0409(): void
    {
        /** @var ProcedureInterface $procedure */
        foreach ($this->testProcedures as $procedure) {
            $procedureXml = $this->sut->createProcedureDeleted409FromObject($procedure->getId());
            $this->validateProcedureXML($procedureXml);
        }
    }

    public function testPlanung2BeteiligungRaumordnungLoeschen0309(): void
    {
        /** @var ProcedureInterface $procedure */
        foreach ($this->testProcedures as $procedure) {
            $procedureXml = $this->sut->createXMLFor309($procedure->getId());
            $this->validateProcedureXML($procedureXml);
        }
    }
}
