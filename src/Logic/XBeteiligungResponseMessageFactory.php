<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuNOK0421;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuOK0411;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuOK0411\Beteiligung2PlanungBeteiligungKommunalNeuOK0411AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0411;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412\Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0412;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419\Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0419;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalNeuNOK0421\Beteiligung2PlanungBeteiligungKommunalNeuNOK0421AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0421;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422\Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0422;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429\Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0429;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311\Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0311;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0312;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319\Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0319;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321\Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0321;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0322;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329\Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0329;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0211;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0212;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0219;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221\Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0221;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0222;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229AnonymousPHPType\NachrichteninhaltAnonymousPHPType as NachrichteninhaltAnonymousPHPType0229;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungPlanfeststellungNeu0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungNeu0301;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedureCreated;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\BaseTypesHandler;
use GoetasWebservices\Xsd\XsdToPhpRuntime\Jms\Handler\XmlSchemaDateHandler;
use JMS\Serializer\Handler\HandlerRegistryInterface;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerBuilder;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use const  LIBXML_NOCDATA;
class XBeteiligungResponseMessageFactory
{
    public const XBETEILIGUNG_VERSION = 'V14';
    public const STANDARD = 'XBeteiligung';
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
        $serializerBuilder->addMetadataDir(__DIR__ . '/../Soap/metadata', 'DemosEurope\DemosplanAddon\XBeteiligung\Soap');
        $serializerBuilder->configureHandlers(static function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling
        });

        return $serializerBuilder->build();
    }

    public function getSerializer(): Serializer
    {
        return $this->serializer;
    }

    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a procedure.
     *
     * @throws Exception
     */
    public function buildProcedureCreatedResponse411(
        ProcedureInterface $procedure,
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
    ): ResponseValue
    {
        try {
            $procedureCreated = new ProcedureCreated();
            $procedureCreated->setProcedureId($procedure->getId());
            $planId = $xmlObject401->getNachrichteninhalt()->getBeteiligung()->getPlanID();
            $procedureCreated->setPlanId($planId);
            $procedureCreated->lock();

            $response = new ResponseValue();
            $message = new Beteiligung2PlanungBeteiligungKommunalNeuOK0411();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0411');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0411();
            $content->setBeteiligungsID($procedureCreated->getProcedureId());
            $content->setPlanID($procedureCreated->getPlanId());
            $content->setVorgangsID($xmlObject401->getNachrichteninhalt()->getVorgangsID());

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error('A Procedure with id "'.$procedure->getId().'" was created but the Message (Beteiligung2PlanungBeteiligungNeuOK0411) couldn\'t be built.');

            return new ResponseValue();
        }
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse412(
        Planung2BeteiligungBeteiligungKommunalAktualisieren0402 $xmlObject402,
        ProcedureInterface                                      $procedure
    ): ResponseValue {
        try {
            $procedureId = $procedure->getId();
            $planId = $xmlObject402->getNachrichteninhalt()->getBeteiligung()->getPlanID();
            $instanceId = $xmlObject402->getNachrichteninhalt()->getVorgangsID();

            $response = new ResponseValue();
            $message = new Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0412');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0412();
            $content->setBeteiligungsID($procedureId);
            $content->setPlanID($planId);
            $content->setVorgangsID($instanceId);

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                'A Procedure with id "'.$procedure->getId().
                '" was updated but the Message (Beteiligung2PlanungBeteiligung) couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse419(Planung2BeteiligungBeteiligungKommunalLoeschen0409 $xmlObject409): ResponseValue
    {
        try {
            $message = new Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0419');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0419();
            $content->setBeteiligungsID($xmlObject409->getNachrichteninhalt()->getBeteiligungsID());
            $content->setPlanID($xmlObject409->getNachrichteninhalt()->getPlanID());
            $content->setVorgangsID($xmlObject409->getNachrichteninhalt()->getVorgangsID());

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response = new ResponseValue();
            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error('A Procedure with id "'
                .$xmlObject409->getNachrichteninhalt()->getBeteiligungsID()
                .'" was deleted but the Message (Beteiligung2PlanungBeteiligungNeuOK0419) couldn\'t be built.', [$e]);

            return new ResponseValue();
        }
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse421(
        array $errorTypes,
        Planung2BeteiligungBeteiligungKommunalNeu0401 $xmlObject401
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungKommunalNeuNOK0421();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0421');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0421();
        $content->setVorgangsID($xmlObject401->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject401->getNachrichteninhalt()->getBeteiligung()->getPlanID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to update a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     * @throws SchemaException
     */
    public function buildProcedureUpdateErrorResponse422(
        array $errorTypes,
        Planung2BeteiligungBeteiligungKommunalAktualisieren0402 $xmlObject402
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0422');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0422();
        $content->setVorgangsID($xmlObject402->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject402->getNachrichteninhalt()->getBeteiligung()->getPlanID());
        $content->setBeteiligungsID($xmlObject402->getNachrichteninhalt()->getBeteiligung()->getBeteiligungsID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse429(
        array $errorTypes,
        Planung2BeteiligungBeteiligungKommunalLoeschen0409 $xmlObject409
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0429');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0429();
        $content->setVorgangsID($xmlObject409->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject409->getNachrichteninhalt()->getPlanID());
        $content->setBeteiligungsID($xmlObject409->getNachrichteninhalt()->getBeteiligungsID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);

        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a procedure.
     *
     * @throws Exception
     */
    public function buildProcedureCreatedResponse311(
        ProcedureInterface $procedure,
        Planung2BeteiligungBeteiligungRaumordnungNeu0301 $xmlObject401
    ): ResponseValue
    {
        try {
            $procedureCreated = new ProcedureCreated();
            $procedureCreated->setProcedureId($procedure->getId());
            $planId = $xmlObject401->getNachrichteninhalt()->getBeteiligung()->getPlanID();
            $procedureCreated->setPlanId($planId);
            $procedureCreated->lock();

            $response = new ResponseValue();
            $message = new Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0311');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0311();
            $content->setBeteiligungsID($procedureCreated->getProcedureId());
            $content->setPlanID($procedureCreated->getPlanId());
            $content->setVorgangsID($xmlObject401->getNachrichteninhalt()->getVorgangsID());

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error('A Procedure with id "'.$procedure->getId().'" was created but the Message (Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311) couldn\'t be built.');

            return new ResponseValue();
        }
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse312(
        Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302 $xmlObject402,
        ProcedureInterface                                      $procedure
    ): ResponseValue {
        try {
            $procedureId = $procedure->getId();
            $planId = $xmlObject402->getNachrichteninhalt()->getBeteiligung()->getPlanID();
            $instanceId = $xmlObject402->getNachrichteninhalt()->getVorgangsID();

            $response = new ResponseValue();
            $message = new Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0312');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0312();
            $content->setBeteiligungsID($procedureId);
            $content->setPlanID($planId);
            $content->setVorgangsID($instanceId);

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                'A Procedure with id "'.$procedure->getId().
                '" was updated but the Message (Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312) couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse319(
        Planung2BeteiligungBeteiligungRaumordnungLoeschen0309 $xmlObject409
    ): ResponseValue
    {
        try {
            $message = new Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0319');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0319();
            $content->setBeteiligungsID($xmlObject409->getNachrichteninhalt()->getBeteiligungsID());
            $content->setPlanID($xmlObject409->getNachrichteninhalt()->getPlanID());
            $content->setVorgangsID($xmlObject409->getNachrichteninhalt()->getVorgangsID());

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response = new ResponseValue();
            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error('A Procedure with id "'
                .$xmlObject409->getNachrichteninhalt()->getBeteiligungsID()
                .'" was deleted but the Message (Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319) couldn\'t be built.', [$e]);

            return new ResponseValue();
        }
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse321(
        array $errorTypes,
        Planung2BeteiligungBeteiligungRaumordnungNeu0301 $xmlObject401
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0321');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0321();
        $content->setVorgangsID($xmlObject401->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject401->getNachrichteninhalt()->getBeteiligung()->getPlanID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to update a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     * @throws SchemaException
     */
    public function buildProcedureUpdateErrorResponse322(
        array $errorTypes,
        Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302 $xmlObject402
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0322');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0322();
        $content->setVorgangsID($xmlObject402->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject402->getNachrichteninhalt()->getBeteiligung()->getPlanID());
        $content->setBeteiligungsID($xmlObject402->getNachrichteninhalt()->getBeteiligung()->getBeteiligungsID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse329(
        array $errorTypes,
        Planung2BeteiligungBeteiligungRaumordnungLoeschen0309 $xmlObject409
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0329');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0329();
        $content->setVorgangsID($xmlObject409->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject409->getNachrichteninhalt()->getPlanID());
        $content->setBeteiligungsID($xmlObject409->getNachrichteninhalt()->getBeteiligungsID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);

        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * Builds a valid XBeteiligungsmessage as a response of creating a procedure.
     *
     * @throws Exception
     */
    public function buildProcedureCreatedResponse211(
        ProcedureInterface $procedure,
        Planung2BeteiligungBeteiligungPlanfeststellungNeu0201 $xmlObject401
    ): ResponseValue
    {
        try {
            $procedureCreated = new ProcedureCreated();
            $procedureCreated->setProcedureId($procedure->getId());
            $planId = $xmlObject401->getNachrichteninhalt()->getBeteiligung()->getPlanID();
            $procedureCreated->setPlanId($planId);
            $procedureCreated->lock();

            $response = new ResponseValue();
            $message = new Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0211');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0211();
            $content->setBeteiligungsID($procedureCreated->getProcedureId());
            $content->setPlanID($procedureCreated->getPlanId());
            $content->setVorgangsID($xmlObject401->getNachrichteninhalt()->getVorgangsID());

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error('A Procedure with id "'.$procedure->getId().'" was created but the Message (Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211) couldn\'t be built.');

            return new ResponseValue();
        }
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse212(
        Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202 $xmlObject402,
        ProcedureInterface                                      $procedure
    ): ResponseValue {
        try {
            $procedureId = $procedure->getId();
            $planId = $xmlObject402->getNachrichteninhalt()->getBeteiligung()->getPlanID();
            $instanceId = $xmlObject402->getNachrichteninhalt()->getVorgangsID();

            $response = new ResponseValue();
            $message = new Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0212');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0212();
            $content->setBeteiligungsID($procedureId);
            $content->setPlanID($planId);
            $content->setVorgangsID($instanceId);

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                'A Procedure with id "'.$procedure->getId().
                '" was updated but the Message (Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212) couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse219(Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209 $xmlObject409): ResponseValue
    {
        try {
            $message = new Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219();
            $this->setProductInfo($message);

            $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
            $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'K1');
            $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
            $headerBuilder = $this->setMessageInfo($headerBuilder, '0219');
            $header = $headerBuilder->build();

            $content = new NachrichteninhaltAnonymousPHPType0219();
            $content->setBeteiligungsID($xmlObject409->getNachrichteninhalt()->getBeteiligungsID());
            $content->setPlanID($xmlObject409->getNachrichteninhalt()->getPlanID());
            $content->setVorgangsID($xmlObject409->getNachrichteninhalt()->getVorgangsID());

            $message->setNachrichtenkopf($header);
            $message->setNachrichteninhalt($content);

            $messageXml = $this->serializeData($message);
            $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

            $response = new ResponseValue();
            $response->setPayload($messageXml);
            $response->lock();

            return $response;
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error('A Procedure with id "'
                .$xmlObject409->getNachrichteninhalt()->getBeteiligungsID()
                .'" was deleted but the Message (Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219) couldn\'t be built.', [$e]);

            return new ResponseValue();
        }
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse221(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungNeu0201 $xmlObject401
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0221');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0221();
        $content->setVorgangsID($xmlObject401->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject401->getNachrichteninhalt()->getBeteiligung()->getPlanID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to update a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     * @throws SchemaException
     */
    public function buildProcedureUpdateErrorResponse222(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202 $xmlObject402
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0222');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0222();
        $content->setVorgangsID($xmlObject402->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject402->getNachrichteninhalt()->getBeteiligung()->getPlanID());
        $content->setBeteiligungsID($xmlObject402->getNachrichteninhalt()->getBeteiligung()->getBeteiligungsID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse229(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209 $xmlObject409
    ): ResponseValue {
        $message = new Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229();
        $this->setProductInfo($message);

        $headerBuilder = new XBeteiligungMessageHeadG2GTypeBuilder();
        $headerBuilder = $this->setDiplanCockpitInfo($headerBuilder, 'reader', 'LGV');
        $headerBuilder = $this->setDemosInfo($headerBuilder, 'author');
        $headerBuilder = $this->setMessageInfo($headerBuilder, '0229');
        $header = $headerBuilder->build();

        $content = new NachrichteninhaltAnonymousPHPType0229();
        $content->setVorgangsID($xmlObject409->getNachrichteninhalt()->getVorgangsID());
        $content->setPlanID($xmlObject409->getNachrichteninhalt()->getPlanID());
        $content->setBeteiligungsID($xmlObject409->getNachrichteninhalt()->getBeteiligungsID());

        foreach ($errorTypes as $errorType) {
            $content->addToFehler($errorType);
        }

        $message->setNachrichtenkopf($header);
        $message->setNachrichteninhalt($content);

        $messageXml = $this->serializeData($message);

        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($messageXml);

        $response = new ResponseValue();
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    /**
     * Attributes in top Tag.
     */
    public function setProductInfo(NachrichtG2GTypeType $messageObject): NachrichtG2GTypeType
    {
        $messageObject->setProdukt('A1'); // required
        $messageObject->setProdukthersteller('DEMOS plan GmbH'); // required
        $messageObject->setProduktversion('1.0'); // optional
        $messageObject->setStandard(self::STANDARD); // required
        // $messageObject->setTest(''); // optional
        $messageObject->setVersion('1.0'); // required

        return $messageObject;
    }

    /**
     * Creates an object with the info for K1 (to be used as reader or author in xml's).
     */
    private function setDiplanCockpitInfo(
        XBeteiligungMessageHeadG2GTypeBuilder $headerBuilder,
        string $agentType,
        string $prefixName
    ): XBeteiligungMessageHeadG2GTypeBuilder
    {
        $headerBuilder->setAgentAgencyIdentificationPrefixListVersionId('', $agentType)
            // Reader => Behoerdenkennung => praefix
            //TODO: check what is Agency Address, name, contact code
            ->setAgentAgencyIdentificationPrefixCode('diplanfhh', $agentType)
            ->setAgentAgencyIdentificationPrefixName($prefixName, $agentType)
            // Reader => Behoerdenkennung => Kennung
            ->setAgentAgencyIdentificationLabelListURI('', $agentType)
            ->setAgentAgencyIdentificationLabelListVersionID('', $agentType)
            ->setAgentAgencyIdentificationLabelCode('0200', $agentType)
            ->setAgentAgencyIdentificationLabelName($prefixName, $agentType)
            ->setAgentAgencyName('BSW Hamburg', $agentType)
            // Reader => Erreichbarkeit[0] (Contact[0]) => Kennung (Label)
            ->setAgentContactChannelCode('01', 0, $agentType)
            ->setAgentContactChannelName('E-Mail', 0, $agentType)
            ->setAgentContactLabel('info@gv.hamburg.de', 0, $agentType)
            ->setAgentAddition('', 0, $agentType)
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
            //TODO: check what is prefix code
            //->setAgentAgencyIdentificationPrefixCode('diplanfhh', $agentType)
            ->setAgentAgencyIdentificationPrefixName('DEMOS E-Partizipation GmbH', $agentType)
            // Autor => Behoerdenkennung => Kennung (Label)
            ->setAgentAgencyIdentificationLabelListURI('', $agentType)
            ->setAgentAgencyIdentificationLabelListVersionID('', $agentType)
            ->setAgentAgencyIdentificationLabelCode('0400', $agentType)
            ->setAgentAgencyIdentificationLabelName('', $agentType)

            // Autor => Erreichbarkeit[0] (Contact[0]) => Kennung (Label)
            ->setAgentContactChannelCode('02', 0, $agentType)
            ->setAgentContactChannelName('Telefon', 0, $agentType)
            ->setAgentContactLabel('0049 40 22 86 73 57 0', 0, $agentType)
            ->setAgentAddition('', 0, $agentType)
            // Autor => Erreichbarkeit[1] (Contact[1) => Kennung (Label, $agentType)
            ->setAgentContactChannelCode('01', 1, $agentType)
            ->setAgentContactChannelName('E-Mail', 1, $agentType)
            ->setAgentContactLabel('officehamburg@demos-international.com', 1, $agentType)
            // Autor => Anschrift => Gebaude
            ->setAgentAddressBuildingNumber('43', $agentType)
            ->setAgentAddressBuildingAdditionalLetter('', $agentType)
            ->setAgentAddressBuildingZipcode('22769', $agentType)
            ->setAgentAddressBuildingFloorNumber('', $agentType)
            ->setAgentAddressStreet('Eifflerstraße', $agentType)
            ->setAgentAddressBuildingApartmentNumber('4', $agentType)
            //TODO: check what is municipal
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
    private function addNamespacesToBeteiligung2PlanungXML(string $xml): string
    {
        $simpleXML = simplexml_load_string($xml);

        $simpleXML->addAttribute('xmlns:xmlns:xoev-code', 'http://xoev.de/schemata/code/1_0');
        $simpleXML->addAttribute('xmlsn:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        $simpleXML->addAttribute('xmlsn:xsi:schemaLocation', 'https://www.xleitstelle.de/xbeteiligung/1 xbeteiligung-planung2beteiligung.xsd');

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