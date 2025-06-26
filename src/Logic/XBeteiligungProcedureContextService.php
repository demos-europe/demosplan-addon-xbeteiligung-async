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

use Psr\Log\LoggerInterface;

/**
 * Request-scoped context service for temporarily storing AGS codes during procedure creation
 * Used to pass AGS codes from XBeteiligungService to XBeteiligungEventSubscriber
 *
 * This singleton service maintains in-memory storage for the duration of a single HTTP request.
 * Data is automatically isolated between different requests due to PHP's request lifecycle.
 */
class XBeteiligungProcedureContextService
{
    private array $procedureContexts = [];

    public function __construct(
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * Store AGS codes temporarily for a procedure that's about to be created
     */
    public function storeAgsContext(string $planId, array $agsCodes): void
    {
        $this->procedureContexts[$planId] = [
            'ags_codes' => $agsCodes,
            'created_at' => microtime(true)
        ];

        $this->logger->info('Stored AGS context for procedure', [
            'planId' => $planId,
            'autorAgs' => $agsCodes['autor'],
            'leserAgs' => $agsCodes['leser']
        ]);
    }

    /**
     * Retrieve and automatically clear AGS codes for a procedure
     * This should be called from the EventSubscriber after procedure creation
     */
    public function retrieveAndClearAgsContext(string $planId): ?array
    {
        $context = $this->procedureContexts[$planId] ?? null;

        if (null === $context) {
            $this->logger->warning('No AGS context found for procedure', [
                '$planId' => $planId,
                'availableContexts' => array_keys($this->procedureContexts)
            ]);

            return null;
        }

        // Remove from memory immediately (auto-cleanup)
        unset($this->procedureContexts[$planId]);

        $agsCodes = $context['ags_codes'];
        $this->logger->info('Retrieved and cleared AGS context for procedure', [
            '$planId' => $planId,
            'autorAgs' => $agsCodes['autor'],
            'leserAgs' => $agsCodes['leser'],
            'contextAge' => round((microtime(true) - $context['created_at']) * 1000, 2) . 'ms'
        ]);

        return $agsCodes;
    }
}
