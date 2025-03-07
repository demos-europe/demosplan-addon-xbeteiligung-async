<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungMessageHeadG2GTypeBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateNOKType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichteninhaltTemplateOKType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedureCreated;
use Exception;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use const LIBXML_NOCDATA;

class XBeteiligungResponseMessageFactory
{
    public const XBETEILIGUNG_VERSION = 'V14';
    public const STANDARD = 'XBeteiligung';

    private const SCHEMALOCATION = 'xmlsn:xsi:schemaLocation';
    private const ERROR_TEXT = 'A Procedure with id';
    private array $messageTypeMapping = [
        '400' => [
            'classes' => [
                KommunalInitiieren0401::class,
                KommunalAktualisieren0402::class,
                KommunalLoeschen0409::class,
            ]
        ],

        '300' => [
            'classes' => [
                RaumordnungInitiieren0301::class,
                RaumordnungAktualisieren0302::class,
                RaumordnungLoeschen0309::class,
            ]
        ],
        '200' => [
            'classes' => [
                PlanfeststellungInitiieren0201::class,
                PlanfeststellungAktualisieren0202::class,
                PlanfeststellungLoeschen0209::class,
            ]
        ],
    ];

    /**
     * @var LoggerInterface
     */
    private $dplanCockpitLogger;


    /** @var Serializer */
    protected $serializer;

    public function __construct(
        LoggerInterface $dplanCockpitLogger
    ) {
        $this->dplanCockpitLogger = $dplanCockpitLogger;
        $this->serializer = $this->getSerializerBuild();
    }

    public function getSerializerBuild(): Serializer
    {
        $serializerBuilder = SerializerBuilder::create();
        $serializerBuilder->addMetadataDir(__DIR__ . '/../../Soap/metadata', 'DemosEurope\DemosplanAddon\XBeteiligung\Soap');
        $serializerBuilder->configureHandlers(static function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling
        });

