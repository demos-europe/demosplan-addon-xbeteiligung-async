<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedureMapping;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedureMappingRepository;

/**
 * It will detect the corresponding phase in demos related to the corresponding external phase code
 * Right now, it does not do that, instead it returns the default ones and store the external codes in the database
 * to be used later when sending back the message
 */
class PhaseCodeMapper {
    public function __construct(
        private readonly XBeteiligungProcedureMappingRepository $repository
    ) {
    }

    public function storeExternalProcedurePhaseCodes(
        string $planId,
        ?string $publicPhaseCode,
        ?string $institutionPhaseCode
    ): void {
        $mapping = $this->repository->findOneBy(['planId' => $planId])
            ?? (new XBeteiligungProcedureMapping())
                ->setPlanId($planId);

        $mapping
            ->setPublicParticipationPhaseCode($publicPhaseCode)
            ->setInstitutionParticipationPhaseCode($institutionPhaseCode);

        $this->repository->save($mapping);
    }
}
