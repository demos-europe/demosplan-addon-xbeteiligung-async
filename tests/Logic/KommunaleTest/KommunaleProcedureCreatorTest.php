<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\KommunaleTest;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use JMS\Serializer\Serializer;
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

    private MockFactoryTest $mockFactory;

    protected function setUp(): void
    {
        $mockFactory = new MockFactoryTest();
        $this->mockFactory = $mockFactory;
        $this->logger = new Logger();
        $this->serializer = SerializerFactory::getSerializer();
        $procedureHandlerFactory = new KommunaleProcedureHandlerFactory($mockFactory);
        $this->sut = $procedureHandlerFactory->createProcedureHandler('creator');

    }

    /**
     * @dataProvider getTestXmlFiles()
     */
    public function testCreateNewProcedureFromKommunaleXbeteiligungMessage($filePath): void
    {
        $inputMsgXml = file_get_contents(AddonPath::getRootPath($filePath));
        /** @var KommunalInitiieren0401 $inputMsgObj */
        $inputMsgObj = $this->serializer->deserialize(
            $inputMsgXml,
            KommunalInitiieren0401::class,
            'xml'
        );

        // Act
        $procedure = $this->sut->createNewKommunalProcedureFromXBeteiligungMessage($inputMsgObj);
        $inputMsgContent = $inputMsgObj->getNachrichteninhalt()->getBeteiligung();

        // Assert
        $valid = false;
        if ($procedure instanceof ProcedureInterface) {
            $valid = true;
        }
        self::assertTrue($valid);
        self::assertSame($inputMsgContent->getPlanname(), $procedure->getName());
        self::assertSame($inputMsgContent->getPlanname(), $procedure->getExternalName());
        self::assertSame($inputMsgContent->getPlanID(), $procedure->getXtaPlanId());
        self::assertSame($inputMsgContent->getBeschreibungPlanungsanlass(), $procedure->getDesc());
        self::assertSame($inputMsgContent->getVerfahrensschrittKommunal()->getCode(), $procedure->getSettings()->getTerritory());
    }

    /**
     * A list of file paths to xml files used for testing
     *
     * @return string[][]
     */
    public function getTestXmlFiles(): array
    {
        return [
            ['tests/res/xmlv14/xbeteiligung-test-kommunal.Initiieren.0401.xml'],
        ];
    }

}
