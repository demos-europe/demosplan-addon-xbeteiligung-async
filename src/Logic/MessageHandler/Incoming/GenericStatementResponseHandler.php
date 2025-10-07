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
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenNOK0721;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenOK0711;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\FehlerType;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use Psr\Log\LoggerInterface;

class GenericStatementResponseHandler implements IncomingMessageHandlerInterface
{
    private string $currentMessageType = '';

    public function __construct(
        private readonly XBeteiligungIncomingMessageParser $incomingMessageParser,
        private readonly IncomingMessageAuditHelper $auditHelper,
        private readonly StatementIdExtractor $statementIdExtractor,
        private readonly LoggerInterface $logger
    ) {
    }

    /**
     * @throws SchemaException
     */
    public function handleIncomingMessage(string $messageXml, bool $auditEnabled, ?string $routingKey): ?ResponseValue
    {
        $this->currentMessageType = XBeteiligungMessageType::fromXmlContent($messageXml);

        $this->logger->debug('Processing statement response message', [
            'messageType' => $this->currentMessageType,
            'routingKey' => $routingKey
        ]);

        /** @var AllgemeinStellungnahmeNeuabgegebenOK0711|AllgemeinStellungnahmeNeuabgegebenNOK0721 $xmlObject */
        $xmlObject = $this->parseXmlMessage($messageXml);
        $statementId = $this->statementIdExtractor->extractFromXml($xmlObject);

        $this->processStatementResponse($messageXml, $xmlObject, $statementId, $auditEnabled, $routingKey);

        // Statement responses don't require a return value
        return null;
    }

    /**
     * @throws SchemaException
     */
    private function parseXmlMessage(string $messageXml): NachrichtG2GTypeType
    {
        $messageEnum = XBeteiligungMessageType::tryFrom($this->currentMessageType);
        $messageCode = $messageEnum?->getMessageCode();

        if (null === $messageCode) {
            throw new SchemaException("No message code for type: {$this->currentMessageType}");
        }

        return $this->incomingMessageParser->getXmlObject($messageXml, $messageCode);
    }

    private function processStatementResponse(
        string $messageXml,
        NachrichtG2GTypeType $xmlObject,
        string $statementId,
        bool $auditEnabled,
        ?string $routingKey
    ): void {
        if ($auditEnabled) {
            $auditRecord = $this->auditHelper->createStatementResponseAuditRecord(
                $messageXml,
                $this->currentMessageType,
                $statementId,
                $routingKey
            );

            if ($this->currentMessageType === XBeteiligungMessageType::STELLUNGNAHME_OK->value) {
                $this->auditHelper->markAsProcessed($auditRecord);
            }
            if ($this->currentMessageType === XBeteiligungMessageType::STELLUNGNAHME_NOK->value) {
                $errorMessage = $this->extractErrorMessage($xmlObject);
                $this->auditHelper->markAsFailed($auditRecord, $errorMessage);
            }
        }

        $this->logStatementResponse($statementId, $xmlObject);
    }

    private function extractErrorMessage(NachrichtG2GTypeType $xmlObject): string
    {
        if ($this->currentMessageType !== XBeteiligungMessageType::STELLUNGNAHME_NOK->value) {
            return '';
        }

        /** @var AllgemeinStellungnahmeNeuabgegebenNOK0721 $newStatementNOK721 */
        $newStatementNOK721 = $xmlObject;
        $errorMessagesArray = $newStatementNOK721->getNachrichteninhalt()?->getFehler() ?? [];

        return $this->extractErrorDescriptions($errorMessagesArray);
    }

    private function extractErrorDescriptions(array $errorMessage): string
    {
        $errorDescriptions = [];
        foreach ($errorMessage as $fehler) {
            if ($fehler instanceof FehlerType) {
                $beschreibung = $fehler->getBeschreibung();
                if (null !== $beschreibung) {
                    $errorDescriptions[] = $beschreibung;
                }
            }
        }

        return [] !== $errorDescriptions
            ? implode('; ', $errorDescriptions)
            : 'Statement rejected by cockpit';
    }

    private function logStatementResponse(string $statementId, NachrichtG2GTypeType $xmlObject): void
    {
        if ($this->currentMessageType === XBeteiligungMessageType::STELLUNGNAHME_OK->value) {
            $this->logger->info('Statement OK response processed', [
                'statementId' => $statementId,
                'messageType' => $this->currentMessageType
            ]);
        }

        if ($this->currentMessageType === XBeteiligungMessageType::STELLUNGNAHME_NOK->value) {
            $errorMessage = $this->extractErrorMessage($xmlObject);
            $this->logger->warning('Statement NOK response processed', [
                'statementId' => $statementId,
                'errorMessage' => $errorMessage,
                'messageType' => $this->currentMessageType
            ]);
        }
    }
}
