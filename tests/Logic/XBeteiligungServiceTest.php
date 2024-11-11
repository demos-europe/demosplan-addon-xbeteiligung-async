<?php
declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\SerializerFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\Routing\RouterInterface;

abstract class XBeteiligungServiceTest extends TestCase
{
    protected MockObject $gisLayerCategoryRepository;
    protected MockObject $testProcedureWithoutBBox;
    protected MockObject $testProcedure;
    protected MockObject $procedureNewsService;
    protected MockObject $procedureMessageRepository;
    protected XBeteiligungService $sut;
    protected const GEO_JSON_FG_TEST = '{"type":"FeatureCollection","features":[{"type":"Feature","geometry":{"type":"Polygon","coordinates":[[[1116296.9705734858,6634813.663749559],[1117905.9884860306,6634187.8624979565],[1117301.031359643,6636161.866445964],[1115603.7905328334,6635901.465925163],[1116296.9705734858,6634813.663749559]]]},"properties":null}]}';

    protected function setUp(): void
    {
        parent::setUp();

        $this->gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $this->procedureNewsService = $this->createMock(ProcedureNewsServiceInterface::class);
        $this->testProcedure = $this->getTestProcedure($this->getTestProcedureSettings());
        $this->testProcedureWithoutBBox = $this->getTestProcedure($this->getTestProcedureSettings(false));
        $this->procedureMessageRepository = $this->createMock(ProcedureMessageRepository::class);


        $serializer = new SerializerFactory();
        $this->sut = new XBeteiligungService(
            $this->gisLayerCategoryRepository,
            $this->createMock(LoggerInterface::class),
            $serializer,
            $this->procedureNewsService,
            $this->procedureMessageRepository,
            $this->createMock( PlanningDocumentsLinkCreator::class),
            $this->createMock(RouterInterface::class)
        );
    }

    private function createEnabledAndVisibleGisMOck(): GisLayerInterface
    {
        $EnabledUndVisibleGisMo = $this->createMock(GisLayerInterface::class);
        $EnabledUndVisibleGisMo->method('getName')->willReturn('basemap');
        $EnabledUndVisibleGisMo->method('getUrl')->willReturn('https://sgx.geodatenzentrum.de/wms_basemapde');
        $EnabledUndVisibleGisMo->method('getLayerVersion')->willReturn('1.3.0');
        $EnabledUndVisibleGisMo->method('getLayers')->willReturn('de_basemapde_web_raster_farbe');
        $EnabledUndVisibleGisMo->method('getType')->willReturn('base');
        $EnabledUndVisibleGisMo->method('hasDefaultVisibility')->willReturn(true);
        $EnabledUndVisibleGisMo->method('isEnabled')->willReturn(true);

        return $EnabledUndVisibleGisMo;
    }
    private function createEnabledGisMOck(): GisLayerInterface
    {
        $EnabledGisMo = $this->createMock(GisLayerInterface::class);
        $EnabledGisMo->method('getName')->willReturn('CustomLayerName');
        $EnabledGisMo->method('getUrl')->willReturn('CustomLayerUrl');
        $EnabledGisMo->method('getLayerVersion')->willReturn('CustomLayerVersion');
        $EnabledGisMo->method('getLayers')->willReturn('CustomLayer');
        $EnabledGisMo->method('getType')->willReturn('base');
        $EnabledGisMo->method('hasDefaultVisibility')->willReturn(false);
        $EnabledGisMo->method('isEnabled')->willReturn(true);

        return $EnabledGisMo;
    }

    private function createVisibleGisMOck(): GisLayerInterface
    {
        $VisibleGisMo = $this->createMock(GisLayerInterface::class);
        $VisibleGisMo->method('getName')->willReturn('CustomLayerName');
        $VisibleGisMo->method('getUrl')->willReturn('CustomLayerUrl');
        $VisibleGisMo->method('getLayerVersion')->willReturn('CustomLayerVersion');
        $VisibleGisMo->method('getLayers')->willReturn('CustomLayer');
        $VisibleGisMo->method('getType')->willReturn('base');
        $VisibleGisMo->method('hasDefaultVisibility')->willReturn(true);
        $VisibleGisMo->method('isEnabled')->willReturn(false);

        return $VisibleGisMo;
    }

    private function createNotEnabledAndNotVisibleGisMOck(): GisLayerInterface
    {
        $VisibleGisMo = $this->createMock(GisLayerInterface::class);
        $VisibleGisMo->method('getName')->willReturn('CustomLayerName');
        $VisibleGisMo->method('getUrl')->willReturn('CustomLayerUrl');
        $VisibleGisMo->method('getLayerVersion')->willReturn('CustomLayerVersion');
        $VisibleGisMo->method('getLayers')->willReturn('CustomLayer');
        $VisibleGisMo->method('getType')->willReturn('base');
        $VisibleGisMo->method('hasDefaultVisibility')->willReturn(true);
        $VisibleGisMo->method('isEnabled')->willReturn(false);

        return $VisibleGisMo;
    }

