<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */


namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;

use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\InstitutionParticipationPhase;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\PublicParticipationPhase;

class ProcedurePhaseData extends ValueObject
{
    public function __construct(
        private ?PublicParticipationPhase $publicParticipationPhase,
        private ?InstitutionParticipationPhase $institutionParticipationPhase,
        private ?DateTime $publicParticipationStartDate,
        private ?DateTime $publicParticipationEndDate,
        private ?DateTime $institutionParticipationStartDate,
        private ?DateTime $institutionParticipationEndDate,
        private ?int $publicParticipationIteration,
        private ?int $institutionParticipationIteration
    ) {
        $this->publicParticipationPhase = $publicParticipationPhase;
        $this->institutionParticipationPhase = $institutionParticipationPhase;
        $this->publicParticipationStartDate = $publicParticipationStartDate;
        $this->publicParticipationEndDate = $publicParticipationEndDate;
        $this->institutionParticipationStartDate = $institutionParticipationStartDate;
        $this->institutionParticipationEndDate = $institutionParticipationEndDate;
        $this->publicParticipationIteration = $publicParticipationIteration;
        $this->institutionParticipationIteration = $institutionParticipationIteration;

        $this->lock();
    }

    public function getPublicParticipationPhase(): ?PublicParticipationPhase
    {
        return $this->publicParticipationPhase;
    }

    public function getInstitutionParticipationPhase(): ?InstitutionParticipationPhase
    {
        return $this->institutionParticipationPhase;
    }

    public function getPublicParticipationStartDate(): ?DateTime
    {
        return $this->publicParticipationStartDate;
    }

    public function getPublicParticipationEndDate(): ?DateTime
    {
        return $this->publicParticipationEndDate;
    }

    public function getInstitutionParticipationStartDate(): ?DateTime
    {
        return $this->institutionParticipationStartDate;
    }

    public function getInstitutionParticipationEndDate(): ?DateTime
    {
        return $this->institutionParticipationEndDate;
    }

    public function getPublicParticipationIteration(): ?int
    {
        return $this->publicParticipationIteration;
    }

    public function getInstitutionParticipationIteration(): ?int
    {
        return $this->institutionParticipationIteration;
    }
}
