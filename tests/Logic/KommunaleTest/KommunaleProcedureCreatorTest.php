<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\KommunaleTest;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\DataFactory\XBeteiligung401TestFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Log\Logger;

class KommunaleProcedureCreatorTest extends TestCase
{
    /**
     * @var KommunaleProcedureCreater
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

    /**
     * @var XBeteiligungIncomingMessageParser
     */
    protected $messageParser;

    /**
     * @var XBeteiligung401TestFactory
     */
    protected $xmlFactory;


    public function createMockObject(string $className): MockObject
    {
        return $this->createMock($className);
    }

    protected function setUp(): void
    {
        $mockFactory = new MockFactoryTest($this);
        $this->logger = new Logger();
        $this->serializer = SerializerFactory::getSerializer();
        $this->messageParser = new XBeteiligungIncomingMessageParser($this->logger);
        $procedureHandlerFactory = new KommunaleProcedureHandlerFactory($mockFactory);
        $this->sut = $procedureHandlerFactory->createProcedureHandler('creator');

        // Initialize XML factory for dynamic test data generation
        $commonHelpers = new CommonHelpers($this->logger);
        $this->xmlFactory = new XBeteiligung401TestFactory(
            AddonPath::getRootPath(),
            $commonHelpers
        );
    }

    /**
     * @dataProvider getTestScenarios()
     */
    public function testCreateNewProcedureFromKommunaleXbeteiligungMessage(string $scenarioName): void
    {
        $inputMsgXml = $this->xmlFactory->createXML($scenarioName, true);
        /** @var KommunalInitiieren0401 $inputMsgObj */
        $inputMsgObj = $this->messageParser->getXmlObject($inputMsgXml, '401');

        self::assertInstanceOf(KommunalInitiieren0401::class, $inputMsgObj);
        // Act
        $procedure = $this->sut->createNewKommunalProcedureFromXBeteiligungMessage($inputMsgObj);
        $inputMsgContent = $inputMsgObj->getNachrichteninhalt()->getBeteiligung();

        self::assertNotNull($inputMsgContent);

        // Assert
        $valid = false;
        if ($procedure instanceof ProcedureInterface) {
            $valid = true;
        }
        self::assertTrue($valid);
        // Test that procedure was created with an organization (may be fallback if org not found in database)
        $xmlOrgName = $inputMsgContent->getAkteurVorhaben()?->getVeranlasser()?->getName()?->getName();
        $procedureOrgName = $procedure->getOrga()->getName();
        self::assertNotEmpty($procedureOrgName);

        // The XML should contain the expected organization name from the scenario
        self::assertContains($xmlOrgName, ['Stadt Quickborn', 'Büro'],
            "XML should contain expected organization name from scenario, got: $xmlOrgName");

        // Test that admin user was assigned
        self::assertSame('Admin User', $procedure->getAuthorizedUsers()[0]->getName());

        // Test that procedure name matches plan name from XML
        self::assertSame($inputMsgContent->getPlanname(), $procedure->getName());
        self::assertSame($inputMsgContent->getPlanname(), $procedure->getExternalName());

        // Test that plan ID and description are correctly mapped
        self::assertSame($inputMsgContent->getPlanID(), $procedure->getXtaPlanId());
        self::assertSame($inputMsgContent->getBeschreibungPlanungsanlass(), $procedure->getDesc());

        // Test that territory GeoJSON is preserved
        self::assertStringContainsString('"type":"Polygon"', $procedure->getSettings()->getTerritory());
        self::assertStringContainsString('"coordinates":', $procedure->getSettings()->getTerritory());

        // Test that bounding box and map extent are calculated
        self::assertNotEmpty($procedure->getSettings()->getBoundingBox());
        self::assertNotEmpty($procedure->getSettings()->getMapExtent());
    }

    /**
     * Provides test scenarios for procedure creation testing.
     *
     * @return string[][]
     */
    public static function getTestScenarios(): array
    {
        return [
            'Stadt Quickborn minimal' => ['quickborn_minimal'],
            'Stadt Quickborn comprehensive' => ['quickborn_comprehensive'],
            'Büro Flächennutzungsplan' => ['buero_flachennutzung'],
        ];
    }
}
