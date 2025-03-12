<?php

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
use DemosEurope\DemosplanAddon\Contracts\Events\StatementCreatedEventInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\UnsupportedMessageTypeException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\XBeteiligungResponseMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeKommunikationKanalTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerzeichnisdienstTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBeteiligungNachrichtenType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalAktualisieren0402\KommunalAktualisieren0402AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalAktualisieren0402\KommunalAktualisieren0402AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiieren0401\KommunalInitiieren0401AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiieren0401\KommunalInitiieren0401AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalLoeschen0409\KommunalLoeschen0409AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalLoeschen0409\KommunalLoeschen0409AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungAktualisieren0202\PlanfeststellungAktualisieren0202AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungInitiieren0201\PlanfeststellungInitiieren0201AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PlanfeststellungLoeschen0209\PlanfeststellungLoeschen0209AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungAktualisieren0302\RaumordnungAktualisieren0302AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungAktualisieren0302\RaumordnungAktualisieren0302AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInitiieren0301\RaumordnungInitiieren0301AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungInitiieren0301\RaumordnungInitiieren0301AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungLoeschen0309\RaumordnungLoeschen0309AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\RaumordnungLoeschen0309\RaumordnungLoeschen0309AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAsyncAddon;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class XBeteiligungService
{
    private Serializer $serializer;

    // code: 1000 -> Einleitungsphase -- nicht beteiligungsrelevant
    // code: 2000 -> Frühzeitige Behördenbeteiligung
    // code: 3000 -> Aufstellungsbeschluss -- nicht beteiligungsrelevant
    // code: 3600 -> Einleitungszustimmung -- nicht beteiligungsrelevant
    // code: 4000 -> Frühzeitige Öffentlichkeitsbeteiligung
    // code: 5000 -> Beteiligung Töb
    // code: 6000 -> öffentliche Auslegung
    // code: 7000 -> Feststellungsverfahren -- nicht beteiligungsrelevant
    // code: 8000 -> Schlussphase -- nicht beteiligungsrelevant
    // code: 9998 -> kein VS // no clue what that means - but it is beteiligungsrelevant
    private const PUBLICPARTICIPATIONPHASEMAP = [
        'configuration' => [
            'code' => '1000',
            'name' => 'Einleitungsphase',
        ],
        'earlyparticipation' => [
            'code' => '4000',
            'name' => 'Frühzeitige Öffentlichkeitsbeteiligung',
        ],
        'participation' => [
            'code' => '6000',
            'name' => 'öffentliche Auslegung',
        ],
        'anotherparticipation' => [
            'code' => '6000',
            'name' => 'öffentliche Auslegung',
        ],
        'evaluating' => [
            'code' => '7000',
            'name' => 'Feststellungsverfahren',
        ],
        'closed' => [
            'code' => '8000',
            'name' => 'Schlussphase',
        ]
    ];
    private const INSTITUTIONPARTICIPATIONPHASEMAP = [
        'configuration' => [
            'code' => '1000',
            'name' => 'Einleitungsphase',
        ],
        'earlyparticipation' => [
            'code' => '2000',
            'name' => 'Frühzeitige Behördenbeteiligung',
        ],
        'participation' => [
            'code' => '5000',
            'name' => 'Beteiligung Töb',
        ],
        'anotherparticipation' => [
            'code' => '5000',
            'name' => 'Beteiligung Töb',
        ],
        'evaluating' => [
            'code' => '7000',
            'name' => 'Feststellungsverfahren',
        ],
        'closed' => [
            'code' => '8000',
            'name' => 'Schlussphase',
        ]
    ];

    private const NON_EXISTING_CODE = 'work probably in progress';
    private const NON_EXISTING_CODE_NAME =
        'Die XLeitstelle muss im Rahmen der Eintragung von Diensten in das DVDV erstellt werden';

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
        SerializerFactory                                    $serializerFactory,
        private readonly ProcedureNewsServiceInterface       $procedureNewsService,
        private readonly ProcedureMessageRepository          $procedureMessageRepository,
        private readonly PlanningDocumentsLinkCreator        $planningDocumentsLinkCreator,
        private readonly RouterInterface                     $router,
        private readonly XBeteiligungIncomingMessageParser   $incomingMessageParser,
        private readonly XBeteiligungResponseMessageFactory  $beteiligungMessageFactory,
        private readonly KommunaleProcedureCreater           $kommunaleProcedureCreater,
        private readonly StatementCreator                    $statementCreator,
    ) {
        $this->serializer = $serializerFactory->getSerializer();
    }

    /**
     * @throws Exception
     */
    public function createProcedureNew401FromObject(ProcedureInterface $procedure): string
    {
        $procedureCreated401Object = new KommunalInitiieren0401AnonymousPHPType();
        $procedureCreated401Object = $this->beteiligungMessageFactory->setProductInfo($procedureCreated401Object); // required
        $procedureCreated401Object->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureCreated401Object)
        ); // required
        $procedureCreated401Object->setNachrichteninhalt(
            $this->generateMain401MessageContent($procedure)
        ); // required

        return $this->beteiligungMessageFactory->serializeData($procedureCreated401Object);
    }

    public function createXMLFor301(ProcedureInterface $procedure)
    {
        $procedureCreated301 = new RaumordnungInitiieren0301AnonymousPHPType();
        $procedureCreated301 = $this->beteiligungMessageFactory->setProductInfo($procedureCreated301); // required
        $procedureCreated301->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureCreated301)
        ); // required
        $procedureCreated301->setNachrichteninhalt(
            $this->generateMain301MessageContent($procedure)
        ); // required
        return $this->beteiligungMessageFactory->serializeData($procedureCreated301);
    }

    /**
     * @throws Exception
     */
    public function createProcedureUpdate402FromObject(ProcedureInterface $procedure): string
    {
        $procedureUpdated402Object = new KommunalAktualisieren0402AnonymousPHPType();
        $procedureUpdated402Object = $this->beteiligungMessageFactory->setProductInfo($procedureUpdated402Object); // required
        $procedureUpdated402Object->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureUpdated402Object)
        ); // required
        $procedureUpdated402Object->setNachrichteninhalt(
            $this->generateMain402MessageContent($procedure)
        ); // required

        return $this->beteiligungMessageFactory->serializeData($procedureUpdated402Object);
    }

    public function createXMLFor302(ProcedureInterface $procedure): string
    {
        $procedureUpdated302 = new RaumordnungAktualisieren0302AnonymousPHPType();
        $procedureUpdated302 = $this->beteiligungMessageFactory->setProductInfo($procedureUpdated302);
        $procedureUpdated302->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureUpdated302)
        );
        $procedureUpdated302->setNachrichteninhalt(
            $this->generateMain302MessageContent($procedure)
        );

        return $this->beteiligungMessageFactory->serializeData($procedureUpdated302);
    }

    /**
     * @throws Exception
     */
    public function createProcedureDeleted409FromObject(string $procedureId): string
    {
        $procedureDeleted409Object = new KommunalLoeschen0409AnonymousPHPType();
        $procedureDeleted409Object = $this->beteiligungMessageFactory->setProductInfo($procedureDeleted409Object); // required
        $procedureDeleted409Object->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureDeleted409Object)
        ); // required
        $procedureDeleted409Object->setNachrichtenInhalt($this->generateMain409MessageContent($procedureId));

        return $this->beteiligungMessageFactory->serializeData($procedureDeleted409Object);
    }

    public function createXMLFor309(string $procedureId): string
    {
        $procedureDeleted409 = new RaumordnungLoeschen0309AnonymousPHPType();
        $procedureDeleted409 = $this->beteiligungMessageFactory->setProductInfo($procedureDeleted409);
        $procedureDeleted409->setNachrichtenkopfG2g(
            $this->createMessageHeadFor($procedureDeleted409)
        );
        $procedureDeleted409->setNachrichteninhalt(
            $this->generateMain309MessageContent($procedureId)
        );

        return $this->beteiligungMessageFactory->serializeData($procedureDeleted409);
    }

    private function generateMain401MessageContent(ProcedureInterface $procedure): Nachrichteninhalt401
    {
        $messageContent = new Nachrichteninhalt401();
        $messageContent->setVorgangsID($this->beteiligungMessageFactory->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungKommunalType())
        );

        return $messageContent;
    }

    private function generateMain301MessageContent(ProcedureInterface $procedure): Nachrichteninhalt301
    {
        $messageContent = new Nachrichteninhalt301();
        $messageContent->setVorgangsID($this->beteiligungMessageFactory->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungRaumordnungType())
        );

        return $messageContent;
    }

    private function generateMain402MessageContent(ProcedureInterface $procedure): Nachrichteninhalt402
    {
        $messageContent = new Nachrichteninhalt402();
        $messageContent->setVorgangsID($this->beteiligungMessageFactory->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungKommunalType())
        );

        return $messageContent;
    }

    private function generateMain302MessageContent(ProcedureInterface $procedure): Nachrichteninhalt302
    {
        $messageContent = new Nachrichteninhalt302();
        $messageContent->setVorgangsID($this->beteiligungMessageFactory->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungRaumordnungType())
        );

        return $messageContent;
    }

    private function generateMain409MessageContent(string $procedureId): Nachrichteninhalt409
    {
        $messageContent = new Nachrichteninhalt409();
        $messageContent->setVorgangsID($this->beteiligungMessageFactory->uuid());
        $messageContent->setPlanID($procedureId);
        $messageContent->setBeteiligungsID($procedureId); // why does only a 409 Message still has this property?

        return $messageContent;
    }

    public function generateMain309MessageContent(string $procedureId): Nachrichteninhalt309
    {
        $messageContent = new Nachrichteninhalt309();
        $messageContent->setVorgangsID($this->beteiligungMessageFactory->uuid());
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
        $organisationType->setName($orgaName);
        $actorsOfProcedure->setVeranlasser($organisationType);

        return $actorsOfProcedure;
    }

    /**
     * CAN BE REMOVED WITH NEXT STANDARD UPDATE (HOPEFULLY)
     * Creates a type for holding information about the public participation phase of a procedure.
     * @deprecated This information is (for 0301/0302 will be) moved to another type.
     * See for 0401/0402 {@link self::getPublicProcedurePhaseCodeType()}.
     */
    private function createCodeType(
        CodeVerfahrensschrittKommunalType|CodeVerfahrensschrittRaumordnungType $codeType,
        string $listUri,
        string $publicParticipationPhase
    ): CodeVerfahrensschrittKommunalType|CodeVerfahrensschrittRaumordnungType {
        $codeType->setListVersionID('1.0');
        $codeType->setListURI($listUri);
        $procedurePhaseCode = '4000';
        $procedurePhaseName = 'Frühzeitige Öffentlichkeitsbeteiligung';
        if (array_key_exists($publicParticipationPhase, self::PUBLICPARTICIPATIONPHASEMAP)) {
            $procedurePhaseCode = self::PUBLICPARTICIPATIONPHASEMAP[$publicParticipationPhase]['code'];
            $procedurePhaseName = self::PUBLICPARTICIPATIONPHASEMAP[$publicParticipationPhase]['name'];
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

    private static function hasReadOrWritePermissionSet(string $permissionSet): bool
    {
        return in_array($permissionSet,
            [
                ProcedureInterface::PROCEDURE_PHASE_PERMISSIONSET_READ,
                ProcedureInterface::PROCEDURE_PHASE_PERMISSIONSET_WRITE
            ],
            true
        );
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
            $this->createCodeType(
                new CodeVerfahrensschrittKommunalType(),
                'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittkommunal',
                $procedure->getPublicParticipationPhase()
            )
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
            $this->createCodeType(
                new  CodeVerfahrensschrittRaumordnungType(),
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
        $institutionParticipationType->setBeteiligungsID($this->beteiligungMessageFactory->uuid());
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
        $publicParticipationType->setBeteiligungsID($this->beteiligungMessageFactory->uuid());
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
    public function createMessageHeadFor(NachrichtG2GTypeType $messageObject): NachrichtenkopfG2GTypeType
    {
        $messageHead = new NachrichtenkopfG2GTypeType();
        $messageHead->setIdentifikationNachricht($this->createMessageIdentification($messageObject)); // required
        $messageHead->setLeser($this->createReaderInformation()); // required
        $messageHead->setAutor($this->createAuthorInformation()); // required

        return $messageHead;
    }

    public function createReaderInformation(): BehoerdeTypeType
    {
        $reader = new BehoerdeTypeType();
        $reader->setKennung(''); // required
        $reader->setName('K3'); // required
        $verzeichnisdienst = new CodeVerzeichnisdienstTypeType();
        $verzeichnisdienst->setListVersionID('');
        $verzeichnisdienst->setListURI('urn:xoev-de:bund:bmi:bit:codeliste:dvdv.praefix');
        $verzeichnisdienst->setCode(self::NON_EXISTING_CODE);
        $reader->setVerzeichnisdienst($verzeichnisdienst); // required
        $reader->setName(self::NON_EXISTING_CODE_NAME);


        $codeAuthorityIdentification = new KommunikationTypeType();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('');
        $kanal->setListURI('');
        $kanal->setName(self::NON_EXISTING_CODE_NAME);
        $kanal->setCode('work probably in progress');
        $codeAuthorityIdentification->setKanal($kanal);
        $reader->setErreichbarkeit([$codeAuthorityIdentification]); // required

        return $reader;
    }

    public function createAuthorInformation(): BehoerdeTypeType
    {
        $author = new BehoerdeTypeType();
        $author->setKennung('psw:01003110');
        $prefixType = new CodeVerzeichnisdienstTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('urn:xoev-de:bund:bmi:bit:codeliste:dvdv.praefix');
        $prefixType->setName(self::NON_EXISTING_CODE_NAME);
        $prefixType->setCode(self::NON_EXISTING_CODE);
        $author->setVerzeichnisdienst($prefixType); // required

        $codeAuthorityIdentification = new KommunikationTypeType();
        $kanal = new CodeKommunikationKanalTypeType();
        $kanal->setListVersionID('');
        $kanal->setListURI('');
        $kanal->setName(self::NON_EXISTING_CODE_NAME);
        $kanal->setCode(self::NON_EXISTING_CODE);
        $codeAuthorityIdentification->setKanal($kanal);
        $author->setErreichbarkeit([$codeAuthorityIdentification]); // required
        $author->addToErreichbarkeit($this->addAuthorCommunicationType()); // required list 1 entry
        $author->setName('DEMOS plan GmbH'); // required

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

    /**
     * @return array<int, KommunikationTypeType>
     */
    private function addReaderCommunicationType(): array
    {
        $communicationType = new KommunikationTypeType();
        $comCode = new CodeKommunikationKanalTypeType();
        // Quelle - AdoRepo: Erreichbarkeit-3.xml
        // 01 -> E-Mail, 02 -> Telefon Festnetz, 03 -> Telefon mobil, 04 -> Fax, 05 -> Instant Messenger,
        // 06 -> Pager, 07 -> Sonstiges, 08 -> DE-Mail, 09 -> Web
        $comCode->setCode('');
        $comCode->setName('');
        $comCode->setListURI('urn:de:xoev:codeliste:erreichbarkeit');
        $comCode->setListVersionID('3');
        $communicationType->setKanal($comCode); // required
        // kennung: In der Regel werden hier Adressangaben eingetragen, etwa die Telefonnummer oder die E-Mail-Adresse.
        $communicationType->setKennung(''); // required
        $communicationType->setZusatz(''); // optional

        return [$communicationType];
    }

    /**
     * @return KommunikationTypeType
     */
    private function addAuthorCommunicationType(): KommunikationTypeType
    {
        $communicationType = new KommunikationTypeType();
        $comCode = new CodeKommunikationKanalTypeType();
        // Quelle - AdoRepo: Erreichbarkeit-3.xml
        // 01 -> E-Mail, 02 -> Telefon Festnetz, 03 -> Telefon mobil, 04 -> Fax, 05 -> Instant Messenger,
        // 06 -> Pager, 07 -> Sonstiges, (08 -> DE-Mail, 09 -> Web - these don't exist in validation)
        $comCode->setCode('07');
        $comCode->setName('Sonstiges');
        $comCode->setListURI('urn:de:xoev:codeliste:erreichbarkeit');
        $comCode->setListVersionID('1');
        $communicationType->setKanal($comCode); // required
        $communicationType->setKennung('https://demosplan.com/impressum.html'); // required
        $communicationType->setZusatz(''); // optional

        return $communicationType;
    }

    /**
     * @throws Exception
     */
    public function createMessageIdentification(NachrichtG2GTypeType $messageObject): IdentifikationNachrichtTypeType
    {
        if ($messageObject instanceof KommunalInitiieren0401AnonymousPHPType) {
            $code = '0401';
            $name = 'kommunal.Initiieren.0401';
        } elseif ($messageObject instanceof KommunalAktualisieren0402AnonymousPHPType) {
            $code = '0402';
            $name = 'kommunal.Aktualisieren.0402';
        } elseif ($messageObject instanceof  KommunalLoeschen0409AnonymousPHPType) {
            $code = '0409';
            $name = 'kommunal.Loeschen.0409';
        } elseif ($messageObject instanceof RaumordnungInitiieren0301AnonymousPHPType ) {
            $code = '0301'; // 0301
            $name = 'raumordnung.Initiieren.0301';
        } elseif ($messageObject instanceof RaumordnungAktualisieren0302AnonymousPHPType ) {
            $code = '0302'; // 0302
            $name = 'raumordnung.Aktualisieren.0302';
        } elseif ($messageObject instanceof RaumordnungLoeschen0309AnonymousPHPType ) {
            $code = '0309'; // 0309
            $name = 'raumordnung.Loeschen.0309';
        } elseif ($messageObject instanceof PlanfeststellungInitiieren0201AnonymousPHPType ) {
            $code = '0201'; // 0201
            $name = 'planfeststellung.Initiieren.0201';
        } elseif ($messageObject instanceof PlanfeststellungAktualisieren0202AnonymousPHPType ) {
            $code = '0202'; // 0202
            $name = 'planfeststellung.Aktualisieren.0202';
        } elseif ($messageObject instanceof PlanfeststellungLoeschen0209AnonymousPHPType ) {
            $code = '0209'; // 0209
            $name = 'planfeststellung.Loeschen.0209';
        } else {
            $this->logger->error('Class '.$messageObject::class.' not supported yet');
            throw new UnsupportedMessageTypeException(
                $messageObject::class . ' is not supported - unable to set messageIdentification code'
            );
        }

        $identificationMessage = new IdentifikationNachrichtTypeType();

        $messageTypeCode = new CodeXBeteiligungNachrichtenType();
        $messageTypeCode->setListURI('urn:xoev-de:xleitstelle:codeliste:xbeteiligung-nachrichten');
        $messageTypeCode->setListVersionID('1.3');
        $messageTypeCode->setName($name);
        $messageTypeCode->setCode($code);

        // id has to match pattern: '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}'
        $identificationMessage->setNachrichtenUUID($this->beteiligungMessageFactory->uuid()); // required
        $identificationMessage->setErstellungszeitpunkt(new DateTime()); // required
        $identificationMessage->setNachrichtentyp($messageTypeCode); // required

        return $identificationMessage;
    }

    /**
     * Validates a message against a given xsd file located in plugin xsd folder.
     */
    public function isValidMessage(
        string $message,
        bool $verboseDebug = false,
        string $path = '',
        array $xsdFiles = ['xbeteiligung-kommunaleBauleitplanung.xsd', 'xbeteiligung-raumordnung.xsd', 'xbeteiligung-planfeststellung.xsd']
    ): bool
    {
        if ('' === $path) {
            $path = AddonPath::getRootPath('Resources/xsd/');
        }

        foreach ($xsdFiles as $xsdFile) {
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
                        // handle verbose debug
                    }
                }
                libxml_clear_errors();
                return false;
            }
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
            0,
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
        $codeProcedurePhase->setListVersionID('');
        $codeProcedurePhase->setListVersionID('1.0');
        $codeProcedurePhase->setCode(
            self::INSTITUTIONPARTICIPATIONPHASEMAP[$procedure->getPhase()]['code']
        );
        // not expected in validation
//        $codeProcedurePhase->setName(
//            self::INSTITUTIONPARTICIPATIONPHASEMAP[$procedure->getPhase()]['name']
//        );

        return $codeProcedurePhase;
    }

    private function getPublicProcedurePhaseCodeType(ProcedureInterface $procedure): CodeVerfahrensschrittKommunalType
    {
        $codeProcedurePhase = new CodeVerfahrensschrittKommunalType();
        $codeProcedurePhase->setListVersionID('');
        $codeProcedurePhase->setListVersionID('1.0');
        $codeProcedurePhase->setCode(
            self::PUBLICPARTICIPATIONPHASEMAP[$procedure->getPublicParticipationPhase()]['code']
        );
        // not expected in validation
//        $codeProcedurePhase->setName(
//            self::PUBLICPARTICIPATIONPHASEMAP[$procedure->getPublicParticipationPhase()]['name']
//        );

        return $codeProcedurePhase;
    }

    private function getInstitutionNewsList(ProcedureInterface $procedure): array
    {
        $procedureNewsList = $this->procedureNewsService->getProcedureNewsAdminList($procedure->getId())['result'];
        $institutionNewsList = [];
        foreach ($procedureNewsList as $news) {
            foreach ($news['roles'] as $role) {
                if ($role['groupCode'] === 'GPSORG'
                ) {
                    if (isset($news['title'], $news['text'])) {
                        $institutionNewsList[] = strip_tags($news['title'].': '.$news['text']);

                        break;
                    }
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
                if ($role['code'] === RoleInterface::CITIZEN
                    || $role['code'] === RoleInterface::GUEST
                ) {
                    if (isset($news['title'], $news['text'])) {
                        $institutionNewsList[] = strip_tags($news['title'].': '.$news['text']);

                        break;
                    }
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
            $xmlObject401 = $this->incomingMessageParser->getXmlObject($payload, 401);
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

    public function getStatementCreatedFromEvent(StatementCreatedEventInterface $event): StatementCreated
    {
        return $this->statementCreator->getStatementCreatedFromEvent($event);
    }

}
