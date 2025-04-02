<?php /** @noinspection PhpUnitMissingTargetForTestInspection */

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\Enum;


use DemosEurope\DemosplanAddon\XBeteiligung\Enum\InstitutionParticipationPhase;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\PublicParticipationPhase;
use PHPUnit\Framework\TestCase;

class ProcedurePhaseEnumTest extends TestCase
{
    public function testFromKeyReturnsCorrectEnum(): void
    {
        $this->assertionsForPublicParticipationPhase();
        $this->assertionsForInstitutionParticipationPhase();
    }

    public function testFromKeyReturnsNullForInvalidKey(): void
    {
        self::assertNull(PublicParticipationPhase::fromKey('invalidkey'));
        self::assertNull(PublicParticipationPhase::fromKey(''));
    }

    private function assertionsForPublicParticipationPhase(): void
    {
        self::assertSame(
            PublicParticipationPhase::CONFIGURATION,
            PublicParticipationPhase::fromKey('configuration')
        );

        self::assertSame(
            PublicParticipationPhase::EARLY_PARTICIPATION,
            PublicParticipationPhase::fromKey('earlyparticipation')
        );

        self::assertSame(
            PublicParticipationPhase::PARTICIPATION,
            PublicParticipationPhase::fromKey('participation')
        );

        self::assertSame(
            PublicParticipationPhase::ANOTHER_PARTICIPATION,
            PublicParticipationPhase::fromKey('anotherparticipation')
        );

        self::assertSame(
            PublicParticipationPhase::EVALUATING,
            PublicParticipationPhase::fromKey('evaluating')
        );

        self::assertSame(
            PublicParticipationPhase::CLOSED,
            PublicParticipationPhase::fromKey('closed')
        );
    }

    private function assertionsForInstitutionParticipationPhase(): void
    {
        self::assertSame(
            InstitutionParticipationPhase::CONFIGURATION,
            InstitutionParticipationPhase::fromKey('configuration')
        );

        self::assertSame(
            InstitutionParticipationPhase::EARLY_PARTICIPATION,
            InstitutionParticipationPhase::fromKey('earlyparticipation')
        );

        self::assertSame(
            InstitutionParticipationPhase::PARTICIPATION,
            InstitutionParticipationPhase::fromKey('participation')
        );

        self::assertSame(
            InstitutionParticipationPhase::ANOTHER_PARTICIPATION,
            InstitutionParticipationPhase::fromKey('anotherparticipation')
        );

        self::assertSame(
            InstitutionParticipationPhase::EVALUATING,
            InstitutionParticipationPhase::fromKey('evaluating')
        );

        self::assertSame(
            InstitutionParticipationPhase::CLOSED,
            InstitutionParticipationPhase::fromKey('closed')
        );
    }
}
