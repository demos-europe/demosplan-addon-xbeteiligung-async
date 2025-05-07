<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligingService;

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\OrgaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\RoleInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\KommunaleProcedureCreater;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
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
    protected MockObject $testProcedure;
    protected MockObject $testProcedureWithoutBBox;
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

        $globalConfigMock = $this->createMock(GlobalConfigInterface::class);
        $globalConfigMock->method('getMapDefaultProjection')->willReturn([
            'label' => 'EPSG:3857',
        ]);

        $reusableMessageBlocks =
            new ReusableMessageBlocks(new CommonHelpers($this->createMock(LoggerInterface::class)));

        $this->sut = new XBeteiligungService(
            $this->gisLayerCategoryRepository,
            $globalConfigMock,
            $this->createMock(KommunaleProcedureCreater::class),
            $this->createMock(LoggerInterface::class),
            $this->createMock( PlanningDocumentsLinkCreator::class),
            $this->procedureMessageRepository,
            $this->procedureNewsService,
            $this->createMock(RouterInterface::class),
            $this->createMock(XBeteiligungIncomingMessageParser::class),
            $this->createMock(CommonHelpers::class),
            $reusableMessageBlocks
        );
    }

    protected function getTestProcedure(MockObject $procedureSettingsMock)
    {
        $gisLayerCategoryInterfaceMock = $this->createMock(
            GisLayerCategoryInterface::class
        );
        $gisMo = $this->createMock(GisLayerInterface::class);
        $gisMo->method('getName')->willReturn('basemap');
        $gisMo->method('getUrl')->willReturn('https://sgx.geodatenzentrum.de/wms_basemapde');
        $gisMo->method('getLayerVersion')->willReturn('1.3.0');
        $gisMo->method('getLayers')->willReturn('de_basemapde_web_raster_farbe');
        $gisMo->method('getType')->willReturn('base');
        $gisMo->method('isEnabled')->willReturn(true);
        $gisMo->method('getProjectionLabel')->willReturn('EPSG:3857');
        $gisLayerCategoryInterfaceMock->method('getGisLayers')->willReturn(new ArrayCollection([$gisMo]));
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
        $procedurePhaseMock->method('getIteration')->willReturn(1);
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

    protected function validateProcedureXML(string $procedureXml, string $messageClass): void
    {
        // Basic XML validation
        self::assertNotEmpty($procedureXml, "Generated XML should not be empty");
        self::assertStringContainsString('<?xml', $procedureXml, "Should be a valid XML document");

        // Check for important XML message elements
        if (str_contains($messageClass, 'Kommunal')) {
            self::assertStringContainsString('kommunal', $procedureXml, "XML should contain kommunal message tag");
        } else if (str_contains($messageClass, 'Raumordnung')) {
            self::assertStringContainsString('raumordnung', $procedureXml, "XML should contain raumordnung message tag");
        } else if (str_contains($messageClass, 'Planfeststellung')) {
            self::assertStringContainsString('planfeststellung', $procedureXml, "XML should contain planfeststellung message tag");
        }

        // Check for common elements
        self::assertStringContainsString('nachrichtenkopf.g2g', $procedureXml, "XML should contain message header");
        self::assertStringContainsString('nachrichteninhalt', $procedureXml, "XML should contain message content");
        self::assertStringContainsString('beteiligung', $procedureXml, "XML should contain participation element");
        $commonHelpers = new CommonHelpers($this->createMock(LoggerInterface::class));

        $isValid = $commonHelpers->isValidMessage(
            $procedureXml,
            true,
            '',
            $messageClass,
        );
        self::assertTrue($isValid);
    }
}
