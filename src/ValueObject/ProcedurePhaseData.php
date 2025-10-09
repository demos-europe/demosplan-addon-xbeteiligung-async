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
        private ?string $publicParticipationPhaseKey,
        private ?string $institutionParticipationPhaseKey,
        private ?DateTime $publicParticipationStartDate,
        private ?DateTime $publicParticipationEndDate,
        private ?DateTime $institutionParticipationStartDate,
        private ?DateTime $institutionParticipationEndDate,
        private ?int $publicParticipationIteration,
        private ?int $institutionParticipationIteration
    ) {
        $this->publicParticipationPhaseKey = $publicParticipationPhaseKey;
        $this->institutionParticipationPhaseKey = $institutionParticipationPhaseKey;
        $this->publicParticipationStartDate = $publicParticipationStartDate;
        $this->publicParticipationEndDate = $publicParticipationEndDate;
        $this->institutionParticipationStartDate = $institutionParticipationStartDate;
        $this->institutionParticipationEndDate = $institutionParticipationEndDate;
        $this->publicParticipationIteration = $publicParticipationIteration;
        $this->institutionParticipationIteration = $institutionParticipationIteration;

        $this->lock();
    }

    public function getPublicParticipationPhaseKey(): ?string
    {
        return $this->publicParticipationPhaseKey;
    }

    public function getInstitutionParticipationPhaseKey(): ?string
    {
        return $this->institutionParticipationPhaseKey;
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
