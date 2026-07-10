<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Entity;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\Doctrine\Generator\UuidV4Generator;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeMappingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Maps an XBeteiligung Verfahrensschritt code to a ProcedurePhaseDefinition.
 *
 * Mandanten-Admins configure which DemoPlan phase definition corresponds to which
 * XBeteiligung Verfahrensschritt code (DPLAN-16767). Incoming messages use this mapping
 * to resolve the correct phase definition when a phase code change is detected.
 *
 * The constraint is one code per phase definition (unique on phase_definition_id).
 * Filtering by customer and audience is done via the linked ProcedurePhaseDefinition entity.
 */
#[ORM\Entity(repositoryClass: XBeteiligungPhaseDefinitionCodeMappingRepository::class)]
#[ORM\Table(name: 'xbeteiligung_phase_definition_code_mapping')]
class XBeteiligungPhaseDefinitionCodeMapping implements UuidEntityInterface
{
    #[ORM\Column(type: 'string', length: 36, nullable: false, options: ['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?string $id = null;

    /** XBeteiligung Verfahrensschritt code */
    #[ORM\Column(name: 'xbeteiligung_standard_code', type: 'string', length: 100, nullable: true)]
    private ?string $xBeteiligungStandardCode = null;

    #[ORM\ManyToOne(targetEntity: XBeteiligungDcatApPluStandardCode::class)]
    #[ORM\JoinColumn(name: 'dcat_ap_plu_standard_code_id', referencedColumnName: 'id', nullable: false)]
    private XBeteiligungDcatApPluStandardCode $dcatApPluStandardCode;

    #[ORM\ManyToOne(targetEntity: ProcedurePhaseDefinitionInterface::class)]
    #[ORM\JoinColumn(name: 'phase_definition_id', referencedColumnName: 'id', nullable: false, unique: true)]
    private ProcedurePhaseDefinitionInterface $phaseDefinition;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[Gedmo\Timestampable(on: 'update')]
    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $modifiedAt;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getXBeteiligungStandardCode(): ?string
    {
        return $this->xBeteiligungStandardCode;
    }

    public function setXBeteiligungStandardCode(?string $xBeteiligungStandardCode): self
    {
        $this->xBeteiligungStandardCode = $xBeteiligungStandardCode;

        return $this;
    }

    public function getDcatApPluStandardCode(): XBeteiligungDcatApPluStandardCode
    {
        return $this->dcatApPluStandardCode;
    }

    public function setDcatApPluStandardCode(XBeteiligungDcatApPluStandardCode $dcatApPluStandardCode): self
    {
        $this->dcatApPluStandardCode = $dcatApPluStandardCode;

        return $this;
    }

    /**
     * The DCAT-AP-PLU code to use for outgoing K3 messages, or null if this phase definition
     * hasn't been classified yet (DCAT code still "unknown") — callers fall back accordingly.
     */
    public function getEffectiveDcatCode(): ?string
    {
        return $this->dcatApPluStandardCode->isUnknown() ? null : $this->dcatApPluStandardCode->getCode();
    }

    public function getPhaseDefinition(): ProcedurePhaseDefinitionInterface
    {
        return $this->phaseDefinition;
    }

    public function setPhaseDefinition(ProcedurePhaseDefinitionInterface $phaseDefinition): self
    {
        $this->phaseDefinition = $phaseDefinition;

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
