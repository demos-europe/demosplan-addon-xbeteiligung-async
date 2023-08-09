<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class XBeteiligungServiceTest402 extends TestCase
{
    private MockObject $repoMock;
    private MockObject $procedureNewsService;
    private MockObject $procedureMessageRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repoMock = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $this->procedureNewsService = $this->createMock(ProcedureNewsServiceInterface::class);
        $this->procedureMessageRepository = $this->createMock(ProcedureMessageRepository::class);

        $serializer = new SerializerFactory();
        $this->sut = new XBeteiligungService(
            $this->repoMock,
            $this->createMock(LoggerInterface::class),
            $serializer,
            $this->procedureNewsService,
            $this->procedureMessageRepository
        );
    }

    public function testPlanung2BeteiligungBeteiligungNeu0402()
    {
        $gisLayerCategoryInterfaceMock = $this->createMock(GisLayerCategoryInterface::class);
        $gisMo = $this->createMock(GisLayerInterface::class);
        $gisMo->method('getName')->willReturn('basemap');
        $gisMo->method('getUrl')->willReturn('https://sgx.geodatenzentrum.de/wms_basemapde');
        $gisMo->method('getLayerVersion')->willReturn('1.3.0');
        $gisMo->method('getLayers')->willReturn('de_basemapde_web_raster_farbe');
        $gisLayerCategoryInterfaceMock->method('getGisLayers')->willReturn(new ArrayCollection([$gisMo]));
        $procedureSettingsMock = $this->createMock(ProcedureSettingsInterface::class);
        $procedureSettingsMock->method('getBoundingBox')
            ->willReturn('904640.92309477,7067292.9633037,1195347.6354542,7350657.5148909');
        $this->repoMock->method('getRootLayerCategory')->willReturn($gisLayerCategoryInterfaceMock);

        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('7606f622-439b-4929-8625-0856c161409e');
        $procedure->method('getXtaPlanId')->willReturn('7606f622-439b-4929-8625-0856c161409e');
        $orga = $this->createMock(OrgaInterface::class);
        $orga->method('getName')->willReturn('SoFreshAndSoClean');
        $procedure->method('getOrga')->willReturn($orga);
        $procedure->method('getName')->willReturn('Mars 2050');
        $procedure->method('getDesc')->willReturn('return will be planned on the fly :)');
        $procedure->method('getStartDate')->willReturn(new DateTime());
        $procedure->method('getEndDate')->willReturn((new DateTime())->add(new DateInterval('P7D')));
        $procedure->method('getSettings')->willReturn($procedureSettingsMock);
        $procedure->method('getPublicParticipationPhase')->willReturn('configuration');
        $procedure->method('getPhase')->willReturn('earlyparticipation');
        $procedure->method('getPublicParticipationStartDate')->willReturn(new DateTime());
        $procedure->method('getPublicParticipationEndDate')->willReturn(
            (new DateTime())->add(new DateInterval('P7D'))
        );

        $this->procedureNewsService->method('getProcedureNewsAdminList')->willReturn(
            [
                'result' => [
                    [
                        'title' => 'Test Titel',
                        'text'  => 'Test Inhalt',
                        'roles' => [['groupCode' => 'GPSORG', 'code' => RoleInterface::PLANNING_AGENCY_WORKER]]
                    ],
                    [
                        'title' => 'noch ein <p>Test Titel</p>',
                        'text'  => '<b>Test</b> These Tags will be removed via strip_tags()',
                        'roles' => [['groupCode' => 'GPSORG', 'code' => RoleInterface::CITIZEN]]
                    ],
                ]
            ]
        );

        $procedureXml = $this->sut->createProcedureUpdate402FromObject($procedure);

        $isValid = $this->sut->isValidMessage($procedureXml, true);
        self::assertTrue($isValid);
    }
}
