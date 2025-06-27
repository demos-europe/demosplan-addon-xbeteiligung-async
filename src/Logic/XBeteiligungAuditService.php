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

    public const STATUS_PENDING = 'pending';
    public const STATUS_PROCESSED = 'processed';
    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';

    public function __construct(
        private readonly XBeteiligungMessageAuditRepository $auditRepository,
        private readonly LoggerInterface $logger,
    ) {}

    /**
     * Audit a received message from external system
     */
    public function auditReceivedMessage(
        string $xmlContent,
        string $messageType,
        ?string $planId = null,
        ?string $procedureId = null,
        ?string $responseToMessageId = null
    ): XBeteiligungMessageAudit {
        $audit = new XBeteiligungMessageAudit();
        $audit->setDirection(self::DIRECTION_RECEIVED);
        $audit->setMessageType($messageType);
        $audit->setMessageContent($xmlContent);
        $audit->setPlanId($planId);
        $audit->setProcedureId($procedureId);
        $audit->setResponseToMessageId($responseToMessageId);


        $this->auditRepository->save($audit);

        $this->logger->info('XBeteiligung Message Audit: Received message logged', [
            'auditId' => $audit->getId(),
            'messageType' => $messageType,
            'direction' => self::DIRECTION_RECEIVED,
            'procedureId' => $procedureId
        ]);

        return $audit;
    }

    /**
     * Audit a sent message to external system
     */
    public function auditSentMessage(
        string $xmlContent,
        string $messageType,
        ?string $procedureId = null,
        ?string $planId = null,
        ?string $responseToMessageId = null
    ): XBeteiligungMessageAudit {
        $audit = new XBeteiligungMessageAudit();
        $audit->setDirection(self::DIRECTION_SENT);
        $audit->setMessageType($messageType);
        $audit->setMessageContent($xmlContent);
        $audit->setProcedureId($procedureId);
        $audit->setPlanId($planId);
        $audit->setResponseToMessageId($responseToMessageId);
        $audit->setStatus(self::STATUS_PENDING);


        $this->auditRepository->save($audit);

        $this->logger->info('XBeteiligung Message Audit: Sent message logged', [
            'auditId' => $audit->getId(),
            'messageType' => $messageType,
            'direction' => self::DIRECTION_SENT,
            'procedureId' => $procedureId,
            'planId' => $planId
        ]);

        return $audit;
    }

    /**
     * Mark a message as processed and link to procedure if available
     */
    public function markAsProcessed(string $auditId, ?string $procedureId = null): void
    {
        $audit = $this->auditRepository->get($auditId);
        if (null === $audit) {
            $this->logger->warning(
                'XBeteiligung Message Audit: Cannot mark as processed - audit record not found',
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

        $this->logger->info('XBeteiligung Message Audit: Message marked as processed', [
            'auditId' => $auditId,
            'procedureId' => $procedureId
        ]);
    }

    /**
     * Mark a message as sent
     */
    public function markAsSent(string $auditId): void
    {
        $audit = $this->auditRepository->get($auditId);
        if (null === $audit) {
            $this->logger->warning(
                'XBeteiligung Message Audit: Cannot mark as sent - audit record not found',
                ['auditId' => $auditId]
            );
            return;
        }

        $audit->setStatus(self::STATUS_SENT);
        $audit->setSentAt(new DateTime());

        $this->auditRepository->save($audit);

        $this->logger->info('XBeteiligung Message Audit: Message marked as sent', ['auditId' => $auditId]);
    }

    /**
     * Mark a message as failed with error details
     */
    public function markAsFailed(string $auditId, string $errorDetails): void
    {
        $audit = $this->auditRepository->get($auditId);
        if (null === $audit) {
            $this->logger->warning(
                'XBeteiligung Message Audit: Cannot mark as failed - audit record not found',
                ['auditId' => $auditId]
            );
            return;
        }

        $audit->setStatus(self::STATUS_FAILED);
        $audit->setErrorDetails($errorDetails);

        $this->auditRepository->save($audit);

        $this->logger->error('XBeteiligung Message Audit: Message marked as failed', [
            'auditId' => $auditId,
            'errorDetails' => $errorDetails
        ]);
    }

    /**
     * Update audit record with procedure ID after procedure creation
     */
    public function updateAuditWithProcedureId(string $auditId, string $procedureId): void
    {
        $audit = $this->auditRepository->get($auditId);
        if (null === $audit) {
            $this->logger->warning(
                'XBeteiligung Message Audit: Cannot update with procedure ID - audit record not found',
                ['auditId' => $auditId]
            );
            return;
        }

        $audit->setProcedureId($procedureId);
        $this->auditRepository->save($audit);

        $this->logger->info('XBeteiligung Message Audit: Updated audit record with procedure ID', [
            'auditId' => $auditId,
            'procedureId' => $procedureId
        ]);
    }
}
