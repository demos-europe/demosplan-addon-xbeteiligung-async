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
use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\Doctrine\Generator\UuidV4Generator;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungFileMappingRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * XBeteiligungFileMapping - Tracks relationship between XML dokumentId and demosplan file entities
 *
 * This entity enables proper file replacement when 402 messages (procedure updates) contain
 * attachments with the same dokumentId as previous 401/402 messages.
 */
#[ORM\Entity(repositoryClass: XBeteiligungFileMappingRepository::class)]
#[ORM\Table(name: 'xbeteiligung_file_mapping')]
#[ORM\UniqueConstraint(name: 'unique_xml_file_per_procedure', columns: ['xml_file_id', 'procedure_id'])]
class XBeteiligungFileMapping implements UuidEntityInterface
{
    #[ORM\Column(type: 'string', length: 36, nullable: false, options: ['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?string $id = null;

    /**
     * @var string The dokumentId from XBeteiligung XML message
     */
    #[ORM\Column(type: 'string', length: 255, nullable: false)]
    private string $xmlFileId;

    /**
     * @var string Foreign key to _procedure table
     */
    #[ORM\Column(type: 'string', length: 36, nullable: false)]
    private string $procedureId;

    /**
     * @var string Foreign key to _files table (_f_ident)
     */
    #[ORM\Column(type: 'string', length: 36, nullable: false)]
    private string $fileId;

    /**
     * @var string Foreign key to _single_doc table (_sd_id)
     */
    #[ORM\Column(type: 'string', length: 36, nullable: false)]
    private string $singleDocumentId;

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

    public function getXmlFileId(): string
    {
        return $this->xmlFileId;
    }

    public function setXmlFileId(string $xmlFileId): self
    {
        $this->xmlFileId = $xmlFileId;
        return $this;
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

    public function getFileId(): string
    {
        return $this->fileId;
    }

    public function setFileId(string $fileId): self
    {
        $this->fileId = $fileId;
        return $this;
    }

    public function getSingleDocumentId(): string
    {
        return $this->singleDocumentId;
    }

    public function setSingleDocumentId(string $singleDocumentId): self
    {
        $this->singleDocumentId = $singleDocumentId;
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
