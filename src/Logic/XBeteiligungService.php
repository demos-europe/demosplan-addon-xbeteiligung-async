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
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AkteurVorhabenType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeErreichbarTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdenkennungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittRaumordnungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBeteiligungNachrichtenType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalAktualisieren0402\Planung2BeteiligungBeteiligungKommunalAktualisieren0402AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302\Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalLoeschen0409\Planung2BeteiligungBeteiligungKommunalLoeschen0409AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungLoeschen0309\Planung2BeteiligungBeteiligungRaumordnungLoeschen0309AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401\Planung2BeteiligungBeteiligungKommunalNeu0401AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungLoeschen0309;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungNeu0301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungRaumordnungNeu0301\Planung2BeteiligungBeteiligungRaumordnungNeu0301AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt301;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType\HausnummernBisAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType;
use DemosEurope\DemosplanAddon\XBeteiligung\XBeteiligungAsyncAddon;
use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class XBeteiligungService
{
    public const XBETEILIGUNG_VERSION = '0.9';
    public const STANDARD = 'XBeteiligung';
    private \JMS\Serializer\Serializer $serializer;

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
        'evaluating' => [ // todo not sure about this one - pls check
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
        'evaluating' => [ // todo not sure about this one - pls check
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

    public function __construct(
        private readonly GisLayerCategoryRepositoryInterface    $gisLayerCategoryRepository,
        private readonly LoggerInterface                        $logger,
        SerializerFactory                                       $serializerFactory,
        private readonly ProcedureNewsServiceInterface          $procedureNewsService,
        private readonly ProcedureMessageRepository             $procedureMessageRepository,
        private readonly PlanningDocumentsLinkCreator           $planningDocumentsLinkCreator,
        private readonly RouterInterface                        $router,
    ) {
        $this->serializer = $serializerFactory->getSerializer();
    }

    /**
     * @throws Exception
     */
    public function createProcedureNew401FromObject(ProcedureInterface $procedure): string
    {
        $procedureCreated401Object = new Planung2BeteiligungBeteiligungKommunalNeu0401();
        $procedureCreated401Object = $this->setProductInfo($procedureCreated401Object); // required
        $procedureCreated401Object->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureCreated401Object)
        ); // required
        $procedureCreated401Object->setNachrichteninhalt(
            $this->generateMain401MessageContent($procedure)
        ); // required

        return $this->serializeData($procedureCreated401Object);
    }

    public function createXMLFor301(ProcedureInterface $procedure)
    {
        $procedureCreated301 = new Planung2BeteiligungBeteiligungRaumordnungNeu0301();
        $procedureCreated301 = $this->setProductInfo($procedureCreated301); // required
        $procedureCreated301->setNachrichtenkopf(
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
    public function createProcedureUpdate402FromObject(ProcedureInterface $procedure): string
    {
        $procedureUpdated402Object = new Planung2BeteiligungBeteiligungKommunalAktualisieren0402();
        $procedureUpdated402Object = $this->setProductInfo($procedureUpdated402Object); // required
        $procedureUpdated402Object->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureUpdated402Object)
        ); // required
        $procedureUpdated402Object->setNachrichteninhalt(
            $this->generateMain402MessageContent($procedure)
        ); // required

        return $this->serializeData($procedureUpdated402Object);
    }

    public function createXMLFor302(ProcedureInterface $procedure): string
    {
        $procedureUpdated302 = new Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302();
        $procedureUpdated302 = $this->setProductInfo($procedureUpdated302);
        $procedureUpdated302->setNachrichtenkopf(
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
    public function createProcedureDeleted409FromObject(string $procedureId): string
    {
        $procedureDeleted409Object = new Planung2BeteiligungBeteiligungKommunalLoeschen0409();
        $procedureDeleted409Object = $this->setProductInfo($procedureDeleted409Object); // required
        $procedureDeleted409Object->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureDeleted409Object)
        ); // required
        $procedureDeleted409Object->setNachrichtenInhalt($this->generateMain409MessageContent($procedureId));

        return $this->serializeData($procedureDeleted409Object);
    }

    public function createXMLFor309(string $procedureId): string
    {
        $procedureDeleted409 = new Planung2BeteiligungBeteiligungRaumordnungLoeschen0309();
        $procedureDeleted409 = $this->setProductInfo($procedureDeleted409);
        $procedureDeleted409->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureDeleted409)
        );
        $procedureDeleted409->setNachrichteninhalt(
            $this->generateMain309MessageContent($procedureId)
        );

        return $this->serializeData($procedureDeleted409);
    }

    private function serializeData($data): string
    {
        $xml = $this->serializer->serialize($data, 'xml');
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $xml->asXML() ?? '';
    }

    private function setProductInfo(NachrichtG2GTypeType $messageObject): NachrichtG2GTypeType
    {
        $messageObject->setProdukt('A1'); // required
        $messageObject->setProdukthersteller('DEMOS plan GmbH'); // required
        $messageObject->setProduktversion(self::XBETEILIGUNG_VERSION); // optional
        $messageObject->setStandard(self::STANDARD); // required
        // $messageObject->setTest(''); // optional
        $messageObject->setVersion(self::XBETEILIGUNG_VERSION); // required

        return $messageObject;
    }

    private function generateMain401MessageContent(ProcedureInterface $procedure): Nachrichteninhalt401
    {
        $messageContent = new Nachrichteninhalt401();
        $messageContent->setVorgangsID($this->uuid());  // required
        $messageContent->setBeteiligung($this->generateParticipationContentFor401OR402Message($procedure)); // optional

        return $messageContent;
    }

    private function generateMain301MessageContent(ProcedureInterface $procedure): Nachrichteninhalt301
    {
        $messageContent = new Nachrichteninhalt301();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung($this->generateParticipationContentFor301OR302Message($procedure));

        return $messageContent;
    }

    private function generateMain402MessageContent(ProcedureInterface $procedure): Nachrichteninhalt402
    {
        $messageContent = new Nachrichteninhalt402();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung($this->generateParticipationContentFor401OR402Message($procedure)); // optional

        return $messageContent;
    }

    private function generateMain302MessageContent(ProcedureInterface $procedure): Nachrichteninhalt302
    {
        $messageContent = new Nachrichteninhalt302();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung($this->generateParticipationContentFor301OR302Message($procedure));

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
        $organisationType = new OrganisationTypeType();
        $organisationName = new NameOrganisationTypeType();
        $organisationName->setName($orgaName);
        $organisationType->setName($organisationName);
        $actorsOfProcedure->setVeranlasser($organisationType);

        return $actorsOfProcedure;
    }

    /**
     * CAN BE REMOVED WITH NEXT STANDARD UPDATE (HOPEFULLY)
     * Creates a type for holding information about the public participation phase of a procedure.
     * @deprecated This information is (for 0301/0302 will be) moved to another type.
     * See for 0401/0402 {@link self::getPublicProcedurePhaseCodeType()}.
     */
    private function createCodeType(CodeType $codeType, string $listUri, string $publicParticipationPhase): CodeType
    {
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

    private function generateParticipationContentFor401OR402Message(ProcedureInterface $procedure): BeteiligungKommunalType
    {
        $participationType = new BeteiligungKommunalType();
        $participationType->setAkteurVorhaben(
            $this->createAkteurVorhabenType($procedure->getOrga()?->getName() ?? '')
        );

        $participationType->setPlanID($procedure->getId()); // required
        $participationType->setPlanname($procedure->getName()); // required
        $participationType->setPlanartKommunal($this->createNewCodePlanartKommunalType()); // optional

        /** @var CodeVerfahrensschrittKommunalType $codeType */
        $codeType = $this->createCodeType(
            new CodeVerfahrensschrittKommunalType(),
            'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittkommunal',
            $procedure->getPublicParticipationPhase()
        );
        $participationType->setVerfahrensschrittKommunal(
            $codeType
        );

        $participationType->setBeschreibungPlanungsanlass($this->getExternalDescriptionOfProcedure($procedure));
        $participationType->setFlaechenabgrenzungUrl(
            $this->generateFaceBoundaryWMSUrl($procedure)
        ); // optional - we want to use it
        if (in_array($procedure->getPublicParticipationPhasePermissionset(),
            [ProcedureInterface::PROCEDURE_PHASE_PERMISSIONSET_READ, ProcedureInterface::PROCEDURE_PHASE_PERMISSIONSET_WRITE])) {

            $participationType->setBeteiligungURL(
                $this->router->generate(
                    'DemosPlan_procedure_public_detail',
                    ['procedure' => $procedure->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            );
        }

        $participationType->setGeltungsbereich($procedure->getSettings()->getTerritory());
        $participationType->setRaeumlicheBeschreibung('');

        $participationType->setBeteiligungOeffentlichkeit($this->generatePublicParticipationType($procedure));
        $participationType->setBeteiligungTOEB($this->generateInstitutionParticipationType($procedure));

        return $participationType;
    }

    private function generateParticipationContentFor301OR302Message(ProcedureInterface $procedure): BeteiligungRaumordnungType
    {
        $participationType = new BeteiligungRaumordnungType();
        $participationType->setAkteurVorhaben(
            $this->createAkteurVorhabenType($procedure->getOrga()?->getName() ?? '')
        );

        $participationType->setPlanID($procedure->getId()); // required
        $participationType->setPlanname($procedure->getName()); // required
        $participationType->setPlanart($this->createNewCodePlanartRaumordnungType()); // optional

        /** @var CodeVerfahrensschrittRaumordnungType $codeType */
        $codeType = $this->createCodeType(
            new  CodeVerfahrensschrittRaumordnungType(),
            'urn:xoev-de:xleitstelle:codeliste:verfahrensschrittraumordnung',
            $procedure->getPublicParticipationPhase()
        );
        $participationType->setVerfahrensschritt($codeType);

        $participationType->setBeschreibungPlanungsanlass($this->getExternalDescriptionOfProcedure($procedure));

        // currently required fields
        $timeSpan = new ZeitraumType();
        $timeSpan->setBeginn($procedure->getPublicParticipationStartDate());
        $timeSpan->setEnde($procedure->getPublicParticipationEndDate());
        $participationType->setZeitraum($timeSpan);
        $participationType->setAktuelleMitteilung($this->getInstitutionNewsList($procedure));
        $participationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        );
        $participationType->setDurchgang(1);

        $participationType->setFlaechenabgrenzungUrl(
            $this->generateFaceBoundaryWMSUrl($procedure)
        );
        if (in_array($procedure->getPublicParticipationPhasePermissionset(),
            [ProcedureInterface::PROCEDURE_PHASE_PERMISSIONSET_READ, ProcedureInterface::PROCEDURE_PHASE_PERMISSIONSET_WRITE])) {

            $participationType->setBeteiligungURL(
                $this->router->generate(
                    'DemosPlan_procedure_public_detail',
                    ['procedure' => $procedure->getId()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                )
            );
        }

        // In rog we have currently no "Geltungsbereich zeichnen" option under "Planungsdokumente und Planzeichnung".
        $participationType->setGeltungsbereich('');
        $participationType->setRaeumlicheBeschreibung('');

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
        $timeSpan = new ZeitraumType();
        $timeSpan->setBeginn($procedure->getStartDate());
        $timeSpan->setEnde($procedure->getEndDate());
        $institutionParticipationType->setZeitraum($timeSpan); // optional - we want to use it
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
        $timeSpan = new ZeitraumType();
        $timeSpan->setBeginn($procedure->getPublicParticipationStartDate());
        $timeSpan->setEnde($procedure->getPublicParticipationEndDate());
        $publicParticipationType->setZeitraum($timeSpan); // optional - we want to use it
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
    private function createMessageHeadFor(NachrichtG2GTypeType $messageObject): NachrichtenkopfG2GTypeType
    {
        $messageHead = new NachrichtenkopfG2GTypeType();
        $messageHead->setIdentifikationNachricht($this->createMessageIdentification($messageObject)); // required
        $messageHead->setLeser($this->createReaderInformation()); // required
        $messageHead->setAutor($this->createAuthorInformation()); // required

        return $messageHead;
    }

    private function createReaderInformation(): BehoerdeTypeType
    {
        $reader = new BehoerdeTypeType();
        $reader->setBehoerdenkennung($this->addReadingAuthorityIdentificationType()); // required
//        $reader->setErreichbarkeit($this->addReaderCommunicationType()); // optional list
//        $reader->setAnschrift($this->addReaderPostalInformation()); // optional
        $reader->setBehoerdenname('K3'); // required

        return $reader;
    }

    private function createAuthorInformation(): BehoerdeErreichbarTypeType
    {
        $author = new BehoerdeErreichbarTypeType();
        $author->setBehoerdenkennung($this->addAuthorityIdentificationOfAuthor()); // required
        $author->setErreichbarkeit($this->addAuthorCommunicationType()); // required list 1 entry
        $author->setAnschrift($this->addAuthorPostalInformation()); // required
        $author->setBehoerdenname('DEMOS plan GmbH'); // required

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

    private function addReadingAuthorityIdentificationType(): BehoerdenkennungTypeType
    {
        $authorityIdentificationType = new BehoerdenkennungTypeType();

        $prefixType = new CodePraefixTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('urn:xoev-de:bund:bmi:bit:codeliste:dvdv.praefix');
        $prefixType->setName(self::NON_EXISTING_CODE_NAME);
        $prefixType->setCode(self::NON_EXISTING_CODE);
        $authorityIdentificationType->setPraefix($prefixType); // required

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName(self::NON_EXISTING_CODE_NAME);
        $codeAuthorityIdentification->setCode('work probably in progress');
        $authorityIdentificationType->setKennung($codeAuthorityIdentification); // required

        return $authorityIdentificationType;
    }

    private function addAuthorityIdentificationOfAuthor(): BehoerdenkennungTypeType
    {
        $authorityIdentificationType = new BehoerdenkennungTypeType();

        $prefixType = new CodePraefixTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('urn:xoev-de:bund:bmi:bit:codeliste:dvdv.praefix');
        $prefixType->setName(self::NON_EXISTING_CODE_NAME);
        $prefixType->setCode(self::NON_EXISTING_CODE);
        $authorityIdentificationType->setPraefix($prefixType); // required

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName(self::NON_EXISTING_CODE_NAME);
        $codeAuthorityIdentification->setCode(self::NON_EXISTING_CODE);
        $authorityIdentificationType->setKennung($codeAuthorityIdentification); // required

        return $authorityIdentificationType;
    }

    /**
     * @return array<int, KommunikationTypeType>
     */
    private function addReaderCommunicationType(): array
    {
        $communicationType = new KommunikationTypeType();
        $comCode = new CodeErreichbarkeitTypeType();
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
     * @return array<int, KommunikationTypeType>
     */
    private function addAuthorCommunicationType(): array
    {
        $communicationType = new KommunikationTypeType();
        $comCode = new CodeErreichbarkeitTypeType();
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

        return [$communicationType];
    }

    private function addReaderPostalInformation(): PostalischeInlandsanschriftTypeType
    {
        $postAddress = new PostalischeInlandsanschriftTypeType();

        $buildingAddress = new PostalischeInlandsanschriftGebaeudeanschriftTypeType();
        $buildingNumber = new HausnummernBisAnonymousPHPType();
        $buildingNumber->setHausnummerBis('');
        $buildingNumber->setHausnummerbuchstabezusatzzifferBis('');
        $buildingNumber->setTeilnummerderhausnummerBis('');
        $buildingAddress->setHausnummernBis($buildingNumber); // optional
        $buildingAddress->setWohnort(''); // required
        $buildingAddress->setPostleitzahl(''); // required
        $buildingAddress->setHausnummer(''); // optional
        $buildingAddress->setHausnummerBuchstabeZusatzziffer(''); // optional
        $buildingAddress->setStockwerkswohnungsnummer(''); // optional
        $buildingAddress->setStrasse(''); // required
        $postAddress->setGebaeude($buildingAddress); // required

        $postMailBoxAddress = new PostalischeInlandsanschriftPostfachanschriftTypeType();
        $postMailBoxAddress->setPostfach('') // optional
            ->setPostleitzahl('') // required
            ->setWohnort('') // required
        ;
        $postAddress->setPostfach($postMailBoxAddress);

        return $postAddress;
    }

    private function addAuthorPostalInformation(): PostalischeInlandsanschriftTypeType
    {
        $postAddress = new PostalischeInlandsanschriftTypeType();

        $buildingAddress = new PostalischeInlandsanschriftGebaeudeanschriftTypeType();
        $buildingNumber = new HausnummernBisAnonymousPHPType();
        $buildingNumber->setHausnummerBis('1');
        $buildingNumber->setHausnummerbuchstabezusatzzifferBis('');
        $buildingNumber->setTeilnummerderhausnummerBis('');
        $buildingAddress->setHausnummernBis($buildingNumber); // optional
        $buildingAddress->setWohnort('Berlin'); // required
        $buildingAddress->setPostleitzahl('10178'); // required
        $buildingAddress->setHausnummer('1'); // optional
        $buildingAddress->setHausnummerBuchstabeZusatzziffer(''); // optional
        $buildingAddress->setStockwerkswohnungsnummer(''); // oprional
        $buildingAddress->setStrasse('Panoramastraße'); // required
        $postAddress->setGebaeude($buildingAddress); // required

        $postMailBoxAddress = new PostalischeInlandsanschriftPostfachanschriftTypeType();
        $postMailBoxAddress->setPostfach('') // optional
            ->setPostleitzahl('') // required
            ->setWohnort('') // required
        ;
        //$postAddress->setPostfach($postMailBoxAddress); // required not expected in validation

        return $postAddress;
    }

    /**
     * @throws Exception
     */
    private function createMessageIdentification(NachrichtG2GTypeType $messageObject): IdentifikationNachrichtTypeType
    {
        if ($messageObject instanceof Planung2BeteiligungBeteiligungKommunalNeu0401) {
            $code = '0401';
            $name = 'planung2Beteiligung.BeteiligungKommunalNeu.0401';
        } elseif ($messageObject instanceof Planung2BeteiligungBeteiligungKommunalAktualisieren0402) {
            $code = '0402';
            $name = 'planung2Beteiligung.BeteiligungKommunalAktualisieren.0402';
        } elseif ($messageObject instanceof  Planung2BeteiligungBeteiligungKommunalLoeschen0409) {
            $code = '0409';
            $name = 'planung2Beteiligung.BeteiligungKommunalLoeschen.0409';
        } elseif ($messageObject instanceof Planung2BeteiligungBeteiligungRaumordnungNeu0301 ) {
            $code = '0301'; // 0301
            $name = 'planung2Beteiligung.BeteiligungRaumordnungNeu.0301';
        } elseif ($messageObject instanceof Planung2BeteiligungBeteiligungRaumordnungAktualisieren0302 ) {
            $code = '0302'; // 0302
            $name = 'planung2Beteiligung.RaumordnungAktualisieren.0302';
        } elseif ($messageObject instanceof Planung2BeteiligungBeteiligungRaumordnungLoeschen0309 ) {
            $code = '0309'; // 0309
            $name = 'planung2Beteiligung.RaumordnungLoeschen.0309';
        } else {
            $this->logger->error('Class '.$messageObject::class.' not supported yet');
            throw new Exception(
                $messageObject::class . ' is not supported - unable to set messageIdentification code'
            );
        }

        $identificationMessage = new IdentifikationNachrichtTypeType();

        $messageTypeCode = new CodeXBeteiligungNachrichtenType();
        $messageTypeCode->setListURI('urn:xoev-de:xleitstelle:codeliste:xbeteiligung-nachrichten');
        $messageTypeCode->setListVersionID('1.0');
        $messageTypeCode->setName($name);
        $messageTypeCode->setCode($code);

        // id has to match pattern: '[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}'
        $identificationMessage->setNachrichtenUUID($this->uuid()); // required
        $identificationMessage->setErstellungszeitpunkt(new DateTime()); // required
        $identificationMessage->setNachrichtentyp($messageTypeCode); // required

        return $identificationMessage;
    }

    private function uuid(): string
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

    /**
     * Validates a message against a given xsd file located in plugin xsd folder.
     */
    public function isValidMessage(
        string $message,
        bool $verboseDebug = false,
        string $path = '',
        string $xsdFile = 'xbeteiligung-planung2beteiligung.xsd'): bool
    {
        if ('' === $path) {
            $path = AddonPath::getRootPath('Resources/xsd/');
        }

        $path .= $xsdFile;

        $document = new \DOMDocument();
        $document->loadXML($message);
        $isValid = $document->schemaValidate($path);
        if ($isValid) {
            return true;
        }
        // revalidate with error handling
        libxml_use_internal_errors(true);
        $document->schemaValidate($path);
        $errors = libxml_get_errors();
        foreach ($errors as $error) {
            $this->logger->warning('Invalid xta message', [$error]);
            if ($verboseDebug) {
                print_r($error);
            }
        }
        libxml_clear_errors();
        libxml_use_internal_errors(false);
        if ($verboseDebug) {
            print_r($message);
        }

        return false;
    }

    /**
     * @return \JMS\Serializer\Serializer
     */
    public function getSerializer()
    {
        return $this->serializer;
    }

    /**
     * @param \JMS\Serializer\Serializer $serializer
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
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

    public function getProcedureMessage(string $procedureMessageId): ProcedureMessage {
        return $this->procedureMessageRepository->get($procedureMessageId);
    }

    public function deleteProcedureMessageOnFlush(string $procedureMessageId): void {
        $this->procedureMessageRepository->deleteOnFlush($procedureMessageId);
    }
}
