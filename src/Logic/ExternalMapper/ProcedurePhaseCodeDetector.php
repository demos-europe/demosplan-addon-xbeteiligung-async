<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedurePhaseCockpit;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedurePhaseCockpitRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;

/**
 * It will detect the corresponding phase in demos related to the corresponding external phase code
 * Right now, it does not do that, instead it returns the default ones and store the external codes in the database
 * to be used later when sending back the message
 */
class ProcedurePhaseCodeDetector {
    private const DEFAULT_PROCEDURE_PHASE_CODE = 'invalid';

    public function __construct(
        private readonly XBeteiligungProcedurePhaseCockpitRepository $repository,
        private readonly VerfasserBuilder                            $verfasserBuilder,
        private readonly XBeteiligungConfiguration                   $xbeteiligungConfiguration,
    ) {
    }

    public function storeCokpitProcedurePhaseCodes(
        string $procedureId,
        ProcedureDataValueObject $procedureDataValueObject,
    ): void {
        $procedurePhaseData = $procedureDataValueObject->getProcedurePhaseData();

        if (null === $procedurePhaseData) {
            return;
        }

        $procedurePhaseCockpit = $this->repository->findOneBy(['planId' => $procedureId])
            ?? (new XBeteiligungProcedurePhaseCockpit())
                ->setProcedureId($procedureId);

        $procedurePhaseCockpit
            ->setPlanId($procedureDataValueObject->getPlanId())
            ->setGeneralPhaseCode($procedurePhaseData->getGeneralPhaseCode())
            ->setPublicParticipationPhaseCode($procedurePhaseData->getPublicParticipationPhaseCode())
            ->setPublicParticipationSubPhaseCode($procedurePhaseData->getPublicParticipationSubPhaseCode())
            ->setInstitutionParticipationPhaseCode($procedurePhaseData->getInstitutionParticipationPhaseCode())
            ->setInstitutionParticipationSubPhaseCode($procedurePhaseData->getInstitutionParticipationSubPhaseCode());

        $this->repository->save($procedurePhaseCockpit);
    }

    public function getExternalProcedurePhaseCode(StatementCreated $statementCreated): string {
        /**
         * @var XBeteiligungProcedurePhaseCockpit $mapping
         */
        $planId = $statementCreated->getPlanId();
        $mapping = $this->repository->findOneBy(['planId' => $planId]);
        //@todo what happens when no mapping is found?
        //@todo it hapens with the existing statements

        if (!$mapping) {
            $code = '' === $this->xbeteiligungConfiguration->verfahrensschrittCode
                ? self::DEFAULT_PROCEDURE_PHASE_CODE
                : $this->xbeteiligungConfiguration->verfahrensschrittCode;
            return $code;
        }

        if($this->verfasserBuilder->getTypeOfPerson($statementCreated)) {

            return $mapping->getPublicParticipationPhaseCode();
        }
        return $mapping->getInstitutionParticipationPhaseCode();


    }

    public function getExternalProcedureSubPhaseCode(StatementCreated $statementCreated): ?string {
        /**
         * @var XBeteiligungProcedurePhaseCockpit $mapping
         */
        $planId = $statementCreated->getPlanId();
        $mapping = $this->repository->findOneBy(['planId' => $planId]);
        //@todo what happens when no mapping is found?
        //@todo it hapens with the existing statements

        if (!$mapping) {

            $code = '' === $this->xbeteiligungConfiguration->verfahrensteilschrittCode
                ? self::DEFAULT_PROCEDURE_PHASE_CODE
                : $this->xbeteiligungConfiguration->verfahrensteilschrittCode;
            return $code;
        }

        if($this->verfasserBuilder->getTypeOfPerson($statementCreated)) {

            return $mapping->getPublicParticipationSubPhaseCode();
        }
        return $mapping->getInstitutionParticipationSubPhaseCode();


    }
}
