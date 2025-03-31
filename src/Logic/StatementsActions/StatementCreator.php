<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions;

use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class StatementCreator
{
    protected RouterInterface $router;
    protected UserInterface $user;
    protected ProcedureInterface $procedure;
    protected StatementMetaInterface $meta;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
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
        $statementCreated->setDescription($eventStatement->getTextShort());
        $routeParameters = [
            'procedureId' => $eventStatement->getProcedureId(),
            'statement'   => $eventStatement->getId(),
        ];
        $plannerDetailViewUrl = $this->router->generate(
            'dm_plan_assessment_single_view',
            $routeParameters,
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $statementCreated->setPlannerDetailViewUrl($plannerDetailViewUrl);
        // manual Statements may possibly have no real Orga
        $orgaName = $eventStatement->getOrganisation() instanceof OrgaInterface
            ? $eventStatement->getOrganisation()->getName()
            : $eventStatement->getOName();
        $statementCreated->setOrganizationName($orgaName);
        $statementCreated->setPublicUseName($eventStatement->getPublicUseName());
        $statementCreated->setPhase($eventStatement->getPhase());
        $statementCreated->setProcedureName($eventStatement->getProcedure()->getName());
        $statementCreated->setStatus($eventStatement->getStatus());
        $statementCreated->setTitle($eventStatement->getTitle());
        $statementCreated->setFeedback($eventStatement->getFeedback());
        $statementCreated->setPriority($eventStatement->getPriority());
        $statementCreated->setVotes($eventStatement->getVotes());
        $statementCreated->setTags($eventStatement->getTags()->toArray());
        $statementCreated->lock();

        return $statementCreated;
    }
}
