<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\StatementTest;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\StatementMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\XBeteiligungResponseMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegeben0701;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Behoerde\BehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\Kommunikation\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtenkopfG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\RouterInterface;

class StatementCreatorTest extends TestCase
{
    protected XBeteiligungResponseMessageFactory $sut;
    protected Logger $logger;
    protected Serializer $serializer;
    protected XBeteiligungService $XBeteiligungService;

    private MockFactoryTest $mockFactory;
    protected MockObject $permissionEvaluator;
    protected MockObject $gisLayerCategoryRepository;
    protected MockObject $procedureNewsService;
    protected MockObject $procedureMessageRepository;
    protected MockObject $user;
    protected MockObject $procedure;
    protected MockObject $meta;

    public function createMockObject(string $className): MockObject
    {
        return $this->createMock($className);
    }

    protected function setUp(): void
    {
        $this->mockFactory = new MockFactoryTest($this);
        $this->permissionEvaluator = $this->createMock(PermissionEvaluatorInterface::class);
        $this->permissionEvaluator->method('isPermissionEnabled')->willReturn(true);
        $this->gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->procedure = $this->createMock(ProcedureInterface::class);
        $this->meta = $this->createMock(StatementMetaInterface::class);
        $this->procedureNewsService = $this->createMock(ProcedureNewsServiceInterface::class);
        $this->procedureMessageRepository = $this->createMock(ProcedureMessageRepository::class);
        $reusableMessageBlocks =
            new ReusableMessageBlocks(new CommonHelpers($this->createMock(LoggerInterface::class)));

        $globalConfigMock = $this->createMock(GlobalConfigInterface::class);
        $globalConfigMock->method('getMapDefaultProjection')->willReturn([
            'label' => 'EPSG:3857',
        ]);

        $xbeteiligungService = new XBeteiligungService(
            $this->gisLayerCategoryRepository,
            $globalConfigMock,
            $this->createMock(LoggerInterface::class),
            $this->createMock(ParameterBagInterface::class),
            $this->createMock(PlanningDocumentsLinkCreator::class),
            $this->procedureMessageRepository,
            $this->procedureNewsService,
            $this->createMock(RouterInterface::class),
            $this->createMock(XBeteiligungIncomingMessageParser::class),
            $this->createMock(CommonHelpers::class),
            $reusableMessageBlocks,
            $this->createMock(XBeteiligungAuditService::class)
        );
        $this->XBeteiligungService = $xbeteiligungService;
        $this->logger = new Logger();
        $this->serializer = SerializerFactory::getSerializer();
        $this->sut = new StatementMessageFactory(
            $this->createMock(CommonHelpers::class),
            $this->mockFactory->getLoggerInterfaceMock(),
            $this->permissionEvaluator,
            $reusableMessageBlocks
        );
    }

    public function testCreateBeteiligung2PlanungStellungnahmeNeu0701(): void
    {
        $statementCreated = $this->createStatement0701(3);
        $xmlMessageString = $this->sut->createBeteiligung2PlanungStellungnahmeNeu0701($statementCreated);
        //echo $xmlMessageString; // for debugging
        $this->validateProcedureXML($xmlMessageString);
        /** @var AllgemeinStellungnahmeNeuabgegeben0701 $xmlMessage */
        $xmlMessage = $this->serializer->deserialize(
            $xmlMessageString,
            AllgemeinStellungnahmeNeuabgegeben0701::class,
            'xml'
        );
        $header = $xmlMessage->getNachrichtenkopfG2g();
        $content = $xmlMessage->getNachrichteninhalt();
        $readerAgency = $header->getLeser();
        $authorAgency = $header->getAutor();

        $this->validateProductInfo($xmlMessage);
        $this->validateMessageId('0701', $header);
        $commChannels = ['Sonstiges' => $readerAgency->getErreichbarkeit()[0]];
        $this->validateK1Agency($readerAgency, 'LGV', $commChannels);
        $this->validateDemosAgency($authorAgency);

        $stellungnahme = $content->getStellungnahme();
        $this->validateStatement($statementCreated, $stellungnahme);
        self::assertSame('0300', $stellungnahme->getVerfahrensteilschritt()->getCode());
        self::assertSame('2000', $stellungnahme->getVerfahrensschrittKommunal()->getCode());
    }

