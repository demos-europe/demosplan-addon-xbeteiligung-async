<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\StatementTest;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\StatementMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\XBeteiligungResponseMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\StatementsActions\StatementCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\XBeteiligung\AllgemeinStellungnahmeNeuabgegeben0701;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Basisnachricht\Behoerde\BehoerdeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Basisnachricht\Kommunikation\KommunikationTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Basisnachricht\G2g\NachrichtenkopfG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Basisnachricht\G2g\NachrichtG2GTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\XBeteiligung\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use Doctrine\Common\Collections\ArrayCollection;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\Log\Logger;
use Symfony\Component\Routing\RouterInterface;

class StatementCreatorTest extends TestCase
{
    /**
     * @var XBeteiligungResponseMessageFactory
     */
    protected $sut;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @var Serializer
     */
    protected $serializer;

    private MockFactoryTest $mockFactory;
    protected MockObject $globalConfig;
    protected MockObject $gisLayerCategoryRepository;
    protected MockObject $procedureNewsService;
    protected MockObject $procedureMessageRepository;
    protected MockObject $user;
    protected MockObject $procedure;
    protected MockObject $meta;

    protected function setUp(): void
    {
        $mockFactory = new MockFactoryTest();
        $this->mockFactory = $mockFactory;
        $this->globalConfig = $this->createMock(GlobalConfigInterface::class);
        $this->globalConfig->method('getProjectPrefix')->willReturn('diplanbau');
        $this->gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $this->user = $this->createMock(UserInterface::class);
        $this->procedure = $this->createMock(ProcedureInterface::class);
        $this->meta = $this->createMock(StatementMetaInterface::class);
        $this->procedureNewsService = $this->createMock(ProcedureNewsServiceInterface::class);
        $this->procedureMessageRepository = $this->createMock(ProcedureMessageRepository::class);
        $xbeteiligungService = new XBeteiligungService(
            $this->gisLayerCategoryRepository,
            $this->createMock(LoggerInterface::class),
            $this->procedureNewsService,
            $this->procedureMessageRepository,
            $this->createMock( PlanningDocumentsLinkCreator::class),
            $this->createMock(RouterInterface::class),
            $this->createMock(XBeteiligungIncomingMessageParser::class),
            $this->createMock(KommunaleProcedureCreater::class),
            $this->createMock(StatementCreator::class),
        );
        $this->XBeteiligungService = $xbeteiligungService;
        $this->logger = new Logger();
        $this->serializer = SerializerFactory::getSerializer();
        $this->sut = new StatementMessageFactory(
            $this->mockFactory->getLoggerInterfaceMock(),
            $xbeteiligungService,
            $this->globalConfig,
        );
    }

    public function testCreateBeteiligung2PlanungStellungnahmeNeu0701(): void
    {
        $statementCreated = $this->createStatement0701(3);
        $xmlMessageString = $this->sut->createBeteiligung2PlanungStellungnahmeNeu0701($statementCreated);
        $isValid = $this->isValidMessage($xmlMessageString, true);
        self::assertTrue($isValid);
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
        $commChannels = ['email' => $readerAgency->getErreichbarkeit()[0]];
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
            $status = 'neue Stellungnahme';
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
            $feedback = 'E-Mail';
            $priority = 'A-Punkt';
            $vote = new ArrayCollection(['Der Stellungnahme wird gefolgt.']);
            $tags = ['Radverkehr', 'Straßenbau', 'Straßenbäume', 'Städtebaulicher Vertrag'];
            $file = 'Legende.pdf:dc855abd-c8df-11e5-8550-005056ae0004:119994:application/pdf';
            $accessUrl = 'http://bob.beispiel.de?stellungnahmeID=S34992191-830d-4d1d-a136-f38d322b521d';
        } else {
            throw new \RuntimeException("Version {$version} supported");
        }
        $phaseObject = $this->createMock(ProcedurePhaseInterface::class);
        $phaseObject->method('getIteration')->willReturn($iteration);
        $phaseObject->method('setIteration')->with($iteration);

        $meta = $this->createMock(StatementMetaInterface::class);
        $meta->method('getOrgaName')->willReturn('Privatperson');
        $meta->method('setOrgaName')->with('Privatperson');

        // Configure the procedure mock to return the phase object
        $this->procedure->method('getPhaseObject')->willReturn($phaseObject);

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
        $statementCreated->setVotes($vote);
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
        self::assertSame('DiPlan Cockpit', $xmlMessage->getProdukt());
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
        if (isset($commChannels['email'])) {
            $emailComm = $commChannels['email'];
            self::assertSame('01', $emailComm->getKanal()->getCode());
            self::assertSame('info@gv.hamburg.de', $emailComm->getKennung());
            self::assertEmpty($emailComm->getZusatz());
        }
        self::assertSame('0200', $agency->getVerzeichnisdienst()->getCode());
        self::assertSame('3', $agency->getVerzeichnisdienst()->getListVersionID());
        self::assertSame('urn:xoev-de:kosit:codeliste:verzeichnisdienst', $agency->getVerzeichnisdienst()->getListURI());

    }

    private function validateDemosAgency(BehoerdeTypeType $demosAgency): void
    {
        self::assertSame('3', $demosAgency->getVerzeichnisdienst()->getListVersionID());
        self::assertSame('DEMOS plan GmbH', $demosAgency->getVerzeichnisdienst()->getCode());
        self::assertSame('urn:xoev-de:kosit:codeliste:verzeichnisdienst', $demosAgency->getVerzeichnisdienst()->getListURI());
        $communication1 = $demosAgency->getErreichbarkeit()[0];
        self::assertSame('3', $communication1->getKanal()->getListVersionID());
        self::assertSame('01', $communication1->getKanal()->getCode());
        self::assertEmpty($communication1->getZusatz());
        $communication2 = $demosAgency->getErreichbarkeit()[1];
        self::assertSame('officehamburg@demos-international.com', $communication2->getKennung());
    }

    private function isValidMessage(string $message, bool $verboseDebug = false, string $xsdFile = 'xbeteiligung-allgemein.xsd'): bool
    {
        $path = AddonPath::getRootPath('/Resources/xsd/'.$xsdFile);
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

}