<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Services;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAgsService;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;

class XBeteiligungRoutingService
{
    public function __construct(
        private readonly XBeteiligungConfiguration $config,
        private readonly XBeteiligungAgsService $agsService,
        private readonly LoggerInterface $logger,
    ) {
    }

    public function buildIncomingRoutingKey(): string
    {
        return '*.cockpit.#';
    }

    /**
     * Build outgoing routing key for XBeteiligung messages.
     * Format: {project_type}.beteiligung.{xoevAddressPrefixKommunal}.{sender_ags}.{xoevAddressPrefixCockpit}.{receiver_ags}.{message_type}
     *
     * @throws InvalidArgumentException|Exception
     */
    public function buildOutgoingRoutingKey(string $messageType, ?string $procedureId): string
    {
        try {
            $projectType = $this->config->getProjectTypePrefix();

            // Get AGS codes from audit XML for the procedure
            $agsData = null;
            if (null !== $procedureId) {
                $agsData = $this->agsService->getAgsCodesForRouting($procedureId);
            }

            if (null === $agsData) {
                $this->logger->error('Cannot send message: No AGS codes found for procedure', [
                    'procedureId' => $procedureId,
                    'messageType' => $messageType,
                    'reason' => 'Missing AGS codes from audit XML'
                ]);

                throw new InvalidArgumentException(
                    sprintf('Cannot build routing key: No AGS codes found for procedure %s', $procedureId ?? 'null')
                );
            }
            if ('xyz:0001' === $agsData['sender']) {
                $agsPart = 'xyz.00.02.xyz.00.01';
            } else {
                $agsPart = $this->config->xoevAddressPrefixKommunal.'.'.$agsData['receiver'].'.'.
                    $this->config->xoevAddressPrefixCockpit.'.'.$agsData['sender'];
            }

            // Build XBeteiligung routing key format
            $routingKey = implode('.', [
                $projectType,
                'beteiligung',
                $agsPart,
                $messageType
            ]);

            $this->logger->info('Built XBeteiligung outgoing routing key', [
                'routingKey' => $routingKey,
                'procedureId' => $procedureId,
                'projectType' => $projectType,
                'xoevAddressPrefixKommunal' => $this->config->xoevAddressPrefixKommunal,
                'xoevAddressPrefixCockpit' => $this->config->xoevAddressPrefixCockpit,
                'senderAgs' => $agsData['sender'],
                'receiverAgs' => $agsData['receiver']
            ]);

            return $routingKey;

        } catch (Exception $e) {
            $this->logger->error('Cannot send message: Failed to build dynamic routing key', [
                'procedureId' => $procedureId,
                'messageType' => $messageType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // Re-throw the exception - do not send message if routing key cannot be built
            throw $e;
        }
    }
}
