<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageHandler\Incoming;

use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungMessageAudit;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;

class IncomingMessageAuditHelper
{
    public function __construct(
        private readonly XBeteiligungAuditService $auditService,
        private readonly PlanIdExtractor $planIdExtractor
    ) {
    }

    /**
     * Create an audit record for an incoming message.
     */
    public function createAuditRecord(
        string $messageXml,
        string $messageType,
        ?string $routingKey
    ): XBeteiligungMessageAudit {
        $planId = $this->planIdExtractor->extractFromMessage($messageXml, $messageType);

        return $this->auditService->auditReceivedMessage(
            $messageXml,
            $messageType,
            $planId,
            null, // procedureId
            null, // responseToMessageId
            null, // statementId
            $routingKey
        );
    }

    /**
     * Create an audit record for a statement response message.
     */
    public function createStatementResponseAuditRecord(
        string $messageXml,
        string $messageType,
        string $statementId,
        ?string $routingKey
    ): XBeteiligungMessageAudit {
        // Find original 701 message to get procedureId, planId and for correlation
        $original701Message = $this->auditService->findOriginalOutgoing701MessageByStatementId($statementId);

        return $this->auditService->auditReceivedMessage(
            $messageXml,
            $messageType,
            $original701Message?->getPlanId(), // planId from original 701
            $original701Message?->getProcedureId(), // procedureId from original 701
            $original701Message?->getId(), // responseToMessageId - link to original 701
            $statementId,
            $routingKey
        );
    }

    /**
     * Mark an audit record as successfully processed.
     */
    public function markAsProcessed(
        ?XBeteiligungMessageAudit $auditRecord,
        ?string $procedureId = null
    ): void {
        if (null !== $auditRecord) {
            $this->auditService->markAsProcessed($auditRecord->getId());
            if (null !== $procedureId) {
                $this->auditService->updateAuditWithProcedureId($auditRecord->getId(), $procedureId);
            }
        }
    }

    /**
     * Mark an audit record as failed with error message.
     */
    public function markAsFailed(
        ?XBeteiligungMessageAudit $auditRecord,
        string $errorMessage
    ): void {
        if (null !== $auditRecord) {
            $this->auditService->markAsFailed($auditRecord->getId(), $errorMessage);
        }
    }
}
