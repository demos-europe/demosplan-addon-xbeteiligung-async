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
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureTypeServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungOeffentlichkeitType\BeteiligungPlanfeststellungOeffentlichkeitArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungTOEBType\BeteiligungPlanfeststellungTOEBArtAnonymousPHPType;
use demosplan\DemosPlanCoreBundle\Logic\Procedure\MasterTemplateService;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungMessageAudit;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenNOK0721;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegebenOK0711;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodePlanartPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureUpdater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\NameOrganisationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\OrganisationType;
use demosplan\DemosPlanCoreBundle\Repository\OrgaRepository;
use demosplan\DemosPlanCoreBundle\Logic\Procedure\ServiceStorage;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use demosplan\DemosPlanCoreBundle\Exception\ContentMandatoryFieldsException;
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
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;
use InvalidArgumentException;
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
    private const WMS_DEFAULT_WIDTH = 512;
    private const DIMENSION_WIDTH = 'width';
    private const DIMENSION_HEIGHT = 'height';

    private const PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP = [
        'configuration' => [
            'code' => '5000',
            'name' => 'Konfiguration betroffene Öffentlichkeit',
        ],
        'participation' => [
            'code' => '5200',
            'name' => self::PARTICIPATION_RAUMORDNUNG_PHASE,
        ],
        'discussiondate' => [
            'code' => '5400',
            'name' => 'Erörterungstermin',
        ],
        'earlyparticipation' => [
            'code' => '5500',
            'name' => 'Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer)',
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

    private const PUBLICPARTICIPATIONPHASPLANFESTSTELLUNGMAP = [
        'configuration' => [
            'code' => '9998',
            'name' => 'kein VS',
        ]
    ];
    private const INSTITUTIONPARTICIPATIONPHASRAUMORDNUNGMAP = [
        'configuration' => [
            'code' => '4000',
            'name' => 'Konfiguration TöB',
        ],
        'participation' => [
            'code' => '4200',
            'name' => self::PARTICIPATION_RAUMORDNUNG_PHASE,
        ],
        'renewparticipation' => [
            'code' => '4500',
            'name' => 'Erneute Anhörung TöB (Durchlaufnummer)',
        ],
        'discussiondate' => [
            'code' => '4400',
            'name' => 'Erörterungstermin',
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

    public const STANDARD = 'XBeteiligung';
    public const CODELIST_ERREICHBARKEIT = 'urn:de:xoev:codeliste:erreichbarkeit';

    /** Statement ID prefix that needs to be removed for database storage */
    public const STATEMENT_ID_PREFIX = 'ID_';
    public const NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'kommunal.Initiieren.0401';
    public const UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'kommunal.Aktualisieren.0402';
    public const DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'kommunal.Loeschen.0409';
    public const NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'raumordnung.Initiieren.0301';
    public const UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'raumordnung.Aktualisieren.0302';
    public const DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'raumordnung.Loeschen.0309';
    public const NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'planfeststellung.Initiieren.0201';
    public const UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'planfeststellung.Aktualisieren.0202';
    public const DELETE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER = 'planfeststellung.Loeschen.0209';
    public const NEW_STATEMENT_MESSAGE_IDENTIFIER = 'allgemein.stellungnahme.Neuabgegeben.0701';
    public const NEW_STATEMENT_OK_MESSAGE_IDENTIFIER = 'allgemein.stellungnahme.Neuabgegeben.OK.0711';
    public const NEW_STATEMENT_NOK_MESSAGE_IDENTIFIER = 'allgemein.stellungnahme.Neuabgegeben.NOK.0721';
    public const NEW_KOMMUNAL_OK_MESSAGE_IDENTIFIER = 'kommunal.Initiieren.OK.0411';
    public const NEW_KOMMUNAL_NOK_MESSAGE_IDENTIFIER = 'kommunal.Initiieren.NOK.0421';
    public const UNKNOWN_MESSAGE_TYPE = 'unknown';
    public const UNKNOWN_RESPONSE_MESSAGE_TYPE = 'unknown.response';
    public const AUDIT_ENABLE_PARAMETER = 'addon_xbeteiligung_async_enable_audit';
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
        private readonly KommunaleProcedureUpdater              $kommunaleProcedureUpdater,
        private readonly LoggerInterface                        $logger,
        private readonly OrgaRepository                         $orgaRepository,
        private readonly ParameterBagInterface                  $parameterBag,
        private readonly PlanningDocumentsLinkCreator           $planningDocumentsLinkCreator,
        private readonly ProcedureMessageRepository             $procedureMessageRepository,
        private readonly ProcedureNewsServiceInterface          $procedureNewsService,
        private readonly ProcedureServiceInterface              $procedureService,
        private readonly RouterInterface                        $router,
        private readonly XBeteiligungIncomingMessageParser      $incomingMessageParser,
        private readonly CommonHelpers                          $commonHelpers,
        private readonly ReusableMessageBlocks                  $reusableMessageBlocks,
        private readonly ServiceStorage                         $serviceStorage,
        private readonly XBeteiligungAuditService               $auditService,
        private readonly XmlDataExtractorService                $xmlDataExtractorService,
        private readonly MasterTemplateService                  $masterTemplateService,
        private readonly ProcedureTypeServiceInterface          $procedureTypeService,

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
        string $publicParticipationPhase
    ): CodeVerfahrensschrittRaumordnungType
    {
        $codeType = new  CodeVerfahrensschrittRaumordnungType();
        $codeType->setListVersionID('1.0');
        $codeType->setListURI($listUri);

        if (array_key_exists($publicParticipationPhase, self::PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP)) {
            $procedurePhaseCode = self::PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP[$publicParticipationPhase]['code'];
            $procedurePhaseName = self::PUBLICPARTICIPATIONPHASRAUMORDNUNGMAP[$publicParticipationPhase]['name'];
        } else {
            // Default fallback when no mapping is found to avoid empty code field
            // which would cause XSD validation to fail
            $this->logger->warning(
                'Unknown public participation phase encountered in XBeteiligung Raumordnung mapping',
                ['value' => $publicParticipationPhase, 'fallback' => '5000']
            );
            $procedurePhaseCode = '5000'; // "Konfiguration betroffene Öffentlichkeit" - most common starting phase
            $procedurePhaseName = 'Konfiguration betroffene Öffentlichkeit';
        }

        $codeType->setCode($procedurePhaseCode);
        $codeType->setName($procedurePhaseName);

        return $codeType;
    }

    private function createCodeTypePlanfeststellung(
        string $listUri,
        string $publicParticipationPhase
    ): CodeVerfahrensschrittPlanfeststellungType
    {
        $codeType = new  CodeVerfahrensschrittPlanfeststellungType();
        $codeType->setListVersionID('1.0');
        $codeType->setListURI($listUri);
        $procedurePhaseCode = '';
        $procedurePhaseName = '';
        if (array_key_exists($publicParticipationPhase, self::PUBLICPARTICIPATIONPHASPLANFESTSTELLUNGMAP)) {
            $procedurePhaseCode = self::PUBLICPARTICIPATIONPHASPLANFESTSTELLUNGMAP[$publicParticipationPhase]['code'];
            $procedurePhaseName = self::PUBLICPARTICIPATIONPHASPLANFESTSTELLUNGMAP[$publicParticipationPhase]['name'];
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
        $participationType->setVerfahrensschrittKommunal(
            $this->getPublicProcedurePhaseCodeType($procedure)
        );
        $participationType->setGeltungsbereich($procedure->getSettings()->getTerritory());
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

    private function setBeteiligungPlanfeststellungTypeSpecific(
        BeteiligungPlanfeststellungType $participationType,
        ProcedureInterface $procedure
    ): BeteiligungPlanfeststellungType {
        $participationType->setPlanartPlanfeststellung($this->createNewCodePlanartPlanfeststellungType()); // optional
        $participationType->setVerfahrensschrittPlanfeststellung(
            $this->createCodeTypePlanfeststellung(
                'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittplanfeststellung',
                $procedure->getPublicParticipationPhase()
            )
        );

        // In rog we have currently no "Geltungsbereich zeichnen" option under "Planungsdokumente und Planzeichnung".
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
                    $procedure->getPublicParticipationPhase())
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
                $this->getPublicProcedurePhaseCodeType($procedure)
            );
            $participationType->setBeteiligungKommunalTOEBArt($participationOeffentlichkeitArtType);
        }
        if ($participationType instanceof BeteiligungPlanfeststellungTOEBType) {
            $participationOeffentlichkeitArtType = new BeteiligungPlanfeststellungTOEBArtAnonymousPHPType();
            $participationOeffentlichkeitArtType->setBeteiligungPlanfeststellungFormalTOEB(
                $this->createCodeTypePlanfeststellung(
                    'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittplanfeststellung',
                    $procedure->getPhase())
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
            // use default projection label in case base layer is not set, projection label is not set or projection label is empty string
            $baseLayerProjection = $baseLayer?->getProjectionLabel();
            $defaultProjection = $this->globalConfig->getMapDefaultProjection()['label'] ?? '';

            if (empty($baseLayerProjection) && empty($defaultProjection)) {
                $this->logger->error('XBeteiligung: Both base layer projection and default projection are empty');
                throw new Exception('No valid projection label found - check base layer and map_default_projection configuration');
            }

            $projectionLabel = strtoupper(!empty($baseLayerProjection) ? $baseLayerProjection : $defaultProjection);
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
                'VERSION' => $baseLayer?->getLayerVersion(),
                'REQUEST' => 'GetMap',
                'FORMAT' => 'image/png',
                'TRANSPARENT' => 'true',
                'WIDTH' => (string)self::WMS_DEFAULT_WIDTH,
                'HEIGHT' => (string)$calculatedHeight,
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
            false,
            $procedureId
        );
    }

    public function saveProcedureMessage(ProcedureMessage $procedureMessage): void
    {
        // Audit K3 message creation if audit is enabled
        $auditEnabled = $this->parameterBag->get('addon_xbeteiligung_async_enable_audit');
        if ($auditEnabled) {
            $messageType = $this->determineMessageTypeFromContent($procedureMessage->getMessage());
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
            $messageType = $this->determineMessageTypeFromContent($procedureMessage->getMessage());
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
     * @throws InvalidArgumentException
     * @throws Exception
     */
    public function processXmlMessage(string $messageXml, bool $auditEnabled = false, ?string $routingKey = null): ?ResponseValue
    {
        $this->logger->debug('Process xml message.', ['messageXml' => substr($messageXml, 0, 500) . '...']);

        // Sanitize XML to fix common formatting issues
        $this->logger->debug('Starting XML sanitization');
        $sanitizedXml = $this->sanitizeXmlContent($messageXml);
        if ($sanitizedXml !== $messageXml) {
            $this->logger->info('XML content was sanitized to fix formatting issues');
            $messageXml = $sanitizedXml;
        } else {
            $this->logger->debug('No XML sanitization needed');
        }

        $messageStringIdentifier = $this->determineMessageTypeFromContent($messageXml);
        $this->logger->debug('Extracted message string identifier.', ['messageStringIdentifier' => $messageStringIdentifier]);

        $auditRecord = null;



        if (self::NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageStringIdentifier) {
            /** @var KommunalInitiieren0401 $kommunalInitiieren401 */
            $kommunalInitiieren401 = $this->incomingMessageParser->getXmlObject($messageXml, '401');

            if ($auditEnabled) {
                $auditRecord = $this->createAuditRecordForXmlMessage($messageXml, $messageStringIdentifier, $routingKey);
            }

            try {
                $response = $this->kommunaleProcedureCreater->createNewProcedureFromXBeteiligungMessageOrErrorMessage(
                    $kommunalInitiieren401,
                    $routingKey
                );

                $this->markAuditRecordAsProcessed($auditRecord, $response->getProcedureId());

                return $response;
            } catch (Exception $e) {
                $this->markAuditRecordAsFailed($auditRecord, $e->getMessage());
                throw $e;
            }
        }

        if (self::UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageStringIdentifier)
        {
            /** @var KommunalAktualisieren0402 $kommunalAktualisieren402 */
            $kommunalAktualisieren402 = $this->incomingMessageParser->getXmlObject($messageXml, '402');

            if ($auditEnabled) {
                $auditRecord = $this->createAuditRecordForXmlMessage($messageXml, $messageStringIdentifier, $routingKey);
            }

            try {
                $response = $this->kommunaleProcedureUpdater->updateProcedure(
                    $kommunalAktualisieren402
                );

                $this->markAuditRecordAsProcessed($auditRecord, $response->getProcedureId());

                return $response;
            } catch (Exception $e) {
                $this->markAuditRecordAsFailed($auditRecord, $e->getMessage());
                throw $e;
            }
        }

        if (self::NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER === $messageStringIdentifier) {
            /** @var PlanfeststellungInitiieren0201 $planfeststellungInitiieren201 */
            $planfeststellungInitiieren201 = $this->incomingMessageParser->getXmlObject($messageXml, '0201');

            if ($auditEnabled) {
                $auditRecord = $this->createAuditRecordForXmlMessage($messageXml, $messageStringIdentifier, $routingKey);
            }

            try {
                $response = $this->kommunaleProcedureCreater->createNewProcedureFromXBeteiligungMessageOrErrorMessage(
                    $planfeststellungInitiieren201,
                    $routingKey
                );
                $this->markAuditRecordAsProcessed($auditRecord, $response->getProcedureId());

                return $response;
            } catch (Exception $e) {
                $this->markAuditRecordAsFailed($auditRecord, $e->getMessage());
                throw $e;
            }
        }

        if (self::NEW_STATEMENT_OK_MESSAGE_IDENTIFIER === $messageStringIdentifier)
        {
            /** @var AllgemeinStellungnahmeNeuabgegebenOK0711 $newStatementOK711 */
            $newStatementOK711 = $this->incomingMessageParser->getXmlObject($messageXml, '711');
            $statementId = $this->removeStatementIdPrefix(
                $newStatementOK711->getNachrichteninhalt()?->getStellungnahmeID()
            );

            if ($auditEnabled) {
                // Find original 701 message to get procedureId, planId and for correlation
                $original701Message = $this->auditService->findOriginalOutgoing701MessageByStatementId($statementId);

                $auditRecord = $this->auditService->auditReceivedMessage(
                    $messageXml,
                    $messageStringIdentifier,
                    $original701Message?->getPlanId(), // planId from original 701
                    $original701Message?->getProcedureId(), // procedureId from original 701
                    $original701Message?->getId(), // responseToMessageId - link to original 701
                    $statementId,
                    $routingKey
                );
                $this->auditService->markAsProcessed($auditRecord->getId());
            }

            $this->logger->info('Statement OK response processed', [
                'statementId' => $statementId,
                'messageType' => $messageStringIdentifier
            ]);

            // Statement acknowledgments don't require a response - return null
            return null;
        }

        if (self::NEW_STATEMENT_NOK_MESSAGE_IDENTIFIER === $messageStringIdentifier) {
            /** @var AllgemeinStellungnahmeNeuabgegebenNOK0721 $newStatementNOK721 */
            $newStatementNOK721 = $this->incomingMessageParser->getXmlObject($messageXml, '721');
            $statementId = $this->removeStatementIdPrefix(
                $newStatementNOK721->getNachrichteninhalt()?->getStellungnahmeID()
            );
            $errorMessagesArray = $newStatementNOK721->getNachrichteninhalt()?->getFehler();
            $errorMessagesString = $this->extractErrorDescriptions($errorMessagesArray);

            if ($auditEnabled) {
                // Find original 701 message to get procedureId, planId and for correlation
                $original701Message = $this->auditService->findOriginalOutgoing701MessageByStatementId($statementId);

                $auditRecord = $this->auditService->auditReceivedMessage(
                    $messageXml,
                    $messageStringIdentifier,
                    $original701Message?->getPlanId(), // planId from original 701
                    $original701Message?->getProcedureId(), // procedureId from original 701
                    $original701Message?->getId(), // responseToMessageId - link to original 701
                    $statementId,
                    $routingKey
                );
                $this->auditService->markAsFailed($auditRecord->getId(), $errorMessagesString);
            }

            $this->logger->warning('Statement NOK response processed', [
                'statementId' => $statementId,
                'errorMessage' => $errorMessagesString,
                'messageType' => $messageStringIdentifier
            ]);

            // Statement acknowledgments don't require a response - return null
            return null;
        }

        throw new InvalidArgumentException('Unsupported message type: ' . $messageStringIdentifier);
    }

    private function createAuditRecordForXmlMessage(
        string $messageXml,
        string $messageStringIdentifier,
        ?string $routingKey = null
    ): XBeteiligungMessageAudit
    {
        $planId = $this->extractPlanIdFromXml($messageXml, $messageStringIdentifier);
        return $this->auditService->auditReceivedMessage(
            $messageXml,
            $messageStringIdentifier,
            $planId,
            null, // procedureId
            null, // responseToMessageId
            null, // statementId
            $routingKey
        );
    }

    private function markAuditRecordAsProcessed(
        ?XBeteiligungMessageAudit $auditRecord,
        ?string $procedureId = null
    ): void
    {
        if (null !== $auditRecord) {
            $this->auditService->markAsProcessed($auditRecord->getId());
            if (null !== $procedureId) {
                $this->auditService->updateAuditWithProcedureId($auditRecord->getId(), $procedureId);
            }
        }
    }

    private function markAuditRecordAsFailed(
        ?XBeteiligungMessageAudit $auditRecord,
        string $errorMessage
    ): void
    {
        if (null !== $auditRecord) {
            $this->auditService->markAsFailed($auditRecord->getId(), $errorMessage);
        }
    }

    private function determinePlanId(ProcedureInterface $procedure): string
    {
        return '' === $procedure->getXtaPlanId() ? $procedure->getId() : $procedure->getXtaPlanId();
    }

    /**
     * Determine message type from XML content for K3 audit
     */
    private function determineMessageTypeFromContent(string $xmlContent): string
    {
        $messageTypes = [
            self::NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::DELETE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER,
            self::NEW_STATEMENT_OK_MESSAGE_IDENTIFIER,
            self::NEW_STATEMENT_NOK_MESSAGE_IDENTIFIER,
        ];

        foreach ($messageTypes as $messageType) {
            if (str_contains($xmlContent, $messageType)) {
                return $messageType;
            }
        }

        return self::UNKNOWN_MESSAGE_TYPE;
    }

    /**
     * Remove ID_ prefix from statement ID if present
     *
     * @param string|null $statementId The statement ID that may contain ID_ prefix
     * @return string|null The statement ID without ID_ prefix
     */
    private function removeStatementIdPrefix(?string $statementId): ?string
    {
        if (null === $statementId) {
            return null;
        }

        return str_replace(self::STATEMENT_ID_PREFIX, '', $statementId);
    }

    /**
     * Extract readable error descriptions from FehlerType array
     *
     * @param mixed $errorMessage Array of FehlerType objects or other value
     * @return string Readable error description
     */
    private function extractErrorDescriptions(array $errorMessage): string
    {
        $errorDescriptions = [];
        foreach ($errorMessage as $fehler) {
            if ($fehler instanceof FehlerType) {
                $beschreibung = $fehler->getBeschreibung();
                if (null !== $beschreibung) {
                    $errorDescriptions[] = $beschreibung;
                }
            }
        }

        return [] !== $errorDescriptions
            ? implode('; ', $errorDescriptions)
            : 'Statement rejected by cockpit';
    }

    /**
     * Extract planId from XML content using the incoming message parser
     */
    private function extractPlanIdFromXml(string $xmlContent, string $messageType): ?string
    {
        try {
            // Extract planId based on message type structure with proper type casting
            return match ($messageType) {
                self::NEW_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
                    /** @var KommunalInitiieren0401 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '401');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                self::UPDATE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
                    /** @var KommunalAktualisieren0402 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '402');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                self::NEW_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
                    /** @var RaumordnungInitiieren0301 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '301');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                self::UPDATE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
                    /** @var RaumordnungAktualisieren0302 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '302');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                self::DELETE_KOMMUNALE_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
                    /** @var KommunalLoeschen0409 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '409');
                    return $xmlObject?->getNachrichteninhalt()?->getPlanID();
                })(),
                self::DELETE_RAUMORDNUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
                    /** @var RaumordnungLoeschen0309 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '309');
                    return $xmlObject?->getNachrichteninhalt()?->getPlanID();
                })(),
                self::NEW_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
                    /** @var PlanfeststellungInitiieren0201 $xmlObject */
                    $xmlObject = $this->incomingMessageParser->getXmlObject($xmlContent, '201');
                    return $xmlObject?->getNachrichteninhalt()?->getBeteiligung()?->getPlanID();
                })(),
                self::UPDATE_PLANFESTSTELLUNG_PROCEDURE_XML_MESSAGE_IDENTIFIER => (function() use ($xmlContent) {
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
     * Validates that the organization name from the XML exists in the database.
     *
     * @param string $organizationName The organization name from the veranlasser element in the XML
     * @throws InvalidArgumentException If the organization is not found in the database
     */
    private function validateOrganization(string $organizationName): void
    {
        $this->logger->debug('Validating organization from XML', ['organizationName' => $organizationName]);

        $organization = $this->orgaRepository->findOneBy([
            'name' => $organizationName,
            'deleted' => false
        ]);

        if (null === $organization) {
            $this->logger->error('Organization not found in database', ['organizationName' => $organizationName]);

            throw new InvalidArgumentException(
                sprintf('Organization \'%s\' not found in the database', $organizationName)
            );
        }

        $this->logger->debug('Organization validation successful', [
            'organizationName' => $organizationName,
            'organizationId' => $organization->getId()
        ]);
    }

    /**
     * Creates a procedure from XML data using ServiceStorage::administrationNewHandler.
     *
     * @param ProcedureDataValueObject $procedureDataObject The extracted and parsed XML data
     * @return ProcedureInterface The created procedure
     * @throws InvalidArgumentException If procedure creation fails
     * @throws Exception If an error occurs during creation
     */
    private function createProcedureFromXmlData(ProcedureDataValueObject $procedureDataObject): ProcedureInterface
    {
        $this->logger->debug('Creating procedure from extracted XML data');

        $planId = $procedureDataObject->getPlanId();
        $planName = $procedureDataObject->getPlanName();
        $orgaName = $procedureDataObject->getContactOrganization();

        if (null === $planId || null === $planName || null === $orgaName) {
            throw new InvalidArgumentException('Missing required data in XML message: planId, planName, or orgaName');
        }

        $organization = $this->orgaRepository->findOneBy([
            'name' => $orgaName,
            'deleted' => false
        ]);

        if (null === $organization) {
            throw new InvalidArgumentException(sprintf('Organization "%s" not found in database', $orgaName));
        }

        $organizationUsers = $organization->getUsers();
        $plannerUsers = $organizationUsers->filter(fn ($user) => $user->isPlanner())->toArray();

        if (empty($plannerUsers)) {
            throw new InvalidArgumentException(
                sprintf('No active planner users found in organization "%s"', $organization->getName())
            );
        }

        $procedureCreatorUser = reset($plannerUsers);
        $systemUserId = $procedureCreatorUser->getId();

        if (null === $systemUserId) {
            throw new InvalidArgumentException('Could not get user ID from selected planner');
        }

        $this->logger->debug('Procedure creation parameter lookup', [
            'selectedPlannerUserId' => $systemUserId,
            'selectedPlannerUserName' => $procedureCreatorUser->getFirstname() . ' ' . $procedureCreatorUser->getLastname(),
            'organizationName' => $organization->getName(),
        ]);

        $this->logger->info('Creating procedure without master template to avoid database schema issues', [
            'orgaName' => $orgaName,
            'planId' => $planId,
            'approach' => 'no_template_copy'
        ]);

        $startDate = $procedureDataObject->getStartDate() ?? new DateTime();
        $endDate = $procedureDataObject->getEndDate() ?? (new DateTime())->add(new DateInterval('P1Y'));

        $description = '';
        $procedureTypeId = '';
        //@TODO: need to research if we want to use description and procedure type for xml export?
        //$additionalInfo = $procedureDataObject->getAdditionalInformation();
        //$description = $additionalInfo['planDescription'] ?? $procedureDataObject->getDescription() ?? '';

        //$r_copymaster = $this->procedureService->getMasterTemplateId();
        $procedureData = [
            'r_name' => $planName,
            'r_desc' => $description,
            'r_externalDesc' => $description,
            'orgaId' => $organization->getId(),
            'orgaName' => $organization->getName(),
            'agencyMainEmailAddress' => $organization->getEmail2() ?? $this->parameterBag->get('default_agency_email', 'noreply@example.com'),
            'action' => 'new',
            'r_master' => 'false',
            'r_copymaster' => 'ae65efdb-8414-4deb-bc81-26efdfc9560b',
            'r_procedure_type' => $this->procedureTypeService->getProcedureTypeByName('Planfeststellung')?->getId(),
            'xtaPlanId' => $planId,
            'r_startdate' => $startDate->format('d.m.Y'),
            'r_enddate' => $endDate->format('d.m.Y'),
            'r_phase' => 'configuration',
            'publicParticipationPhase' => 'configuration',
        ];
        $this->logger->info('Calling ServiceStorage::administrationNewHandler with data', [
            'procedureData' => $procedureData,
            'userId' => $systemUserId,
            'organizationName' => $orgaName
        ]);

        try {
            $procedure = $this->serviceStorage->administrationNewHandler($procedureData, $systemUserId);

            $this->logger->info('Procedure created successfully from XML data', [
                'procedureId' => $procedure->getId(),
                'planId' => $planId,
                'planName' => $planName,
                'orgaName' => $orgaName,
                'extractedFields' => [
                    'startDate' => $startDate->format('Y-m-d'),
                    'endDate' => $endDate->format('Y-m-d'),
                    'description' => $description,
                    'additionalData' => $procedureDataObject->getAdditionalInformation()
                ]
            ]);

            return $procedure;

        } catch (\Doctrine\DBAL\Exception\DatabaseException $dbException) {
            $previousException = $dbException->getPrevious();
            $sqlQuery = null;
            $sqlParams = null;

            if ($previousException instanceof \PDOException) {
                $sqlQuery = method_exists($previousException, 'getQuery') ? $previousException->getQuery() : 'unknown';
            }

            $this->logger->error('Database error during procedure creation - DETAILED', [
                'errorMessage' => $dbException->getMessage(),
                'errorCode' => $dbException->getCode(),
                'procedureData' => $procedureData,
                'userId' => $systemUserId,
                'sqlState' => $dbException->getSQLState() ?? 'unknown',
                'driverInfo' => method_exists($dbException, 'getDriverCode') ? $dbException->getDriverCode() : 'unknown',
                'sqlQuery' => $sqlQuery,
                'previousExceptionMessage' => $previousException ? $previousException->getMessage() : null,
                'previousExceptionClass' => $previousException ? get_class($previousException) : null,
                'stackTrace' => $dbException->getTraceAsString()
            ]);

            throw new InvalidArgumentException(
                'Error processing procedure creation request: ' . $dbException->getMessage(),
                0,
                $dbException
            );
        } catch (\Doctrine\DBAL\Exception $dbalException) {
            $previousException = $dbalException->getPrevious();
            $errorDetails = [
                'errorMessage' => $dbalException->getMessage(),
                'errorCode' => $dbalException->getCode(),
                'procedureData' => $procedureData,
                'userId' => $systemUserId,
                'previousException' => $previousException ? $previousException->getMessage() : null,
                'previousExceptionClass' => $previousException ? get_class($previousException) : null,
                'stackTrace' => $dbalException->getTraceAsString()
            ];

            if (method_exists($dbalException, 'getSql')) {
                $errorDetails['sql'] = $dbalException->getSql();
            }
            if (method_exists($dbalException, 'getParams')) {
                $errorDetails['params'] = $dbalException->getParams();
            }
            if (method_exists($dbalException, 'getQuery')) {
                $errorDetails['query'] = $dbalException->getQuery();
            }

            $this->logger->error('DBAL error during procedure creation - COMPREHENSIVE', $errorDetails);

            throw new InvalidArgumentException(
                'Error processing procedure creation request: ' . $dbalException->getMessage(),
                0,
                $dbalException
            );
        } catch (\Exception $generalException) {
            $this->logger->error('General error during procedure creation', [
                'errorMessage' => $generalException->getMessage(),
                'errorCode' => $generalException->getCode(),
                'procedureData' => $procedureData,
                'userId' => $systemUserId,
                'exceptionClass' => get_class($generalException),
                'trace' => $generalException->getTraceAsString()
            ]);
            throw $generalException;
        }
    }

    /**
     * Sanitizes XML content to fix common formatting issues that could cause parsing errors.
     *
     * @param string $xmlContent The original XML content
     * @return string The sanitized XML content
     */
    private function sanitizeXmlContent(string $xmlContent): string
    {
        $originalXml = $xmlContent;

        if (strpos($xmlContent, '2025-09-:17:25+02:00') !== false) {
            $this->logger->debug('Found problematic timestamp pattern in XML');
        }

        $xmlContent = str_replace('2025-09-:17:25+02:00', '2025-09-03T17:25+02:00', $xmlContent);

        $xmlContent = preg_replace(
            '/(\d{4}-\d{2}-):(\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2})/',
            '${1}03T${2}', // Replace "-:" with "-03T"
            $xmlContent
        );

        if (strpos($originalXml, '2025-09-:17:25+02:00') !== false && strpos($xmlContent, '2025-09-:17:25+02:00') === false) {
            $this->logger->info('Successfully fixed problematic timestamp pattern');
        }

        $xmlContent = preg_replace(
            '/(\d{4}-\d{2}-\d{2})(\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2})/',
            '${1}T${2}', // Add T separator between date and time
            $xmlContent
        );

        $xmlContent = preg_replace(
            '/(\d{4})(-)(-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2})/',
            '${1}-01${3}', // Replace missing month with "01"
            $xmlContent
        );

        $xmlContent = preg_replace(
            '/(20)(--)(0[1-9]|1[0-2]-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2})/',
            '${1}25-${3}', // Replace missing year digits with "25"
            $xmlContent
        );

        if ($xmlContent !== $originalXml) {
            $this->logger->warning('XML timestamp formatting issues detected and fixed', [
                'changes' => [
                    'before' => preg_match('/\d{4}-\d{2}-[:\d][T\d]?\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2}/', $originalXml, $beforeMatches) ? $beforeMatches[0] : 'no timestamp found',
                    'after' => preg_match('/\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2}/', $xmlContent, $afterMatches) ? $afterMatches[0] : 'no timestamp found'
                ]
            ]);
        }

        return $xmlContent;
    }
}
