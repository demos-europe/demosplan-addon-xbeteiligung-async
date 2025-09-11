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

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\ProcedurePhaseExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungMapService;
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
        // Create necessary mocks for ProcedureCommonFeatures
        $logger = $this->mockFactory->getLoggerInterfaceMock();
        $phaseExtractor = new ProcedurePhaseExtractor($logger);
        $mapService = new XBeteiligungMapService($logger);
        
        // Create real configuration with test values
        $configuration = new XBeteiligungConfiguration(
            rabbitMqEnabled: false,
            requestTimeout: 30,
            communicationDelay: 300,
            procedureMessageType: 'Kommunal',
            auditEnabled: true,
            rabbitMqExchange: 'init.cockpit',
            xoevAddressPrefixKommunal: 'bdp',
            xoevAddressPrefixCockpit: 'bap',
            maxMessagesPerCycle: 10,
            consumerTimeout: 5,
            procedureTypeName: 'Allgemeine Beteiligung'
        );

        $commonDependencies = [
            $this->mockFactory->getCurrentUserProviderInterfaceMock(),
            $this->mockFactory->getCustomerServiceInterfaceMock(),
            $this->mockFactory->getEntityManagerMock(),
            $this->mockFactory->getKommunaleResponseMessageFactory(),
            $this->mockFactory->getLoggerInterfaceMock(),
            $this->mockFactory->getPlanfeststellungResponseMessageFactory(),
            $phaseExtractor,
            $this->mockFactory->getProcedureServiceInterface(),
            $this->mockFactory->getProcedureServiceStorage(),
            $this->mockFactory->getProcedureTypeService(),
            $this->mockFactory->getRaumordnungResponseMessageFactory(),
            $this->mockFactory->getTransActionServiceInterfaceMock(),
            $this->mockFactory->getTranslatorMock(),
            $this->mockFactory->getUserHandlerMock(),
            $this->mockFactory->getOrgaServiceInterfaceMock(),
            $this->mockFactory->getXBeteiligungAgsServiceMock(),
            $this->mockFactory->getXBeteiligungCustomerMappingServiceMock(),
            $mapService,
            $configuration,
            $this->mockFactory->getXBeteiligungRoutingKeyParserMock()
        ];

        switch ($handlerType) {
            case 'creator':
                return new KommunaleProcedureCreater(...$commonDependencies);
            default:
                throw new InvalidArgumentException('Invalid handler type');
        }
    }

}
