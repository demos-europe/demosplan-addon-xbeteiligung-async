<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\XBeteiligingService;

use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseDefinitionInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\MapProjectionConverterInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungDcatApPluStandardCode;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungPhaseDefinitionCodeMapping;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungPhaseDefinitionCodeMappingRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * DPLAN-18120: outgoing K3 messages must prefer the mandant-configured DCAT-AP-PLU code
 * over the hardcoded ProcedurePhaseMapping, unless it's still the default "unknown".
 */
class XBeteiligungServiceConfiguredPhaseCodeTest extends TestCase
{
    protected XBeteiligungService $sut;
    protected MockObject $phaseDefinitionCodeMappingRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $globalConfig = $this->createMock(GlobalConfigInterface::class);
        $globalConfig->method('getMapDefaultProjection')
            ->willReturn([
                'label' => 'EPSG:3857',
                'value' => '+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext  +no_defs',
            ]);

        $gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $gisLayerCategory = $this->createMock(GisLayerCategoryInterface::class);
        $gisLayer = $this->createMock(GisLayerInterface::class);
        $gisLayer->method('getName')->willReturn('basemap');
        $gisLayer->method('getUrl')->willReturn('https://test.example.com/wms');
        $gisLayer->method('getLayerVersion')->willReturn('1.3.0');
        $gisLayer->method('getLayers')->willReturn('test_layer');
        $gisLayer->method('getType')->willReturn('base');
        $gisLayer->method('isEnabled')->willReturn(true);
        $gisLayer->method('getProjectionLabel')->willReturn('EPSG:3857');
        $gisLayerCategory->method('getGisLayers')->willReturn(new \Doctrine\Common\Collections\ArrayCollection([$gisLayer]));
        $gisLayerCategoryRepository->method('getRootLayerCategory')->willReturn($gisLayerCategory);

        $procedureNewsService = $this->createMock(ProcedureNewsServiceInterface::class);
        $procedureNewsService->method('getProcedureNewsAdminList')->willReturn(['result' => []]);

        $reusableMessageBlocks = new ReusableMessageBlocks(
            new CommonHelpers($this->createMock(LoggerInterface::class))
        );

        $this->phaseDefinitionCodeMappingRepository = $this->createMock(XBeteiligungPhaseDefinitionCodeMappingRepository::class);

        $this->sut = new XBeteiligungService(
            $gisLayerCategoryRepository,
            $globalConfig,
            $this->createMock(LoggerInterface::class),
            $this->createMock(MapProjectionConverterInterface::class),
            $this->createMock(ParameterBagInterface::class),
            $this->createMock(PlanningDocumentsLinkCreator::class),
            $this->createMock(ProcedureMessageRepository::class),
            $procedureNewsService,
            $this->createMock(RouterInterface::class),
            $this->createMock(XBeteiligungIncomingMessageParser::class),
            $this->createMock(CommonHelpers::class),
            $reusableMessageBlocks,
            $this->createMock(XBeteiligungAuditService::class),
            $this->phaseDefinitionCodeMappingRepository,
        );
    }

    public function testUsesConfiguredDcatCodeWhenNotUnknown(): void
    {
        // Arrange
        $dcatCode = (new XBeteiligungDcatApPluStandardCode())->setCode('earlyInvolveAuth');
        $mapping = (new XBeteiligungPhaseDefinitionCodeMapping())->setDcatApPluStandardCode($dcatCode);
        $this->phaseDefinitionCodeMappingRepository->method('findOneByPhaseDefinition')->willReturn($mapping);

        $procedure = $this->createProcedureWithPhase('Konfiguration Öffentlichkeit');

        // Act
        $xml = $this->sut->createProcedureUpdate402FromObject($procedure);

        // Assert
        self::assertStringContainsString('earlyInvolveAuth', $xml);
        self::assertStringNotContainsString('>1000<', $xml);
    }

    public function testFallsBackToHardcodedMappingWhenNoConfiguredMapping(): void
    {
        // Arrange
        $this->phaseDefinitionCodeMappingRepository->method('findOneByPhaseDefinition')->willReturn(null);

        $procedure = $this->createProcedureWithPhase('Konfiguration Öffentlichkeit');

        // Act
        $xml = $this->sut->createProcedureUpdate402FromObject($procedure);

        // Assert: 'Konfiguration Öffentlichkeit' maps to '1000' in KOMMUNAL_PUBLIC_PHASE_MAPPING
        self::assertStringContainsString('>1000<', $xml);
    }

    public function testFallsBackToHardcodedMappingWhenDcatCodeIsUnknown(): void
    {
        // Arrange
        $dcatCode = (new XBeteiligungDcatApPluStandardCode())->setCode(XBeteiligungDcatApPluStandardCode::CODE_UNKNOWN);
        $mapping = (new XBeteiligungPhaseDefinitionCodeMapping())->setDcatApPluStandardCode($dcatCode);
        $this->phaseDefinitionCodeMappingRepository->method('findOneByPhaseDefinition')->willReturn($mapping);

        $procedure = $this->createProcedureWithPhase('Konfiguration Öffentlichkeit');

        // Act
        $xml = $this->sut->createProcedureUpdate402FromObject($procedure);

        // Assert: same hardcoded fallback as when no mapping exists at all
        self::assertStringContainsString('>1000<', $xml);
    }

    public function testFallsBackToUnknownWhenNoHardcodedMappingExists(): void
    {
        // Arrange
        $this->phaseDefinitionCodeMappingRepository->method('findOneByPhaseDefinition')->willReturn(null);

        // 'Konfiguration' has no entry in KOMMUNAL_PUBLIC_PHASE_MAPPING/KOMMUNAL_INSTITUTION_PHASE_MAPPING
        $procedure = $this->createProcedureWithPhase('Konfiguration');

        // Act
        $xml = $this->sut->createProcedureUpdate402FromObject($procedure);

        // Assert
        self::assertStringContainsString('unknown', $xml);
    }

    private function createProcedureWithPhase(string $phaseName): MockObject
    {
        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('test-procedure-id');
        $procedure->method('getName')->willReturn('Test Procedure');
        $procedure->method('getXtaPlanId')->willReturn('test-xta-plan-id');

        $startDate = new DateTime('2025-10-13');
        $endDate = new DateTime('2025-10-19');
        $procedure->method('getStartDate')->willReturn($startDate);
        $procedure->method('getEndDate')->willReturn($endDate);

        $phaseDefinition = $this->createMock(ProcedurePhaseDefinitionInterface::class);
        $phaseDefinition->method('getName')->willReturn($phaseName);

        $phaseObject = $this->createMock(ProcedurePhaseInterface::class);
        $phaseObject->method('getPhaseDefinition')->willReturn($phaseDefinition);
        $phaseObject->method('getStartDate')->willReturn($startDate);
        $phaseObject->method('getEndDate')->willReturn($endDate);
        $phaseObject->method('getIteration')->willReturn(1);

        $procedure->method('getPublicParticipationPhaseObject')->willReturn($phaseObject);
        $procedure->method('getPhaseObject')->willReturn($phaseObject);

        $procedure->method('getOrga')->willReturn(null);
        $procedure->method('getSettings')->willReturn(
            $this->createMock(ProcedureSettingsInterface::class)
        );

        return $procedure;
    }
}
