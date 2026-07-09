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

use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\Doctrine\Generator\UuidV4Generator;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungDcatApPluStandardCodeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * One of the fixed DCAT-AP-PLU Verfahrensschritt codes (e.g. "earlyInvolveAuth").
 *
 * The set of rows is seeded once via migration and is not admin-editable (DPLAN-18120) —
 * this entity only exists to give XBeteiligungPhaseDefinitionCodeMapping a proper
 * relationship to select from, instead of a free-text field.
 */
#[ORM\Entity(repositoryClass: XBeteiligungDcatApPluStandardCodeRepository::class)]
#[ORM\Table(name: 'xbeteiligung_dcat_ap_plu_standard_code')]
class XBeteiligungDcatApPluStandardCode implements UuidEntityInterface
{
    #[ORM\Column(type: 'string', length: 36, nullable: false, options: ['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?string $id = null;

    #[ORM\Column(name: 'code', type: 'string', length: 100, nullable: false, unique: true)]
    private string $code;

    #[ORM\Column(name: 'description', type: 'string', length: 255, nullable: false)]
    private string $description;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
