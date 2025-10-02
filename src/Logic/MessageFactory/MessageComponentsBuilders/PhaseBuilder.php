<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\ProjectPrefixNotFoundException;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensteilschrittType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use Psr\Log\LoggerInterface;

class PhaseBuilder
{
    private const DEFAULT_PROCEDURE_PHASE_CODE = '0815';
    private const LIST_VERSION_ID = '3';

    public function __construct(
        private readonly PermissionEvaluatorInterface $permissionEvaluator,
        private readonly LoggerInterface $logger,
        private readonly GlobalConfigInterface $globalConfig,

    ) {
    }

    /**
     * @throws ProjectPrefixNotFoundException
     */
    public function setVerfahrenschritt(StatementCreated $statementCreated, StellungnahmeType $statement): void
    {
        $phaseName = $this->getPhaseName($statementCreated);
        $phaseType = $this->createPhaseType();
        $this->configurePhase($phaseType, $phaseName);
        $this->setPhaseTypeToStatement($phaseType, $statement);
    }

    /**
     * @throws ProjectPrefixNotFoundException
     */
    private function createPhaseType(): CodeVerfahrensschrittKommunalType|CodeVerfahrensschrittRaumordnungType|CodeVerfahrensschrittPlanfeststellungType
    {
        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_kom_create())) {
            return new CodeVerfahrensschrittKommunalType();
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_rog_create())) {
            return new CodeVerfahrensschrittRaumordnungType();
        }

        if ($this->permissionEvaluator->isPermissionEnabled(Features::feature_procedure_message_pln_create())) {
            return new CodeVerfahrensschrittPlanfeststellungType();
        }

        $this->logger->error('No valid procedure type found for statement phase setup.');
        throw new ProjectPrefixNotFoundException('No valid procedure type found.');
    }

    private function configurePhase(
        CodeVerfahrensschrittKommunalType|CodeVerfahrensschrittRaumordnungType|CodeVerfahrensschrittPlanfeststellungType $phaseType,
        string $phaseName): void
    {
        $phaseType->setName($phaseName);
        $phaseType->setCode(self::DEFAULT_PROCEDURE_PHASE_CODE);
        $phaseType->setListVersionID(self::LIST_VERSION_ID);
    }

    private function setPhaseTypeToStatement(object $participationType, StellungnahmeType $statement): void
    {
        match ($participationType::class) {
            CodeVerfahrensschrittKommunalType::class => $statement->setVerfahrensschrittKommunal($participationType),
            CodeVerfahrensschrittRaumordnungType::class => $statement->setVerfahrensschrittRaumordnung($participationType),
            CodeVerfahrensschrittPlanfeststellungType::class => $statement->setVerfahrensschrittPlanfeststellung($participationType),
            default => $this->logger->error('Unknown participation type encountered', ['class' => $participationType::class])
        };
    }

    private function getPhaseName($statementCreated) {
        // If internal statement, use internal phase name
        if (StatementInterface::INTERNAL === $statementCreated->getPublicStatement()) {
            return $this->globalConfig->getPhaseNameWithPriorityInternal($statementCreated->getPhase());
        }

        // Default to external phase name
        return $this->globalConfig->getPhaseNameWithPriorityExternal($statementCreated->getPhase());

    }

    public function setVerfahrensteilschritt(StatementCreated $statementCreated, StellungnahmeType $statement)
    {
        $partParticipationType = new CodeVerfahrensteilschrittType();
        $partParticipationType->setCode(self::DEFAULT_PROCEDURE_PHASE_CODE);
        $partParticipationType->setName($this->getPhaseName($statementCreated));
        $partParticipationType->setListVersionID(self::LIST_VERSION_ID);
        $statement->setVerfahrensteilschritt($partParticipationType);

    }
}
