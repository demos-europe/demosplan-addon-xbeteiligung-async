<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungMessageAudit;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungMessageAuditRepository;
use Psr\Log\LoggerInterface;

class XBeteiligungAuditService
{
    public const DIRECTION_RECEIVED = 'received';
    public const DIRECTION_SENT = 'sent';

    public const TARGET_SYSTEM_COCKPIT = 'cockpit';
    public const TARGET_SYSTEM_K3 = 'k3';

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    // Log message prefixes
    private const LOG_PREFIX = 'XBeteiligung Message Audit: ';

    public function __construct(
        private readonly XBeteiligungMessageAuditRepository $auditRepository,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Create audit record for any message
     */
    private function createAuditRecord(
        string $direction,
        string $targetSystem,
        string $xmlContent,
        string $messageType,
        ?string $procedureId = null,
        ?string $planId = null,
        ?string $responseToMessageId = null,
        ?string $statementId = null,
        ?string $routingKey = null,
        bool $saveOnFlush = false
    ): XBeteiligungMessageAudit {
        $audit = new XBeteiligungMessageAudit();
        $audit->setDirection($direction);
        $audit->setTargetSystem($targetSystem);
        $audit->setMessageType($messageType);
        $audit->setMessageContent($xmlContent);
        $audit->setProcedureId($procedureId);
        $audit->setPlanId($planId);
        $audit->setResponseToMessageId($responseToMessageId);
        $audit->setStatementId($statementId);
        $audit->setRoutingKey($routingKey);

        if (self::DIRECTION_SENT === $direction) {
            $audit->setStatus(self::STATUS_PENDING);
        }

        if ($saveOnFlush) {
            $this->auditRepository->saveOnFlush($audit);
        } else {
            $this->auditRepository->save($audit);
        }

        $this->logger->info(self::LOG_PREFIX . 'Message logged', [
            'auditId' => $audit->getId(),
            'direction' => $direction,
            'targetSystem' => $targetSystem,
            'messageType' => $messageType,
            'procedureId' => $procedureId
        ]);

        return $audit;
    }

    /**
     * Audit a received message from external system (Cockpit)
     */
    public function auditReceivedMessage(
        string $xmlContent,
        string $messageType,
        ?string $planId = null,
        ?string $procedureId = null,
        ?string $responseToMessageId = null,
        ?string $statementId = null,
        ?string $routingKey = null
    ): XBeteiligungMessageAudit {
        return $this->createAuditRecord(
            self::DIRECTION_RECEIVED,
            self::TARGET_SYSTEM_COCKPIT,
            $xmlContent,
            $messageType,
            $procedureId,
            $planId,
            $responseToMessageId,
            $statementId,
            $routingKey
        );
    }

    /**
     * Audit a sent message to external system (Cockpit)
     */
    public function auditSentMessage(
        string $xmlContent,
        string $messageType,
        ?string $procedureId = null,
        ?string $planId = null,
        ?string $responseToMessageId = null,
        ?string $statementId = null
    ): XBeteiligungMessageAudit {
        return $this->createAuditRecord(
            self::DIRECTION_SENT,
            self::TARGET_SYSTEM_COCKPIT,
            $xmlContent,
            $messageType,
            $procedureId,
            $planId,
            $responseToMessageId,
            $statementId,
            null,
        );
    }

    /**
     * Mark a message as processed and link to procedure if available
     */
    public function markAsProcessed(string $auditId, ?string $procedureId = null): void
    {
        $audit = $this->auditRepository->get($auditId);
        if (null === $audit) {
            $this->logger->warning(
                self::LOG_PREFIX . 'Cannot mark as processed - audit record not found',
                ['auditId' => $auditId]
            );
            return;
        }

        $audit->setStatus(self::STATUS_PROCESSED);
        $audit->setProcessedAt(new DateTime());

        if (null !== $procedureId) {
            $audit->setProcedureId($procedureId);
        }

        $this->auditRepository->save($audit);

        $this->logger->info(self::LOG_PREFIX . 'Message marked as processed', [
            'auditId' => $auditId,
            'procedureId' => $procedureId
        ]);
    }

    /**
     * Update audit record with callback function
     */
    private function updateAuditRecord(string $auditId, callable $updateFunction, string $logMessage, array $logContext = []): void
    {
        $audit = $this->auditRepository->get($auditId);
        if (null === $audit) {
            $this->logger->warning(
                self::LOG_PREFIX . 'Cannot update audit record - not found',
                ['auditId' => $auditId]
            );
            return;
        }

        $updateFunction($audit);
        $this->auditRepository->save($audit);

        $this->logger->info($logMessage, array_merge(['auditId' => $auditId], $logContext));
    }

    /**
     * Mark a message as sent
     */
    public function markAsSent(string $auditId): void
    {
        $this->updateAuditRecord(
            $auditId,
            function (XBeteiligungMessageAudit $audit) {
                $audit->setStatus(self::STATUS_SENT);
                $audit->setSentAt(new DateTime());
            },
            self::LOG_PREFIX . 'Message marked as sent'
        );
    }

    /**
     * Mark a message as failed with error details
     */
    public function markAsFailed(string $auditId, string $errorDetails): void
    {
        $this->updateAuditRecord(
            $auditId,
            function (XBeteiligungMessageAudit $audit) use ($errorDetails) {
                $audit->setStatus(self::STATUS_FAILED);
                $audit->setErrorDetails($errorDetails);
            },
            self::LOG_PREFIX . 'Message marked as failed',
            ['errorDetails' => $errorDetails]
        );
    }

    /**
     * Update audit record with procedure ID after procedure creation
     */
    public function updateAuditWithProcedureId(string $auditId, string $procedureId): void
    {
        $this->updateAuditRecord(
            $auditId,
            function (XBeteiligungMessageAudit $audit) use ($procedureId) {
                $audit->setProcedureId($procedureId);
            },
            self::LOG_PREFIX . 'Updated audit record with procedure ID',
            ['procedureId' => $procedureId]
        );
    }

    /**
     * Audit a message created for K3 system
     */
    public function auditK3Message(
        string $xmlContent,
        string $messageType,
        string $procedureId,
        ?string $planId = null,
        bool $saveOnFlush = false
    ): XBeteiligungMessageAudit {
        return $this->createAuditRecord(
            self::DIRECTION_SENT,
            self::TARGET_SYSTEM_K3,
            $xmlContent,
            $messageType,
            $procedureId,
            $planId,
            null,
            null,
            null,
            $saveOnFlush
        );
    }

    /**
     * Safely mark K3 message as delivered with proper validation
     * Returns true if successfully marked as delivered, false otherwise
     */
    public function markK3MessageAsDelivered(string $auditId): bool
    {
        $audit = $this->auditRepository->get($auditId);

        if (null === $audit) {
            $this->logger->warning(self::LOG_PREFIX . 'Audit record not found', ['auditId' => $auditId]);
            return false;
        }

        if (!$audit->isSentDirection()) {
            $this->logger->warning(self::LOG_PREFIX . 'Audit record is not a sent message', ['auditId' => $auditId]);
            return false;
        }

        if ($audit->getTargetSystem() !== self::TARGET_SYSTEM_K3) {
            $this->logger->warning(self::LOG_PREFIX . 'Audit record is not for K3 system', ['auditId' => $auditId]);
            return false;
        }

        if ($audit->getStatus() !== self::STATUS_PENDING) {
            $this->logger->info(self::LOG_PREFIX . 'Audit record already processed', [
                'auditId' => $auditId,
                'currentStatus' => $audit->getStatus()
            ]);
            return $audit->getStatus() === self::STATUS_SENT; // Return true if already sent
        }

        $this->markAsSent($auditId);
        return true;
    }


    /**
     * Find the original incoming 401 message for a procedure from cockpit
     * This should be unique - there should only be one per procedure
     */
    public function findOriginalIncoming401Message(string $procedureId): ?XBeteiligungMessageAudit
    {
        $auditRecords = $this->auditRepository->findByProcedureIdAndTargetSystem(
            $procedureId,
            self::TARGET_SYSTEM_COCKPIT
        );

        foreach ($auditRecords as $record) {
            if ($record->isReceived() &&
                XBeteiligungService::NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER === $record->getMessageType() &&
                $record->getProcedureId() === $procedureId &&
                $record->getTargetSystem() === self::TARGET_SYSTEM_COCKPIT) {
                return $record;
            }
        }

        return null;
    }

    /**
     * Find the original outgoing 701 message for a statement
     */
    public function findOriginalOutgoing701MessageByStatementId(string $statementId): ?XBeteiligungMessageAudit
    {
        $auditRecords = $this->auditRepository->findBy([
            'statementId' => $statementId,
            'messageType' => XBeteiligungService::NEW_STATEMENT_MESSAGE_IDENTIFIER,
            'direction' => self::DIRECTION_SENT
        ]);

        // Should be unique - there should only be one outgoing 701 message per statement
        return $auditRecords[0] ?? null;
    }
}
