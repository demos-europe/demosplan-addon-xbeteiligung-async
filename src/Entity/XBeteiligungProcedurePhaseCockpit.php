<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Entity;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\Doctrine\Generator\UuidV4Generator;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedurePhaseCockpitRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * XBeteiligungProcedurePhaseCockpit - Stores XBeteiligung-specific procedure data
 *
 * This entity stores the external planId and phase codes needed for XBeteiligung
 * message generation. It maps demosplan procedures to their external identifiers
 * and participation phase codes.
 */
#[ORM\Entity(repositoryClass: XBeteiligungProcedurePhaseCockpitRepository::class)]
#[ORM\Table(name: 'xbeteiligung_procedure_phase_cockpit')]
class XBeteiligungProcedurePhaseCockpit implements UuidEntityInterface
{
    #[ORM\Column(type: 'string', length: 36, nullable: false, options: ['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?string $id = null;

    /**
     * Internal demosplan procedure ID
     */
    #[ORM\Column(name: 'procedure_id', type: 'string', length: 36, nullable: false)]
    private string $procedureId;

    /**
     * XBeteiligung planId - the external system's plan identifier
     * Used in all XBeteiligung messages to identify the procedure
     */
    #[ORM\Column(name: 'plan_id', type: 'string', length: 255, nullable: false)]
    private string $planId;

    /**
     * Public participation phase code
     * Code representing the current phase for public participation
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $publicParticipationPhaseCode = null;

    /**
     * General phase code
     * Code representing the current phase general phase code which is actually used for the public participation
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $generalPhaseCode = null;

    /**
     * Public participation sub phase code
     * Code representing the current phase for public participation
     * oeffentlichkeitVerfahrensteilschrittCode
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $publicParticipationSubPhaseCode = null;


    /**
     * Institution participation phase code
     * Code representing the current phase for institutional participation
     * $toebVerfahrensteilschrittCode
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $institutionParticipationPhaseCode = null;

    /**
     * Institution participation sub phase code
     * Code representing the current phase for institutional participation
     * $toebVerfahrensteilschrittCode
     */
    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $institutionParticipationSubPhaseCode = null;


    /**
     * Timestamps
     */
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column( type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $modifiedAt;

    // === Getters and Setters ===

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getProcedureId(): string
    {
        return $this->procedureId;
    }

    public function setProcedureId(string $procedureId): self
    {
        $this->procedureId = $procedureId;
        return $this;
    }

    public function getPlanId(): string
    {
        return $this->planId;
    }

    public function setPlanId(string $planId): self
    {
        $this->planId = $planId;
        return $this;
    }

    public function getGeneralPhaseCode(): ?string
    {
        return $this->generalPhaseCode;
    }

    public function setGeneralPhaseCode(?string $generalPhaseCode): self
    {
        $this->generalPhaseCode = $generalPhaseCode;
        return $this;
    }

    public function getPublicParticipationPhaseCode(): ?string
    {
        return $this->publicParticipationPhaseCode;
    }

    public function setPublicParticipationPhaseCode(?string $publicParticipationPhaseCode): self
    {
        $this->publicParticipationPhaseCode = $publicParticipationPhaseCode;
        return $this;
    }

    public function getInstitutionParticipationPhaseCode(): ?string
    {
        return $this->institutionParticipationPhaseCode;
    }

    public function setInstitutionParticipationPhaseCode(?string $institutionParticipationPhaseCode): self
    {
        $this->institutionParticipationPhaseCode = $institutionParticipationPhaseCode;
        return $this;
    }

    public function getPublicParticipationSubPhaseCode(): ?string
    {
        return $this->publicParticipationSubPhaseCode;
    }

    public function setPublicParticipationSubPhaseCode(?string $publicParticipationSubPhaseCode): self
    {
        $this->publicParticipationSubPhaseCode = $publicParticipationSubPhaseCode;
        return $this;
    }

    public function getInstitutionParticipationSubPhaseCode(): ?string
    {
        return $this->institutionParticipationSubPhaseCode;
    }

    public function setInstitutionParticipationSubPhaseCode(?string $institutionParticipationSubPhaseCode): self
    {
        $this->institutionParticipationSubPhaseCode = $institutionParticipationSubPhaseCode;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getModifiedAt(): DateTime
    {
        return $this->modifiedAt;
    }

}

