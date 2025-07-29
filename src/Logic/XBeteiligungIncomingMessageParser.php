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

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\XBeteiligungResponseMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use function in_array;

class XBeteiligungIncomingMessageParser
{
    public const INCOMING_MESSAGE = 'Incoming Message could not be validated';
    public const UNEXPECTED_NAME = 'Unexpected name, won’t continue';

    private array $messageTypeMapping = [
        '401' => [
            'class' => KommunalInitiieren0401::class,
            'identifier' => XBeteiligungService::NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '402' => [
            'class' => KommunalAktualisieren0402::class,
            'identifier' => XBeteiligungService::UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '409' => [
            'class' => KommunalLoeschen0409::class,
            'identifier' => XBeteiligungService::DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '301' => [
            'class' => RaumordnungInitiieren0301::class,
            'identifier' => XBeteiligungService::NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '302' => [
            'class' => RaumordnungAktualisieren0302::class,
            'identifier' => XBeteiligungService::UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '309' => [
            'class' => RaumordnungLoeschen0309::class,
            'identifier' => XBeteiligungService::DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '201' => [
            'class' => PlanfeststellungInitiieren0201::class,
            'identifier' => XBeteiligungService::NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '202' => [
            'class' => PlanfeststellungAktualisieren0202::class,
            'identifier' => XBeteiligungService::UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '209' => [
            'class' => PlanfeststellungLoeschen0209::class,
            'identifier' => XBeteiligungService::DELETE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
    ];

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * @throws SchemaException
     */
    public function getXmlObject(string $incomingMessage, string $messageType): NachrichtG2GTypeType
    {
        if (!isset($this->messageTypeMapping[$messageType])) {
            throw new SchemaException("Invalid message type: $messageType");
        }

        $messageClass = $this->messageTypeMapping[$messageType]['class'];
        $expectedXmlName = $this->messageTypeMapping[$messageType]['identifier'];

        $simpleXML = $this->getSimpleXmlElementWithCertainty($incomingMessage, $messageType);
        $this->validateRequiredNamespace($simpleXML);
        $this->validateXmlName($simpleXML, $expectedXmlName);

        $parsedMessage = $this->deserializeMessageWithCertainty($incomingMessage, $messageClass);
        if ($parsedMessage instanceof $messageClass) {
            return $parsedMessage;
        }
        throw new SchemaException(self::INCOMING_MESSAGE);
    }

    /**
     * @throws SchemaException
     */
    private function validateXmlName(SimpleXMLElement $simpleXML, string $expectedXmlName): void
    {
        // Get the local name without namespace prefix
        $name = $simpleXML->getName();

        // If there's a namespace prefix, strip it off to get the local name
        if (($pos = strpos($name, ':')) !== false) {
            $localName = substr($name, $pos + 1);
            $this->logger->info('XML element has namespace prefix', [
                'fullName' => $name,
                'localName' => $localName
            ]);

            // If the local part matches expected patterns, accept it
            if (str_contains(
                $localName,
                'planung2Beteiligung.BeteiligungKommunalNeu.0401'
            )) {
                $this->logger->info('Accepted XML with namespace prefix', [
                    'expected' => $expectedXmlName,
                    'received' => $name,
                    'messageType' => '401'
                ]);
                return;
            }
        }

        // Similar to how XBauleitplanung parser works, we'll extract key parts
        // of the message type (like 0401, 0402) and match on those
        $msgCode = '';
        if (preg_match('/\.(0\d{3})$/', $expectedXmlName, $matches)) {
            $msgCode = $matches[1];
        }

        // If we have a message code, check if it appears in the element name
        if ($msgCode && str_contains($name, $msgCode)) {
            $this->logger->info('XML element validated by message code', [
                'expected' => $expectedXmlName,
                'received' => $name,
                'code' => $msgCode
            ]);
            return;
        }

        // Original check as fallback
        if ($expectedXmlName === $name) {
            return;
        }

        $this->logger->error('Unexpected message type name in XML', [
            'expected' => $expectedXmlName,
            'received' => $name,
        ]);
        throw new SchemaException(self::UNEXPECTED_NAME);
    }


    /**
     * @throws SchemaException
     */
    private function getSimpleXmlElementWithCertainty(string $incomingMessage, string $messageType): SimpleXMLElement
    {
        libxml_use_internal_errors(true);
        $simpleXML = simplexml_load_string($incomingMessage);
        if (false === $simpleXML) {
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $this->logger->error('XML parsing error', [
                    'messageType' => $messageType,
                    'message' => $error->message,
                    'line' => $error->line,
                    'column' => $error->column,
                ]);
            }
            libxml_clear_errors();
            throw new SchemaException('Could not parse incoming message as XML');
        }
        return $simpleXML;
    }

    /**
     * Validates that the incoming XML contains the expected xBeteiligung namespace.
     *
     * The primary namespace for this addon is XLeitstelle xBeteiligung (xleitstelle.de/xbeteiligung/12)
     * as we implement the xBeteiligung standard for public participation workflows.
     *
     * XPlan namespace (xplanverfahren.de/V14) is for spatial planning data exchange and is often
     * declared in XML for compatibility but typically unused in actual message content, so it serves as a fallback only.
     */
    private function validateRequiredNamespace(SimpleXMLElement $simpleXML): void
    {
        $namespaces = $simpleXML->getNamespaces();
        $this->logger->info('XML namespaces', ['namespaces' => $namespaces]);

        // Check for our primary expected namespace (XLeitstelle xBeteiligung 1.2)
        $expectedNamespace = 'https://www.xleitstelle.de/xbeteiligung/12';
        foreach ($namespaces as $prefix => $namespace) {
            if ($namespace === $expectedNamespace) {
                $this->logger->info('Found expected XLeitstelle xBeteiligung namespace', [
                    'namespace' => $expectedNamespace
                ]);
                return;
            }
        }

        $this->logger->warning('Missing expected namespace', [
            'expected' => $expectedNamespace,
            'found' => $namespaces
        ]);
    }

    /**
     * @throws SchemaException
     */
    private function deserializeMessageWithCertainty(string $incomingMessage, string $className): NachrichtG2GTypeType
    {
        try {
            /** @var NachrichtG2GTypeType $message */
            $message = SerializerFactory::getSerializer()->deserialize(
                $incomingMessage,
                $className,
                'xml'
            );

            $this->logger->info('Successfully deserialized XML message', [
                'class' => $className,
                'produkt' => $message?->getProdukt(),
                'hasMessage' => $message !== null
            ]);

            if (null === $message || null === $message->getProdukt()) {
                throw new SchemaException('Incoming message is not a valid '.$className.' message');
            }

            return $message;
        } catch (\Exception $e) {
            $this->logger->error('Error deserializing XML message', [
                'class' => $className,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw new SchemaException('Error deserializing message as '.$className.': '.$e->getMessage());
        }
    }
}

