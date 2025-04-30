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
        $currentUserProvider = $this->mockFactory->getCurrentUserProviderInterfaceMock();
        $entityManager = $this->mockFactory->getEntityManagerMock();
        $kommunaleMessageFactory = $this->mockFactory->getKommunaleResponseMessageFactory();
        $logger = $this->mockFactory->getLoggerInterfaceMock();
        $planfeststellungMessageFactory = $this->mockFactory->getPlanfeststellungResponseMessageFactory();
        $procedurePhaseExtractor = $this->mockFactory->getProcedurePhaseExtractorMock();
        $procedureService = $this->mockFactory->getProcedureServiceInterface();
        $procedureServiceStorage = $this->mockFactory->getProcedureServiceStorage();
        $procedureTypeService = $this->mockFactory->getProcedureTypeService();
        $raumordnungMessageFactory = $this->mockFactory->getRaumordnungResponseMessageFactory();
        $transactionService = $this->mockFactory->getTransActionServiceInterfaceMock();
        $translator = $this->mockFactory->getTranslatorMock();
        $userHandler = $this->mockFactory->getUserHandlerMock();
        $orgaService = $this->mockFactory->getOrgaServiceInterfaceMock();
        $xbeteiligungMapService = $this->mockFactory->getXBeteiligungMapServiceMock();

        switch ($handlerType) {
            case 'creator':
                return new KommunaleProcedureCreater(
                    $currentUserProvider,
                    $entityManager,
                    $kommunaleMessageFactory,
                    $logger,
                    $planfeststellungMessageFactory,
                    $procedurePhaseExtractor,
                    $procedureService,
                    $procedureServiceStorage,
                    $procedureTypeService,
                    $raumordnungMessageFactory,
                    $transactionService,
                    $translator,
                    $userHandler,
                    $orgaService,
                    $xbeteiligungMapService
                );
            default:
                throw new InvalidArgumentException('Invalid handler type');
        }
    }

}
