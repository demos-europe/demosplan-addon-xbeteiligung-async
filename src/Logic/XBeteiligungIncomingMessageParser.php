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
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;
use SimpleXMLElement;
use function in_array;

class XBeteiligungIncomingMessageParser
{
    public const INCOMING_MESSAGE = 'Incoming Message could not be validated';
    public const UNEXPECTED_NAME = 'Unexpected name, won’t continue';

    protected Serializer $serializer;

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
        $this->serializer = $this->getSerializerBuild();
    }

    private function getSerializerBuild(): Serializer
    {
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(__DIR__ . '/../Soap/Metadata', 'DemosEurope\DemosplanAddon\XBeteiligung\Soap');
        $serializerBuilder->configureHandlers(static function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler());
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler());
        });

        return $serializerBuilder->build();
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
        if ($expectedXmlName !== $simpleXML->getName()) {
            $this->logger->error('Unexpected message type name in XML', [
                'Unexpected name' => $simpleXML->getName(),
            ]);
            throw new SchemaException(self::UNEXPECTED_NAME);
        }
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

    private function validateRequiredNamespace(SimpleXMLElement $simpleXML): void
    {
        $namespaces = $simpleXML->getNamespaces();
        if (!in_array('http://xplanverfahren.de/'.XBeteiligungResponseMessageFactory::XBETEILIGUNG_VERSION, $namespaces, true)) {
            $this->logger->warning('Probably missing relevant namespace?', [
                'namespace' => 'http://xplanverfahren.de/'.XBeteiligungResponseMessageFactory::XBETEILIGUNG_VERSION
                ]
            );
        }
    }

    /**
     * @throws SchemaException
     */
    private function deserializeMessageWithCertainty(string $incomingMessage, string $className): NachrichtG2GTypeType
    {
        /** @var NachrichtG2GTypeType $message */
        $message = $this->serializer->deserialize(
            $incomingMessage,
            $className,
            'xml'
        );

        if (null === $message || null === $message->getProdukt()) {
            throw new SchemaException('Incoming message is not a valid '.$className.' message');
        }

        return $message;
    }
}

