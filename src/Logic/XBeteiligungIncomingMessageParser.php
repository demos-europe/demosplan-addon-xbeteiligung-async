<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungNeu0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungNeu0301;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use SimpleXMLElement;

class XBeteiligungIncomingMessageParser
{
    public const INCOMING_MESSAGE = 'Incoming Message could not be validated';
    public const UNEXPECTED_NAME = 'Unexpected name, won’t continue';

    protected Serializer $serializer;

    private array $messageTypeMapping = [
        '401' => [
            'class' => Planung2BeteiligungBeteiligungKommunalNeu0401::class,
            'identifier' => XBeteiligungService::NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '402' => [
            'class' => Planung2BeteiligungBeteiligungKommunalAktualisieren0402::class,
            'identifier' => XBeteiligungService::UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '409' => [
            'class' => Planung2BeteiligungBeteiligungKommunalLoeschen0409::class,
            'identifier' => XBeteiligungService::DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '301' => [
            'class' => Planung2BeteiligungBeteiligungRaumordnungNeu0301::class,
            'identifier' => XBeteiligungService::NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '302' => [
            'class' => Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302::class,
            'identifier' => XBeteiligungService::UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '309' => [
            'class' => Planung2BeteiligungBeteiligungRaumordnungLoeschen0309::class,
            'identifier' => XBeteiligungService::DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '201' => [
            'class' => Planung2BeteiligungBeteiligungPlanfeststellungNeu0201::class,
            'identifier' => XBeteiligungService::NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '202' => [
            'class' => Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202::class,
            'identifier' => XBeteiligungService::UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
        '209' => [
            'class' => Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209::class,
            'identifier' => XBeteiligungService::DELETE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER
        ],
    ];

    public function __construct()
    {
        $this->serializer = $this->getSerializerBuild();
    }

    private function getSerializerBuild(): Serializer
    {
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(__DIR__ . '/../Soap/metadata', 'DemosEurope\DemosplanAddon\XBeteiligung\Soap');
        $serializerBuilder->configureHandlers(static function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler());
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler());
        });

        return $serializerBuilder->build();
    }

    /**
     * @template T of NachrichtG2GType
     * @param string $messageType
     * @param string $incomingMessage
     * @return T
     * @throws SchemaException
     */
    public function getXmlObject(string $messageType, string $incomingMessage): NachrichtG2GType
    {
        if (!isset($this->messageTypeMapping[$messageType])) {
            throw new SchemaException("Invalid message type: $messageType");
        }

        $messageClass = $this->messageTypeMapping[$messageType]['class'];
        $expectedXmlName = $this->messageTypeMapping[$messageType]['identifier'];

        $simpleXML = $this->getSimpleXmlElementWithCertainty($incomingMessage);
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
            throw new SchemaException(self::UNEXPECTED_NAME);
        }
    }


    /**
     * @throws SchemaException
     */
    private function getSimpleXmlElementWithCertainty(string $incomingMessage): SimpleXMLElement
    {
        libxml_use_internal_errors(true);
        $simpleXML = simplexml_load_string($incomingMessage);
        if (false === $simpleXML) {
            libxml_clear_errors();
            throw new SchemaException('Could not parse payload as XML');
        }
        return $simpleXML;
    }

    /**
     * @throws SchemaException
     */
    private function validateRequiredNamespace(SimpleXMLElement $simpleXML): void
    {
        $namespaces = $simpleXML->getNamespaces();
        if (!in_array('http://xplanverfahren.de/'.XBeteiligungResponseMessageFactory::XBETEILIGUNG_VERSION, $namespaces, true)) {
            throw new SchemaException('Unexpected namespace, won’t continue');
        }
    }

    /**
     * @template T of NachrichtG2GType
     * @param string $incomingMessage
     * @param class-string<T> $className
     * @return T
     * @throws SchemaException
     */
    private function deserializeMessageWithCertainty(string $incomingMessage, string $className): NachrichtG2GType
    {
        /** @var NachrichtG2GType $message */
        $message = $this->serializer->deserialize(
            $incomingMessage,
            $className,
            'xml'
        );

        if (null === $message || null === $message->getNachrichteninhalt()) {
            throw new SchemaException('Incoming message is not a valid '.$className.' message');
        }

        return $message;
    }
}

