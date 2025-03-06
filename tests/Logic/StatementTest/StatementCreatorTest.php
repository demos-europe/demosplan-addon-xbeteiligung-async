<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\StatementTest;

use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\XBeteiligungResponseMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungStellungnahmeNeu0701\Beteiligung2PlanungStellungnahmeNeu0701AnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Beteiligung2PlanungStellungnahmeNeu0701\Beteiligung2PlanungStellungnahmeNeu0701AnonymousPHPType\NachrichteninhaltAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\StellungnahmeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\StatementCreated;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Log\Logger;

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

    protected function setUp(): void
    {
        $mockFactory = new MockFactoryTest();
        $this->mockFactory = $mockFactory;
        $this->logger = new Logger();
        $this->serializer = new SerializerFactory();
        $statementHandlerFactory = new StatementHandlerFactory($mockFactory);
        $this->sut = $statementHandlerFactory->createStatementHandler('creator');
    }

    public function testCreateBeteiligung2PlanungStellungnahmeNeu0701(): void
    {
        $statementCreated = $this->createStatement0701(1);
        $xmlMessageString = $this->sut->createBeteiligung2PlanungStellungnahmeNeu0701($statementCreated);
        $isValid = $this->mockFactory->isValidMessage($xmlMessageString, true);
        self::assertTrue($isValid);
        /** @var Beteiligung2PlanungStellungnahmeNeu0701AnonymousPHPType $xmlMessage */
        $xmlMessage = $this->serializer->deserialize(
            $xmlMessageString,
            Beteiligung2PlanungStellungnahmeNeu0701AnonymousPHPType::class,
            'xml'
        );
        $header = $xmlMessage->getNachrichtenkopf();
        $content = $xmlMessage->getNachrichteninhalt();
        $readerAgency = $header->getLeser();
        $authorAgency = $header->getAutor();

        $this->mockFactory->validateProductInfo($xmlMessage);
        $this->mockFactory->validateMessageId('0701', $header);
        $commChannels = ['email' => $readerAgency->getErreichbarkeit()[0]];
        $this->mockFactory->validateK1Agency($readerAgency, 'LGV', $commChannels);
        $this->mockFactory->validateDemosAgency($authorAgency);

        $stellungnahme = $content->getStellungnahme();
        $this->validateStatement($statementCreated, $stellungnahme);
        self::assertSame('S34992191-830d-4d1d-a136-f38d322b521d', $stellungnahme->getStellungnahmeID());
    }

    private function createStatement0701(int $version): StatementCreated
    {
        if (1 === $version) {
            $statementId = 'S34992191-830d-4d1d-a136-f38d322b521d';
            $planId = 'P9fd5b777-d02b-4340-81dc-89cb0a86029f';
            $procedureId = 'r20a6413-6c48-11eb-aaea-00505697774f';
            $procedureName = 'Alsterdorf19';
            $organization = 'Testorganisation';
            $description = 'Aus Sicht des Radverkehrs ist eine zügige und geradlinige Verbindung am Weidenbaumsweg entsprechend der aktuellen Erfordernisse anzustreben. In der vorliegenden Planung ist das nicht zu erkennen.
                Im Radverkehrskonzept für Bergedorf stellt der Weidenbaumsweg eine wichtige bzw. die Haupt Süd-Nordtrasse für den Radverkehr dar. Vor allem unter diesem Gesichtspunkt muss eine für die gesamte Straße einheitliche Radverkehrsführung hergestellt werden. In der Stellungnahme vom 28.10.16 wurden Radfahr- oder Schutzstreifen für den Weidenbaumsweg vorgeschlagen, wenn auch zu Lasten von Kfz Stellplätzen. Diese Untersuchungen bzw. die Ergebnisse sind in den Kreisverkehr mit einzubringen bzw. nochmal zu überdenken.
                Aus straßenbautechnischer Sicht bestehen bei Einhaltung sowie Umsetzung der z. Zt. Gültigen Regelwerke, Vorschriften sowie Richtlinien für Straßenbau gegen diese Maßnahme keine Bedenken.
                Der Weidenbaumsweg im betroffenen Abschnitt zwischen Kampbille und Einmündung Wiesnerring ist mit Holländischen Linden bzw. Winter Linden als Begleitgrün (Straßenbäume) bestanden. Die Bäume sind unterschiedlichen Alters, aber größtenteils in den neunzehnhundertachtziger/neunziger Jahren gepflanzt worden. Bedingt durch die notwendige Anbindung des neuen Quartiers an den öffentlichen Verkehr sind Straßenumbaumaßnahmen am Weidenbaumsweg erforderlich. Durch diese Umbaumaßnahmen sind auch Eingriffe in den Straßenbaumbestand nicht zu umgehen, d.h. es müssen aller Voraussicht nach Straßenbäume gefällt werden. Nach derzeit vorliegender Planung soll die Anbindung im südl. Bereich des neuen Quartiers in Form einer Einmündung und im Bereich der Hausnummer XYZ in Form eines Kreisels stattfinden. In diesem Fall wären 8 - 10 Straßenbäume betroffen. Genaueres kann diesseits erst nach Vorliegen detaillierterer Planung gesagt werden. B/XY ist der Herstellungswert (berechnet nach der Methode Koch) der betroffenen Straßenbäume zu erstatten, dann kann von hier aus eine Fällgenehmigung erteilt werden.
                Straßenumbaumaßnahmen sind in jedem Fall im Bereich von Bäumen durch eine Fachfirma baumpflegerisch zu begleiten. Für Neupflanzungen von Straßenbäumen im Vorhabengebiet ist unbedingt die FLL Richtlinie Empfehlungen für Baumpflanzungen- Pflanzgrubenbauweise anzuwenden!
                Im Städtebaulichen Vertrag wird in § 14 Öffentlicher Kinderspielplatz geregelt, dass der Investor sich verpflichtet, einen bestimmten Betrag für die Ausstattung der Grün- und Parkanlagen mit Spielgeräten im Nahbereich des Vorhabens zur Verfügung stellt. In der Begründung zum Bebauungsplan unter Punkt 4.1.2 wird innerhalb der Parkanlage ein ca. 4.500 m² großer öffentlicher Spielplatz festgelegt. Hier geht nicht deutlich hervor, ob es in dem Gebiet einen Spielplatz geben wird bzw. ob die zur Verfügung gestellte Summe für diesen Spielplatz zur Verfügung steht oder es für einen beliebigen anderen Spielplatz gilt, der in der näheren Umgebung liegt. Hier sollte klar dargestellt werden, wo der Spielplatz liegen soll. Dies sollte ebenfalls in der Planzeichnung dargestellt werden.';
            $date = new \DateTime('2019-05-01');
            $accessUrl = 'http://bob.beispiel.de?stellungnahmeID=S34992191-830d-4d1d-a136-f38d322b521d';
        } else {
            throw new \RuntimeException("Version {$version} supported");
        }
        $statementCreated = new StatementCreated();
        $statementCreated->setPlanId($planId);
        $statementCreated->setProcedureName($procedureName);
        $statementCreated->setProcedureId($procedureId);
        $statementCreated->setDescription($description);
        $statementCreated->setPlannerDetailViewUrl($accessUrl);
        $statementCreated->setCreatedAt($date);
        $statementCreated->setPublicId($statementId);
        $statementCreated->setOrganizationName($organization);
        $statementCreated->setPhase('participation');

        $statementCreated->lock();

        return $statementCreated;
    }

    private function validateStatement(StatementCreated $statement1, StellungnahmeType $statement2): void
    {
        self::assertSame($statement1->getPublicId(), $statement2->getStellungnahmeID());
        self::assertSame($statement1->getPlanId(), $statement2->getPlanID());
        self::assertSame($statement1->getProcedureId(), $statement2->getBeteiligungsID());
        self::assertSame($statement1->getProcedureId(), $statement2->getPlanID());
        self::assertSame($statement1->getOrganizationName(), $statement2->getVerfasser()->getName());
        self::assertSame($statement1->getDescription(), $statement2->getBeschreibung());
        self::assertEquals($statement2->getDatum(), $statement1->getCreatedAt());
    }

}