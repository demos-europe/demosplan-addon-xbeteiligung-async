<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DIplanBau;

use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Diplanbau\XtaKommunaleProcedureCreater;
use InvalidArgumentException;

class XtaKommunaleProcedureHandlerFactory
{
    public function __construct(private MockFactory $mockFactory)
    {
        $this->mockFactory = $mockFactory;
    }

    public function createXtaProcedureHandler(
        string $handlerType
    ): XtaKommunaleProcedureCreater {
        $commonDependencies = [
            $this->mockFactory->getCurrentUserProviderInterfaceMock(),
            $this->mockFactory->getLoggerInterfaceMock(),
            $this->mockFactory->getProcedureServiceInterface(),
            $this->mockFactory->getProcedureServiceStorage(),
            $this->mockFactory->getUserHandlerMock(),
            $this->mockFactory->getEntityManagerMock(),
            $this->mockFactory->getResponseMessageFactoryMock(),
            $this->mockFactory->getTranslatorMock(),
        ];

        switch ($handlerType) {
            case 'creator':
                return new XtaKommunaleProcedureCreater(...$commonDependencies);
            default:
                throw new InvalidArgumentException('Invalid handler type');
        }
    }

}