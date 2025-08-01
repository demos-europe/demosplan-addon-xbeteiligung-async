<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Entity;
use DateTime;

use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * ProcedureMessage - Defines a specific message of Procedure
 */
#[ORM\Entity(repositoryClass: ProcedureMessageRepository::class)]
class ProcedureMessage implements UuidEntityInterface

{
    #[ORM\Column(type: 'string', length: 36, nullable: false, options:['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: \demosplan\DemosPlanCoreBundle\Doctrine\Generator\UuidV4Generator::class)]
    private ?string $id;

    #[ORM\Column(name: 'procedure_id', length: 36, type: 'string', nullable: false)]
    private string $procedureId;

    #[ORM\Column(name: 'message', type: 'text', nullable: false)]
    private string $message = '';

    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdDate;

    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: "datetime", nullable: false, options: ["default" => "CURRENT_TIMESTAMP"])]
    private DateTime $modificationDate;

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false] )]
    private bool $error;

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false])]
    private bool $deleted;

    #[ORM\Column(type: "integer", nullable: false, options: ["default" => 0])]
    private int $requestCount;

    #[ORM\Column(name: 'audit_id', type: 'string', length: 36, nullable: true)]
    private ?string $auditId = null;

    public function __construct(
        string $message,
        bool $deleted,
        bool $error,
        bool $requestCount,
        string $procedureId
    ) {
        $this->message = $message;
        $this->deleted = $deleted;
        $this->error = $error;
        $this->requestCount = $requestCount;
        $this->procedureId = $procedureId;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function getError(): bool
    {
        return $this->error;
    }

    public function setError(bool $error): void
    {
        $this->error = $error;
    }

    public function getDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(): bool
    {
        return $this->deleted = true;
    }

    public function getRequestCount(): int
    {
        return $this->requestCount;
    }

    public function increaseRequestCountByOne(): int
    {
        return $this->requestCount++;
    }

    public function getProcedureId(): string
    {
        return $this->procedureId;
    }

    public function getAuditId(): ?string
    {
        return $this->auditId;
    }

    public function setAuditId(?string $auditId): void
    {
        $this->auditId = $auditId;
    }
}
