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

use DateTime;

/**
 * Value object to hold procedure data extracted from XML messages.
 * Used for validation and procedure creation from external XML sources.
 */
class ProcedureDataValueObject
{
    private ?string $processId;
    private ?string $planId;
    private ?string $planName;
    private ?string $workingTitle;
    private ?string $planType;
    private ?string $location;
    private ?string $description;
    private ?string $participationType;
    private ?string $proceduralStep;
    private ?DateTime $startDate;
    private ?DateTime $endDate;
    private ?string $contactOrganization;
    private ?string $contactPerson;
    private ?string $contactEmail;
    private ?string $contactPhone;
    private ?string $websiteUrl;
    private ?DateTime $announcementDate;
    private ?string $planDescription;
    private ?string $areaDelimitationUrl;
    private ?string $jurisdiction;
    private ?string $spatialDescription;
    private ?string $participationUrl;
    private ?int $iteration;
    private array $additionalInformation = [];

    public function __construct()
    {
    }

    public function getProcessId(): ?string
    {
        return $this->processId;
    }

    public function setProcessId(?string $processId): void
    {
        $this->processId = $processId;
    }

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

    public function getWorkingTitle(): ?string
    {
        return $this->workingTitle;
    }

    public function setWorkingTitle(?string $workingTitle): void
    {
        $this->workingTitle = $workingTitle;
    }

    public function getPlanType(): ?string
    {
        return $this->planType;
    }

    public function setPlanType(?string $planType): void
    {
        $this->planType = $planType;
    }

    public function getParticipationType(): ?string
    {
        return $this->participationType;
    }

    public function setParticipationType(?string $participationType): void
    {
        $this->participationType = $participationType;
    }

    public function getProceduralStep(): ?string
    {
        return $this->proceduralStep;
    }

    public function setProceduralStep(?string $proceduralStep): void
    {
        $this->proceduralStep = $proceduralStep;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): void
    {
        $this->location = $location;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(?\DateTime $startDate): void
    {
        $this->startDate = $startDate;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): void
    {
        $this->endDate = $endDate;
    }

    public function getContactOrganization(): ?string
    {
        return $this->contactOrganization;
    }

    public function setContactOrganization(?string $contactOrganization): void
    {
        $this->contactOrganization = $contactOrganization;
    }

    public function getContactPerson(): ?string
    {
        return $this->contactPerson;
    }

    public function setContactPerson(?string $contactPerson): void
    {
        $this->contactPerson = $contactPerson;
    }

    public function getContactEmail(): ?string
    {
        return $this->contactEmail;
    }

    public function setContactEmail(?string $contactEmail): void
    {
        $this->contactEmail = $contactEmail;
    }

    public function getContactPhone(): ?string
    {
        return $this->contactPhone;
    }

    public function setContactPhone(?string $contactPhone): void
    {
        $this->contactPhone = $contactPhone;
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->websiteUrl;
    }

    public function setWebsiteUrl(?string $websiteUrl): void
    {
        $this->websiteUrl = $websiteUrl;
    }

    public function getAdditionalInformation(): array
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(array $additionalInformation): void
    {
        $this->additionalInformation = $additionalInformation;
    }

    public function getAnnouncementDate(): ?\DateTime
    {
        return $this->announcementDate;
    }

    public function setAnnouncementDate(?\DateTime $announcementDate): void
    {
        $this->announcementDate = $announcementDate;
    }

    public function getPlanDescription(): ?string
    {
        return $this->planDescription;
    }

    public function setPlanDescription(?string $planDescription): void
    {
        $this->planDescription = $planDescription;
    }

    public function getAreaDelimitationUrl(): ?string
    {
        return $this->areaDelimitationUrl;
    }

    public function setAreaDelimitationUrl(?string $areaDelimitationUrl): void
    {
        $this->areaDelimitationUrl = $areaDelimitationUrl;
    }

    public function getJurisdiction(): ?string
    {
        return $this->jurisdiction;
    }

    public function setJurisdiction(?string $jurisdiction): void
    {
        $this->jurisdiction = $jurisdiction;
    }

    public function getSpatialDescription(): ?string
    {
        return $this->spatialDescription;
    }

    public function setSpatialDescription(?string $spatialDescription): void
    {
        $this->spatialDescription = $spatialDescription;
    }

    public function getParticipationUrl(): ?string
    {
        return $this->participationUrl;
    }

    public function setParticipationUrl(?string $participationUrl): void
    {
        $this->participationUrl = $participationUrl;
    }

    public function getIteration(): ?int
    {
        return $this->iteration;
    }

    public function setIteration(?int $iteration): void
    {
        $this->iteration = $iteration;
    }

}
