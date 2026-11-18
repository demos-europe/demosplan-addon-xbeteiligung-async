<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions;

use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use DemosEurope\DemosplanAddon\Contracts\Services\CustomerUrlGeneratorInterface;

class StatementCreator
{
    protected CustomerUrlGeneratorInterface $customerUrlGenerator;
    protected UserInterface $user;
    protected ProcedureInterface $procedure;
    protected StatementMetaInterface $meta;

    public function __construct(CustomerUrlGeneratorInterface $customerUrlGenerator)
    {
        $this->customerUrlGenerator = $customerUrlGenerator;
    }
    public function getStatementCreatedFromEvent(StatementCreatedEventInterface $event): StatementCreated
    {
        /** @var StatementInterface $eventStatement */
        $eventStatement = $event->getStatement();
        $meta = $eventStatement->getMeta();
        $user = $eventStatement->getUser();
        $procedure = $eventStatement->getProcedure();
        $statementCreated = new StatementCreated($user, $procedure, $meta);

        $statementCreated->setPublicId($eventStatement->getId());
        $statementCreated->setCreatedAt($eventStatement->getCreated());
        $statementCreated->setPlanId($eventStatement->getProcedure()->getXtaPlanId());
        $statementCreated->setProcedureId($eventStatement->getProcedureId());
        $statementCreated->setDescription($eventStatement->getText());
        $routeParameters = [
            'procedureId' => $eventStatement->getProcedureId(),
            'statement'   => $eventStatement->getId(),
        ];
        $plannerDetailViewUrl = $this->customerUrlGenerator->generate(
            $procedure->getCustomer(),
            'dm_plan_assessment_single_view',
            $routeParameters
        );
        $statementCreated->setPlannerDetailViewUrl($plannerDetailViewUrl);
        // manual Statements may possibly have no real Orga
        $orgaName = $eventStatement->getOrganisation() instanceof OrgaInterface
            ? $eventStatement->getOrganisation()->getName()
            : $eventStatement->getOName();
        $statementCreated->setOrganizationName($orgaName);
        $statementCreated->setPublicUseName($eventStatement->getPublicUseName());
        $statementCreated->setPhase($eventStatement->getPhaseDefinition()->getName());
        $statementCreated->setProcedureName($eventStatement->getProcedure()->getName());
        $statementCreated->setStatus($eventStatement->getStatus());
        $statementCreated->setTitle($eventStatement->getTitle());
        $statementCreated->setFeedback($eventStatement->getFeedback());
        $statementCreated->setPriority($eventStatement->getPriority());
        $statementCreated->setVotePla($eventStatement->getVotePla());
        $statementCreated->setPublicStatement($eventStatement->getPublicStatement());
        $statementCreated->setTags($eventStatement->getTags()->toArray());
        $statementCreated->lock();

        return $statementCreated;
    }
}
