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
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
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
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402\KommunalAktualisieren0402AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401\KommunalInitiieren0401AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalLoeschen0409\KommunalLoeschen0409AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt409;
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
use proj4php\Point;
use proj4php\Proj;
use proj4php\Proj4php;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class XBeteiligungService
{
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

    public const NON_EXISTING_CODE = 'work probably in progress';
    public const STANDARD = 'XBeteiligung';
    public const CODELIST_ERREICHBARKEIT = 'urn:de:xoev:codeliste:erreichbarkeit';
    public const NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'kommunal.Initiieren.0401';
    public const UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'kommunal.Aktualisieren.0402';
    public const DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'kommunal.Loeschen.0409';
    public const NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'raumordnung.Initiieren.0301';
    public const UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'raumordnung.Aktualisieren.0302';
    public const DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'raumordnung.Loeschen.0309';
    public const NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'planfeststellung.Initiieren.0201';
    public const UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'planfeststellung.Aktualisieren.0202';
    public const DELETE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'planfeststellung.Loeschen.0209';
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
        private readonly GisLayerCategoryRepositoryInterface    $gisLayerCategoryRepository,
        private readonly GlobalConfigInterface                  $globalConfig,
        private readonly KommunaleProcedureCreater              $kommunaleProcedureCreater,
        private readonly LoggerInterface                        $logger,
        private readonly PlanningDocumentsLinkCreator           $planningDocumentsLinkCreator,
        private readonly ProcedureMessageRepository             $procedureMessageRepository,
        private readonly ProcedureNewsServiceInterface          $procedureNewsService,
        private readonly RouterInterface                        $router,
        private readonly XBeteiligungIncomingMessageParser      $incomingMessageParser,
        private readonly CommonHelpers                          $commonHelpers,
        private readonly ReusableMessageBlocks                  $reusableMessageBlocks,
    ) {
    }

    /**
     * @throws Exception
     */
    public function createProcedureNew401FromObject(
        ProcedureInterface $procedure
    ): string
    {
        $procedureCreated401Object = new KommunalInitiieren0401();
        $this->reusableMessageBlocks->setProductInfo($procedureCreated401Object);
        $procedureCreated401Object->setNachrichtenkopfG2g(
            $this->reusableMessageBlocks->createMessageHeadFor($procedureCreated401Object)
        ); // required
        $procedureCreated401Object->setNachrichteninhalt(
            $this->generateMain401MessageContent($procedure)
        ); // required

        return SerializerFactory::serializeData($procedureCreated401Object, $this->logger);
    }

    /**
     * @throws Exception
     */
    public function createXMLFor301(
        ProcedureInterface $procedure
    ): string
    {
        $procedureCreated301 = new RaumordnungInitiieren0301();
        $this->reusableMessageBlocks->setProductInfo($procedureCreated301);
        $procedureCreated301->setNachrichtenkopfG2g(
            $this->reusableMessageBlocks->createMessageHeadFor($procedureCreated301)
        ); // required
        $procedureCreated301->setNachrichteninhalt(
            $this->generateMain301MessageContent($procedure)
        ); // required

        return SerializerFactory::serializeData($procedureCreated301, $this->logger);
    }

    /**
     * @throws Exception
     */
    public function createProcedureUpdate402FromObject(
        ProcedureInterface $procedure,
        $procedureUpdated402Object = new KommunalAktualisieren0402()
    ): string
    {
        $this->reusableMessageBlocks->setProductInfo($procedureUpdated402Object);
        $procedureUpdated402Object->setNachrichtenkopfG2g(
            $this->reusableMessageBlocks->createMessageHeadFor($procedureUpdated402Object)
        ); // required
        $procedureUpdated402Object->setNachrichteninhalt(
            $this->generateMain402MessageContent($procedure)
        ); // required

        return SerializerFactory::serializeData($procedureUpdated402Object, $this->logger);
    }

    /**
     * @throws Exception
     */
    public function createXMLFor302(
        ProcedureInterface $procedure,
    ): string
    {
        $procedureUpdated302 = new RaumordnungAktualisieren0302();
        $this->reusableMessageBlocks->setProductInfo($procedureUpdated302);
        $procedureUpdated302->setNachrichtenkopfG2g(
            $this->reusableMessageBlocks->createMessageHeadFor($procedureUpdated302)
        );
        $procedureUpdated302->setNachrichteninhalt(
            $this->generateMain302MessageContent($procedure)
        );

        return SerializerFactory::serializeData($procedureUpdated302, $this->logger);
    }

    /**
     * @throws Exception
     */
    public function createProcedureDeleted409FromObject(
        ProcedureInterface $procedure
    ): string
    {
        $procedureDeleted409Object = new KommunalLoeschen0409();
        $this->reusableMessageBlocks->setProductInfo($procedureDeleted409Object);
        $procedureDeleted409Object->setNachrichtenkopfG2g(
            $this->reusableMessageBlocks->createMessageHeadFor($procedureDeleted409Object)
        ); // required
        $procedureDeleted409Object->setNachrichteninhalt($this->generateMain409MessageContent($procedure));

        return SerializerFactory::serializeData($procedureDeleted409Object, $this->logger);
    }

    /**
     * @throws Exception
     */
    public function createXMLFor309(
        ProcedureInterface $procedure
    ): string
    {
        $procedureDeleted309 = new RaumordnungLoeschen0309();
        $this->reusableMessageBlocks->setProductInfo($procedureDeleted309);
        $procedureDeleted309->setNachrichtenkopfG2g(
            $this->reusableMessageBlocks->createMessageHeadFor($procedureDeleted309)
        );
        $procedureDeleted309->setNachrichteninhalt(
            $this->generateMain309MessageContent($procedure)
        );

        return SerializerFactory::serializeData($procedureDeleted309, $this->logger);
    }

    private function generateMain401MessageContent(ProcedureInterface $procedure): Nachrichteninhalt401
    {
        $messageContent = new Nachrichteninhalt401();
        $messageContent->setVorgangsID($this->commonHelpers->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungKommunalType())
        );

        return $messageContent;
    }

    private function generateMain301MessageContent(ProcedureInterface $procedure): Nachrichteninhalt301
    {
        $messageContent = new Nachrichteninhalt301();
        $messageContent->setVorgangsID($this->commonHelpers->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungRaumordnungType())
        );

        return $messageContent;
    }

    private function generateMain402MessageContent(ProcedureInterface $procedure): Nachrichteninhalt402
    {
        $messageContent = new Nachrichteninhalt402();
        $messageContent->setVorgangsID($this->commonHelpers->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungKommunalType())
        );

        return $messageContent;
    }

    private function generateMain302MessageContent(ProcedureInterface $procedure): Nachrichteninhalt302
    {
        $messageContent = new Nachrichteninhalt302();
        $messageContent->setVorgangsID($this->commonHelpers->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungRaumordnungType())
        );

        return $messageContent;
    }

    private function generateMain409MessageContent(ProcedureInterface $procedure): Nachrichteninhalt409
    {
        $messageContent = new Nachrichteninhalt409();
        $messageContent->setVorgangsID($this->commonHelpers->uuid());
        $messageContent->setPlanID($this->determinePlanId($procedure));
        $messageContent->setBeteiligungsID($procedure->getId()); // why does only a 409 Message still has this property?

        return $messageContent;
    }

    public function generateMain309MessageContent(ProcedureInterface $procedure): Nachrichteninhalt309
    {
        $messageContent = new Nachrichteninhalt309();
        $messageContent->setVorgangsID($this->commonHelpers->uuid());
        $messageContent->setPlanID($this->determinePlanId($procedure));
        $messageContent->setBeteiligungsID($procedure->getId());

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
        $participationType->setPlanID($this->determinePlanId($procedure));
        $participationType->setPlanname($procedure->getName());
        $participationType->setBeschreibungPlanungsanlass($this->getExternalDescriptionOfProcedure($procedure));
        $wmsUrl = $this->generateFaceBoundaryWMSUrl($procedure);
        if (null !== $wmsUrl) {
            $participationType->setFlaechenabgrenzungUrl(
                $wmsUrl
            );
        }
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
        // Ensure durchgang is at least 1 as required by XSD schema (xs:positiveInteger)
        $iteration = $procedure->getPublicParticipationPhaseObject()->getIteration();
        $participationType->setDurchgang($iteration);
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
        $institutionParticipationType->setBeteiligungsID($this->commonHelpers->uuid());
        // this MetadatenAnlageType should support a base64 container to dump files into, but it does not - S.C. is informed
        //$publicParticipationType->setAnlagen([new MetadatenAnlageType()]); // optional - still not fixed
        $institutionParticipationType->setZeitraum($this->createTimeSpanOfProcedurePhase($procedure->getPhaseObject()));
        $institutionParticipationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        // Ensure durchgang is at least 1 as required by XSD schema (xs:positiveInteger)
        $iteration = $procedure->getPhaseObject()->getIteration();
        $institutionParticipationType->setDurchgang($iteration);
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
        $publicParticipationType->setBeteiligungsID($this->commonHelpers->uuid());
        $publicParticipationType->setZeitraum(
            $this->createTimeSpanOfProcedurePhase($procedure->getPublicParticipationPhaseObject())
        );
        $publicParticipationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        // Ensure durchgang is at least 1 as required by XSD schema (xs:positiveInteger)
        $iteration = $procedure->getPublicParticipationPhaseObject()->getIteration();
        $publicParticipationType->setDurchgang($iteration);
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
    private function generateFaceBoundaryWMSUrl(ProcedureInterface $procedure): ?string
    {
        try {
            $rootCategory = $this->gisLayerCategoryRepository->getRootLayerCategory($procedure->getId());

            Assert::notNull($rootCategory, 'new procedure has no root layer category');

            $gisLayers = $rootCategory->getGisLayers();
            $baseLayer = null;
            /** @var GisLayerInterface $gisLayer */
            foreach ($gisLayers as $gisLayer) {
                $layerType = $gisLayer->getType();
                $enabled = $gisLayer->isEnabled();
                if ($enabled &&
                    'base' === $layerType)
                {
                    $baseLayer = $gisLayer;
                }
            }

            if (null === $baseLayer) {
                $this->logger->warning('No enabled base layer found at new procedure');

                return null;
            }

            // prior to wms v1.3.0 the keyword SRS has to be used instead of CRS within urls
            $crsORsrs = version_compare(
                '1.3.0',
                $baseLayer?->getLayerVersion(),
                '<='
            ) ? 'CRS' : 'SRS';
            $projectionLabel = strtoupper(
                $baseLayer?->getProjectionLabel()
            );
            // for some projections after v1.3.0 the x and y coords are swapped
            // - there are more, but the common ones are at least treated:
            $areCoordsSwapped =
                'CRS' === $crsORsrs &&
                ('EPSG:4326' === $projectionLabel || 'EPSG:4258' === $projectionLabel)
            ;
            // why mapExtend? see here: T32377
            $mapExtent = $procedure->getSettings()->getMapExtent();
            $bboxSourceArray = !empty($mapExtent) ? explode(',', $mapExtent) : [];
            // ratio is independent of wms version and projection - coords are always stored as EPSG:3857
            $widthAndHeight = $this->getWidthAndHeight($bboxSourceArray);
            // transform coords to desired layer-projection
            $transformedBboxArray = $this->reprojectBoundsFromCoordsStoredInDefaultMapProjection(
                $bboxSourceArray,
                $projectionLabel,
                $areCoordsSwapped
            );

            $transformedBbox = implode(',', $transformedBboxArray);

            $baseUrl = $baseLayer?->getUrl();
            $urlParams = [
                'SERVICE' => 'WMS',
                'VERSION' => $baseLayer?->getLayerVersion(),
                'REQUEST' => 'GetMap',
                'FORMAT' => 'image/png',
                'TRANSPARENT' => 'true',
                'WIDTH' => '512',
                'HEIGHT' => (string)(int)(512 * $widthAndHeight['height'] / $widthAndHeight['width']),
                $crsORsrs => $projectionLabel,
                'STYLES' => '',
                'LAYERS' => $baseLayer?->getLayers(),
                'BBOX' => $transformedBbox,
            ];
            $url = $baseUrl . '?' . http_build_query($urlParams);

            return $url;
        } catch (Exception $exception) {
            $this->logger->error(
                'XBeteiligung async: An error occurred on postProcedureCreate trying to build the wmsUrl to include xml',
                ['exceptionMessage: ' => $exception->getMessage()]
            );
            throw $exception;
        }
    }

    /**
     * @param array $procedureSettingsBBox array of bbox coordinates
     * @param string $targetProjectionName all procedureSetting sourceProjection coords are EPSG:3857 formatted
     * @param bool $areCoordsSwapped true if SRS in combination with geographic projections
     * @return array{0: string, 1: string, 2: string, 3:string}
     */
    private function reprojectBoundsFromCoordsStoredInDefaultMapProjection(
        array $procedureSettingsBBox,
        string $targetProjectionName,
        bool $areCoordsSwapped): array
    {
        // If no bbox set or if target projection is the same as the source, return the input
        if (empty($procedureSettingsBBox) || 'EPSG:3857' === $targetProjectionName) {
            return $procedureSettingsBBox ?: ['0', '0', '0', '0'];
        }

        // Check if we have all 4 coordinates needed for a bounding box
        if (4 !== count($procedureSettingsBBox)) {
            // Return default bounding box for Germany in the target projection
            return ['0', '0', '0', '0'];
        }

        // Initialize proj4php with source and target projections
        $proj4 = new Proj4php();
        $sourceProj = new Proj($proj4, 'EPSG:3857');
        $targetProj = new Proj($proj4, $targetProjectionName);

        // Extract coordinates
        $west = (float)$procedureSettingsBBox[0];
        $south = (float)$procedureSettingsBBox[1];
        $east = (float)$procedureSettingsBBox[2];
        $north = (float)$procedureSettingsBBox[3];

        // Transform corner points
        $sourcePoint1 = new Point($west, $south, $sourceProj);
        $sourcePoint2 = new Point($east, $north, $sourceProj);

        $targetPoint1 = $proj4->transform($targetProj, $sourcePoint1);
        $targetPoint2 = $proj4->transform($targetProj, $sourcePoint2);

        // For some projections like EPSG:4326, x and y coordinates are swapped
        if ($areCoordsSwapped) {
            return [
                (string)$targetPoint1->y,
                (string)$targetPoint1->x,
                (string)$targetPoint2->y,
                (string)$targetPoint2->x,
            ];
        }

        return [
            (string)$targetPoint1->x,
            (string)$targetPoint1->y,
            (string)$targetPoint2->x,
            (string)$targetPoint2->y,
        ];
    }

    /**
     * Calculate width and height from bounding box coordinates
     * 
     * @param array $bboxArray Array of bbox coordinates [west, south, east, north]
     * @return array{width: float, height: float} Width and height values
     */
    private function getWidthAndHeight(array $bboxArray): array
    {
        $width = 1.0;
        $height = 1.0;

        if (4 === count($bboxArray)) {
            $west = (float)$bboxArray[0];
            $south = (float)$bboxArray[1];
            $east = (float)$bboxArray[2];
            $north = (float)$bboxArray[3];
            
            $width = abs($east - $west);
            $height = abs($north - $south);
        }

        return ['width' => $width, 'height' => $height];
    }

    public function createProcedureMessage(string $xml, string $procedureId, string $messageClass): ProcedureMessage
    {
        $error = false;
        $path = AddonPath::getRootPath('addons/vendor/' .
            XBeteiligungAsyncAddon::ADDON_NAME . '/Resources/xsd/');
        if (false === $this->commonHelpers->isValidMessage($xml, path: $path, messageClass: $messageClass)) {
            $this->logger->warning('The generated XML is not valid.', [
                'procedureId' => $procedureId,
                'generatedXML' => $xml
            ]);
            $error = true;
        }

        if (false === $error) {
            $this->logger->info('Created XML Message is valid.', ['procedureId' => $procedureId]);
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

    private function determinePlanId(ProcedureInterface $procedure): string
    {
        return '' === $procedure->getXtaPlanId() ? $procedure->getId() : $procedure->getXtaPlanId();
    }
}
