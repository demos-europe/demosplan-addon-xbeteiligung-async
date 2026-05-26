<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */


namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;

use DateTime;

class ProcedurePhaseData extends ValueObject
{
    public function __construct(
        private ?string $generalPhaseCode,
        private ?string $publicParticipationPhaseCode,
        private ?string $publicParticipationSubPhaseCode,
        private ?string $institutionParticipationPhaseCode,
        private ?string $institutionParticipationSubPhaseCode,
        private ?DateTime $publicParticipationStartDate,
        private ?DateTime $publicParticipationEndDate,
        private ?DateTime $institutionParticipationStartDate,
        private ?DateTime $institutionParticipationEndDate,
        private ?int $publicParticipationIteration,
        private ?int $institutionParticipationIteration
    ) {
        $this->generalPhaseCode = $generalPhaseCode;
        $this->publicParticipationPhaseCode = $publicParticipationPhaseCode;
        $this->publicParticipationSubPhaseCode = $publicParticipationSubPhaseCode;
        $this->institutionParticipationPhaseCode = $institutionParticipationPhaseCode;
        $this->institutionParticipationSubPhaseCode = $institutionParticipationSubPhaseCode;
        $this->publicParticipationStartDate = $publicParticipationStartDate;
        $this->publicParticipationEndDate = $publicParticipationEndDate;
        $this->institutionParticipationStartDate = $institutionParticipationStartDate;
        $this->institutionParticipationEndDate = $institutionParticipationEndDate;
        $this->publicParticipationIteration = $publicParticipationIteration;
        $this->institutionParticipationIteration = $institutionParticipationIteration;

        $this->lock();
    }

    public function getGeneralPhaseCode(): ?string
    {
        return $this->generalPhaseCode;
    }

    public function getPublicParticipationPhaseCode(): ?string
    {
        return $this->publicParticipationPhaseCode;
    }

    public function getPublicParticipationSubPhaseCode(): ?string
    {
        return $this->publicParticipationSubPhaseCode;
    }

    public function getInstitutionParticipationPhaseCode(): ?string
    {
        return $this->institutionParticipationPhaseCode;
    }

    public function getInstitutionParticipationSubPhaseCode(): ?string
    {
        return $this->institutionParticipationSubPhaseCode;
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
