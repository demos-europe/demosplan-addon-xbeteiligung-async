<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ParticipationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\XBeteiligungMessageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungOeffentlichkeitType\BeteiligungPlanfeststellungOeffentlichkeitArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungTOEBType\BeteiligungPlanfeststellungTOEBArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\MapProjectionConverterInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use JsonException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201\PlanfeststellungInitiieren0201AnonymousPHPType\NachrichteninhaltAnonymousPHPType  as Nachrichteninhalt201;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungInitiieren0301\RaumordnungInitiieren0301AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\RaumordnungLoeschen0309\RaumordnungLoeschen0309AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAsyncAddon;
use Exception;
use proj4php\Point;
use proj4php\Proj;
use proj4php\Proj4php;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Webmozart\Assert\Assert;

class XBeteiligungService
{
    private const WMS_DEFAULT_WIDTH = 512;
    private const DIMENSION_WIDTH = 'width';
    private const DIMENSION_HEIGHT = 'height';
    private const PLACEHOLDER_PROCEDURE_PHASE_CODE = '0815';
    public const STANDARD = 'XBeteiligung';
    public const CODELIST_ERREICHBARKEIT = 'urn:de:xoev:codeliste:erreichbarkeit';
    public const MISSING_USER_ERROR_DESCRIPTION = 'Es konnte kein*e Nutzer*in mit der ID %1$s gefunden werden.';
    public const MISSING_USER_ERROR_CODE = '3000';
    public const WRONG_ATTACHMENT_FORMAT_ERROR_CODE = '3000';
    public const WRONG_ATTACHMENT_FORMAT_ERROR_DESCRIPTION = 'Falsches Dateiformat der Anlage';
    public const ACCESS_DENIED_ERROR_CODE = '3000';
    public const ACCESS_DENIED_ERROR_DESCRIPTION = 'Der/Die Nutzer*in hat nicht die notwendigen Rechte um ein Verfahren anzulegen';
    public const MISCELLANEOUS_ERROR_CODE = '3000';
    public const GENERIC_ERROR_CODE = '3000';
    public const GENERIC_ERROR_DESCRIPTION = 'Während der Erstellung/Bearbeitung des Verfahrens ist ein Fehler aufgetreten.';

