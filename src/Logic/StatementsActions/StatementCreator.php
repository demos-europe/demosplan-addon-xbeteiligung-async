<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions;

use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
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
    public function getStatementCreatedFromEvent(StatementCreatedEventInterface $event): StatementCreated
    {
        $statementCreated = new StatementCreated($this->user, $this->procedure);

        $eventStatement = $event->getStatement();

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
        $statementCreated->setTags($eventStatement->getTags());
        $statementCreated->setFile($eventStatement->getFile());
        $statementCreated->lock();

        return $statementCreated;
    }
}