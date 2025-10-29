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

use DateInterval;
use DateTime;
use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerCategoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\GisLayerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedurePhaseInterface;
use DemosEurope\DemosplanAddon\Contracts\Repositories\GisLayerCategoryRepositoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureNewsServiceInterface;
use Doctrine\Common\Collections\ArrayCollection;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\CommonHelpers;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\ReusableMessageBlocks;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAuditService;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungIncomingMessageParser;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\ProcedureMessageRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

/**
 * Test that XBeteiligungService correctly handles stale phase names during onFlush events.
 *
 * During Doctrine's onFlush event, enriched entity fields (like ProcedurePhase->name)
 * may still contain old values from before the flush, while persisted fields
 * (like ProcedurePhase->key) have already been updated.
 *
 * This test simulates that scenario and verifies the service uses the current key
 * to look up the correct phase name, not the stale name field.
 */
class XBeteiligungServiceStalePhaseTest extends TestCase
{
    protected XBeteiligungService $sut;
    protected MockObject $globalConfig;
    protected MockObject $gisLayerCategoryRepository;
    protected MockObject $procedureNewsService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->globalConfig = $this->createMock(GlobalConfigInterface::class);
        $this->globalConfig->method('getMapDefaultProjection')
            ->willReturn([
                'label' => 'EPSG:3857',
                'value' => '+proj=merc +a=6378137 +b=6378137 +lat_ts=0.0 +lon_0=0.0 +x_0=0.0 +y_0=0 +k=1.0 +units=m +nadgrids=@null +wktext  +no_defs'
            ]);

        // Mock phase name lookups - this is what should be used instead of the stale name field
        $this->globalConfig->method('getPhaseNameWithPriorityExternal')
            ->willReturnCallback(function ($phaseKey) {
                // Configuration for mapping phase keys to names
                $phaseMap = [
                    'configuration' => 'Konfiguration',
                    'earlyparticipation' => 'Frühzeitige Beteiligung Öffentlichkeit',
                    'participation' => 'Öffentliche Auslegung',
                ];
                return $phaseMap[$phaseKey] ?? $phaseKey;
            });

        $this->globalConfig->method('getPhaseNameWithPriorityInternal')
            ->willReturnCallback(function ($phaseKey) {
                $phaseMap = [
                    'configuration' => 'Konfiguration',
                    'earlyparticipation' => 'Frühzeitige Beteiligung TÖB',
                    'participation' => 'Beteiligung der Träger öffentlicher Belange',
                ];
                return $phaseMap[$phaseKey] ?? $phaseKey;
            });

        // Mock GIS layer category repository with a proper layer setup
        $this->gisLayerCategoryRepository = $this->createMock(GisLayerCategoryRepositoryInterface::class);
        $gisLayerCategory = $this->createMock(GisLayerCategoryInterface::class);
        $gisLayer = $this->createMock(GisLayerInterface::class);
        $gisLayer->method('getName')->willReturn('basemap');
        $gisLayer->method('getUrl')->willReturn('https://test.example.com/wms');
        $gisLayer->method('getLayerVersion')->willReturn('1.3.0');
        $gisLayer->method('getLayers')->willReturn('test_layer');
        $gisLayer->method('getType')->willReturn('base');
        $gisLayer->method('isEnabled')->willReturn(true);
        $gisLayer->method('getProjectionLabel')->willReturn('EPSG:3857');
        $gisLayerCategory->method('getGisLayers')->willReturn(new ArrayCollection([$gisLayer]));
        $this->gisLayerCategoryRepository->method('getRootLayerCategory')->willReturn($gisLayerCategory);

        // Mock procedure news service
        $this->procedureNewsService = $this->createMock(ProcedureNewsServiceInterface::class);
        $this->procedureNewsService->method('getProcedureNewsAdminList')->willReturn(['result' => []]);

        $reusableMessageBlocks = new ReusableMessageBlocks(
            new CommonHelpers($this->createMock(LoggerInterface::class))
        );