    public function __construct(
        private readonly GisLayerCategoryRepositoryInterface    $gisLayerCategoryRepository,
        private readonly GlobalConfigInterface                  $globalConfig,
        private readonly LoggerInterface                        $logger,
        private readonly MapProjectionConverterInterface        $mapProjectionConverter,
        private readonly ParameterBagInterface                  $parameterBag,
        private readonly PlanningDocumentsLinkCreator           $planningDocumentsLinkCreator,
        private readonly ProcedureMessageRepository             $procedureMessageRepository,
        private readonly ProcedureNewsServiceInterface          $procedureNewsService,
        private readonly RouterInterface                        $router,
        private readonly XBeteiligungIncomingMessageParser      $incomingMessageParser,
        private readonly CommonHelpers                          $commonHelpers,
        private readonly ReusableMessageBlocks                  $reusableMessageBlocks,
        private readonly XBeteiligungAuditService               $auditService,
        private readonly XBeteiligungPhaseDefinitionCodeRepository $phaseDefinitionCodeRepository,
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

    public function createXMLFor201(
        ProcedureInterface $procedure
    ): string
    {
        $procedureCreated201 = new PlanfeststellungInitiieren0201();
        $this->reusableMessageBlocks->setProductInfo($procedureCreated201);
        $procedureCreated201->setNachrichtenkopfG2g(
            $this->reusableMessageBlocks->createMessageHeadFor($procedureCreated201)
        ); // required
        $procedureCreated201->setNachrichteninhalt(
            $this->generateMain201MessageContent($procedure)
        ); // required

        return SerializerFactory::serializeData($procedureCreated201, $this->logger);
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

    private function generateMain201MessageContent(ProcedureInterface $procedure): Nachrichteninhalt201
    {
        $messageContent = new Nachrichteninhalt201();
        $messageContent->setVorgangsID($this->commonHelpers->uuid());
        $messageContent->setBeteiligung(
            $this->generateParticipationContentForX01OrX02Message($procedure, new BeteiligungPlanfeststellungType())
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
        ProcedureInterface $procedure,
        ParticipationType $participationType
    ): CodeVerfahrensschrittRaumordnungType
    {
        $codeType = new  CodeVerfahrensschrittRaumordnungType();
        $codeType->setListVersionID('1.0');
        $codeType->setListURI($listUri);

        $phaseObject = $participationType === ParticipationType::PUBLIC
            ? $procedure->getPublicParticipationPhaseObject()
            : $procedure->getPhaseObject();

        $codeType->setCode($this->getPhaseCodeFromDefinition($phaseObject, $procedure->getId()));
        $codeType->setName($phaseObject->getPhaseDefinition()->getName());

        return $codeType;
    }

    private function createCodeTypePlanfeststellung(
        string $listUri,
        ProcedureInterface $procedure,
        ParticipationType $participationType
    ): CodeVerfahrensschrittPlanfeststellungType
    {
        $codeType = new  CodeVerfahrensschrittPlanfeststellungType();
        $codeType->setListVersionID('1.0');
        $codeType->setListURI($listUri);

        $phaseObject = $participationType === ParticipationType::PUBLIC
            ? $procedure->getPublicParticipationPhaseObject()
            : $procedure->getPhaseObject();

        $codeType->setCode($this->getPhaseCodeFromDefinition($phaseObject, $procedure->getId()));
        $codeType->setName($phaseObject->getPhaseDefinition()->getName());

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

        if ($participationType instanceof BeteiligungPlanfeststellungType) {
            $participationType = $this->setBeteiligungPlanfeststellungTypeSpecific($participationType, $procedure);
        }

        return $participationType;
    }

    private function setBeteiligungKommunalTypeSpecific(
        BeteiligungKommunalType $participationType,
        ProcedureInterface $procedure
    ): BeteiligungKommunalType {
        $participationType->setPlanartKommunal($this->createNewCodePlanartKommunalType()); // optional
        $participationType->setVerfahrensschrittKommunal($this->getPublicProcedurePhaseCodeType($procedure));

        // Extract and set Geltungsbereich from territory data
        $territory = $procedure->getSettings()->getTerritory();
        if (null === $territory || '' === trim($territory)) {
            $this->logger->warning('XBeteiligung: Procedure has no territory data - Geltungsbereich will be missing in message', [
                'procedureId' => $procedure->getId(),
                'procedureName' => $procedure->getName()
            ]);
        }
        $geltungsbereich = $this->extractOriginalGeltungsbereichFromTerritory($territory);
        $participationType->setGeltungsbereich($geltungsbereich);

        $participationType->setBeteiligungOeffentlichkeit($this->generatePublicParticipationType($procedure, $participationType));
        $participationType->setBeteiligungTOEB($this->generateInstitutionParticipationType($procedure, $participationType));

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
                $procedure,
                ParticipationType::PUBLIC
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

    private function setBeteiligungPlanfeststellungTypeSpecific(
        BeteiligungPlanfeststellungType $participationType,
        ProcedureInterface $procedure
    ): BeteiligungPlanfeststellungType {
        $participationType->setPlanartPlanfeststellung($this->createNewCodePlanartPlanfeststellungType()); // optional
        $participationType->setVerfahrensschrittPlanfeststellung(
            $this->createCodeTypePlanfeststellung(
                'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittplanfeststellung',
                $procedure,
                ParticipationType::PUBLIC
            )
        );

        // no Geltungsbereich zeichnen option in Planfeststellung yet
        $participationType->setGeltungsbereich('');

        $participationType->setBeteiligungOeffentlichkeit($this->generatePublicParticipationType($procedure, $participationType));
        $participationType->setBeteiligungTOEB($this->generateInstitutionParticipationType($procedure, $participationType));

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

    private function createNewCodePlanartPlanfeststellungType(): CodePlanartPlanfeststellungType
    {
        $planType = new CodePlanartPlanfeststellungType();
        /* @TODO: Clarify which code to use here - there was no fitting code in the provided list */
        $planType->setCode('?????')
            ->setListVersionID('1.0')
            ->setListURI('urn:xoev-de:xleitstelle:codeliste:planartplanfeststellung');

        return $planType;
    }

    private function generateInstitutionParticipationType(
        ProcedureInterface $procedure,
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $participationType
    ): BeteiligungKommunalTOEBType|BeteiligungPlanfeststellungTOEBType {
        $institutionParticipationType = $this->getSpecificParticipationToebType($participationType);

        // we as demos think this id is useless - did not win the discussion as it seems :(
        $institutionParticipationType?->setBeteiligungsID($this->commonHelpers->uuid());
        // this MetadatenAnlageType should support a base64 container to dump files into, but it does not - S.C. is informed
        //$publicParticipationType->setAnlagen([new MetadatenAnlageType()]); // optional - still not fixed
        $institutionParticipationType?->setZeitraum($this->createTimeSpanOfProcedurePhase($procedure->getPhaseObject()));
        $institutionParticipationType?->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        // Ensure durchgang is at least 1 as required by XSD schema (xs:positiveInteger)
        $iteration = $procedure->getPhaseObject()->getIteration();
        $institutionParticipationType?->setDurchgang($iteration);
        if (null !== $institutionParticipationType) {
            $this->setSpecificParticipationToebArtType($procedure, $institutionParticipationType);
        }
        // optional - we want to use it
        $institutionParticipationType->setAktuelleMitteilung($this->getInstitutionNewsList($procedure));

        return $institutionParticipationType;
    }

    private function generatePublicParticipationType(
        ProcedureInterface $procedure,
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $participationType
    ): BeteiligungKommunalOeffentlichkeitType|BeteiligungPlanfeststellungOeffentlichkeitType
    {
        $publicParticipationType = $this->getSpecificParticipationOeffentlichkeitType($participationType);

        // we as demos think this id is useless - did not win the discussion as it seems :(
        $publicParticipationType?->setBeteiligungsID($this->commonHelpers->uuid());
        $publicParticipationType?->setZeitraum(
            $this->createTimeSpanOfProcedurePhase($procedure->getPublicParticipationPhaseObject())
        );
        $publicParticipationType?->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        // Ensure durchgang is at least 1 as required by XSD schema (xs:positiveInteger)
        $iteration = $procedure->getPublicParticipationPhaseObject()->getIteration();
        $publicParticipationType?->setDurchgang($iteration);
        if (null !== $publicParticipationType) {
            $this->setSpecificParticipationOeffentlichkeitArtType($procedure, $publicParticipationType);
        }
        $publicParticipationType?->setAktuelleMitteilung($this->getPublicNewsList($procedure));
        $publicParticipationType?->setAnlagen($this->planningDocumentsLinkCreator->getPlanningDocuments($procedure));

        return $publicParticipationType;
    }

    private function getSpecificParticipationOeffentlichkeitType(
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $participationType
    ): null|BeteiligungKommunalOeffentlichkeitType|BeteiligungPlanfeststellungOeffentlichkeitType {
        $participationOeffentlichkeitType = null;
        if ($participationType instanceof BeteiligungKommunalType) {
            $participationOeffentlichkeitType = new BeteiligungKommunalOeffentlichkeitType();
        }
        if ($participationType instanceof BeteiligungPlanfeststellungType) {
            $participationOeffentlichkeitType = new BeteiligungPlanfeststellungOeffentlichkeitType();
        }

        return $participationOeffentlichkeitType;
    }

    private function getSpecificParticipationToebType(
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $participationType
    ): null|BeteiligungKommunalTOEBType|BeteiligungPlanfeststellungTOEBType {
        $participationToebType = null;
        if ($participationType instanceof BeteiligungKommunalType) {
            $participationToebType = new BeteiligungKommunalTOEBType();
        }
        if ($participationType instanceof BeteiligungPlanfeststellungType) {
            $participationToebType = new BeteiligungPlanfeststellungTOEBType();
        }

        return $participationToebType;
    }

    private function setSpecificParticipationOeffentlichkeitArtType(
        ProcedureInterface $procedure,
        BeteiligungKommunalOeffentlichkeitType|BeteiligungPlanfeststellungOeffentlichkeitType $participationType
    ): void {
        if ($participationType instanceof BeteiligungKommunalOeffentlichkeitType) {
            $participationOeffentlichkeitArtType = new BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType();
            $participationOeffentlichkeitArtType->setBeteiligungKommunalFormalOeffentlichkeit(
                $this->getPublicProcedurePhaseCodeType($procedure)
            );
            $participationType->setBeteiligungKommunalOeffentlichkeitArt($participationOeffentlichkeitArtType);
        }
        if ($participationType instanceof BeteiligungPlanfeststellungOeffentlichkeitType) {
            $participationOeffentlichkeitArtType = new BeteiligungPlanfeststellungOeffentlichkeitArtAnonymousPHPType();
            $participationOeffentlichkeitArtType->setBeteiligungPlanfeststellungFormalOeffentlichkeit(
                $this->createCodeTypePlanfeststellung(
                    'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittplanfeststellung',
                    $procedure,
                    ParticipationType::PUBLIC
                )
            );
            $participationType->setBeteiligungPlanfeststellungOeffentlichkeitArt($participationOeffentlichkeitArtType);
        }
    }

    private function setSpecificParticipationToebArtType(
        ProcedureInterface $procedure,
        BeteiligungKommunalTOEBType|BeteiligungPlanfeststellungTOEBType $participationType
    ): void {
        if ($participationType instanceof BeteiligungKommunalTOEBType) {
            $participationOeffentlichkeitArtType = new BeteiligungKommunalTOEBArtAnonymousPHPType();
            $participationOeffentlichkeitArtType->setBeteiligungKommunalFormalTOEB(
                $this->getInstitutionProcedurePhaseCodeType($procedure)
            );
            $participationType->setBeteiligungKommunalTOEBArt($participationOeffentlichkeitArtType);
        }
        if ($participationType instanceof BeteiligungPlanfeststellungTOEBType) {
            $participationOeffentlichkeitArtType = new BeteiligungPlanfeststellungTOEBArtAnonymousPHPType();
            $participationOeffentlichkeitArtType->setBeteiligungPlanfeststellungFormalTOEB(
                $this->createCodeTypePlanfeststellung(
                    'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittplanfeststellung',
                    $procedure,
                    ParticipationType::INSTITUTION)
            );
            $participationType->setBeteiligungPlanfeststellungTOEBArt($participationOeffentlichkeitArtType);
        }
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
            $bplanLayer = null;
            /** @var GisLayerInterface $gisLayer */
            foreach ($gisLayers as $gisLayer) {
                $enabled = $gisLayer->isEnabled();
                $isBplan = $gisLayer->isBplan();
                if ($enabled && $isBplan) {
                    $bplanLayer = $gisLayer;
                    break; // Found the BPlan layer, no need to continue
                }
            }

            if (null === $bplanLayer) {
                $this->logger->warning('No enabled BPlan layer found for procedure', [
                    'procedureId' => $procedure->getId(),
                    'procedureName' => $procedure->getName()
                ]);

                return null;
            }

            // prior to wms v1.3.0 the keyword SRS has to be used instead of CRS within urls
            $crsORsrs = version_compare(
                '1.3.0',
                $bplanLayer->getLayerVersion(),
                '<='
            ) ? 'CRS' : 'SRS';
            // use default projection label in case BPlan layer projection label is not set or is empty string
            $bplanLayerProjection = $bplanLayer->getProjectionLabel();
            $defaultProjection = $this->globalConfig->getMapDefaultProjection()['label'] ?? '';

            if (empty($bplanLayerProjection) && empty($defaultProjection)) {
                $this->logger->error('XBeteiligung: Both BPlan layer projection and default projection are empty');
                throw new Exception('No valid projection label found - check BPlan layer and map_default_projection configuration');
            }

            $projectionLabel = strtoupper(!empty($bplanLayerProjection) ? $bplanLayerProjection : $defaultProjection);
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

            $baseUrl = $bplanLayer->getUrl();

            // Calculate height with division by zero protection
            $width = $widthAndHeight[self::DIMENSION_WIDTH];
            $height = $widthAndHeight[self::DIMENSION_HEIGHT];
            $calculatedHeight = self::WMS_DEFAULT_WIDTH; // Default square aspect ratio

            if ($width > 0) {
                $calculatedHeight = (int)(self::WMS_DEFAULT_WIDTH * $height / $width);
            }

            if ($width <= 0) {
                $this->logger->warning('Width is zero or negative in bounding box calculation, using default square aspect ratio', [
                    self::DIMENSION_WIDTH => $width,
                    self::DIMENSION_HEIGHT => $height,
                    'bbox' => $transformedBbox
                ]);
            }

            $urlParams = [
                'SERVICE' => 'WMS',
                'VERSION' => $bplanLayer->getLayerVersion(),
                'REQUEST' => 'GetMap',
                'FORMAT' => 'image/png',
                'TRANSPARENT' => 'true',
                'WIDTH' => (string)self::WMS_DEFAULT_WIDTH,
                'HEIGHT' => (string)$calculatedHeight,
                $crsORsrs => $projectionLabel,
                'STYLES' => '',
                'LAYERS' => $bplanLayer->getLayers(),
                'BBOX' => $transformedBbox,
            ];

            return $baseUrl . '?' . http_build_query($urlParams);
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
        // Check if we have all required bbox coordinates
        if (count($procedureSettingsBBox) !== 4) {
            // Return a default bbox covering Germany for the projection if not enough coordinates
            if ($targetProjectionName === 'EPSG:4326') {
                return ['5.866', '47.270', '15.042', '55.058']; // Germany in WGS84 (lat/lon)
            }
            return ['653300', '5975800', '1674500', '6636200']; // Germany in EPSG:3857 Web Mercator
        }

        $west = (float)$procedureSettingsBBox[0];
        $south = (float)$procedureSettingsBBox[1];
        $east = (float)$procedureSettingsBBox[2];
        $north = (float)$procedureSettingsBBox[3];
        $reprojectParams = [
            [min([$west, $east]), min([$north, $south])],
            [max([$west, $east]), max([$north, $south])],
        ];

        $proj4 = new Proj4php();

        $targetProjection = new Proj($targetProjectionName, $proj4);
        $sourceProjection = new Proj($this->globalConfig->getMapDefaultProjection()['label'], $proj4);

        $transformedCoords = array_map(
            fn (array $coordinate) => $this->convertPoint(
                $coordinate,
                $sourceProjection,
                $targetProjection
            ),
            $reprojectParams
        );

        $west = (string)$transformedCoords[0][0];
        $east = (string)$transformedCoords[1][0];
        $south = (string)$transformedCoords[0][1];
        $north = (string)$transformedCoords[1][1];
        $bboxArray = [$west, $south, $east, $north];
        if ($areCoordsSwapped) {
            $bboxArray = [$south, $west, $north, $east];
        }

        return $bboxArray;
    }

    /**
     * @param string $returnType [self::ARRAY_RETURN_TYPE | self::STRING_RETURN_TYPE]
     *
     * @return array|string
     */
    public function convertPoint(
        array $coordinate,
        Proj $currentProjection,
        Proj $newProjection
    ) {
        $projectionTransformer = new Proj4php();
        $pointSrc = new Point($coordinate[0], $coordinate[1], $currentProjection);
        $pointDest = $projectionTransformer
            ->transform($newProjection, $pointSrc)
            ->toArray();

        return [$pointDest[0], $pointDest[1]];
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

        return [self::DIMENSION_WIDTH => $width, self::DIMENSION_HEIGHT => $height];
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
            0,
            $procedureId
        );
    }

    public function saveProcedureMessage(ProcedureMessage $procedureMessage): void
    {
        // Audit K3 message creation if audit is enabled
        $auditEnabled = $this->parameterBag->get('addon_xbeteiligung_async_enable_audit');
        if ($auditEnabled) {
            $messageType = XBeteiligungMessageType::fromXmlContent($procedureMessage->getMessage());
            $planId = $this->extractPlanIdFromXml($procedureMessage->getMessage(), $messageType);

            $auditRecord = $this->auditService->auditK3Message(
                $procedureMessage->getMessage(),
                $messageType,
                $procedureMessage->getProcedureId(),
                $planId
            );

            // Store audit ID for direct linking
            $procedureMessage->setAuditId($auditRecord->getId());
        }

        $this->procedureMessageRepository->save($procedureMessage);
    }

    public function saveProcedureMessageOnFlush(ProcedureMessage $procedureMessage): void
    {
        // Audit K3 message creation (for 402/409/302/309 messages during flush)
        $auditEnabled = $this->parameterBag->get('addon_xbeteiligung_async_enable_audit', false);
        if ($auditEnabled && null !== $this->auditService) {
            $messageType = XBeteiligungMessageType::fromXmlContent($procedureMessage->getMessage());
            $planId = $this->extractPlanIdFromXml($procedureMessage->getMessage(), $messageType);
            $auditRecord = $this->auditService->auditK3Message(
                $procedureMessage->getMessage(),
                $messageType,
                $procedureMessage->getProcedureId(),
                $planId,
                true // saveOnFlush to avoid infinite recursion
            );

            // Store audit ID for direct linking
            $procedureMessage->setAuditId($auditRecord->getId());
        }

        $this->procedureMessageRepository->saveOnFlush($procedureMessage);
    }

    private function getPublicProcedurePhaseCodeType(ProcedureInterface $procedure): CodeVerfahrensschrittKommunalType
    {
        $codeProcedurePhase = new CodeVerfahrensschrittKommunalType();
        $codeProcedurePhase->setListURI('urn:xoev-de:xleitstelle:codeliste:verfahrensschrittkommunal');
        $codeProcedurePhase->setListVersionID('1.0');

        $phaseObject = $procedure->getPublicParticipationPhaseObject();
        $codeProcedurePhase->setCode($this->getPhaseCodeFromDefinition($phaseObject, $procedure->getId()));
        $codeProcedurePhase->setName($phaseObject->getPhaseDefinition()->getName());

        return $codeProcedurePhase;
    }

    private function getInstitutionProcedurePhaseCodeType(ProcedureInterface $procedure): CodeVerfahrensschrittKommunalType
    {
        $codeProcedurePhase = new CodeVerfahrensschrittKommunalType();
        $codeProcedurePhase->setListURI('urn:xoev-de:xleitstelle:codeliste:verfahrensschrittkommunal');
        $codeProcedurePhase->setListVersionID('1.0');

        $phaseObject = $procedure->getPhaseObject();
        $codeProcedurePhase->setCode($this->getPhaseCodeFromDefinition($phaseObject, $procedure->getId()));
        $codeProcedurePhase->setName($phaseObject->getPhaseDefinition()->getName());

        return $codeProcedurePhase;
    }

    private function getPhaseCodeFromDefinition(ProcedurePhaseInterface $phaseObject, string $procedureId): string
    {
        $definition = $phaseObject->getPhaseDefinition();
        $mapping = $this->phaseDefinitionCodeRepository->findOneByPhaseDefinition($definition);
        if (null === $mapping) {
            $this->logger->warning('XBeteiligung: No code mapping for phase definition, falling back to placeholder', [
                'phaseDefinitionId' => $definition->getId() ?? '',
                'procedureId'       => $procedureId,
            ]);

            return self::PLACEHOLDER_PROCEDURE_PHASE_CODE;
        }

        return $mapping->getCode();
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

    private function determinePlanId(ProcedureInterface $procedure): string
    {
        return '' === $procedure->getXtaPlanId() ? $procedure->getId() : $procedure->getXtaPlanId();
    }

    /**
     * Extract planId from XML content using the incoming message parser
     */
    private function extractPlanIdFromXml(string $xmlContent, string $messageType): ?string
    {
        try {
            // Extract planId based on message type structure with proper type casting
            return match ($messageType) {
                XBeteiligungMessageType::KOMMUNAL_INITIIEREN->value => (function() use ($xmlContent) {
                    /** @var KommunalInitiieren0401 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '401');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                XBeteiligungMessageType::KOMMUNAL_AKTUALISIEREN->value => (function() use ($xmlContent) {
                    /** @var KommunalAktualisieren0402 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '402');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                XBeteiligungMessageType::RAUMORDNUNG_INITIIEREN->value => (function() use ($xmlContent) {
                    /** @var RaumordnungInitiieren0301 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '301');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                XBeteiligungMessageType::RAUMORDNUNG_AKTUALISIEREN->value => (function() use ($xmlContent) {
                    /** @var RaumordnungAktualisieren0302 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '302');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                XBeteiligungMessageType::KOMMUNAL_LOESCHEN->value => (function() use ($xmlContent) {
                    /** @var KommunalLoeschen0409 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '409');
                    return $xmlObject?->getNachrichteninhalt()?->getPlanID();
                })(),
                XBeteiligungMessageType::RAUMORDNUNG_LOESCHEN->value => (function() use ($xmlContent) {
                    /** @var RaumordnungLoeschen0309 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '309');
                    return $xmlObject?->getNachrichteninhalt()?->getPlanID();
                })(),
                XBeteiligungMessageType::PLANFESTSTELLUNG_INITIIEREN->value => (function() use ($xmlContent) {
                    /** @var PlanfeststellungInitiieren0201 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '201');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                XBeteiligungMessageType::PLANFESTSTELLUNG_AKTUALISIEREN->value => (function() use ($xmlContent) {
                    /** @var PlanfeststellungAktualisieren0202 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '202');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                default => null
            };
        } catch (Exception $e) {
            $this->logger->warning('Could not extract planId from K3 message XML', [
                'messageType' => $messageType,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Extract the WGS84 polygon from a territory FeatureCollection for use in outgoing XBeteiligung messages.
     *
     * Territory is stored as a FeatureCollection with one feature in EPSG:3857 (Web Mercator).
     * Since GeoJSON (RFC 7946) and the XBeteiligung standard require WGS84/EPSG:4326 coordinates,
     * the stored EPSG:3857 geometry is converted to WGS84 before being returned.
     */
    private function extractOriginalGeltungsbereichFromTerritory(?string $territory): ?string
    {
        if (null === $territory || '' === trim($territory)) {
            return null;
        }

        try {
            $featureCollection = json_decode($territory, true, 512, JSON_THROW_ON_ERROR);
            return $this->processExtractedTerritoryData($featureCollection, $territory);

        } catch (JsonException $e) {
            $this->logger->error('Failed to parse territory JSON for Geltungsbereich extraction', [
                'territory' => $territory,
                'error' => $e->getMessage()
            ]);

            return null;
        }
    }

    /**
     * @throws JsonException
     */
    private function extractGeometryFromTerritoryData(array $featureCollection, string $territory): ?string
    {
        $hasType = isset($featureCollection['type']);
        $isLegacyGeometry = $hasType && in_array($featureCollection['type'], ['Polygon', 'MultiPolygon']);

        // Handle legacy format (direct polygon/multipolygon) — also stored in EPSG:3857, convert to WGS84
        if ($isLegacyGeometry) {
            return $this->convertEpsg3857GeometryToWgs84Json($featureCollection);
        }

        $isFeatureCollection = $hasType && 'FeatureCollection' === $featureCollection['type'];
        $hasFeaturesArray = isset($featureCollection['features']);
        $hasFeatures = $hasFeaturesArray && count($featureCollection['features']) > 0;

        if ($isFeatureCollection && $hasFeatures) {
            // All FeatureCollection features are stored in EPSG:3857 — convert to WGS84
            return $this->convertEpsg3857GeometryToWgs84Json($featureCollection['features'][0]['geometry']);
        }

        return null;
    }

    /**
     * @throws JsonException
     */
    private function convertEpsg3857GeometryToWgs84Json(array $geometry): string
    {
        $proj3857 = $this->mapProjectionConverter->getProjection('EPSG:3857');
        $proj4326 = $this->mapProjectionConverter->getProjection('EPSG:4326');

        $featureCollectionJson = json_encode([
            'type'     => 'FeatureCollection',
            'features' => [['type' => 'Feature', 'geometry' => $geometry, 'properties' => null]],
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);

        $converted = $this->mapProjectionConverter->convertGeoJsonPolygon(
            $featureCollectionJson,
            $proj3857,
            $proj4326,
            MapProjectionConverterInterface::OBJECT_RETURN_TYPE
        );

        return json_encode($converted->features[0]->geometry, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES);
    }

    /**
     * @throws JsonException
     */
    private function processExtractedTerritoryData(array $featureCollection, string $territory): ?string
    {
        $result = $this->extractGeometryFromTerritoryData($featureCollection, $territory);

        if (null === $result) {
            $this->logger->warning('Unable to extract original Geltungsbereich from territory format', [
                'territory' => $territory
            ]);
        }

        return $result;
    }
}
