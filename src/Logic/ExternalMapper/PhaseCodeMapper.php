<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedurePhaseMapping;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedurePhaseMappingRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;

/**
 * It will detect the corresponding phase in demos related to the corresponding external phase code
 * Right now, it does not do that, instead it returns the default ones and store the external codes in the database
 * to be used later when sending back the message
 */
class PhaseCodeMapper {
    private const DEFAULT_PROCEDURE_PHASE_CODE = 'invalid';

    public function __construct(
        private readonly XBeteiligungProcedurePhaseMappingRepository $repository,
        private readonly VerfasserBuilder                            $verfasserBuilder,
        private readonly XBeteiligungConfiguration                   $xbeteiligungConfiguration,
    ) {
    }

    public function storeExternalProcedurePhaseCodes(
        string $planId,
        ?string $publicPhaseCode,
        ?string $institutionPhaseCode,
        ?string $publicSubPhaseCode,
        ?string $institutionSubPhaseCode,
    ): void {
        $mapping = $this->repository->findOneBy(['planId' => $planId])
            ?? (new XBeteiligungProcedurePhaseMapping())
                ->setPlanId($planId);

        $mapping
            ->setPublicParticipationPhaseCode($publicPhaseCode)
            ->setInstitutionParticipationPhaseCode($institutionPhaseCode)
            ->setPublicParticipationSubPhaseCode($publicSubPhaseCode)
            ->setInstitutionParticipationSubPhaseCode($institutionSubPhaseCode);

        $this->repository->save($mapping);
    }

    public function getExternalProcedurePhaseCode(StatementCreated $statementCreated): string {
        /**
         * @var XBeteiligungProcedurePhaseMapping $mapping
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
         * @var XBeteiligungProcedurePhaseMapping $mapping
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
