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

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\InstitutionParticipationPhase;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\PublicParticipationPhase;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\UnsupportedMessageTypeException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\CodeVerzeichnisdienstTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Autor;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\IdentifikationNachricht;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\Leser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtenkopfG2g;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\CodeKommunikationKanalTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\Erreichbarkeit;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameOrganisationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\OrganisationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AkteurVorhabenType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeXBeteiligungNachrichtenType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402\KommunalAktualisieren0402AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401\KommunalInitiieren0401AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409\KommunalLoeschen0409AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungLoeschen0209;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungAktualisieren0302\RaumordnungAktualisieren0302AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301\RaumordnungInitiieren0301AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309\RaumordnungLoeschen0309AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAsyncAddon;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class XBeteiligungService
{
    private Serializer $serializer;
    private const PARTICIPATION_RAUMORDNUNG_PHASE = 'Erwiderung /Planänderung bzw. Auswertung';

    private const PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP = [
        'configuration' => [
            'code' => '5000',
            'name' => 'Konfiguration betroffene Öffentlichkeit',
        ],
        'earlyparticipation' => [
            'code' => '5500',
            'name' => 'Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer)',
        ],
        'participation' => [
            'code' => '5300',
            'name' => self::PARTICIPATION_RAUMORDNUNG_PHASE,
        ],
        'anotherparticipation' => [
            'code' => '5300',
            'name' => self::PARTICIPATION_RAUMORDNUNG_PHASE,
        ],
        'evaluating' => [
            'code' => '5600',
            'name' => 'Auswertung betroffene Öffentlichkeit',
        ],
        'closed' => [
            'code' => '5700',
            'name' => 'Beschlussfassung betroffene Öffentlichkeit',
        ]
    ];
    private const INSTITUTIONPARTICIPATIONPHASRAUMORDNUNGMAP = [
        'configuration' => [
            'code' => '4000',
            'name' => 'Konfiguration TöB',
        ],
        'earlyparticipation' => [
            'code' => '4500',
            'name' => 'Erneute Anhörung TöB (Durchlaufnummer)',
        ],
        'participation' => [
            'code' => '4300',
            'name' => self::PARTICIPATION_RAUMORDNUNG_PHASE,
        ],
        'anotherparticipation' => [
            'code' => '4300',
            'name' => self::PARTICIPATION_RAUMORDNUNG_PHASE,
        ],
        'evaluating' => [
            'code' => '4600',
            'name' => 'Auswertung TöB',
        ],
        'closed' => [
            'code' => '4700',
            'name' => 'Beschlussfassung TöB',
        ]
    ];

    private array $messageTypeMapping = [
        '400' => [
            'xsd' => 'xbeteiligung-kommunaleBauleitplanung.xsd',
            'classes' => [
                KommunalInitiieren0401::class,
                KommunalAktualisieren0402::class,
                KommunalLoeschen0409::class,
            ],
        ],
        '300' => [
            'xsd' => 'xbeteiligung-raumordnung.xsd',
            'classes' => [
                RaumordnungInitiieren0301::class,
                RaumordnungAktualisieren0302::class,
                RaumordnungLoeschen0309::class,
            ],
        ],
        '200' => [
            'xsd' => 'xbeteiligung-planfeststellung.xsd',
            'classes' => [
                PlanfeststellungInitiieren0201::class,
                PlanfeststellungAktualisieren0202::class,
                PlanfeststellungLoeschen0209::class,
            ],
        ],
    ];

    private const NON_EXISTING_CODE = 'work probably in progress';
    public const STANDARD = 'XBeteiligung';
    public const CODELIST_ERREICHBARKEIT = 'urn:de:xoev:codeliste:erreichbarkeit';
    public const NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:kommunal.Initiieren.0401';
    public const UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:kommunal.Aktualisieren.0402';
    public const DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:kommunal.Loeschen.0409';
    public const NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:raumordnung.Initiieren.0301';
    public const UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:raumordnung.Aktualisieren.0302';
    public const DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:raumordnung.Loeschen.0309';
    public const NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:planfeststellung.Initiieren.0201';
    public const UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:planfeststellung.Aktualisieren.0202';
    public const DELETE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'xbeteiligung:planfeststellung.Loeschen.0209';
    public const MISSING_USER_ERROR_DESCRIPTION = 'Es konnte kein*e Nutzer*in mit der ID %1$s gefunden werden.';
    public const MISSING_USER_ERROR_CODE = '0300';
    public const WRONG_ATTACHMENT_FORMAT_ERROR_CODE = '0200';
    public const WRONG_ATTACHMENT_FORMAT_ERROR_DESCRIPTION = 'Falsches Dateiformat der Anlage';
    public const ACCESS_DENIED_ERROR_CODE = '0300';
    public const ACCESS_DENIED_ERROR_DESCRIPTION = 'Der/Die Nutzer*in hat nicht die notwendigen Rechte um ein Verfahren anzulegen';
    public const MISCELLANEOUS_ERROR_CODE = '0300';
    public const GENERIC_ERROR_CODE = '0300';
    public const GENERIC_ERROR_DESCRIPTION = 'Während der Erstellung/Bearbeitung des Verfahrens ist ein Fehler aufgetreten.';

    public function __construct(
        private readonly GisLayerCategoryRepositoryInterface $gisLayerCategoryRepository,
        private readonly LoggerInterface                     $logger,
        private readonly ProcedureNewsServiceInterface       $procedureNewsService,
        private readonly ProcedureMessageRepository          $procedureMessageRepository,
        private readonly PlanningDocumentsLinkCreator        $planningDocumentsLinkCreator,
        private readonly RouterInterface                     $router,
        private readonly XBeteiligungIncomingMessageParser   $incomingMessageParser,
        private readonly KommunaleProcedureCreater           $kommunaleProcedureCreater,
    ) {
        $this->serializer = SerializerFactory::getSerializer();
    }

    /**
     * @throws Exception
     */
    public function createProcedureNew401FromObject(
        ProcedureInterface $procedure
    ): string
    {
        $procedureCreated401Object = new KommunalInitiieren0401();
        $this->setProductInfo($procedureCreated401Object);
        $procedureCreated401Object->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureCreated401Object)
        ); // required
        $procedureCreated401Object->setNachrichteninhalt(
            $this->generateMain401MessageContent($procedure)
        ); // required

        return $this->serializeData($procedureCreated401Object);
    }

    public function createXMLFor301(
        ProcedureInterface $procedure
    )
    {
        $procedureCreated301 = new RaumordnungInitiieren0301();
        $this->setProductInfo($procedureCreated301);
        $procedureCreated301->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureCreated301)
        ); // required
        $procedureCreated301->setNachrichteninhalt(
            $this->generateMain301MessageContent($procedure)
        ); // required
        return $this->serializeData($procedureCreated301);
    }

    /**
     * @throws Exception
     */
    public function createProcedureUpdate402FromObject(
        ProcedureInterface $procedure,
        $procedureUpdated402Object = new KommunalAktualisieren0402()
    ): string
    {
        $this->setProductInfo($procedureUpdated402Object);
        $procedureUpdated402Object->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureUpdated402Object)
        ); // required
        $procedureUpdated402Object->setNachrichteninhalt(
            $this->generateMain402MessageContent($procedure)
        ); // required

        return $this->serializeData($procedureUpdated402Object);
    }

    public function createXMLFor302(
        ProcedureInterface $procedure,
    ): string
    {
        $procedureUpdated302 = new RaumordnungAktualisieren0302();
        $this->setProductInfo($procedureUpdated302);
        $procedureUpdated302->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureUpdated302)
        );
        $procedureUpdated302->setNachrichteninhalt(
            $this->generateMain302MessageContent($procedure)
        );

        return $this->serializeData($procedureUpdated302);
    }

    /**
     * @throws Exception
     */
    public function createProcedureDeleted409FromObject(
        string $procedureId
    ): string
    {
        $procedureDeleted409Object = new KommunalLoeschen0409();
        $this->setProductInfo($procedureDeleted409Object);
        $procedureDeleted409Object->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureDeleted409Object)
        ); // required
        $procedureDeleted409Object->setNachrichtenInhalt($this->generateMain409MessageContent($procedureId));

        return $this->serializeData($procedureDeleted409Object);
    }

    public function createXMLFor309(
        string $procedureId
    ): string
    {
        $procedureDeleted309 = new RaumordnungLoeschen0309();
        $this->setProductInfo($procedureDeleted309);
        $procedureDeleted309->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureDeleted309)
        );
        $procedureDeleted309->setNachrichteninhalt(
            $this->generateMain309MessageContent($procedureId)
        );

        return $this->serializeData($procedureDeleted309);
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
        $messageObject->setVersion('1.3'); // required

        return $messageObject;
    }

    private function generateMain401MessageContent(ProcedureInterface $procedure): Nachrichteninhalt401
    {
        $messageContent = new Nachrichteninhalt401();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungKommunalType())
        );

        return $messageContent;
    }

    private function generateMain301MessageContent(ProcedureInterface $procedure): Nachrichteninhalt301
    {
        $messageContent = new Nachrichteninhalt301();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungRaumordnungType())
        );

        return $messageContent;
    }

    private function generateMain402MessageContent(ProcedureInterface $procedure): Nachrichteninhalt402
    {
        $messageContent = new Nachrichteninhalt402();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungKommunalType())
        );

        return $messageContent;
    }

    private function generateMain302MessageContent(ProcedureInterface $procedure): Nachrichteninhalt302
    {
        $messageContent = new Nachrichteninhalt302();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungRaumordnungType())
        );

        return $messageContent;
    }

    private function generateMain409MessageContent(string $procedureId): Nachrichteninhalt409
    {
        $messageContent = new Nachrichteninhalt409();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setPlanID($procedureId);
        $messageContent->setBeteiligungsID($procedureId); // why does only a 409 Message still has this property?

        return $messageContent;
    }

    public function generateMain309MessageContent(string $procedureId): Nachrichteninhalt309
    {
        $messageContent = new Nachrichteninhalt309();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setPlanID($procedureId);
        $messageContent->setBeteiligungsID($procedureId);

        return $messageContent;
    }

    /**
     * Creates a type which holds information about the initiator and other actors of a procedure.
     */
    private function createAkteurVorhabenType(string $orgaName): AkteurVorhabenType
    {
        $actorsOfProcedure = new AkteurVorhabenType();
        $organisationType = new OrganisationType();
        $name = new NameOrganisationType();
        $name->setName($orgaName);
        $organisationType->setName($name); // nested element required
        $actorsOfProcedure->setVeranlasser($organisationType);

        return $actorsOfProcedure;
    }

    private function createCodeTypeRaumordnung(
        string $listUri,
        string $publicParticipationPhase
    ): CodeVerfahrensschrittRaumordnungType
    {
        $codeType = new  CodeVerfahrensschrittRaumordnungType();
        $codeType->setListVersionID('1.0');
        $codeType->setListURI($listUri);
        $procedurePhaseCode = '';
        $procedurePhaseName = '';
        if (array_key_exists($publicParticipationPhase, self::PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP)) {
            $procedurePhaseCode = self::PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP[$publicParticipationPhase]['code'];
            $procedurePhaseName = self::PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP[$publicParticipationPhase]['name'];
        }

        $codeType->setCode($procedurePhaseCode);
        $codeType->setName($procedurePhaseName);

        return $codeType;
    }

    private function getExternalDescriptionOfProcedure(ProcedureInterface $procedure): string
    {
        return str_replace('<br>', "\n", strip_tags($procedure->getExternalDesc() ?? ''));
    }

    private function createTimeSpanOfProcedurePhase(ProcedurePhaseInterface $procedurePhase): ZeitraumType
    {
        $timeSpan = new ZeitraumType();

        return $timeSpan->setBeginn($procedurePhase->getStartDate())->setEnde($procedurePhase->getEndDate());
    }

    /**
     * @param BeteiligungKommunalType|BeteiligungRaumordnungType    $participationType
     *
     * @return BeteiligungKommunalType|BeteiligungRaumordnungType
     */
    public function generateParticipationContentForX01OrX02Message(
        ProcedureInterface $procedure,
        mixed $participationType
    ): mixed {
        $participationType->setAkteurVorhaben(
            $this->createAkteurVorhabenType($procedure->getOrga()?->getName() ?? '')
        );
        $participationType->setPlanID($procedure->getId());
        $participationType->setPlanname($procedure->getName());
        $participationType->setBeschreibungPlanungsanlass($this->getExternalDescriptionOfProcedure($procedure));
        $participationType->setFlaechenabgrenzungUrl(
            $this->generateFaceBoundaryWMSUrl($procedure)
        );
        $participationType->setBeteiligungURL(
            $this->router->generate(
                'DemosPlan_procedure_public_detail',
                ['procedure' => $procedure->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
        $participationType->setRaeumlicheBeschreibung('');

        if ($participationType instanceof BeteiligungKommunalType) {
            $participationType = $this->setBeteiligungKommunalTypeSpecific($participationType, $procedure);
        }

        if ($participationType instanceof BeteiligungRaumordnungType) {
            $participationType = $this->setBeteiligungRaumordnungTypeSpecific($participationType, $procedure);
        }

        return $participationType;
    }

    private function setBeteiligungKommunalTypeSpecific(
        BeteiligungKommunalType $participationType,
        ProcedureInterface $procedure
    ): BeteiligungKommunalType {
        $participationType->setPlanartKommunal($this->createNewCodePlanartKommunalType()); // optional
        $participationType->setVerfahrensschrittKommunal(
            $this->getPublicProcedurePhaseCodeType($procedure)
        );
        $participationType->setGeltungsbereich($procedure->getSettings()->getTerritory());
        $participationType->setBeteiligungOeffentlichkeit($this->generatePublicParticipationType($procedure));
        $participationType->setBeteiligungTOEB($this->generateInstitutionParticipationType($procedure));

        return $participationType;
    }

    private function setBeteiligungRaumordnungTypeSpecific(
        BeteiligungRaumordnungType $participationType,
        ProcedureInterface $procedure
    ): BeteiligungRaumordnungType {
        $participationType->setPlanart($this->createNewCodePlanartRaumordnungType()); // optional
        $participationType->setVerfahrensschritt(
            $this->createCodeTypeRaumordnung(
                'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittraumordnung',
                $procedure->getPublicParticipationPhase()
            )
        );
        // ***********currently for 0301 and 0302 required fields*******************************************************
        // deprecated: With next standard update this setters could be removed.
        $participationType->setZeitraum(
            $this->createTimeSpanOfProcedurePhase($procedure->getPublicParticipationPhaseObject())
        );
        $participationType->setAktuelleMitteilung($this->getPublicNewsList($procedure));
        $participationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        );
        $participationType->setDurchgang(1);
        $participationType->setAnlagen($this->planningDocumentsLinkCreator->getPlanningDocuments($procedure));

        // In rog we have currently no "Geltungsbereich zeichnen" option under "Planungsdokumente und Planzeichnung".
        $participationType->setGeltungsbereich('');
        // *************************************************************************************************************
        // *** With the next standard update something like this should be available. **********************************
        //$participationType->setBeteiligungOeffentlichkeit($this->generatePublicParticipationType($procedure));
        //$participationType->setBeteiligungTOEB($this->generateInstitutionParticipationType($procedure));
        // *************************************************************************************************************

        return $participationType;
    }

    private function createNewCodePlanartKommunalType(): CodePlanartKommunalType
    {
        $planType = new CodePlanartKommunalType();
        $planType->setCode('6_3_EinfacherBPlan')
            ->setName('Einfacher Bebauungsplan')
            ->setListVersionID('1.0')
            ->setListURI('urn:xoev-de:xleitstelle:codeliste:planartkommunal');

        return $planType;
    }

    private function createNewCodePlanartRaumordnungType(): CodePlanartRaumordnungType
    {
        $planType = new CodePlanartRaumordnungType();
        $planType->setCode('3_1_Regionalplan')
            ->setName('Regionalplan')
            ->setListVersionID('1.0')
            ->setListURI('urn:xoev-de:xleitstelle:codeliste:planartraumordnung');

        return $planType;
    }

    private function generateInstitutionParticipationType(ProcedureInterface $procedure): BeteiligungKommunalTOEBType
    {
        $institutionParticipationType = new BeteiligungKommunalTOEBType();

        // we as demos think this id is useless - did not win the discussion as it seems :(
        $institutionParticipationType->setBeteiligungsID($this->uuid());
        // this MetadatenAnlageType should support a base64 container to dump files into, but it does not - S.C. is informed
        //$publicParticipationType->setAnlagen([new MetadatenAnlageType()]); // optional - still not fixed
        $institutionParticipationType->setZeitraum($this->createTimeSpanOfProcedurePhase($procedure->getPhaseObject()));
        $institutionParticipationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        $institutionParticipationType->setDurchgang(1); // required not documented not wanted
        $bkTOEBaaType = new BeteiligungKommunalTOEBArtAnonymousPHPType();
        $bkTOEBaaType->setBeteiligungKommunalFormalTOEB($this->getInstitutionProcedurePhaseCodeType($procedure));
        $institutionParticipationType->setBeteiligungKommunalTOEBArt($bkTOEBaaType);
        // optional - we want to use it
        $institutionParticipationType->setAktuelleMitteilung($this->getInstitutionNewsList($procedure));

        return $institutionParticipationType;
    }

    private function generatePublicParticipationType(ProcedureInterface $procedure): BeteiligungKommunalOeffentlichkeitType
    {
        $publicParticipationType = new BeteiligungKommunalOeffentlichkeitType();
        // we as demos think this id is useless - did not win the discussion as it seems :(
        $publicParticipationType->setBeteiligungsID($this->uuid());
        // this MetadatenAnlageType should support a base64 container to dump files into but it does not - S.C. is informed
        // $publicParticipationType->setAnlagen([new MetadatenAnlageType()]); // optional - still not fixed
        $publicParticipationType->setZeitraum(
            $this->createTimeSpanOfProcedurePhase($procedure->getPublicParticipationPhaseObject())
        );
        $publicParticipationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        $publicParticipationType->setDurchgang(1); // required not documented not wanted
        $bkoeaaType = new BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType();
        $bkoeaaType->setBeteiligungKommunalFormalOeffentlichkeit(
            $this->getPublicProcedurePhaseCodeType($procedure)
        );
        $publicParticipationType->setBeteiligungKommunalOeffentlichkeitArt($bkoeaaType);
        $publicParticipationType->setAktuelleMitteilung($this->getPublicNewsList($procedure));
        $publicParticipationType->setAnlagen($this->planningDocumentsLinkCreator->getPlanningDocuments($procedure));


        return $publicParticipationType;
    }

    /**
     * @throws Exception
     */
    public function createMessageHeadFor(NachrichtG2GTypeType $messageObject): NachrichtenkopfG2g
    {
        $messageHead = new NachrichtenkopfG2g();
        $messageHead->setIdentifikationNachricht($this->createMessageIdentification($messageObject)); // required
        $messageHead->setLeser($this->createReaderInformation()); // required
        $messageHead->setAutor($this->createAuthorInformation()); // required

        return $messageHead;
    }

    public function createReaderInformation(): Leser
    {
        $reader = new Leser();
        $reader->setKennung(''); // required
        $reader->setName('K3'); // required
        $verzeichnisdienst = new CodeVerzeichnisdienstTypeType();
        $verzeichnisdienst->setListVersionID('');
        $verzeichnisdienst->setListURI('urn:xoev-de:kosit:codeliste:verzeichnisdienst');
        $verzeichnisdienst->setCode(self::NON_EXISTING_CODE);
        $reader->setVerzeichnisdienst($verzeichnisdienst); // required


        $codeAuthorityIdentification = new Erreichbarkeit();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('');
        $kanal->setListURI(self::CODELIST_ERREICHBARKEIT);
        $kanal->setCode('work probably in progress');
        $codeAuthorityIdentification->setKanal($kanal);
        $codeAuthorityIdentification->setKennung(''); // required
        $reader->setErreichbarkeit([$codeAuthorityIdentification]); // required

        return $reader;
    }

    public function createAuthorInformation(): Autor
    {
        $author = new Autor();
        $author->setKennung('');
        $author->setName(''); // required
        $prefixType = new CodeVerzeichnisdienstTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('urn:xoev-de:kosit:codeliste:verzeichnisdienst');
        $prefixType->setCode(self::NON_EXISTING_CODE);
        $author->setVerzeichnisdienst($prefixType); // required

        $codeAuthorityIdentification = new Erreichbarkeit();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('');
        $kanal->setListURI(self::CODELIST_ERREICHBARKEIT);
        $kanal->setCode(self::NON_EXISTING_CODE);
        $codeAuthorityIdentification->setKanal($kanal);
        $codeAuthorityIdentification->setKennung(''); // required
        $author->setErreichbarkeit([$codeAuthorityIdentification]); // required
        $author->addToErreichbarkeit($this->addAuthorCommunicationType()); // required list 1 entry


        return $author;
    }

    private function generateFaceBoundaryWMSUrl(ProcedureInterface $procedure): string
    {
        $rootCategory = $this->gisLayerCategoryRepository->getRootLayerCategory($procedure->getId());

        if (null === $rootCategory) {
            // Currently, all procedures have a root layer category
            throw new InvalidArgumentException('Procedure has no root layer category, cannot add layers');
        }

        $gisLayers = $rootCategory->getGisLayers();
        $basemapGisLayer = null;
        /** @var GisLayerInterface $gisLayer */
        foreach ($gisLayers as $gisLayer) {
            if ('basemap' === $gisLayer->getName()) {
                $basemapGisLayer = $gisLayer;
            }
        }
        // why mapExtend? see here: T32377
        $bboxArray = explode(',', $procedure->getSettings()->getMapExtent());
        $absWidth = 1;
        $absHeight = 1;

        if (4 === count($bboxArray)) {
            $west = (float)$bboxArray[0];
            $east = (float)$bboxArray[2];
            $south = (float)$bboxArray[1];
            $north = (float)$bboxArray[3];
            $absWidth = abs($west - $east);
            $absHeight = abs($south - $north);
        }

        $url = $basemapGisLayer->getUrl();
        $serviceType = '?SERVICE=WMS';
        $version = '&VERSION=' . $basemapGisLayer->getLayerVersion();
        $request = '&REQUEST=GetMap';
        $format = '&FORMAT=image%2Fpng';
        $transparent = '&TRANSPARENT=true';
        $layers = '&LAYERS=' . str_replace(',', '%2C', $basemapGisLayer->getLayers());
        $width = '&WIDTH=' . '512';
        $height = '&HEIGHT=' . 512 * ($absHeight / $absWidth);
        $crs = '&CRS=EPSG%3A3857';
        $styles = '&STYLES=';
        // why mapExtend? see here: T32377
        $bbox = '&BBOX=' . str_replace(',', '%2C', $procedure->getSettings()->getMapExtent());


        return $url . $serviceType . $version . $request . $format . $transparent . $layers . $width .
            $height . $crs . $styles . $bbox;
    }

    private function addAuthorCommunicationType(): Erreichbarkeit
    {
        $communicationType = new Erreichbarkeit();
        $comCode = new CodeKommunikationKanalTypeType();
        // Quelle - AdoRepo: Erreichbarkeit-3.xml
        // 01 -> E-Mail, 02 -> Telefon Festnetz, 03 -> Telefon mobil, 04 -> Fax, 05 -> Instant Messenger,
        // 06 -> Pager, 07 -> Sonstiges, (08 -> DE-Mail, 09 -> Web - these don't exist in validation)
        $comCode->setCode('07');
        //$comCode->setName('Sonstiges'); // not expected in validation
        $comCode->setListURI(self::CODELIST_ERREICHBARKEIT);
        $comCode->setListVersionID('1');
        $communicationType->setKanal($comCode); // required
        $communicationType->setKennung('https://demosplan.com/impressum.html'); // required
        $communicationType->setZusatz(''); // optional

        return $communicationType;
    }

    /**
     * @throws Exception
     */
    public function createMessageIdentification(NachrichtG2GTypeType $messageObject): IdentifikationNachricht
    {
        if ($messageObject instanceof KommunalInitiieren0401) {
            $code = '0401';
            $name = 'kommunal.Initiieren.0401';
        } elseif ($messageObject instanceof KommunalAktualisieren0402) {
            $code = '0402';
            $name = 'kommunal.Aktualisieren.0402';
        } elseif ($messageObject instanceof  KommunalLoeschen0409) {
            $code = '0409';
            $name = 'kommunal.Loeschen.0409';
        } elseif ($messageObject instanceof RaumordnungInitiieren0301 ) {
            $code = '0301'; // 0301
            $name = 'raumordnung.Initiieren.0301';
        } elseif ($messageObject instanceof RaumordnungAktualisieren0302 ) {
            $code = '0302'; // 0302
            $name = 'raumordnung.Aktualisieren.0302';
        } elseif ($messageObject instanceof RaumordnungLoeschen0309 ) {
            $code = '0309'; // 0309
            $name = 'raumordnung.Loeschen.0309';
        } elseif ($messageObject instanceof PlanfeststellungInitiieren0201 ) {
            $code = '0201'; // 0201
            $name = 'planfeststellung.Initiieren.0201';
        } elseif ($messageObject instanceof PlanfeststellungAktualisieren0202 ) {
            $code = '0202'; // 0202
            $name = 'planfeststellung.Aktualisieren.0202';
        } elseif ($messageObject instanceof PlanfeststellungLoeschen0209 ) {
            $code = '0209'; // 0209
            $name = 'planfeststellung.Loeschen.0209';
        } else {
            $this->logger->error('Class '.$messageObject::class.' not supported yet');
            throw new UnsupportedMessageTypeException(
                $messageObject::class . ' is not supported - unable to set messageIdentification code'
            );
        }

        $identificationMessage = new IdentifikationNachricht();

        $messageTypeCode = new CodeXBeteiligungNachrichtenType();
        $messageTypeCode->setListURI('urn:xoev-de:xleitstelle:codeliste:xbeteiligung-nachrichten');
        $messageTypeCode->setListVersionID('1.3');
        $messageTypeCode->setName($name);
        $messageTypeCode->setCode($code);

        // id has to match pattern: '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}'
        $identificationMessage->setNachrichtenUUID($this->uuid()); // required
        $identificationMessage->setErstellungszeitpunkt(new DateTime()); // required
        $identificationMessage->setNachrichtentyp($messageTypeCode); // required

        return $identificationMessage;
    }

    private function resolveXsdFilePath(string $messageClass): string
    {
        foreach ($this->messageTypeMapping as $group) {
            if (in_array($messageClass, $group['classes'], true)) {
                return $group['xsd'];
            }
        }

        throw new InvalidArgumentException(sprintf(
            'No XSD file found for message class: %s',
            $messageClass
        ));
    }

    /**
     * Validates a message against a given xsd file located in plugin xsd folder.
     */
    public function isValidMessage(
        string $message,
        bool $verboseDebug = false,
        string $path = '',
        string $messageClass = ''
    ): bool
    {
        if ('' === $path) {
            $path = AddonPath::getRootPath('Resources/xsd/');
        }
        $xsdFile = $this->resolveXsdFilePath($messageClass);
        $fullPath = $path . $xsdFile;
        $document = new \DOMDocument();
        $document->loadXML($message);
        $isValid = $document->schemaValidate($fullPath);
        if (!$isValid) {
            // revalidate with error handling
            libxml_use_internal_errors(true);
            $document->schemaValidate($fullPath);
            $errors = libxml_get_errors();
            foreach ($errors as $error) {
                $this->logger->warning('Invalid XML message', [$error]);
                if ($verboseDebug) {
                    $this->logger->debug('XML validation error', ['error' => $error]);
                }
            }
            libxml_clear_errors();
            return false;
        }
        return true;
    }

    public function createProcedureMessage(string $xml, string $procedureId): ProcedureMessage
    {
        $error = false;
        $path = AddonPath::getRootPath('addons/vendor/' .
            XBeteiligungAsyncAddon::ADDON_NAME . '/Resources/xsd/');
        if (false === $this->isValidMessage($xml, path: $path))
        {
            $this->logger->warning('The generated XML is not valid.', [
                'procedureId' => $procedureId,
                'generatedXML' => $xml
            ]);
            $error = true;
        }
        return new ProcedureMessage(
            $xml,
            false,
            $error,
            false,
            $procedureId
        );
    }

    public function saveProcedureMessage(ProcedureMessage $procedureMessage): void
    {
        $this->procedureMessageRepository->save($procedureMessage);
    }

    public function saveProcedureMessageOnFlush(ProcedureMessage $procedureMessage): void
    {
        $this->procedureMessageRepository->saveOnFlush($procedureMessage);
    }

    private function getInstitutionProcedurePhaseCodeType(ProcedureInterface $procedure): CodeVerfahrensschrittKommunalType
    {
        $codeProcedurePhase = new CodeVerfahrensschrittKommunalType();
        $codeProcedurePhase->setListVersionID('1.0');
        $procedurePhase = InstitutionParticipationPhase::fromKey($procedure->getPhase());
        if(null !== $procedurePhase) {
            $codeProcedurePhase->setCode(
                $procedurePhase->getCode()
            );
            $codeProcedurePhase->setName(
                $procedurePhase->getName()
            );
        }

        return $codeProcedurePhase;
    }

    private function getPublicProcedurePhaseCodeType(ProcedureInterface $procedure): CodeVerfahrensschrittKommunalType
    {
        $codeProcedurePhase = new CodeVerfahrensschrittKommunalType();
        $codeProcedurePhase->setListURI('urn:xoev-de:xleitstelle:codeliste:verfahrensschrittkommunal');
        $codeProcedurePhase->setListVersionID('1.0');
        $publicParticipationPhase = PublicParticipationPhase::fromKey($procedure->getPublicParticipationPhase());
        if (null !== $publicParticipationPhase) {
            $codeProcedurePhase->setCode(
                $publicParticipationPhase->getCode()
            );
            $codeProcedurePhase->setName(
                $publicParticipationPhase->getName()
            );
        }

        return $codeProcedurePhase;
    }

    private function getInstitutionNewsList(ProcedureInterface $procedure): array
    {
        $procedureNewsList = $this->procedureNewsService->getProcedureNewsAdminList($procedure->getId())['result'];
        $institutionNewsList = [];
        foreach ($procedureNewsList as $news) {
            foreach ($news['roles'] as $role) {
                if ($role['groupCode'] === 'GPSORG' && isset($news['title'], $news['text'])) {
                    $institutionNewsList[] = strip_tags($news['title'].': '.$news['text']);
                    break;
                }
            }
        }

        return $institutionNewsList;
    }

    private function getPublicNewsList(ProcedureInterface $procedure): array
    {
        $procedureNewsList = $this->procedureNewsService->getProcedureNewsAdminList($procedure->getId())['result'];
        $institutionNewsList = [];
        foreach ($procedureNewsList as $news) {
            foreach ($news['roles'] as $role) {
                if (
                    ($role['code'] === RoleInterface::CITIZEN || $role['code'] === RoleInterface::GUEST) &&
                    isset($news['title'], $news['text'])) {
                    $institutionNewsList[] = strip_tags($news['title'].': '.$news['text']);
                    break;
                }
            }
        }

        return $institutionNewsList;
    }

    public function getPlanningDocumentsLinkCreator(): PlanningDocumentsLinkCreator
    {
        return $this->planningDocumentsLinkCreator;
    }

    /**
     * @throws SchemaException
     */
    public function determineMessageContextAndDelegateAction(array $message): ResponseValue
    {
        $payload = $message['messageData'];
        $messageTypeCode = array_key_exists('messageTypeCode', $message) ? $message['messageTypeCode'] : '';
        $this->logger->info('Incoming message type', [$messageTypeCode]);
        if (self::NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageTypeCode) {
            /** @var KommunalInitiieren0401 $xmlObject401 */
            $xmlObject401 = $this->incomingMessageParser->getXmlObject($payload, '401');
            return $this->kommunaleProcedureCreater->createNewProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject401);
        }
        /*
         * The code is for different message types code and we use this thing in future
         * There are not implement yet
         *
        if (self::UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageTypeCode) {
            $messageAttachments = $message['messageAttachments'];
            $xmlObject402 = $this->incomingMessageParser->getXmlObject($payload, 402);

            return $this->updateProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject402, $messageAttachments);
        }
        if (str_contains($payload, self::DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER)) {
            $xmlObject409 = $this->incomingMessageParser->getXmlObject($payload, 409);

            return $this->deleteProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject409);
        }
        if (self::NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageTypeCode) {
            $messageAttachments = $message['messageAttachments'] ?? [];
            $xmlObject301 = $this->incomingMessageParser->getXmlObject($payload, 301);

            return $this->createNewProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject301, $messageAttachments);
        }
        if (self::UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageTypeCode) {
            $messageAttachments = $message['messageAttachments'];
            $xmlObject302 = $this->incomingMessageParser->getXmlObject($payload, 302);

            return $this->updateProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject302, $messageAttachments);
        }
        if (str_contains($payload, self::DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER)) {
            $xmlObject309 = $this->incomingMessageParser->getXmlObject($payload, 309);

            return $this->deleteProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject309);
        }
        if (self::NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageTypeCode) {
            $messageAttachments = $message['messageAttachments'] ?? [];
            $xmlObject201 = $this->incomingMessageParser->getXmlObject($payload, 201);

            return $this->createNewProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject201, $messageAttachments);
        }
        if (self::UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageTypeCode) {
            $messageAttachments = $message['messageAttachments'];
            $xmlObject202 = $this->incomingMessageParser->getXmlObject($payload, 202);

            return $this->updateProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject202, $messageAttachments);
        }
        if (str_contains($payload, self::DELETE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER)) {
            $xmlObject209 = $this->incomingMessageParser->getXmlObject($payload, 209);

            return $this->deleteProcedureFromXBeteiligungMessageOrErrorMessage($xmlObject209);
        }
        */
        throw new InvalidArgumentException('Message payload not supported');
    }

    public function serializeData($data): string
    {
        // Serialize the data to XML with a custom root name
        $xml =  $this->serializer->serialize($data, 'xml');
        $this->logger->debug('Serialized XML:', [$xml]);

        // Load the XML string into a SimpleXMLElement object
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        if ($xml === false) {
            $this->logger->error('Failed to load XML string.');
            return '';
        }

        // Save the XML to a string
        $result = $xml->saveXML();
        if ($result === false) {
            $this->logger->error('Error on save serialized xml.', [$xml->asXML()]);
            return '';
        }

        return $result;
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
