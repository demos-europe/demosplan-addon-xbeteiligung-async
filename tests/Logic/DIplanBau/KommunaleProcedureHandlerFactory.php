<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DIplanBau;

use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use InvalidArgumentException;

class KommunaleProcedureHandlerFactory
{
    public function __construct(private MockFactory $mockFactory)
    {
        $this->mockFactory = $mockFactory;
    }

    public function createProcedureHandler(
        string $handlerType
    ): KommunaleProcedureCreater {
        $commonDependencies = [
            $this->mockFactory->getCurrentUserProviderInterfaceMock(),
            $this->mockFactory->getLoggerInterfaceMock(),
            $this->mockFactory->getProcedureServiceInterface(),
            $this->mockFactory->getProcedureServiceStorage(),
            $this->mockFactory->getProcedureTypeService(),
            $this->mockFactory->getUserHandlerMock(),
            $this->mockFactory->getEntityManagerMock(),
            $this->mockFactory->getResponseMessageFactoryMock(),
            $this->mockFactory->getTranslatorMock(),
            $this->mockFactory->getTransActionServiceInterfaceMock()
        ];

        switch ($handlerType) {
            case 'creator':
                return new KommunaleProcedureCreater(...$commonDependencies);
            default:
                throw new InvalidArgumentException('Invalid handler type');
        }
    }

}