    private function createStatement0701(int $version): StatementCreated
    {
        if (3 === $version) {
            $statementId = 'S34992191-830d-4d1d-a136-f38d322b521d';
            $planId = 'P9fd5b777-d02b-4340-81dc-89cb0a86029f';
            $procedureId = 'r20a6413-6c48-11eb-aaea-00505697774f';
            $status = 'new';
            $organization = 'Testorganisation';
            $title = 'Stellungnahme zum Bebauungsplan';
            $description = 'Aus Sicht des Radverkehrs ist eine zügige und geradlinige Verbindung am Weidenbaumsweg entsprechend der aktuellen Erfordernisse anzustreben. In der vorliegenden Planung ist das nicht zu erkennen.
                Im Radverkehrskonzept für Bergedorf stellt der Weidenbaumsweg eine wichtige bzw. die Haupt Süd-Nordtrasse für den Radverkehr dar. Vor allem unter diesem Gesichtspunkt muss eine für die gesamte Straße einheitliche Radverkehrsführung hergestellt werden. In der Stellungnahme vom 28.10.16 wurden Radfahr- oder Schutzstreifen für den Weidenbaumsweg vorgeschlagen, wenn auch zu Lasten von Kfz Stellplätzen. Diese Untersuchungen bzw. die Ergebnisse sind in den Kreisverkehr mit einzubringen bzw. nochmal zu überdenken.
                Aus straßenbautechnischer Sicht bestehen bei Einhaltung sowie Umsetzung der z. Zt. Gültigen Regelwerke, Vorschriften sowie Richtlinien für Straßenbau gegen diese Maßnahme keine Bedenken.
                Der Weidenbaumsweg im betroffenen Abschnitt zwischen Kampbille und Einmündung Wiesnerring ist mit Holländischen Linden bzw. Winter Linden als Begleitgrün (Straßenbäume) bestanden. Die Bäume sind unterschiedlichen Alters, aber größtenteils in den neunzehnhundertachtziger/neunziger Jahren gepflanzt worden. Bedingt durch die notwendige Anbindung des neuen Quartiers an den öffentlichen Verkehr sind Straßenumbaumaßnahmen am Weidenbaumsweg erforderlich. Durch diese Umbaumaßnahmen sind auch Eingriffe in den Straßenbaumbestand nicht zu umgehen, d.h. es müssen aller Voraussicht nach Straßenbäume gefällt werden. Nach derzeit vorliegender Planung soll die Anbindung im südl. Bereich des neuen Quartiers in Form einer Einmündung und im Bereich der Hausnummer XYZ in Form eines Kreisels stattfinden. In diesem Fall wären 8 - 10 Straßenbäume betroffen. Genaueres kann diesseits erst nach Vorliegen detaillierterer Planung gesagt werden. B/XY ist der Herstellungswert (berechnet nach der Methode Koch) der betroffenen Straßenbäume zu erstatten, dann kann von hier aus eine Fällgenehmigung erteilt werden.
                Straßenumbaumaßnahmen sind in jedem Fall im Bereich von Bäumen durch eine Fachfirma baumpflegerisch zu begleiten. Für Neupflanzungen von Straßenbäumen im Vorhabengebiet ist unbedingt die FLL Richtlinie Empfehlungen für Baumpflanzungen- Pflanzgrubenbauweise anzuwenden!
                Im Städtebaulichen Vertrag wird in § 14 Öffentlicher Kinderspielplatz geregelt, dass der Investor sich verpflichtet, einen bestimmten Betrag für die Ausstattung der Grün- und Parkanlagen mit Spielgeräten im Nahbereich des Vorhabens zur Verfügung stellt. In der Begründung zum Bebauungsplan unter Punkt 4.1.2 wird innerhalb der Parkanlage ein ca. 4.500 m² großer öffentlicher Spielplatz festgelegt. Hier geht nicht deutlich hervor, ob es in dem Gebiet einen Spielplatz geben wird bzw. ob die zur Verfügung gestellte Summe für diesen Spielplatz zur Verfügung steht oder es für einen beliebigen anderen Spielplatz gilt, der in der näheren Umgebung liegt. Hier sollte klar dargestellt werden, wo der Spielplatz liegen soll. Dies sollte ebenfalls in der Planzeichnung dargestellt werden.';
            $iteration = 1;
            $date = new \DateTime('2019-05-01');
            $feedback = 'email';
            $priority = 'A-Punkt';
            $firstName = 'Max';
            $lastName = 'Mustermann';
            $gender = 'männlich';
            $userTitle = 'Dr.';
            $vote = 'acknowledge';
            $tags = ['Radverkehr', 'Straßenbau', 'Straßenbäume', 'Städtebaulicher Vertrag'];
            $file = 'Legende.pdf:dc855abd-c8df-11e5-8550-005056ae0004:119994:application/pdf';
            $accessUrl = 'https://bob.beispiel.de?stellungnahmeID=S34992191-830d-4d1d-a136-f38d322b521d';
        } else {
            throw new \RuntimeException("Version {$version} supported");
        }
        $phaseObject = $this->createMock(ProcedurePhaseInterface::class);
        $phaseObject->method('getIteration')->willReturn($iteration);
        $phaseObject->method('setIteration')->with($iteration);
        // Configure the procedure mock to return the phase object
        $this->procedure->method('getPhaseObject')->willReturn($phaseObject);
        // Configure the meta mock to return the organization name
        $meta = $this->createMock(StatementMetaInterface::class);
        $meta->method('getOrgaName')->willReturn('Privatperson');
        $meta->method('setOrgaName')->with('Privatperson');
        $this->meta->method('getOrgaName')->willReturn('Privatperson');
        //configure the user mock to return the user data
        $this->user->method('getFirstName')->willReturn($firstName);
        $this->user->method('getLastName')->willReturn($lastName);
        $this->user->method('getGender')->willReturn($gender);
        $this->user->method('getTitle')->willReturn($userTitle);

        $statementCreated = new StatementCreated($this->user, $this->procedure, $this->meta);
        $statementCreated->setPublicId($statementId);
        $statementCreated->setPlanId($planId);
        $statementCreated->setProcedureId($procedureId);
        $statementCreated->setStatus($status);
        $statementCreated->setOrganizationName($organization);
        $statementCreated->setTitle($title);
        $statementCreated->setDescription($description);
        $statementCreated->setPlannerDetailViewUrl($accessUrl);
        $statementCreated->setCreatedAt($date);
        $statementCreated->setFeedback($feedback);
        $statementCreated->setPublicUseName(true);
        $statementCreated->setPublicStatement(StatementInterface::INTERNAL);
        $statementCreated->setPhase('earlyparticipation');
        $statementCreated->setPriority($priority);
        $statementCreated->setVotePla($vote);
        $statementCreated->setTags($tags);
        $statementCreated->setFile($file);
        $statementCreated->lock();

        return $statementCreated;
    }