    public function testGetAvailableGisLayer()
    {
        // In this case the procedure has 3 layers and one of them is enabled and visible, and it should be returned
        // and used (visible and enabled layers are prioritized to be used as available layer)
        $gisMo = new ArrayCollection();
        $gisMo->add($this->createEnabledGisMOck());
        $gisMo->add($this->createVisibleGisMOck());
        $gisMo->add($this->createEnabledAndVisibleGisMOck());
        $availableGis = $this->sut->getAvailableGisLayer($gisMo);

        $this->assertNotEmpty($availableGis);
        $this->assertEquals($availableGis->hasDefaultVisibility(),true);
        $this->assertEquals($availableGis->isEnabled(),true);

        // In this case the procedure has 2 layers and one of them is enabled, and it should be returned
        // and used (if there are no visible and enabled layers then only enabled layers are prioritized to be used as
        // available layer)
        $gisMo = new ArrayCollection();
        $gisMo->add($this->createEnabledGisMOck());
        $gisMo->add($this->createVisibleGisMOck());
        $availableGis= $this->sut->getAvailableGisLayer($gisMo);

        $this->assertNotEmpty($availableGis);
        $this->assertEquals($availableGis->hasDefaultVisibility(),false);
        $this->assertEquals($availableGis->isEnabled(),true);

        // In this case the procedure has 2 layers and one of them is visible, and it should be returned
        // and used  (if there are no visible and enabled layers and no only enabled layers then only visible layers
        // are prioritized to be used as available layer)
        $gisMo = new ArrayCollection();
        $gisMo->add($this->createNotEnabledAndNotVisibleGisMOck());
        $gisMo->add($this->createVisibleGisMOck());
        $availableGis= $this->sut->getAvailableGisLayer($gisMo);

        $this->assertNotEmpty($availableGis);
        $this->assertEquals($availableGis->hasDefaultVisibility(),true);
    }
    protected function getTestProcedure(MockObject $procedureSettingsMock)
    {
        $gisLayerCategoryInterfaceMock = $this->createMock(
            GisLayerCategoryInterface::class
        );

        // practically the 'getAvailableGisLayer' decide which layer will
        // be used (see testGetAvailableGisLayer) but for now we can use any of the created mocks.
        $gisMo = $this->createEnabledAndVisibleGisMOck();
        $gisLayerCategoryInterfaceMock->method('getGisLayers')->willReturn($gisMo);

        $this->gisLayerCategoryRepository->method('getRootLayerCategory')->willReturn($gisLayerCategoryInterfaceMock);

        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('7606f622-439b-4929-8625-0856c161409e');
        $procedure->method('getXtaPlanId')->willReturn('7606f622-439b-4929-8625-0856c161409e');
        $orga = $this->createMock(OrgaInterface::class);
        $orga->method('getName')->willReturn('SoFreshAndSoClean');
        $procedure->method('getOrga')->willReturn($orga);
        $procedure->method('getName')->willReturn('Mars 2050');
        $procedure->method('getDesc')->willReturn('return will be planned on the fly :)');
        $startDate = new DateTime();
        $endDate = (new DateTime())->add(new DateInterval('P7D'));
        $procedure->method('getStartDate')->willReturn($startDate);
        $procedure->method('getEndDate')->willReturn($endDate);
        $procedurePhaseMock = $this->createMock(ProcedurePhaseInterface::class);
        $procedurePhaseMock->method('getStartDate')->willReturn($startDate);
        $procedurePhaseMock->method('getEndDate')->willReturn($endDate);
        $procedure->method('getPhaseObject')->willReturn($procedurePhaseMock);
        $procedure->method('getPublicParticipationPhaseObject')->willReturn($procedurePhaseMock);
        $procedure->method('getPublicParticipationPhase')->willReturn('configuration');
        $procedure->method('getPhase')->willReturn('earlyparticipation');
        $procedure->method('getSettings')->willReturn($procedureSettingsMock);
        $this->procedureNewsService->method('getProcedureNewsAdminList')->willReturn(
            [
                'result' => [
                    [
                        'title' => 'Test Titel',
                        'text' => 'Test Inhalt',
                        'roles' => [
                            [
                                'groupCode' => 'GPSORG',
                                'code' => RoleInterface::PLANNING_AGENCY_WORKER
                            ]
                        ]
                    ],
                    [
                        'title' => 'noch ein <p>Test Titel</p>',
                        'text' => '<b>Test</b> These Tags will be removed via strip_tags()',
                        'roles' => [
                            [
                                'groupCode' => 'GPSORG',
                                'code' => RoleInterface::CITIZEN
                            ]
                        ]
                    ],
                ]
            ]
        );
        return $procedure;
    }

    protected function getTestProcedureSettings(bool $withBBox = true)
    {
        $procedureSettingsMock = $this->createMock(
            ProcedureSettingsInterface::class
        );
        $procedureSettingsMock->method('getTerritory')
            ->willReturn(self::GEO_JSON_FG_TEST);
        if ($withBBox) {
            $procedureSettingsMock->method('getMapExtent')
                ->willReturn(
                    '904640.92309477,7067292.9633037,1195347.6354542,7350657.5148909'
                );
        }
        if (!$withBBox) {
            $procedureSettingsMock->method('getMapExtent')
                ->willReturn('');
        }

        return $procedureSettingsMock;
    }

    protected function validateProcedureXML(string $procedureXml): void
    {
        $isValid = $this->sut->isValidMessage($procedureXml, true);
        self::assertTrue($isValid);
    }
}
