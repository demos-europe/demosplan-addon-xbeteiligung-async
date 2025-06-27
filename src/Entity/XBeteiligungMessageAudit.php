<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Entity;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\UuidEntityInterface;
use DemosEurope\DemosplanAddon\Doctrine\Generator\UuidV4Generator;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungMessageAuditRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * XBeteiligungMessageAudit - Comprehensive audit logging for XBeteiligung messages
 */
#[ORM\Entity(repositoryClass: XBeteiligungMessageAuditRepository::class)]
#[ORM\Table(name: 'xbeteiligung_async_message_audit')]
class XBeteiligungMessageAudit implements UuidEntityInterface
{
    #[ORM\Column(type: 'string', length: 36, nullable: false, options: ['fixed' => true])]
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: UuidV4Generator::class)]
    private ?string $id = null;

    // === Message Classification ===
    #[ORM\Column(type: 'string', length: 20, nullable: false)]
    private string $direction;

    #[ORM\Column(type: 'string', length: 10, nullable: false)]
    private string $messageType;

    #[ORM\Column(type: 'string', length: 100, nullable: true)]
    private ?string $messageClass = null;

    // === Content Storage ===
    #[ORM\Column(type: 'text', nullable: false)]
    private string $messageContent;



    // === Procedure Relationship ===
    #[ORM\Column(type: 'string', length: 36, nullable: true)]
    private ?string $procedureId = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $planId = null;

    #[ORM\Column(type: 'string', length: 36, nullable: true)]
    private ?string $responseToMessageId = null;

    // === Processing Status ===
    #[ORM\Column(type: 'string', length: 20, nullable: false, options: ['default' => 'pending'])]
    private string $status = 'pending';

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $errorDetails = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $processingNotes = null;

    // === Timestamps ===
    #[Gedmo\Timestampable(on: 'create')]
    #[ORM\Column(type: 'datetime', nullable: false, options: ['default' => 'CURRENT_TIMESTAMP'])]
    private DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $processedAt = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTime $sentAt = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;
        return $this;
    }

    public function getMessageType(): string
    {
        return $this->messageType;
    }

    public function setMessageType(string $messageType): self
    {
        $this->messageType = $messageType;
        return $this;
    }

    public function getMessageClass(): ?string
    {
        return $this->messageClass;
    }

    public function setMessageClass(?string $messageClass): self
    {
        $this->messageClass = $messageClass;
        return $this;
    }

    public function getMessageContent(): string
    {
        return $this->messageContent;
    }

    public function setMessageContent(string $messageContent): self
    {
        $this->messageContent = $messageContent;
        return $this;
    }



    public function getProcedureId(): ?string
    {
        return $this->procedureId;
    }

    public function setProcedureId(?string $procedureId): self
    {
        $this->procedureId = $procedureId;
        return $this;
    }

    public function getPlanId(): ?string
    {
        return $this->planId;
    }

    public function setPlanId(?string $planId): self
    {
        $this->planId = $planId;
        return $this;
    }

    public function getResponseToMessageId(): ?string
    {
        return $this->responseToMessageId;
    }

    public function setResponseToMessageId(?string $responseToMessageId): self
    {
        $this->responseToMessageId = $responseToMessageId;
        return $this;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getErrorDetails(): ?string
    {
        return $this->errorDetails;
    }

    public function setErrorDetails(?string $errorDetails): self
    {
        $this->errorDetails = $errorDetails;
        return $this;
    }

    public function getProcessingNotes(): ?string
    {
        return $this->processingNotes;
    }

    public function setProcessingNotes(?string $processingNotes): self
    {
        $this->processingNotes = $processingNotes;
        return $this;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function getProcessedAt(): ?DateTime
    {
        return $this->processedAt;
    }

    public function setProcessedAt(?DateTime $processedAt): self
    {
        $this->processedAt = $processedAt;
        return $this;
    }

    public function getSentAt(): ?DateTime
    {
        return $this->sentAt;
    }

    public function setSentAt(?DateTime $sentAt): self
    {
        $this->sentAt = $sentAt;
        return $this;
    }

    public function isIncoming(): bool
    {
        return $this->direction === 'incoming';
    }

    public function isOutgoing(): bool
    {
        return $this->direction === 'outgoing';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessed(): bool
    {
        return $this->status === 'processed';
    }

    public function isSent(): bool
    {
        return $this->status === 'sent';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }
}