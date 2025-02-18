<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DIplanBau;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\Planung2BeteiligungBeteiligungKommunalNeu0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Diplanbau\XtaKommunaleProcedureCreater;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Log\Logger;

class KommunaleProcedureCreatorTest extends TestCase
{
    /**
     * @var XtaKommunaleProcedureCreater
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

    private MockFactory $mockFactory;

    protected function setUp(): void
    {
        $mockFactory = new MockFactory();
        $this->mockFactory = $mockFactory;
        $this->logger = new Logger();
        $this->serializer = SerializerFactory::getSerializer();
        $xtaProcedureHandlerFactory = new XtaKommunaleProcedureHandlerFactory($mockFactory);
        $this->sut = $xtaProcedureHandlerFactory->createXtaProcedureHandler('creator');

    }

    //TODO: Need Dataprovider LIKE @dataProvider getTestXmlFiles() but we don't have yet a example xml file
    public function testCreateNewProcedureFromKommunaleXbeteiligungMessage($filePath): void
    {
        $inputMsgXml = file_get_contents(AddonPath::getRootPath($filePath));
        /** @var Planung2BeteiligungBeteiligungKommunalNeu0401 $inputMsgObj */
        $inputMsgObj = $this->serializer->deserialize(
            $inputMsgXml,
            Planung2BeteiligungBeteiligungKommunalNeu0401::class,
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

}