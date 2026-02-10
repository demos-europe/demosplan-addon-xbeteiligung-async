<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedurePhaseCockpit;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedurePhaseCockpitRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;

class ProcedurePhaseCodeDetector {
    private const DEFAULT_PROCEDURE_PHASE_CODE = 'invalid';

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

        if (null === $xBeteiligungProcedurePhaseCockpit) {
            return $this->getFallbackPhaseCode();
        }

        if ($this->verfasserBuilder->isPrivatePerson($statementCreated)) {
            $phaseCode = $xBeteiligungProcedurePhaseCockpit->getPublicParticipationPhaseCode()
                ?? $xBeteiligungProcedurePhaseCockpit->getGeneralPhaseCode();
        } else {
            $phaseCode = $xBeteiligungProcedurePhaseCockpit->getInstitutionParticipationPhaseCode();
        }

        if (null === $phaseCode) {
            return $this->getFallbackPhaseCode();
        }

        return $phaseCode;

    }

    public function getExternalProcedureSubPhaseCode(StatementCreated $statementCreated): string {
        /**
         * @var XBeteiligungProcedurePhaseCockpit $xBeteiligungProcedurePhaseCockpit
         */
        $procedureId = $statementCreated->getProcedureId();
        $xBeteiligungProcedurePhaseCockpit = $this->repository->findOneBy(['procedureId' => $procedureId]);

        if (null === $xBeteiligungProcedurePhaseCockpit) {
            return $this->getFallbackSubPhaseCode();
        }

        $subPhaseCode = $this->verfasserBuilder->isPrivatePerson($statementCreated)
            ? $xBeteiligungProcedurePhaseCockpit->getPublicParticipationSubPhaseCode()
            : $xBeteiligungProcedurePhaseCockpit->getInstitutionParticipationSubPhaseCode();

        if (null === $subPhaseCode) {
            return $this->getFallbackSubPhaseCode();
        }


        return $subPhaseCode;
    }

    private function getFallbackSubPhaseCode(): string {
        return '' === $this->xbeteiligungConfiguration->verfahrensschrittCode
            ? self::DEFAULT_PROCEDURE_PHASE_CODE
            : $this->xbeteiligungConfiguration->verfahrensschrittCode;
    }

    private function getFallbackPhaseCode(): string {
        return '' === $this->xbeteiligungConfiguration->verfahrensschrittCode
            ? self::DEFAULT_PROCEDURE_PHASE_CODE
            : $this->xbeteiligungConfiguration->verfahrensschrittCode;
    }

    public function getInstitutionParticipationPhaseKey(
        string $procedureId,
        ProcedurePhaseData $procedurePhaseData): ?string {

        /** @var XBeteiligungProcedurePhaseCockpit $existingProcedurePhaseCodes */
        $existingProcedurePhaseCodes = $this->repository->findOneBy(['procedureId' => $procedureId]);

        if (!$existingProcedurePhaseCodes) {
            return $procedurePhaseData->getInstitutionParticipationPhaseKey();
        }

        if ($existingProcedurePhaseCodes->getInstitutionParticipationPhaseCode() !== $procedurePhaseData->getInstitutionParticipationPhaseCode()) {
            return $procedurePhaseData->getInstitutionParticipationPhaseKey();
        }


        return null;
    }

    public function getPublicParticipationPhaseKey(
        string $procedureId,
        ProcedurePhaseData $procedurePhaseData): ?string {

        /** @var XBeteiligungProcedurePhaseCockpit $existingProcedurePhaseCodes */
        $existingProcedurePhaseCodes = $this->repository->findOneBy(['procedureId' => $procedureId]);

        // If no existing codes, return the new public participation phase key
        if (!$existingProcedurePhaseCodes) {
            return $procedurePhaseData->getPublicParticipationPhaseKey();
        }

        // First check if we have an existing public participation phase code
        if (null !== $existingProcedurePhaseCodes->getPublicParticipationPhaseCode()) {
            // We have a public one, check if there is a new public one (compare them)
            if ($existingProcedurePhaseCodes->getPublicParticipationPhaseCode() !== $procedurePhaseData->getPublicParticipationPhaseCode()) {
                return $procedurePhaseData->getPublicParticipationPhaseKey();
            }
        } else {
            // If there is no public one, check if there is a generic one
            if (null !== $existingProcedurePhaseCodes->getGeneralPhaseCode()) {
                // We have a generic one, compare with the new generic one
                if ($existingProcedurePhaseCodes->getGeneralPhaseCode() !== $procedurePhaseData->getGeneralPhaseCode()) {
                    return $procedurePhaseData->getPublicParticipationPhaseKey();
                }
            }
        }

        // No changes detected
        return null;
    }


}
