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


use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;

class XBeteiligungService402Test extends XBeteiligungServiceTest
{
    public function testPlanung2BeteiligungBeteiligungNeu0402(): void
    {
        $procedureXml = $this->sut->createProcedureUpdate402FromObject($this->testProcedure);
        $this->validateProcedureXML($procedureXml, KommunalAktualisieren0402::class);
    }

    public function testPlanung2BeteiligungRaumordnungNeu0302(): void
    {
        $procedureXml = $this->sut->createXMLFor302($this->testProcedure);
        $this->validateProcedureXML($procedureXml, RaumordnungAktualisieren0302::class);
    }
}
