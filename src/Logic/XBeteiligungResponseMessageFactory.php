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

    private const SCHEMALOCATION = 'xmlsn:xsi:schemaLocation';
    private array $messageTypeMapping = [
        '400' => [
            'classes' => [
                NachrichteninhaltAnonymousPHPType0411::class,
                NachrichteninhaltAnonymousPHPType0412::class,
                NachrichteninhaltAnonymousPHPType0419::class,
                NachrichteninhaltAnonymousPHPType0421::class,
                NachrichteninhaltAnonymousPHPType0422::class,
                NachrichteninhaltAnonymousPHPType0429::class,
            ]
        ],

        '300' => [
            'classes' => [
                NachrichteninhaltAnonymousPHPType0311::class,
                NachrichteninhaltAnonymousPHPType0312::class,
                NachrichteninhaltAnonymousPHPType0319::class,
                NachrichteninhaltAnonymousPHPType0321::class,
                NachrichteninhaltAnonymousPHPType0322::class,
                NachrichteninhaltAnonymousPHPType0329::class,
            ]
        ],
        '200' => [
            'classes' => [
                NachrichteninhaltAnonymousPHPType0211::class,
                NachrichteninhaltAnonymousPHPType0212::class,
                NachrichteninhaltAnonymousPHPType0219::class,
                NachrichteninhaltAnonymousPHPType0221::class,
                NachrichteninhaltAnonymousPHPType0222::class,
                NachrichteninhaltAnonymousPHPType0229::class,
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
        $serializerBuilder->addMetadataDir(__DIR__ . '/../Soap/metadata', 'DemosEurope\DemosplanAddon\XBeteiligung\Soap');
        $serializerBuilder->configureHandlers(static function (HandlerRegistryInterface $handler) use ($serializerBuilder) {
            $serializerBuilder->addDefaultHandlers();
            $handler->registerSubscribingHandler(new BaseTypesHandler()); // XMLSchema List handling
            $handler->registerSubscribingHandler(new XmlSchemaDateHandler()); // XMLSchema date handling
        });

        return $serializerBuilder->build();
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
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungKommunalNeuOK0411(),
            new NachrichteninhaltAnonymousPHPType0411(),
            '0411'
        );
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
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject402,
            new Beteiligung2PlanungBeteiligungKommunalAktualisierenOK0412(),
            new NachrichteninhaltAnonymousPHPType0412(),
            '0412'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse419(Planung2BeteiligungBeteiligungKommunalLoeschen0409 $xmlObject409): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject409,
            new Beteiligung2PlanungBeteiligungKommunalLoeschenOK0419(),
            new NachrichteninhaltAnonymousPHPType0419(),
            '0419'
        );
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
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungKommunalNeuNOK0421(),
            new NachrichteninhaltAnonymousPHPType0421(),
            '0421'
        );
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
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject402,
            new Beteiligung2PlanungBeteiligungKommunalAktualisierenNOK0422(),
            new NachrichteninhaltAnonymousPHPType0422(),
            '0422'
        );
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
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject409,
            new Beteiligung2PlanungBeteiligungKommunalLoeschenNOK0429(),
            new NachrichteninhaltAnonymousPHPType0429(),
            '0429'
        );
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
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungRaumordnungNeuOK0311(),
            new NachrichteninhaltAnonymousPHPType0311(),
            '0311'
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse312(
        Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302 $xmlObject302,
        ProcedureInterface                                      $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject302,
            new Beteiligung2PlanungBeteiligungRaumordnungAktualisierenOK0312(),
            new NachrichteninhaltAnonymousPHPType0312(),
            '0312'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse319(
        Planung2BeteiligungBeteiligungRaumordnungLoeschen0309 $xmlObject309
    ): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject309,
            new Beteiligung2PlanungBeteiligungRaumordnungLoeschenOK0319(),
            new NachrichteninhaltAnonymousPHPType0319(),
            '0319'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse321(
        array $errorTypes,
        Planung2BeteiligungBeteiligungRaumordnungNeu0301 $xmlObject301
    ): ResponseValue {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject301,
            new Beteiligung2PlanungBeteiligungRaumordnungNeuNOK0321(),
            new NachrichteninhaltAnonymousPHPType0321(),
            '0321'
        );
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
        Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302 $xmlObject302
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject302,
            new Beteiligung2PlanungBeteiligungRaumordnungAktualisierenNOK0322(),
            new NachrichteninhaltAnonymousPHPType0322(),
            '0302'
        );
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse329(
        array $errorTypes,
        Planung2BeteiligungBeteiligungRaumordnungLoeschen0309 $xmlObject309
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject309,
            new Beteiligung2PlanungBeteiligungRaumordnungLoeschenNOK0329(),
            new NachrichteninhaltAnonymousPHPType0329(),
            '0329'
        );
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
        return $this->buildProcedureCreatedResponse(
            $procedure,
            $xmlObject401,
            new Beteiligung2PlanungBeteiligungPlanfeststellungNeuOK0211(),
            new NachrichteninhaltAnonymousPHPType0211(),
            '0211'
        );
    }

    /**
     * 412 means "procedure update successful".
     *
     * @throws Exception
     */
    public function buildProcedureUpdateOKResponse212(
        Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202 $xmlObject202,
        ProcedureInterface                                              $procedure
    ): ResponseValue {
        return $this->buildProcedureUpdateResponse(
            $procedure,
            $xmlObject202,
            new Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenOK0212(),
            new NachrichteninhaltAnonymousPHPType0212(),
            '0212'
        );
    }

    /**
     * 419 means "procedure deletion successful".
     *
     * @throws Exception
     */
    public function buildProcedureDeletedResponse219(
        Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209 $xmlObject209
    ): ResponseValue
    {
        return $this->buildProcedureDeletedResponse(
            $xmlObject209,
            new Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenOK0219(),
            new NachrichteninhaltAnonymousPHPType0219(),
            '0219'
        );
    }

    /**
     * Builds a valid Error-XBeteiligungsmessage as a response for trying to create a procedure.
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureCreatedErrorResponse221(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungNeu0201 $xmlObject201
    ): ResponseValue {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject201,
            new Beteiligung2PlanungBeteiligungPlanfeststellungNeuNOK0221(),
            new NachrichteninhaltAnonymousPHPType0221(),
            '0221'
        );
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
        Planung2BeteiligungBeteiligungPlanfeststellungAktualisieren0202 $xmlObject202
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject202,
            new Beteiligung2PlanungBeteiligungPlanfeststellungAktualisierenNOK0222(),
            new NachrichteninhaltAnonymousPHPType0222(),
            '0222'
        );
    }

    /**
     * 429 means "procedure deletion not successful".
     *
     * @param array<int, FehlerType> $errorTypes
     *
     */
    public function buildProcedureDeletedErrorResponse229(
        array $errorTypes,
        Planung2BeteiligungBeteiligungPlanfeststellungLoeschen0209 $xmlObject209
    ): ResponseValue
    {
        return $this->buildErrorResponse(
            $errorTypes,
            $xmlObject209,
            new Beteiligung2PlanungBeteiligungPlanfeststellungLoeschenNOK0229(),
            new NachrichteninhaltAnonymousPHPType0229(),
            '0229'
        );
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
        // $messageObject->setTest(''); // optional
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
        $contentClass,
        $messageClass,
        $header,
    )
    {
        $response = new ResponseValue();
        $messageClass->setNachrichtenkopf($header);
        $messageClass->setNachrichteninhalt($contentClass);
        $messageXml = $this->serializeData($messageClass);
        $messageXml = $this->addNamespacesToBeteiligung2PlanungXML($contentClass, $messageXml);
        $response->setPayload($messageXml);
        $response->lock();

        return $response;
    }

    private function buildProcedureCreatedResponse(
        ProcedureInterface $procedure,
                           $xmlObject,
        NachrichtG2GTypeType $messageClass,
        $contentClass,
        string $messageType,
    ): ResponseValue {
        try {
            $procedureCreated = $this->createProcedureCreated($procedure, $xmlObject);
            $this->setProductInfo($messageClass);
            $header = $this->buildHeader($messageType);
            $contentClass->setBeteiligungsID($procedureCreated->getProcedureId());
            $contentClass->setPlanID($procedureCreated->getPlanId());
            $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()->getVorgangsID());

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                'A Procedure with id "' . $procedure->getId() . '" was created but the Message couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    private function buildProcedureUpdateResponse(
        ProcedureInterface $procedure,
                           $xmlObject,
        NachrichtG2GTypeType $messageClass,
        $contentClass,
        string $messageType,
    ): ResponseValue {
        try {
            $procedureId = $procedure->getId();
            $planId = $xmlObject->getNachrichteninhalt()->getBeteiligung()->getPlanID();
            $instanceId = $xmlObject->getNachrichteninhalt()->getVorgangsID();
            $this->setProductInfo($messageClass);
            $header = $this->buildHeader($messageType);
            $contentClass->setBeteiligungsID($procedureId);
            $contentClass->setPlanID($planId);
            $contentClass->setVorgangsID($instanceId);

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error(
                'A Procedure with id "'.$procedure->getId().
                '" was updated but the Message (Beteiligung2PlanungBeteiligung) couldn\'t be built.',
                [$e]
            );

            return new ResponseValue();
        }
    }

    private function buildProcedureDeletedResponse(
        $xmlObject,
        NachrichtG2GTypeType $messageClass,
        $contentClass,
        string $messageType,
    ): ResponseValue {
        try {
            $this->setProductInfo($messageClass);
            $header = $this->buildHeader($messageType);
            $contentClass->setBeteiligungsID($xmlObject->getNachrichteninhalt()->getBeteiligungsID());
            $contentClass->setPlanID($xmlObject->getNachrichteninhalt()->getPlanID());
            $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()->getVorgangsID());

            return $this->setResponse($contentClass, $messageClass, $header);
        } catch (Exception $e) {
            $this->dplanCockpitLogger->error('A Procedure with id "'
                .$xmlObject->getNachrichteninhalt()->getBeteiligungsID()
                .'was deleted but the Message (Beteiligung2PlanungBeteiligungNeuOK'.$messageType.') couldn\'t be built.', [$e]);

            return new ResponseValue();
        }
    }

    private function buildErrorResponse(
        array $errorTypes,
              $xmlObject,
        NachrichtG2GTypeType $messageClass,
        $contentClass,
        string $messageType
    ): ResponseValue {
        $this->setProductInfo($messageClass);
        $header = $this->buildHeader($messageType);
        $contentClass->setVorgangsID($xmlObject->getNachrichteninhalt()->getVorgangsID());
        $contentClass->setPlanID($xmlObject->getNachrichteninhalt()->getBeteiligung()->getPlanID());
        $contentClass->setBeteiligungsID($xmlObject->getNachrichteninhalt()->getBeteiligung()->getBeteiligungsID());
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
        $procedureCreated->setPlanId($xmlObject->getNachrichteninhalt()->getBeteiligung()->getPlanID());
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
    private function addNamespacesToBeteiligung2PlanungXML($content, string $xml): string
    {
        $simpleXML = simplexml_load_string($xml);

        $simpleXML->addAttribute('xmlns:xmlns:xoev-code', 'http://xoev.de/schemata/code/1_0');
        $simpleXML->addAttribute('xmlsn:xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
        if (in_array($content, $this->messageTypeMapping['400']['classes'], true)) {
            $simpleXML->addAttribute(
                self::SCHEMALOCATION,
                'https://www.xleitstelle.de/xbeteiligung/1 ../xbeteiligung-kommunaleBauleitplanung.xsd');
        } elseif (in_array($content, $this->messageTypeMapping['300']['clasess'], true)) {
            $simpleXML->addAttribute(
                self::SCHEMALOCATION,
                'https://www.xleitstelle.de/xbeteiligung/1 ../xbeteiligung-raumordnung.xsd');
        } elseif (in_array($content, $this->messageTypeMapping['200']['classes'], true)) {
            $simpleXML->addAttribute(
                self::SCHEMALOCATION,
                'https://www.xleitstelle.de/xbeteiligung/1 ../xbeteiligung-planfeststellung.xsd');
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
