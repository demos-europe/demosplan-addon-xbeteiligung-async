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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommuneType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\BeteiligungKommunalNeuType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeBehoerdenkennungTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeErreichbarkeitTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePlanartKommuneType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodePraefixTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensschrittType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\IdentifikationNachrichtTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtenkopfG2GXInneresTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\NameOrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\OrganisationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneAktualisieren0402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneAktualisieren0402\Planung2BeteiligungBeteiligungKommuneAktualisieren0402AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt402;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneLoeschen0409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneLoeschen0409\Planung2BeteiligungBeteiligungKommuneLoeschen0409AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt409;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneNeu0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommuneNeu0401\Planung2BeteiligungBeteiligungKommuneNeu0401AnonymousPHPType\NachrichteninhaltAnonymousPHPType as Nachrichteninhalt401;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftGebaeudeanschriftTypeType\HausnummernBisAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftPostfachanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\PostalischeInlandsanschriftTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\ZeitraumType;
use Exception;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;
use Psr\Log\LoggerInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;

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

    public function __construct(
        private readonly GisLayerCategoryRepositoryInterface    $gisLayerCategoryRepository,
        private readonly LoggerInterface                        $logger,
        SerializerFactory                                       $serializerFactory,
        private readonly ProcedureNewsServiceInterface          $procedureNewsService,
        private readonly ProcedureMessageRepository             $procedureMessageRepository,
    ) {
        $this->serializer = $serializerFactory->getSerializer();
    }

    /**
     * @throws Exception
     */
    public function createProcedureNew401FromObject(ProcedureInterface $procedure): string
    {
        $procedureCreated401Object = new Planung2BeteiligungBeteiligungKommuneNeu0401();
        $procedureCreated401Object = $this->setProdukctInfo($procedureCreated401Object); // required
        $procedureCreated401Object->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureCreated401Object)
        ); // required
        $procedureCreated401Object->setNachrichteninhalt(
            $this->generateMain401MessageContent($procedure)
        ); // required

        $xml = $this->serializeData($procedureCreated401Object);
        $this->saveProcedureMessage($xml, $procedure->getId());

        return $xml;
    }

    /**
     * @throws Exception
     */
    public function createProcedureUpdate402FromObject(ProcedureInterface $procedure): string
    {
        $procedureUpdated402Object = new Planung2BeteiligungBeteiligungKommuneAktualisieren0402();
        $procedureUpdated402Object = $this->setProdukctInfo($procedureUpdated402Object); // required
        $procedureUpdated402Object->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureUpdated402Object)
        ); // required
        $procedureUpdated402Object->setNachrichteninhalt(
            $this->generateMain402MessageContent($procedure)
        ); // required

        $xml = $this->serializeData($procedureUpdated402Object);
        $this->saveProcedureMessage($xml, $procedure->getId());

        return $xml;
    }

    /**
     * @throws Exception
     */
    public function createProcedureDeleted409FromObject(ProcedureInterface $procedure): string
    {
        $procedureDeleted409Object = new Planung2BeteiligungBeteiligungKommuneLoeschen0409();
        $procedureDeleted409Object = $this->setProdukctInfo($procedureDeleted409Object); // required
        $procedureDeleted409Object->setNachrichtenkopf(
            $this->createMessageHeadFor($procedureDeleted409Object)
        ); // required
        $procedureDeleted409Object->setNachrichtenInhalt($this->generateMain409MessageContent($procedure->getId()));

        $xml = $this->serializeData($procedureDeleted409Object);
        $this->saveProcedureMessage($xml, $procedure->getId());

        return $xml;
    }

    private function serializeData($data): string
    {
        $xml = $this->serializer->serialize($data, 'xml');
        $xml = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        return $xml->asXML() ?? '';
    }

    private function setProdukctInfo(NachrichtG2GTypeType $messageObject): NachrichtG2GTypeType
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
        $messageContent->setBeteiligung($this->generateParticipationContentFor401Messages($procedure)); // optional

        return $messageContent;
    }

    private function generateMain402MessageContent(ProcedureInterface $procedure): Nachrichteninhalt402
    {
        $messageContent = new Nachrichteninhalt402();
        $messageContent->setVorgangsID($this->uuid());
        $messageContent->setBeteiligung($this->generateOldParticipationContent($procedure)); // optional

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

    private function generateParticipationContentFor401Messages(ProcedureInterface $procedure): BeteiligungKommunalNeuType
    {
        $participationType = new BeteiligungKommunalNeuType();

        $procedureInitiatingOrganisation = new AkteurVorhabenType();
        // kteurVorhaben
        $organisationType = new OrganisationTypeType();
        $organisationName = new NameOrganisationTypeType();
        $organisationName->setName($procedure->getOrga()?->getName() ?? '');
        $organisationType->setName($organisationName);
        $procedureInitiatingOrganisation->setVeranlasser($organisationType);
        $participationType->setAkteurVorhaben($procedureInitiatingOrganisation); // required
        // Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird
        $participationType->setPlanID($procedure->getId()); // required
        $participationType->setPlanname($procedure->getName()); // required

        // $participationType->setArbeitstitel(''); // optional
        // $participationType->setPlanartKommunal(new CodePlanartKommuneType()); // optional

        //todo FLIEGT RAUS setBeteiligungOeffentlichkeit & setBeteiligungTOEB beinhalten das
        // why is this code present in this layer - i dont get it - email to Stephan is on the way
        // As far as I understood our meeting - this code should only be present within the institution/participation
        // type thingies.
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
        $procedurePhaseCode = new CodeVerfahrensschrittType();
        $procedurePhaseCode->setListVersionID('1.0');
        $procedurePhaseCode->setListURI('');
        $procedurePhaseCode->setName('Frühzeitige Öffentlichkeitsbeteiligung');
        $procedurePhaseCode->setCode('4000');
        $participationType->setVerfahrensschrittKommunal($procedurePhaseCode); // required - we want to use it
        //todo FLIEGT RAUS setBeteiligungOeffentlichkeit & setBeteiligungTOEB beinhalten das

        //  $participationType->setVerfahrensartKommunal(new CodeVerfahrensartKommuneType); // optional
        $participationType->setBeschreibungPlanungsanlass($procedure->getDesc()); // optional - we want to use it
        $participationType->setFlaechenabgrenzungUrl(
            $this->generateFaceBoundaryWMS_Url($procedure)
        ); // optional - we want to use it
        // Hier ist die räumliche Beschreibung des Geltungsbereichs als Polygon im Format GeoJSON FG Notation zu über-
        // mitteln. todo Format wird noch geprüft.
        $participationType->setGeltungsbereich(''); // required - we dont want to use it
        $participationType->setRaeumlicheBeschreibung(''); // required - we dont want it
        // Hier kann eine URL übermittelt werden, unter der Detailinformationen zum Beteiligungsverfahren
        // eingesehen werden können.
        // $participationType->setBeteiligungURL(''); // optional

        // now the new stuff
        $participationType->setBeteiligungOeffentlichkeit($this->generatePublicParticipationType($procedure));
        $participationType->setBeteiligungTOEB($this->generateInstitutionParticipationType($procedure));

        return $participationType;
    }

    private function generateInstitutionParticipationType(ProcedureInterface $procedure): BeteiligungKommunalTOEBType
    {
        $institutionParticipationType = new BeteiligungKommunalTOEBType();

        // we as demos think this id is useless - did not win the discussion as it seems :(
        $institutionParticipationType->setBeteiligungsID($this->uuid());
        // this MetadatenAnlageType should support a base64 container to dump files into but it does not - S.C. is informed
        // $publicParticipationType->setAnlagen([new MetadatenAnlageType()]); // optional - still not fixed
        $timeSpan = new ZeitraumType();
        $timeSpan->setBeginn($procedure->getStartDate());
        $timeSpan->setEnde($procedure->getEndDate());
        $institutionParticipationType->setZeitraum($timeSpan); // optional - we want to use it
        // Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
        $institutionParticipationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        // we kind of need to implement this feature
        $institutionParticipationType->setDurchgang(1); // required not documented not wanted
        $bkTOEBaaType = new BeteiligungKommunalTOEBArtAnonymousPHPType();
        $bkTOEBaaType->setBeteiligungKommunalFormalTOEB($this->getInstitutionProcedurePhaseCodeType($procedure));
        $institutionParticipationType->setBeteiligungKommunalTOEBArt($bkTOEBaaType);
        // aktuelleMitteilungen optional - we want to use it
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
        // Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
        $publicParticipationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it
        // we kind of need to implement this feature
        $publicParticipationType->setDurchgang(1); // required not documented not wanted
        $bkoeaaType = new BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType();
        $bkoeaaType->setBeteiligungKommunalFormalOeffentlichkeit(
            $this->getPublicProcedurePhaseCodeType($procedure)
        );
        $publicParticipationType->setBeteiligungKommunalOeffentlichkeitArt($bkoeaaType);
        $publicParticipationType->setAktuelleMitteilung($this->getPublicNewsList($procedure));

        return $publicParticipationType;
    }

    private function generateOldParticipationContent(ProcedureInterface $procedure): BeteiligungKommuneType
    {
        $participationType = new BeteiligungKommuneType();

        $procedureInitiatingOrganisation = new AkteurVorhabenType();
        // kteurVorhaben
        $organisationType = new OrganisationTypeType();
        $organisationName = new NameOrganisationTypeType();
        $organisationName->setName($procedure->getOrga()?->getName() ?? '');
        $organisationType->setName($organisationName);
        $procedureInitiatingOrganisation->setVeranlasser($organisationType);
        $participationType->setAkteurVorhaben($procedureInitiatingOrganisation); // required

        $participationType->setPlanname($procedure->getName()); // required
        // todo what do we want here - where is the difference - there are tons more
        // Quelle AdoRepo: urn-xoev-de-xleitstelle-codeliste-planartkommune_1.0
        // just wrote down the ones about "Bebauung"
        // Code: 6_1_QualifizierterBPlan -> Qualifizierter Bebauungsplan
        // Code: 6_2_VorhabenbezogenerBPlan -> Vorhabenbezogener Bebauungsplan
        // Code: 6_3_EinfacherBPlan -> Einfacher Bebauungsplan
        // Code: 6_6_BebauungsplanZurWohnraumversorgung -> Bebauungsplan zur Wohnraumversorgung
        // Code: 6_Bebauungsplan -> Bebauungsplan
        $planType = new CodePlanartKommuneType();
        $planType->setCode('6_3_EinfacherBPlan') // easy has to be good right :)
            ->setName('Einfacher Bebauungsplan')
            ->setListVersionID('1.0')
            ->setListURI('urn:xoev-de:xleitstelle:codeliste:planartkommune');
        $participationType->setPlanart($planType); // optional - we want to use it

        // Hier ist die ID des Planverfahrens zu übermitteln, innerhalb dessen das Beteiligungsverfahren durchgeführt wird
        $participationType->setPlanID($procedure->getId()); // required
        $participationType->setBeschreibungPlanungsanlass($procedure->getDesc()); // optional - we want to use it
        $participationType->setFlaechenabgrenzungUrl(
            $this->generateFaceBoundaryWMS_Url($procedure)
        ); // optional - we want to use it

        // Hier ist die räumliche Beschreibung des Geltungsbereichs als Polygon im Format GeoJSON FG Notation zu über-
        // mitteln. todo Format wird noch geprüft.
        $participationType->setGeltungsbereich(''); // required - we dont want to use it
        $participationType->setRaeumlicheBeschreibung(''); // required - we dont want it
        // zeitraum
        $timeSpan = new ZeitraumType();
        $timeSpan->setBeginn($procedure->getStartDate());
        $timeSpan->setEnde($procedure->getEndDate());
        $participationType->setZeitraum($timeSpan); // optional - we want to use it

        // Termin, zu dem der Start der Beteiligung bekannt gemacht wird (mind. eine Woche vor Start der Beteiligung).
        $participationType->setBekanntmachung(
            DateTime::createFromInterface($procedure->getStartDate())->sub(new DateInterval('P7D'))
        ); // required - we dont want it

        // todo email an Stefan Conrad ist raus - der MetadatenAnlageTypeType ist broken zur Zeit
        // was told by S.C that we get a base64 container at some moment in the future
        // $participationType->setAnlagen([new MetadatenAnlageTypeType()]); // optional - we want to use it

        // todo Code liste gefunden dank Stephan.C - still need to use them
        // On the other hand - we discussed a completely new pattern for this in order to send
        // public participations as well as institution participations together in one message.
        // basically we need to rework this whole BeteiligungKommuneTypeType bs anyways - so do not bother right now
        // Quelle AdoRepo: xoev-de-xplanverfahren-codeliste-verfahrensschritt_1.xml out of date and non existant since 13.07.23
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
        $procedurePhaseCode = new CodeVerfahrensschrittType();
        $procedurePhaseCode->setListVersionID('1.0');
        $procedurePhaseCode->setListURI('');
        $procedurePhaseCode->setName('Frühzeitige Öffentlichkeitsbeteiligung');
        $procedurePhaseCode->setCode('4000');
        $participationType->setVerfahrensschritt($procedurePhaseCode); // required - we want to use it

        //  $participationType->setVerfahrensart(new CodeVerfahrensartKommuneTypeType); // optional

        $procedureNewsList = $this->procedureNewsService->getProcedureNewsAdminList($procedure->getId());
        $aktuelles = [];
        foreach ($procedureNewsList['result'] as $procedureNews) {
            if (isset($procedureNews['title'], $procedureNews['text'])) {
                $aktuelles[] = strip_tags($procedureNews['title'].': '.$procedureNews['text']);
            }
        }
        $participationType->setAktuelleMitteilung($aktuelles); // optional - we want to use it

        // $participationType->setArbeitstitel(''); // optional
        // $participationType->setPlanart(new CodePlanartKommuneTypeType()); // otional
        $participationType->setDurchgang(1); // required not documented not wanted --
        // It is in discussion how to implement this into the standard - as of 07/2023

        return $participationType;
    }

    /**
     * @throws Exception
     */
    private function createMessageHeadFor(NachrichtG2GTypeType $messageObject): NachrichtenkopfG2GTypeType
    {
        $messageHead = new NachrichtenkopfG2GTypeType();
//        $messageHead = new NachrichtenkopfG2GXInneresTypeType();
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

    private function generateFaceBoundaryWMS_Url(ProcedureInterface $procedure): string
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
        // todo does BoundingBox now contains the mapExtent or the BoundingBox as they are switched within our db
        $bboxArray = explode(',', $procedure->getSettings()->getBoundingBox());

        $west = (float)$bboxArray[0];
        $east = (float)$bboxArray[2];
        $south = (float)$bboxArray[1];
        $north = (float)$bboxArray[3];
        $absWidth = abs($west - $east);
        $absHeight = abs($south - $north);

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
        $bbox = '&BBOX=' . str_replace(',', '%2C', $procedure->getSettings()->getBoundingBox());


        return $url . $serviceType . $version . $request . $format . $transparent . $layers . $width .
            $height . $crs . $styles . $bbox;
    }

    private function addReadingAuthorityIdentificationType(): BehoerdenkennungTypeType
    {
        $authorityIdentificationType = new BehoerdenkennungTypeType();

        $prefixType = new CodePraefixTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('');
        $prefixType->setName('die XLeitstelle muss im Rahmen der Eintragung von Diensten in das DVDV erstellt werden');
        $prefixType->setCode('work probably in progress');
        $authorityIdentificationType->setPraefix($prefixType); // required

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName('die XLeitstelle muss im Rahmen der Eintragung von Diensten in das DVDV erstellt werden');
        $codeAuthorityIdentification->setCode('work probably in progress');
        $authorityIdentificationType->setKennung($codeAuthorityIdentification); // required

        return $authorityIdentificationType;
    }

    private function addAuthorityIdentificationOfAuthor(): BehoerdenkennungTypeType
    {
        $authorityIdentificationType = new BehoerdenkennungTypeType();

        $prefixType = new CodePraefixTypeType();
        $prefixType->setListVersionID('');
        $prefixType->setListURI('');
        $prefixType->setName('die XLeitstelle muss im Rahmen der Eintragung von Diensten in das DVDV erstellt werden');
        $prefixType->setCode('work probably in progress');
        $authorityIdentificationType->setPraefix($prefixType); // required

        $codeAuthorityIdentification = new CodeBehoerdenkennungTypeType();
        $codeAuthorityIdentification->setListVersionID('');
        $codeAuthorityIdentification->setListURI('');
        $codeAuthorityIdentification->setName('die XLeitstelle muss im Rahmen der Eintragung von Diensten in das DVDV erstellt werden');
        $codeAuthorityIdentification->setCode('work probably in progress');
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
        // 06 -> Pager, 07 -> Sonstiges, 08 -> DE-Mail, 09 -> Web
        $comCode->setCode('09');
        $comCode->setName('Web');
        $comCode->setListURI('urn:de:xoev:codeliste:erreichbarkeit');
        $comCode->setListVersionID('3');
        $communicationType->setKanal($comCode); // required
        // kennung: In der Regel werden hier Adressangaben eingetragen, etwa die Telefonnummer oder die E-Mail-Adresse.
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
        $postAddress->setPostfach($postMailBoxAddress); // required

        return $postAddress;
    }

    /**
     * @throws Exception
     */
    private function createMessageIdentification(NachrichtG2GTypeType $messageObject): IdentifikationNachrichtTypeType
    {
        if ($messageObject instanceof Planung2BeteiligungBeteiligungKommuneNeu0401) {
            $code = '0401';
            $name = 'planung2Beteiligung.BeteiligungKommuneNeu.0401';
        } elseif ($messageObject instanceof Planung2BeteiligungBeteiligungKommuneAktualisieren0402) {
            $code = '0402';
            $name = 'planung2Beteiligung.BeteiligungKommuneAktualisieren.0402';
        } elseif ($messageObject instanceof  Planung2BeteiligungBeteiligungKommuneLoeschen0409) {
            $code = '0409';
            $name = 'planung2Beteiligung.BeteiligungKommuneLoeschen.0409';
        } else {
            $this->logger->error('Class '.$messageObject::class.' not supported yet');
            throw new Exception(
                $messageObject::class . ' is not supported - unable to set messageIdentification code'
            );
        }

        $identificationMessage = new IdentifikationNachrichtTypeType();

        $messageTypeCode = new CodeType();
        $messageTypeCode->setListURI('urn:de:xbeteiligung:codeliste:xbeteiligungnachrichtencodeliste');
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
    public function isValidMessage(string $message, bool $verboseDebug = false, string $xsdFile = 'xbeteiligung-beteiligung2planung.xsd'): bool
    {
        $path = AddonPath::getRootPath('Resources/xsd/' . $xsdFile);
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

    private function saveProcedureMessage(string $xml, string $procedureId): void
    {
        $procedureMessage = new ProcedureMessage(
            $xml,
            false,
            false,
            0,
            $procedureId
        );
        $this->procedureMessageRepository->createNew($procedureMessage);
    }

    private function getInstitutionProcedurePhaseCodeType(ProcedureInterface $procedure): CodeVerfahrensschrittType
    {
        $codeProcedurePhase = new CodeVerfahrensschrittType();
        $codeProcedurePhase->setListVersionID('');
        $codeProcedurePhase->setListVersionID('1.0');
        $codeProcedurePhase->setCode(
            self::INSTITUTIONPARTICIPATIONPHASEMAP[$procedure->getPhase()]['code']
        );
        $codeProcedurePhase->setName(
            self::INSTITUTIONPARTICIPATIONPHASEMAP[$procedure->getPhase()]['name']
        );

        return $codeProcedurePhase;
    }

    private function getPublicProcedurePhaseCodeType(ProcedureInterface $procedure): CodeVerfahrensschrittType
    {
        $codeProcedurePhase = new CodeVerfahrensschrittType();
        $codeProcedurePhase->setListVersionID('');
        $codeProcedurePhase->setListVersionID('1.0');
        $codeProcedurePhase->setCode(
            self::PUBLICPARTICIPATIONPHASEMAP[$procedure->getPublicParticipationPhase()]['code']
        );
        $codeProcedurePhase->setName(
            self::PUBLICPARTICIPATIONPHASEMAP[$procedure->getPublicParticipationPhase()]['name']
        );

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

}
