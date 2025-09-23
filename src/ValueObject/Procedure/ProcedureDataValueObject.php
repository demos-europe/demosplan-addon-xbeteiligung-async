<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure;

use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\AnlageValueObject;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\MapData;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;

/**
 * Value object to hold procedure data extracted from XML messages.
 * Used for validation and procedure creation from external XML sources.
 */
class ProcedureDataValueObject
{
    private ?string $planId;
    private ?string $planName;
    private ?string $contactOrganization;
    private ?string $planDescription;
    /**
     * @var AnlageValueObject[]
     */
    private array $anlagen = [];

    private ?ProcedurePhaseData $procedurePhaseData = null;
    private ?MapData $mapData = null;

    public function getPlanId(): ?string
    {
        return $this->planId;
    }

    public function setPlanId(?string $planId): void
    {
        $this->planId = $planId;
    }

    public function getPlanName(): ?string
    {
        return $this->planName;
    }

    public function setPlanName(?string $planName): void
    {
        $this->planName = $planName;
    }

    public function getContactOrganization(): ?string
    {
        return $this->contactOrganization;
    }

    public function setContactOrganization(?string $contactOrganization): void
    {
        $this->contactOrganization = $contactOrganization;
    }

    public function getPlanDescription(): ?string
    {
        return $this->planDescription;
    }

    public function setPlanDescription(?string $planDescription): void
    {
        $this->planDescription = $planDescription;
    }

    /**
     * @return AnlageValueObject[]
     */
    public function getAnlagen(): array
    {
        return $this->anlagen;
    }

    /**
     * @param AnlageValueObject[] $anlagen
     */
    public function setAnlagen(array $anlagen): void
    {
        $this->anlagen = $anlagen;
    }

    public function getProcedurePhaseData(): ?ProcedurePhaseData
    {
        return $this->procedurePhaseData;
    }

    public function setProcedurePhaseData(ProcedurePhaseData $procedurePhaseData): void
    {
        $this->procedurePhaseData = $procedurePhaseData;
    }

    public function getMapData(): ?MapData
    {
        return $this->mapData;
    }

    public function setMapData(?MapData $mapData): void
    {
        $this->mapData = $mapData;
    }
}
