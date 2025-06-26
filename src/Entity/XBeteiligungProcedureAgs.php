<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Entity;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\Doctrine\Generator\UuidV4Generator;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedureAgsRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * XBeteiligungProcedureAgs - Stores AGS codes from 401 messages for routing key generation
 */
#[ORM\Entity(repositoryClass: XBeteiligungProcedureAgsRepository::class)]
#[ORM\Table(name: 'xbeteiligung_async_procedure_ags')]
class XBeteiligungProcedureAgs implements UuidEntityInterface
{
    #[ORM\Column(type: 'string', length: 36, nullable: false, options: ['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?string $id = null;

    #[ORM\Column(name: 'procedure_id', length: 36, type: 'string', nullable: false, unique: true)]
    private string $procedureId;

    #[ORM\Column(name: 'autor_ags_code', type: 'string', length: 255, nullable: false)]
    private string $autorAgsCode;

    #[ORM\Column(name: 'leser_ags_code', type: 'string', length: 255, nullable: false)]
    private string $leserAgsCode;

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(name: 'created_date', type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdDate;

    public function __construct(
        string $procedureId,
        string $autorAgsCode,
        string $leserAgsCode
    ) {
        $this->procedureId = $procedureId;
        $this->autorAgsCode = $autorAgsCode;
        $this->leserAgsCode = $leserAgsCode;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getProcedureId(): string
    {
        return $this->procedureId;
    }

    public function setProcedureId(string $procedureId): void
    {
        $this->procedureId = $procedureId;
    }

    public function getAutorAgsCode(): string
    {
        return $this->autorAgsCode;
    }

    public function setAutorAgsCode(string $autorAgsCode): void
    {
        $this->autorAgsCode = $autorAgsCode;
    }

    public function getLeserAgsCode(): string
    {
        return $this->leserAgsCode;
    }

    public function setLeserAgsCode(string $leserAgsCode): void
    {
        $this->leserAgsCode = $leserAgsCode;
    }

    public function getCreatedDate(): DateTime
    {
        return $this->createdDate;
    }

    public function setCreatedDate(DateTime $createdDate): void
    {
        $this->createdDate = $createdDate;
    }
}