<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\KommunaleTest;

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungMapService;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use InvalidArgumentException;

class KommunaleProcedureHandlerFactory
{
    public function __construct(private MockFactoryTest $mockFactory)
    {
        $this->mockFactory = $mockFactory;
    }

    public function createProcedureHandler(
        string $handlerType
    ): KommunaleProcedureCreater {
        $commonDependencies = [
            $this->mockFactory->getCurrentUserProviderInterfaceMock(),
            $this->mockFactory->getEntityManagerMock(),
            $this->mockFactory->getKommunaleResponseMessageFactory(),
            $this->mockFactory->getLoggerInterfaceMock(),
            $this->mockFactory->getPlanfeststellungResponseMessageFactory(),
            $this->mockFactory->getProcedurePhaseExtractorMock(),
            $this->mockFactory->getProcedureServiceInterface(),
            $this->mockFactory->getProcedureServiceStorage(),
            $this->mockFactory->getProcedureTypeService(),
            $this->mockFactory->getRaumordnungResponseMessageFactory(),
            $this->mockFactory->getTransActionServiceInterfaceMock(),
            $this->mockFactory->getTranslatorMock(),
            $this->mockFactory->getUserHandlerMock(),
            $this->mockFactory->getOrgaServiceInterfaceMock(),
            new XBeteiligungMapService($this->mockFactory->getLoggerInterfaceMock())
        ];

        switch ($handlerType) {
            case 'creator':
                return new KommunaleProcedureCreater(...$commonDependencies);
            default:
                throw new InvalidArgumentException('Invalid handler type');
        }
    }

}
