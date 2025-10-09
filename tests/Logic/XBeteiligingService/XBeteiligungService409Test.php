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

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;

class XBeteiligungService409Test extends XBeteiligungServiceTest
{
    public function testPlanung2BeteiligungBeteiligungNeu0409(): void
    {
        $procedureXml = $this->sut->createProcedureDeleted409FromObject($this->testProcedure);
        $this->validateProcedureXML($procedureXml, KommunalLoeschen0409::class);
    }

    public function testPlanung2BeteiligungRaumordnungLoeschen0309(): void
    {
        $procedureXml = $this->sut->createXMLFor309($this->testProcedure);
        $this->validateProcedureXML($procedureXml, RaumordnungLoeschen0309::class);
    }
}
