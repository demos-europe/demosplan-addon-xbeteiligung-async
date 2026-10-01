<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\ExternalMapper;

use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungProcedurePhaseCockpit;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ParticipationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper\ProcedurePhaseCodeDetector;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\MessageComponentsBuilders\VerfasserBuilder;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungProcedurePhaseCockpitRepository;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ProcedurePhaseCodeDetectorTest extends TestCase
{
    private const PROCEDURE_ID = 'procedure-1';

    private ?XBeteiligungProcedurePhaseCockpitRepository $repository = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = $this->createMock(XBeteiligungProcedurePhaseCockpitRepository::class);
    }

    #[DataProvider('participationTypeProvider')]
    public function testSubPhaseCodeByProcedureIdUsesStoredCockpitCode(
        ParticipationType $participationType,
        string $expectedCode
    ): void {
        $cockpit = (new XBeteiligungProcedurePhaseCockpit())
            ->setPublicParticipationSubPhaseCode('public-sub')
            ->setInstitutionParticipationSubPhaseCode('institution-sub');
        $this->mockCockpitLookup($cockpit);

        $code = $this->createSut('configured')
            ->getExternalProcedureSubPhaseCodeByProcedureId(self::PROCEDURE_ID, $participationType);

        self::assertSame($expectedCode, $code);
    }

    public static function participationTypeProvider(): array
    {
        return [
            'public'      => [ParticipationType::PUBLIC, 'public-sub'],
            'institution' => [ParticipationType::INSTITUTION, 'institution-sub'],
        ];
    }

    public function testSubPhaseCodeByProcedureIdFallsBackToConfiguredCodeWithoutCockpitRecord(): void
    {
        $this->mockCockpitLookup(null);

        $code = $this->createSut('configured')
            ->getExternalProcedureSubPhaseCodeByProcedureId(self::PROCEDURE_ID, ParticipationType::PUBLIC);

        self::assertSame('configured', $code);
    }

    public function testSubPhaseCodeByProcedureIdFallsBackWhenStoredCodeIsMissing(): void
    {
        $this->mockCockpitLookup(new XBeteiligungProcedurePhaseCockpit());

        $code = $this->createSut('configured')
            ->getExternalProcedureSubPhaseCodeByProcedureId(self::PROCEDURE_ID, ParticipationType::INSTITUTION);

        self::assertSame('configured', $code);
    }

    public function testSubPhaseCodeByProcedureIdUsesDefaultWithoutConfiguredCode(): void
    {
        $this->mockCockpitLookup(null);

        $code = $this->createSut('')
            ->getExternalProcedureSubPhaseCodeByProcedureId(self::PROCEDURE_ID, ParticipationType::PUBLIC);

        self::assertSame('invalid', $code);
    }

    private function mockCockpitLookup(?XBeteiligungProcedurePhaseCockpit $cockpit): void
    {
        /** @var XBeteiligungProcedurePhaseCockpitRepository&MockObject $repository */
        $repository = $this->repository;
        $repository->expects(self::once())
            ->method('findOneBy')
            ->with(['procedureId' => self::PROCEDURE_ID])
            ->willReturn($cockpit);
    }

    private function createSut(string $verfahrensteilschrittCode): ProcedurePhaseCodeDetector
    {
        return new ProcedurePhaseCodeDetector(
            $this->repository,
            new VerfasserBuilder(),
            new XBeteiligungConfiguration(
                false,
                10,
                0,
                'raumordnung',
                false,
                'bap',
                10,
                10,
                'Raumordnungsverfahren',
                '',
                $verfahrensteilschrittCode,
            ),
        );
    }
}
