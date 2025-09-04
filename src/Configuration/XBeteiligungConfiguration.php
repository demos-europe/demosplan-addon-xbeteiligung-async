<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Configuration;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

readonly class XBeteiligungConfiguration
{
    public function __construct(
        public bool $rabbitMqEnabled,
        public int $requestTimeout,
        public int $communicationDelay,
        public string $procedureMessageType,
        public bool $auditEnabled,
        public string $rabbitMqExchange,
        public string $xoevAddressPrefixKommunal,
        public string $xoevAddressPrefixCockpit,
        public int $maxMessagesPerCycle,
        public int $consumerTimeout,
        public string $procedureTypeName,
    ) {
        if ($this->requestTimeout <= 0) {
            throw new InvalidArgumentException('Request timeout must be positive');
        }

        if ($this->communicationDelay < 0) {
            throw new InvalidArgumentException('Communication delay cannot be negative');
        }

        if (empty($this->procedureMessageType)) {
            throw new InvalidArgumentException('Procedure message type cannot be empty');
        }

        $validProcedureTypes = ['kommunal', 'raumordnung', 'planfeststellung'];
        if (!in_array(strtolower($this->procedureMessageType), $validProcedureTypes, true)) {
            throw new InvalidArgumentException(
                sprintf('Invalid procedure message type "%s". Valid values: %s',
                    $this->procedureMessageType,
                    implode(', ', $validProcedureTypes)
                )
            );
        }

        if (empty($this->procedureTypeName)) {
            throw new InvalidArgumentException('Procedure type name cannot be empty');
        }
    }

    public static function fromParameterBag(ParameterBagInterface $params): self
    {
        return new self(
            $params->get('addon_xbeteiligung_async_enable_rabbitmq_communication'),
            $params->get('addon_xbeteiligung_async_rabbitMqRequestTimeout'),
            $params->get('addon_xbeteiligung_async_rabbitmq_communication_delay'),
            $params->get('addon_xbeteiligung_async_procedure_message_type'),
            $params->get('addon_xbeteiligung_async_enable_audit'),
            'init.cockpit',
            'bdp',
            'bap',
            $params->get('addon_xbeteiligung_async_max_messages_per_cycle'),
            $params->get('addon_xbeteiligung_async_consumer_timeout'),
            $params->get('addon_xbeteiligung_async_procedure_type_name'),
        );
    }

    public function getProjectTypePrefix(): string
    {
        return match (strtolower($this->procedureMessageType)) {
            'kommunal' => 'bau',
            'raumordnung' => 'rog',
            'planfeststellung' => 'pfv',
            default => throw new InvalidArgumentException(
                sprintf('Unknown procedure message type "%s"', $this->procedureMessageType)
            )
        };
    }

    /**
     * Get the RabbitMQ queue name based on procedure message type
     */
    public function getQueueName(): string
    {
        return match (strtolower($this->procedureMessageType)) {
            'kommunal' => 'bau.beteiligung',
            'raumordnung' => 'rog.beteiligung',
            'planfeststellung' => 'pfv.beteiligung',
            default => throw new InvalidArgumentException(
                sprintf('Unknown procedure message type "%s"', $this->procedureMessageType)
            )
        };
    }
}
