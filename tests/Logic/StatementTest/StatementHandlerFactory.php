<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\StatementTest;

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use InvalidArgumentException;

class StatementHandlerFactory
{
    public function __construct(private MockFactoryTest $mockFactory)
    {
        $this->mockFactory = $mockFactory;
    }

    public function createStatementHandler(
        string $handlerType
    ): StatementCreator {
        $commonDependencies = [
            $this->mockFactory->getStatementCreatorMock(),
            $this->mockFactory->getResponseMessageFactoryMock(),
        ];

        switch ($handlerType) {
            case 'creator':
                return new StatementCreator(...$commonDependencies);
            default:
                throw new InvalidArgumentException('Invalid handler type');
        }
    }

}