    private function validateStatement(StatementCreated $statement1, StellungnahmeType $statement2): void
    {
        self::assertSame($statement1->getPublicId(), $statement2->getStellungnahmeID());
        self::assertSame($statement1->getPlanId(), $statement2->getPlanID());
        self::assertSame($statement1->getProcedureId(), $statement2->getBeteiligungsID());
        self::assertSame($statement1->getDescription(), $statement2->getBeschreibung());
        self::assertEquals($statement2->getDatum(), $statement1->getCreatedAt());
    }

    private function validateProductInfo(NachrichtG2GTypeType $xmlMessage): void
    {
        // message tests
        self::assertSame('demosplan', $xmlMessage->getProdukt());
        self::assertSame('DEMOS plan GmbH', $xmlMessage->getProdukthersteller());
        self::assertSame('XBeteiligung', $xmlMessage->getStandard());
        self::assertSame('1.3', $xmlMessage->getVersion());
    }

    private function validateMessageId(string $msgType, NachrichtenkopfG2GTypeType $header): void
    {
        self::assertSame($msgType, $header->getIdentifikationNachricht()->getNachrichtentyp()->getCode());
    }
    /**
     * @param KommunikationTypeType[] $commChannels
     */
    private function validateK1Agency(BehoerdeTypeType $agency,string $prefixName, $commChannels=[]): void
    {
        if (isset($commChannels['Sonstiges'])) {
            $emailComm = $commChannels['Sonstiges'];
            self::assertSame('07', $emailComm->getKanal()->getCode());
            self::assertSame('', $emailComm->getKennung());
            self::assertEmpty($emailComm->getZusatz());
        }
        self::assertSame('DVDV', $agency->getVerzeichnisdienst()->getCode());
        self::assertSame('3', $agency->getVerzeichnisdienst()->getListVersionID());
        self::assertSame('urn:xoev-de:kosit:codeliste:verzeichnisdienst', $agency->getVerzeichnisdienst()->getListURI());

    }

    private function validateDemosAgency(BehoerdeTypeType $demosAgency): void
    {
        self::assertSame('3', $demosAgency->getVerzeichnisdienst()->getListVersionID());
        self::assertSame('DVDV', $demosAgency->getVerzeichnisdienst()->getCode());
        self::assertSame('urn:xoev-de:kosit:codeliste:verzeichnisdienst', $demosAgency->getVerzeichnisdienst()->getListURI());
        $communication1 = $demosAgency->getErreichbarkeit()[0];
        self::assertSame('3', $communication1->getKanal()->getListVersionID());
        self::assertSame('urn:de:xoev:codeliste:erreichbarkeit', $communication1->getKanal()->getListURI());
        self::assertSame('09', $communication1->getKanal()->getCode());
        self::assertSame('https://demos-deutschland.de/impressum.html', $communication1->getKennung());
        self::assertEmpty($communication1->getZusatz());
    }

    protected function validateProcedureXML(string $procedureXml): void
    {
        $commonHelpers = new CommonHelpers($this->createMock(LoggerInterface::class));

        $isValid = $commonHelpers->isValidMessage(
            $procedureXml,
            true,
            '',
            AllgemeinStellungnahmeNeuabgegeben0701::class,
        );
        self::assertTrue($isValid);
    }

}
