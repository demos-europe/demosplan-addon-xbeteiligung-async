<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\StatementTest;

use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CustomerUrlGeneratorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use Doctrine\Common\Collections\ArrayCollection;
use DateTime;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

class StatementCreatorUrlTest extends TestCase
{
    public function testPlannerDetailViewUrlIsGeneratedForCustomerOfProcedure(): void
    {
        $customer = $this->createMock(CustomerInterface::class);
        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getCustomer')->willReturn($customer);
        $procedure->method('getName')->willReturn('Procedure');
        $procedure->method('getXtaPlanId')->willReturn('plan-1');

        $phaseDefinition = new class() {
            public function getName(): string
            {
                return 'phase';
            }
        };
        $statement = $this->createMock(StatementWithPhaseDefinition::class);
        $statement->method('getPhaseDefinition')->willReturn($phaseDefinition);
        $statement->method('getText')->willReturn('text');
        $statement->method('getOName')->willReturn('orga');
        $statement->method('getPublicUseName')->willReturn(false);
        $statement->method('getStatus')->willReturn('new');
        $statement->method('getTitle')->willReturn('title');
        $statement->method('getFeedback')->willReturn('feedback');
        $statement->method('getPriority')->willReturn('priority');
        $statement->method('getVotePla')->willReturn(null);
        $statement->method('getPublicStatement')->willReturn('internal');
        $statement->method('getOrganisation')->willReturn(null);
        $statement->method('getId')->willReturn('statement-1');
        $statement->method('getProcedureId')->willReturn('proc-1');
        $statement->method('getProcedure')->willReturn($procedure);
        $statement->method('getMeta')->willReturn($this->createMock(StatementMetaInterface::class));
        $statement->method('getUser')->willReturn($this->createMock(UserInterface::class));
        $statement->method('getCreated')->willReturn(new DateTime());
        $statement->method('getTags')->willReturn(new ArrayCollection());

        $event = new class($statement) implements StatementCreatedEventInterface {
            public function __construct(private readonly StatementInterface $statement)
            {
            }

            public function getStatement(): StatementInterface
            {
                return $this->statement;
            }
        };

        $customerUrlGenerator = $this->createMock(CustomerUrlGeneratorInterface::class);
        $customerUrlGenerator->expects(self::once())
            ->method('generate')
            ->with($customer, 'dm_plan_assessment_single_view', ['procedureId' => 'proc-1', 'statement' => 'statement-1'])
            ->willReturn('https://customer.example/statement');

        $statementCreated = (new StatementCreator($customerUrlGenerator))->getStatementCreatedFromEvent($event);

        // the value object has no getter for the url
        $url = (new ReflectionProperty($statementCreated, 'plannerDetailViewUrl'))->getValue($statementCreated);
        self::assertSame('https://customer.example/statement', $url);
    }
}

/**
 * StatementInterface of the SDK does not declare the methods the creator reads from the phase.
 */
interface StatementWithPhaseDefinition extends StatementInterface
{
    public function getPhaseDefinition();
}
