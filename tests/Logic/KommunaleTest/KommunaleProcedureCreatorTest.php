<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\KommunaleTest;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Utilities\AddonPath;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\DataFixtures\MockFactoryTest;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use JMS\Serializer\Serializer;
use PHPUnit\Framework\Attributes\DataProvider;
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

    }


    #[DataProvider('getTestXmlFiles')]
    public function testCreateNewProcedureFromKommunaleXbeteiligungMessage($filePath): void
    {
        $inputMsgXml = file_get_contents(AddonPath::getRootPath($filePath));
        /** @var KommunalInitiieren0401 $inputMsgObj */
        $inputMsgObj = $this->messageParser->getXmlObject($inputMsgXml, '401');

        self::assertInstanceOf(KommunalInitiieren0401::class, $inputMsgObj);
        // Act
        $testRoutingKey = 'nrw.cockpit.bap.02.05.00200099.bdp.02.05.00200099.kommunal.initiieren.0401';
        $procedure = $this->sut->createNewKommunalProcedureFromXBeteiligungMessage($inputMsgObj, $testRoutingKey);
        $inputMsgContent = $inputMsgObj->getNachrichteninhalt()->getBeteiligung();

        self::assertNotNull($inputMsgContent);

        // Assert
        $valid = false;
        if ($procedure instanceof ProcedureInterface) {
            $valid = true;
        }
        self::assertTrue($valid);
        self::assertSame($inputMsgContent->getAkteurVorhaben()?->getVeranlasser()?->getName()?->getName(), $procedure->getOrga()->getName());
        self::assertSame('Admin User', $procedure->getAuthorizedUsers()[0]->getName());
        self::assertSame($inputMsgContent->getPlanname(), $procedure->getName());
        self::assertSame($inputMsgContent->getPlanname(), $procedure->getExternalName());
        self::assertSame($inputMsgContent->getPlanID(), $procedure->getXtaPlanId());
        self::assertSame($inputMsgContent->getBeschreibungPlanungsanlass(), $procedure->getDesc());
        self::assertSame('{"type":"Polygon","coordinates":[[[1122490.3573962983,7071484.285754054],[1122482.2362970402,7071490.40680759],[1122478.999109264,7071492.845304786],[1122475.2042036585,7071495.781567061],[1122471.5251732466,7071498.5058959145],[1122471.360964572,7071498.627772408],[1122469.9660731317,7071499.224038446],[1122463.02764217,7071505.135395698],[1122451.0108444085,7071515.071890943],[1122435.128717185,7071525.847386543],[1122434.268690556,7071526.4304593485],[1122410.0366806341,7071547.389219202],[1122421.1579644377,7071561.588730869],[1122441.4799638607,7071587.54543312],[1122456.3661907252,7071606.558539621],[1122430.3149269223,7071588.72595191],[1122402.707278053,7071568.131007109],[1122396.0806524877,7071563.187822573],[1122350.858337287,7071529.455500149],[1122335.8999894143,7071516.840991722],[1122325.8392592138,7071506.425138527],[1122316.3031768298,7071497.64565565],[1122300.8185980634,7071483.391426601],[1122293.7498677047,7071475.827006324],[1122290.965665827,7071472.846312364],[1122288.0672533428,7071469.744618191],[1122283.0888017584,7071464.416527989],[1122299.6096302131,7071442.753501105],[1122320.7897785942,7071414.985345006],[1122334.505386014,7071395.280075988],[1122332.6508171991,7071393.875809563],[1122332.830013415,7071393.636017209],[1122335.6712878277,7071389.863623659],[1122356.6622858304,7071362.0278163785],[1122359.7101755266,7071358.9416223075],[1122366.7414734326,7071366.0306309415],[1122363.7870400304,7071369.307077891],[1122340.8624427796,7071399.7128248485],[1122320.6475335688,7071426.528118282],[1122317.0646003806,7071431.280250348],[1122319.3874984237,7071433.704692957],[1122362.406354156,7071478.592353006],[1122388.8004967493,7071460.632449447],[1122400.3920099915,7071477.869576714],[1122411.4351000325,7071469.425922151],[1122428.9701568882,7071458.0419582715],[1122432.4341652468,7071454.964494912],[1122435.8556671,7071452.624101453],[1122437.2985384408,7071451.763145272],[1122445.9607397611,7071446.593966149],[1122456.4597913737,7071440.3308425555],[1122450.2102129434,7071445.540627205],[1122452.4186055246,7071450.145783418],[1122454.3879646042,7071454.25186411],[1122455.6115985825,7071454.862045752],[1122475.7322453903,7071464.8924076725],[1122476.1818033245,7071465.115902283],[1122490.3573962983,7071484.285754054]]]}', $procedure->getSettings()->getTerritory());
        self::assertSame('1121972.185910,7070987.516246,1122801.260288,7071977.983916', $procedure->getSettings()->getBoundingBox());
        self::assertSame('1122283.0888018,7071358.9416223,1122490.3573963,7071606.5585396', $procedure->getSettings()->getMapExtent());
    }

    /**
     * A list of file paths to xml files used for testing
     *
     * @return string[][]
     */
    public static function getTestXmlFiles(): array
    {
        return [
            ['tests/res/xmlv14/xbeteiligung-test-kommunal.Initiieren.0401.xml'],
        ];
    }

}
