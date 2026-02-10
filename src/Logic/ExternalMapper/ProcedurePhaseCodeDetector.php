<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedurePhaseCockpit;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedurePhaseCockpitRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;

/**
 * It will detect the corresponding phase in demos related to the corresponding external phase code
 * Right now, it does not do that, instead it returns the default ones and store the external codes in the database
 * to be used later when sending back the message
 */
class ProcedurePhaseCodeDetector {
    private const DEFAULT_PROCEDURE_PHASE_CODE = 'invalid';
    private const CONFIGURATION_PHASE = 'configuration';

    public function __construct(
        private readonly XBeteiligungProcedurePhaseCockpitRepository $repository,
        private readonly VerfasserBuilder                            $verfasserBuilder,
        private readonly XBeteiligungConfiguration                   $xbeteiligungConfiguration,
    ) {
    }

    public function storeExternalProcedurePhaseCodes(
        string $procedureId,
        ProcedureDataValueObject $procedureDataValueObject,
    ): void {
        $procedurePhaseData = $procedureDataValueObject->getProcedurePhaseData();

        if (null === $procedurePhaseData) {
            return;
        }

        $procedurePhaseCockpit = $this->repository->findOneBy(['procedureId' => $procedureId])
            ?? (new XBeteiligungProcedurePhaseCockpit())
                ->setProcedureId($procedureId)
                ->setPlanId($procedureDataValueObject->getPlanId());

        $procedurePhaseCockpit
            ->setGeneralPhaseCode($procedurePhaseData->getGeneralPhaseCode())
            ->setPublicParticipationPhaseCode($procedurePhaseData->getPublicParticipationPhaseCode())
            ->setPublicParticipationSubPhaseCode($procedurePhaseData->getPublicParticipationSubPhaseCode())
            ->setInstitutionParticipationPhaseCode($procedurePhaseData->getInstitutionParticipationPhaseCode())
            ->setInstitutionParticipationSubPhaseCode($procedurePhaseData->getInstitutionParticipationSubPhaseCode());

        $this->repository->save($procedurePhaseCockpit);
    }

    public function getExternalProcedurePhaseCode(StatementCreated $statementCreated): string {
        /**
         * @var XBeteiligungProcedurePhaseCockpit $xBeteiligungProcedurePhaseCockpit
         */
        $procedureId = $statementCreated->getProcedureId();
        $xBeteiligungProcedurePhaseCockpit = $this->repository->findOneBy(['procedureId' => $procedureId]);
        //@todo what happens when no mapping is found?
        //@todo it hapens with the existing statements

        if (!$xBeteiligungProcedurePhaseCockpit) {
            $code = '' === $this->xbeteiligungConfiguration->verfahrensschrittCode
                ? self::DEFAULT_PROCEDURE_PHASE_CODE
                : $this->xbeteiligungConfiguration->verfahrensschrittCode;
            return $code;
        }

        if($this->verfasserBuilder->getTypeOfPerson($statementCreated)) {
            if (null !==  $xBeteiligungProcedurePhaseCockpit->getPublicParticipationPhaseCode()) {
                return $xBeteiligungProcedurePhaseCockpit->getPublicParticipationPhaseCode();
            }
            return $xBeteiligungProcedurePhaseCockpit->getGeneralPhaseCode();
        }
        return $xBeteiligungProcedurePhaseCockpit->getInstitutionParticipationPhaseCode();


    }

    public function getExternalProcedureSubPhaseCode(StatementCreated $statementCreated): ?string {
        /**
         * @var XBeteiligungProcedurePhaseCockpit $xBeteiligungProcedurePhaseCockpit
         */
        $procedureId = $statementCreated->getProcedureId();
        $xBeteiligungProcedurePhaseCockpit = $this->repository->findOneBy(['procedureId' => $procedureId]);
        //@todo what happens when no mapping is found?
        //@todo it hapens with the existing statements

        if (!$xBeteiligungProcedurePhaseCockpit) {

            $code = '' === $this->xbeteiligungConfiguration->verfahrensteilschrittCode
                ? self::DEFAULT_PROCEDURE_PHASE_CODE
                : $this->xbeteiligungConfiguration->verfahrensteilschrittCode;
            return $code;
        }

        if($this->verfasserBuilder->getTypeOfPerson($statementCreated)) {

            return $xBeteiligungProcedurePhaseCockpit->getPublicParticipationSubPhaseCode();
        }
        return $xBeteiligungProcedurePhaseCockpit->getInstitutionParticipationSubPhaseCode();


    }

    public function getInstitutionParticipationPhaseKey(
        string $procedureId,
        ProcedurePhaseData $procedurePhaseData) {
        // Get existing codes from database
        /** @var XBeteiligungProcedurePhaseCockpit $existingProcedurePhaseCodes */
        $existingProcedurePhaseCodes = $this->repository->findOneBy(['procedureId' => $procedureId]);

        if (!$existingProcedurePhaseCodes) {
            return $procedurePhaseData->getInstitutionParticipationPhaseKey();
        }

        if ($existingProcedurePhaseCodes->getInstitutionParticipationPhaseCode() !== $procedurePhaseData->getInstitutionParticipationPhaseCode()) {
            return $procedurePhaseData->getPublicParticipationPhaseKey();
        }
        return null;
    }

    public function getPublicParticipationPhaseKey(
        string $procedureId,
        ProcedurePhaseData $procedurePhaseData) {
        // Get existing codes from database
        /** @var XBeteiligungProcedurePhaseCockpit $existingProcedurePhaseCodes */
        $existingProcedurePhaseCodes = $this->repository->findOneBy(['procedureId' => $procedureId]);
        if (!$existingProcedurePhaseCodes) {
            return $procedurePhaseData->getPublicParticipationPhaseKey();
        }
        if ($existingProcedurePhaseCodes->getPublicParticipationPhaseCode() !== $procedurePhaseData->getPublicParticipationPhaseKey()) {
            return $procedurePhaseData->getPublicParticipationPhaseKey();
        }
        return null;
    }

}
