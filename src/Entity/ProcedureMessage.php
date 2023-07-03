<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Entity;
use DateTime;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\Timestampable;

/**
 * ProcedureMessage - Defines a specific message of Procedure
 */
#[ORM\Entity(repositoryClass: ProcedureMessageRepository::class)]
class ProcedureMessage implements UuidEntityInterface

{
    use Timestampable;

    /**
     * @var string|null
     */
    #[ORM\Column(type: 'string', length: 36, nullable: false, options:['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: \demosplan\DemosPlanCoreBundle\Doctrine\Generator\UuidV4Generator::class)]
    private $id;

    /**
     * Every message belongs to an (actual, non-template) procedure. But every procedure can have many relation or doesn't have any
     * with {@link ProcedureMessage}.
     * It fully depends on permissions and availability of the external ProcedureMessage service.
     *
     * @var ProcedureInterface
     *
     */
    #[ORM\ManyToOne(targetEntity: \demosplan\DemosPlanCoreBundle\Entity\Procedure\Procedure:: class)]
    #[ORM\JoinColumn(nullable: false, referencedColumnName: "_p_id", unique: true)]
    private $procedure;

    /**
     * @var string
     */
    #[ORM\Column(name: 'message', type: 'text', nullable: false)]
    private $message = '';

    /**
     * @var DateTime
     */
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private $createdDate;

    /**
     * @var DateTime
     *
     */
    #[Gedmo\Timestampable(on: "update")]
    #[ORM\Column(type: "datetime", nullable: false, options: ["default" => "CURRENT_TIMESTAMP"])]
    private $modificationDate;

    /**
     * @var bool
     */
    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false] )]
    private $error;

    /**
     * @var bool
     *
     */
    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false])]
    private bool $deleted;

    /**
     * @var int
     */
    #[ORM\Column(type: "integer", nullable: false, options: ["default" => 0])]
    private int $requestCount;

    public function __construct(
        string $message,
        bool $deleted,
        bool $error,
        bool $requestCount,
        ProcedureInterface $procedure
    ) {
        $this->message = $message;
        $this->deleted = $deleted;
        $this->error = $error;
        $this->requestCount = $requestCount;
        $this->procedure = $procedure;
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

    public function setRequestCount(): int
    {
        return $this->requestCount++;
    }

    public function getProcedure(): ProcedureInterface
    {
        return $this->procedure;
    }
}
