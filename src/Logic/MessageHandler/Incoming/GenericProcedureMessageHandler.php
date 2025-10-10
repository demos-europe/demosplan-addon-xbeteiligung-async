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

use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureUpdater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use Psr\Log\LoggerInterface;

class GenericProcedureMessageHandler implements IncomingMessageHandlerInterface
{
    private string $currentMessageType = '';

    public function __construct(
        private readonly XBeteiligungIncomingMessageParser $incomingMessageParser,
        private readonly KommunaleProcedureCreater $kommunaleProcedureCreater,
        private readonly KommunaleProcedureUpdater $kommunaleProcedureUpdater,
        private readonly IncomingMessageAuditHelper $auditHelper,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws SchemaException
     * @throws Exception
     */
    public function handleIncomingMessage(string $messageXml, bool $auditEnabled, ?string $routingKey): ?ResponseValue
    {
        $this->logger->debug('Processing procedure message', [
            'routingKey' => $routingKey
        ]);

        /* @var KommunalInitiieren0401|KommunalAktualisieren0402|PlanfeststellungInitiieren0201 $xmlObject */
        $xmlObject = $this->parseXmlMessage($messageXml);

        $auditRecord = null;
        if ($auditEnabled) {
            $auditRecord = $this->auditHelper->createAuditRecord($messageXml, $this->currentMessageType, $routingKey);
        }

        try {
            $response = $this->processMessage($xmlObject, $routingKey);
            $this->auditHelper->markAsProcessed($auditRecord, $response->getProcedureId());

            return $response;
        } catch (Exception $e) {
            $this->auditHelper->markAsFailed($auditRecord, $e->getMessage());
            throw $e;
        }
    }

    /**
     * @throws SchemaException
     */
    private function parseXmlMessage(string $messageXml): NachrichtG2GTypeType
    {
        $this->currentMessageType = XBeteiligungMessageType::fromXmlContent($messageXml);
        $messageEnum = XBeteiligungMessageType::tryFrom($this->currentMessageType);

        if (null === $messageEnum) {
            throw new SchemaException("Unsupported message type: {$this->currentMessageType}");
        }

        $messageCode = $messageEnum->getMessageCode();
        if (null === $messageCode) {
            throw new SchemaException("No message code for type: {$this->currentMessageType}");
        }

        return $this->incomingMessageParser->getXmlObject($messageXml, $messageCode);
    }

    /**
     * @throws Exception
     */
    private function processMessage(
        KommunalInitiieren0401|KommunalAktualisieren0402|PlanfeststellungInitiieren0201 $xmlObject,
        ?string $routingKey
    ): ResponseValue {
        return match ($this->currentMessageType) {
            XBeteiligungMessageType::KOMMUNAL_INITIIEREN->value,
            XBeteiligungMessageType::PLANFESTSTELLUNG_INITIIEREN->value
            => $this->kommunaleProcedureCreater->createNewProcedureFromXBeteiligungMessageOrErrorMessage(
                $xmlObject,
                $routingKey
            ),
            /*XBeteiligungMessageType::KOMMUNAL_AKTUALISIEREN->value
            => $this->kommunaleProcedureUpdater->updateProcedure($xmlObject),*/
            default
            => throw new Exception("Unsupported message type for processing: {$this->currentMessageType}")
        };
    }
}