        return $serializerBuilder->build();
    }

    /**
     * Attributes in top Tag.
     */
    public function setProductInfo(NachrichtG2GTypeType $messageObject): NachrichtG2GTypeType
    {
        $messageObject->setProdukt('DiPlan Cockpit'); // required
        $messageObject->setProdukthersteller('DEMOS plan GmbH'); // required
        $messageObject->setProduktversion('1.1'); // optional
        $messageObject->setStandard(self::STANDARD); // required
        $messageObject->setVersion('1.1'); // required

        return $messageObject;
    }

    private function buildHeader(string $messageType)
    {
        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setK1Info($headerBuilder, 'reader', 'K1');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, $messageType);
        return $headerBuilder->build();
    }

    private function setResponse (
        NachrichteninhaltTemplateOKType|NachrichteninhaltTemplateNOKType $contentClass,
        NachrichtG2GTypeType $messageClass,
        $header,
    )
    {
        $response = new ResponseValue();
        $messageClass->setNachrichtenkopfG2g($header);
        $messageClass->setNachrichteninhalt($contentClass);
        $messageXml = $this->serializeData($messageClass);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($contentClass, $messageXml);
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    public function buildProcedureCreatedResponse(
        ProcedureInterface $procedure,
        KommunalInitiieren0401|RaumordnungInitiieren0301|PlanfeststellungInitiieren0201 $xmlObject,
        NachrichtG2GTypeType $messageClass,
        string $messageType,
    ): ResponseValue {
        try {
            $procedureCreated = $this->createProcedureCreated($procedure, $xmlObject);
            $this->setProductInfo($messageClass);
            $header = $this->buildHeader($messageType);
            $contentClass = new NachrichteninhaltTemplateOKType();
            $contentClass->setBeteiligungsID($procedureCreated->getProcedureId());
            $contentClass->setPlanID($procedureCreated->getPlanId());
            $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                self::ERROR_TEXT.$procedure->getId() . '" was created but the Message couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    public function buildProcedureUpdateResponse(
        ProcedureInterface $procedure,
        KommunalAktualisieren0402|RaumordnungAktualisieren0302|PlanfeststellungAktualisieren0202 $xmlObject,
        NachrichtG2GTypeType $messageClass,
        string $messageType,
    ): ResponseValue {
        try {
            $procedureId = $procedure->getId();
            $planId = $xmlObject->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
            $instanceId = $xmlObject->getNachrichteninhalt()?->getVorgangsID();
            $this->setProductInfo($messageClass);
            $header = $this->buildHeader($messageType);
            $contentClass = new NachrichteninhaltTemplateOKType();
            $contentClass->setBeteiligungsID($procedureId);
            $contentClass->setPlanID($planId);
            $contentClass->setVorgangsID($instanceId);

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                self::ERROR_TEXT.$procedure->getId().
                '" was updated but the Message (Beteiligung2PlanungBeteiligung) couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    /**
     * @param KommunalLoeschen0409|PlanfeststellungLoeschen0209|RaumordnungLoeschen0309 $xmlObject
     * @param NachrichtG2GTypeType $messageClass
     * @param string $messageType
     * @return ResponseValue
     */
    public function buildProcedureDeletedResponse(
        KommunalLoeschen0409|PlanfeststellungLoeschen0209|RaumordnungLoeschen0309 $xmlObject,
        NachrichtG2GTypeType $messageClass,
        string $messageType,
    ): ResponseValue {
        try {
            $this->setProductInfo($messageClass);
            $header = $this->buildHeader($messageType);
            $contentClass = new NachrichteninhaltTemplateOKType();
            $contentClass->setBeteiligungsID($xmlObject->getNachrichteninhalt()?->getBeteiligungsID());
            $contentClass->setPlanID($xmlObject->getNachrichteninhalt()?->getPlanID());
            $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                self::ERROR_TEXT.$xmlObject->getNachrichteninhalt()->getBeteiligungsID()
                .'was deleted but the Message (Beteiligung2PlanungBeteiligungNeuOK'.$messageType.') couldn\'t be built.', [$e]);

            return new ResponseValue();
        }
    }

    /**
     * @param array $errorTypes
     * @param KommunalInitiieren0401|KommunalAktualisieren0402|KommunalLoeschen0409|PlanfeststellungInitiieren0201|PlanfeststellungAktualisieren0202|PlanfeststellungLoeschen0209|RaumordnungInitiieren0301|RaumordnungAktualisieren0302|RaumordnungLoeschen0309 $xmlObject
     * @param NachrichtG2GTypeType $messageClass
     * @param string $messageType
     * @return ResponseValue
     */
    public function buildErrorResponse(
        array $errorTypes,
        $xmlObject,
        NachrichtG2GTypeType $messageClass,
        string $messageType
    ): ResponseValue {
        $this->setProductInfo($messageClass);
        $header = $this->buildHeader($messageType);
        $contentClass = new NachrichteninhaltTemplateNOKType();
        $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()?->getVorgangsID());
        $contentClass->setPlanID($xmlObject->getNachrichteninhalt()?->getBeteiligung()?->getPlanID());
        foreach ($errorTypes as $errorType) {
            $contentClass->addToFehler($errorType);
        }

        return $this->setResponse($contentClass, $messageClass, $header);
    }

    private function createProcedureCreated(
        ProcedureInterface $procedure,
        $xmlObject
    ): ProcedureCreated
    {
        $procedureCreated = new ProcedureCreated();
        $procedureCreated->setProcedureId($procedure->getId());
        /** @var KommunalInitiieren0401|PlanfeststellungInitiieren0201|RaumordnungInitiieren0301 $xmlObject */
        $procedureCreated->setPlanId($xmlObject->getNachrichteninhalt()?->getBeteiligung()?->getPlanID());
        $procedureCreated->lock();

        return $procedureCreated;
    }

    /**
     * Creates an object with the info for K1 (to be used as reader or author in xml's).
     */
    private function setK1Info(
        XBeteiligungMessageHeadG2GTypeBuilder $headerBuilder,
        string $agentType,
        string $prefixName
    ): XBeteiligungMessageHeadG2GTypeBuilder
    {
        $headerBuilder->setAgentAgencyIdentificationPrefixListVersionId('', $agentType)
            // Reader => Behoerdenkennung => praefix
            ->setAgentAgencyIdentificationPrefixCode('diplanfhh', $agentType)
            ->setAgentAgencyIdentificationPrefixName($prefixName, $agentType)
            // Reader => Behoerdenkennung => Kennung
            ->setAgentAgencyIdentificationLabelListURI('', $agentType)
            ->setAgentAgencyIdentificationLabelListVersionID('', $agentType)
            ->setAgentAgencyIdentificationLabelCode('0200', $agentType)
            ->setAgentAgencyIdentificationLabelName($prefixName, $agentType)
            ->setAgentAgencyName('BSW Hamburg', $agentType)
            // Reader => Erreichbarkeit[0] (Contact[0]) => Kennung (Label)
            ->setAgentContactChannelCode('01', $agentType)
            ->setAgentContactChannelName('E-Mail', $agentType)
            ->setAgentContactLabel('info@gv.hamburg.de', $agentType)
            ->setAgentAddition('', $agentType)
            ->setAgentAddressBuildingNumber('19', $agentType)
            ->setAgentAddressBuildingAdditionalLetter('b', $agentType)
            ->setAgentAddressBuildingZipcode('21109', $agentType)
            ->setAgentAddressBuildingFloorNumber('3', $agentType)
            ->setAgentAddressStreet('Neuenfelder Straße', $agentType)
            ->setAgentAddressBuildingApartmentNumber('4', $agentType)
            ->setAgentAddressMunicipal('Freie und Hansestadt Hamburg', $agentType)
            ->setAgentMunicipalPreviousCorporation('', $agentType)
            ->setAgentApartmentOwner('', $agentType)
            ->setAgentAddressBuildingAdditionalInfo('Hinterhaus', $agentType)
            // Reader => Anschrift => Gebaude => Hausnummer.bis
            ->setAgentAddressBuildingNumberBis('22', $agentType)
            ->setAgentAddressBuildingAdditionalLetterBis('c', $agentType)
            ->setAgentAddressBuildingApartmentNumberBis('3', $agentType);

        return $headerBuilder;
    }

    /**
     * Creates an object with the info for Demos (to be used ars reader or author in xmls).
     */
    private function setDemosInfo(
        XBeteiligungMessageHeadG2GTypeBuilder $headerBuilder,
        string $agentType
    ): XBeteiligungMessageHeadG2GTypeBuilder
    {
        $headerBuilder
            // Autor => Behoerdenkennung => Prefix
            ->setAgentAgencyIdentificationPrefixListVersionId('', $agentType)
            //->setAgentAgencyIdentificationPrefixCode('diplanfhh', $agentType)
            ->setAgentAgencyIdentificationPrefixName('DEMOS plan GmbH', $agentType)
            // Autor => Behoerdenkennung => Kennung (Label)
            ->setAgentAgencyIdentificationLabelListURI('', $agentType)
            ->setAgentAgencyIdentificationLabelListVersionID('', $agentType)
            ->setAgentAgencyIdentificationLabelCode('0400', $agentType)
            ->setAgentAgencyIdentificationLabelName('', $agentType)

            // Autor => Erreichbarkeit[0] (Contact[0]) => Kennung (Label)
            ->setAgentContactChannelCode('02', $agentType)
            ->setAgentContactChannelName('Telefon', $agentType)
            ->setAgentContactLabel('0049 40 22 86 73 57 0', $agentType)
            ->setAgentAddition('', $agentType)
            // Autor => Erreichbarkeit[1] (Contact[1) => Kennung (Label, $agentType)
            ->setAgentContactChannelCode('01', $agentType, 1)
            ->setAgentContactChannelName('E-Mail', $agentType, 1)
            ->setAgentContactLabel('officehamburg@demos-international.com', $agentType, 1)
            // Autor => Anschrift => Gebaude
            ->setAgentAddressBuildingNumber('43', $agentType)
            ->setAgentAddressBuildingAdditionalLetter('', $agentType)
            ->setAgentAddressBuildingZipcode('22769', $agentType)
            ->setAgentAddressBuildingFloorNumber('', $agentType)
            ->setAgentAddressStreet('Eifflerstraße', $agentType)
            ->setAgentAddressBuildingApartmentNumber('4', $agentType)
            //->setAgentAddressMunicipal('Freie und Hansestadt Hamburg', $agentType)
            ->setAgentMunicipalPreviousCorporation('', $agentType)
            ->setAgentApartmentOwner('', $agentType)
            ->setAgentAddressBuildingAdditionalInfo('', $agentType)
            // Autor => Anschrift => Gebaude => Hausnummer.bis
            ->setAgentAddressBuildingNumberBis('', $agentType)
            ->setAgentAddressBuildingAdditionalLetterBis('', $agentType)
            ->setAgentAddressBuildingApartmentNumberBis('', $agentType);

        return $headerBuilder;
    }

    /**
     * Tag <identifikation.nachricht>.
     *
     * @return XBeteiligungMessageHeadG2GTypeBuilder
     */
    private function setMessageInfo(XBeteiligungMessageHeadG2GTypeBuilder $headerBuilder, string $msgType)
    {
        $headerBuilder
            // identifikation.nachricht => nachrichtenUUID
            ->setMessageIdentificationUUID($this->uuid())
            // identifikation.nachricht => nachrichtentyp => code
            ->setMessageIdentificationTypeCode($msgType)
            // identifikation.nachricht => erstellungszeitpunkt
            ->setCreationTime(new DateTime());

        return $headerBuilder;
    }

    public function serializeData($data): string
    {
        // Couldn't find the way to avoid CDATA directly with serializer method
        $xml = $this->serializer->serialize($data, 'xml');
        // This is needed to remove cdata from the xml message
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $result = $xml->saveXML();

        if (false === $result) {
            $this->dplanCockpitLogger->error('Error on save serialized xml.', [$xml->asXML()]);
        }

        return $xml->asXML() ?? '';
    }

    /**
     * Generates a string with the necessary namespaces for a 411, 421, 419, 429 xml file.
     */
    private function addNamespacesToBeteiligung2PlanungXML($xmlObject, string $xml): string
    {
        $simpleXML = simplexml_load_string($xml);

        $simpleXML->addAttribute('xmlns:xmlns:xoev-code', 'http://xoev.de/schemata/code/1_0');
        $simpleXML->addAttribute('xmlsn:xmlns:xs', 'http://www.w3.org/2001/XMLSchema-instance');
        if (in_array($xmlObject, $this->messageTypeMapping['400']['classes'], true)) {
            $simpleXML->addAttribute(
                self::SCHEMALOCATION,
                'https://www.xleitstelle.de/xbeteiligung/12 ../../xbeteiligung-kommunaleBauleitplanung.xsd');
        } elseif (in_array($xmlObject, $this->messageTypeMapping['300']['clasess'], true)) {
            $simpleXML->addAttribute(
                self::SCHEMALOCATION,
                'https://www.xleitstelle.de/xbeteiligung/12 ../../xbeteiligung-raumordnung.xsd');
        } elseif (in_array($xmlObject, $this->messageTypeMapping['200']['classes'], true)) {
            $simpleXML->addAttribute(
                self::SCHEMALOCATION,
                'https://www.xleitstelle.de/xbeteiligung/12 ../../xbeteiligung-planfeststellung.xsd');
        }

        return $simpleXML->asXML();
    }

    public function uuid(): string
    {
        $uuid = '';
        $tryAgain = true;
        while ($tryAgain) {
            $uuid = Uuid::uuid4()->toString();
            if (0 !== preg_match('/[A-Za-z]/', $uuid[0])) {
                $tryAgain = false;
            }
        }

        return $uuid;
    }

}