        $this->sut = new XBeteiligungService(
            $this->gisLayerCategoryRepository,
            $this->globalConfig,
            $this->createMock(LoggerInterface::class),
            $this->createMock(ParameterBagInterface::class),
            $this->createMock(PlanningDocumentsLinkCreator::class),
            $this->createMock(ProcedureMessageRepository::class),
            $this->procedureNewsService,
            $this->createMock(RouterInterface::class),
            $this->createMock(XBeteiligungIncomingMessageParser::class),
            $this->createMock(CommonHelpers::class),
            $reusableMessageBlocks,
            $this->createMock(XBeteiligungAuditService::class)
        );
    }

    /**
     * Test that when phase key is "configuration" but name is stale,
     * the service looks up the correct name from the key.
     */
    public function testCreateProcedureUpdate402UsesPhaseKeyNotStaleName(): void
    {
        // ARRANGE: Create a procedure that simulates the onFlush scenario
        $procedure = $this->createProcedureWithStalePhase();

        // ACT: Generate the 0402 message
        $xml = $this->sut->createProcedureUpdate402FromObject($procedure);

        // ASSERT: The XML should contain the CURRENT phase name based on the key, not the stale name
        self::assertStringContainsString('Konfiguration', $xml,
            'XML should contain phase name "Konfiguration" looked up from key "configuration"');

        self::assertStringNotContainsString('Frühzeitige Beteiligung Öffentlichkeit', $xml,
            'XML should NOT contain the stale phase name "Frühzeitige Beteiligung Öffentlichkeit"');
    }

    /**
     * Test that beteiligungOeffentlichkeit is included even when phase is configuration.
     */
    public function testCreateProcedureUpdate402IncludesBeteiligungOeffentlichkeitInConfigurationPhase(): void
    {
        // ARRANGE
        $procedure = $this->createProcedureWithStalePhase();

        // ACT
        $xml = $this->sut->createProcedureUpdate402FromObject($procedure);

        // ASSERT: beteiligungOeffentlichkeit should be included for configuration phase
        self::assertStringContainsString('beteiligungOeffentlichkeit', $xml,
            'beteiligungOeffentlichkeit should be included even when phase is configuration');

        self::assertStringContainsString('beteiligungTOEB', $xml,
            'beteiligungTOEB should be included even when phase is configuration');
    }

    /**
     * Create a mock procedure that simulates the onFlush state:
     * - Phase key is updated to "configuration" (current/persisted value)
     * - Phase name is still "Frühzeitige Beteiligung Öffentlichkeit" (stale/enriched value)
     * - Phase dates are still from the old phase (stale)
     */
    private function createProcedureWithStalePhase(): MockObject
    {
        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('test-procedure-id');
        $procedure->method('getName')->willReturn('Test Procedure');
        $procedure->method('getXtaPlanId')->willReturn('test-xta-plan-id');

        $startDate = new DateTime('2025-10-13');
        $endDate = (new DateTime('2025-10-19'));
        $procedure->method('getStartDate')->willReturn($startDate);
        $procedure->method('getEndDate')->willReturn($endDate);

        // Create phase object with STALE data (simulating onFlush state)
        $phaseObject = $this->createMock(ProcedurePhaseInterface::class);

        // Key is UPDATED (current value)
        $phaseObject->method('getKey')->willReturn('configuration');

        // Name is STALE (old value from before phase change)
        $phaseObject->method('getName')->willReturn('Frühzeitige Beteiligung Öffentlichkeit');

        // Dates are STALE (from the old phase)
        $phaseObject->method('getStartDate')->willReturn($startDate);
        $phaseObject->method('getEndDate')->willReturn($endDate);
        $phaseObject->method('getIteration')->willReturn(1);

        $procedure->method('getPublicParticipationPhaseObject')->willReturn($phaseObject);
        $procedure->method('getPhaseObject')->willReturn($phaseObject);

        // These methods return STALE values (what exists in the entity before enrichment)
        $procedure->method('getPublicParticipationPhaseName')->willReturn('Frühzeitige Beteiligung Öffentlichkeit');
        $procedure->method('getPublicParticipationPhase')->willReturn('configuration');
        $procedure->method('getPhaseName')->willReturn('Frühzeitige Beteiligung Öffentlichkeit');

        $procedure->method('getOrga')->willReturn(null);
        $procedure->method('getSettings')->willReturn(
            $this->createMock(\DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureSettingsInterface::class)
        );

        return $procedure;
    }
}
