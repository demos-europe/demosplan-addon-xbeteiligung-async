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

use demosplan\DemosPlanCoreBundle\ValueObject\ValueObject;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Value object to hold procedure data extracted from XML messages.
 * Used for validation and procedure creation from external XML sources.
 */
class ProcedureDataValueObject extends ValueObject
{
    // === Core Identifiers ===
    
    /**
     * Process/procedure identifier.
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    protected string $processId;

    /**
     * Plan/procedure ID.
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 255)]
    protected string $planId;

    // === Basic Information ===
    
    /**
     * Name of the plan/procedure.
     */
    #[Assert\NotBlank]
    #[Assert\Length(min: 1, max: 500)]
    protected string $planName;

    /**
     * Working title of the procedure.
     */
    protected ?string $workingTitle;

    /**
     * Type of the plan (e.g., infrastructure, construction).
     */
    protected ?string $planType;

    /**
     * Geographic location/area.
     */
    protected ?string $location;

    /**
     * Description of the procedure.
     */
    protected ?string $description;

    // === Procedural Information ===
    
    /**
     * Type of participation procedure.
     */
    protected ?string $participationType;

    /**
     * Current procedural step.
     */
    protected ?string $proceduralStep;

    // === Temporal Information ===
    
    /**
     * Start date of the procedure.
     */
    protected ?\DateTime $startDate;

    /**
     * End date of the procedure.
     */
    protected ?\DateTime $endDate;

    // === Contact Information ===
    
    /**
     * Contact information (organization name).
     */
    protected ?string $contactOrganization;

    /**
     * Contact person name.
     */
    protected ?string $contactPerson;

    /**
     * Contact email address.
     */
    #[Assert\Email]
    protected ?string $contactEmail;

    /**
     * Contact phone number.
     */
    protected ?string $contactPhone;

    // === Additional Information ===
    
    /**
     * Website URL related to the procedure.
     */
    #[Assert\Url]
    protected ?string $websiteUrl;

    /**
     * Additional metadata from the XML.
     */
    protected array $additionalInformation = [];

    public function getProcessId(): string
    {
        return $this->getProperty('processId');
    }

    public function getPlanId(): string
    {
        return $this->getProperty('planId');
    }

    public function getPlanName(): string
    {
        return $this->getProperty('planName');
    }

    public function getWorkingTitle(): ?string
    {
        return $this->getProperty('workingTitle');
    }

    public function getPlanType(): ?string
    {
        return $this->getProperty('planType');
    }

    public function getParticipationType(): ?string
    {
        return $this->getProperty('participationType');
    }

    public function getProceduralStep(): ?string
    {
        return $this->getProperty('proceduralStep');
    }

    public function getLocation(): ?string
    {
        return $this->getProperty('location');
    }

    public function getDescription(): ?string
    {
        return $this->getProperty('description');
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->getProperty('startDate');
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->getProperty('endDate');
    }

    public function getContactOrganization(): ?string
    {
        return $this->getProperty('contactOrganization');
    }

    public function getContactPerson(): ?string
    {
        return $this->getProperty('contactPerson');
    }

    public function getContactEmail(): ?string
    {
        return $this->getProperty('contactEmail');
    }

    public function getContactPhone(): ?string
    {
        return $this->getProperty('contactPhone');
    }

    public function getWebsiteUrl(): ?string
    {
        return $this->getProperty('websiteUrl');
    }

    public function getAdditionalInformation(): array
    {
        return $this->getProperty('additionalInformation');
    }
}