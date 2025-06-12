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
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ProcedurePhaseKey;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\PublicParticipationPhase;
use PHPUnit\Framework\TestCase;

class ProcedurePhaseEnumTest extends TestCase
{
    /**
     * @dataProvider publicKeyToParticipationPhaseProvider
     */
    public function testFromKeyReturnsCorrectEnumForPublicParticipationPhase(
        string $key,
        PublicParticipationPhase $expectedPhase
    ): void {
        self::assertSame($expectedPhase, PublicParticipationPhase::fromKey($key));
    }

    /**
     * @dataProvider institutionKeyToParticipationPhaseProvider
     */
    public function testFromKeyReturnsCorrectEnumForInstitutionParticipationPhase(
        string $key,
        InstitutionParticipationPhase $expectedPhase
    ): void {
        self::assertSame($expectedPhase, InstitutionParticipationPhase::fromKey($key));
    }

    public function testFromKeyReturnsNullForInvalidKey(): void
    {
        self::assertNull(PublicParticipationPhase::fromKey('invalidkey'));
        self::assertNull(PublicParticipationPhase::fromKey(''));
    }

    /**
     * @dataProvider publicParticipationPhaseCodeToKeyProvider
     */
    public function testGetKeyFromCodeReturnsCorrectKey(string $expectedKey, string $phaseCode): void
    {
        self::assertSame($expectedKey, PublicParticipationPhase::fromCode($phaseCode)->getKey());
    }

    /**
     * @dataProvider institutionParticipationPhaseCodeToKeyProvider
     */
    public function testGetKeyFromCodeReturnsCorrectKeyForInstitution(string $expectedKey, string $phaseCode): void
    {
        self::assertSame($expectedKey, InstitutionParticipationPhase::fromCode($phaseCode)->getKey());
    }

    public function testGetKeyFromCodeReturnsNullForUnknownCode(): void
    {
        self::assertNull(PublicParticipationPhase::fromCode('unknowncode'));
        self::assertNull(InstitutionParticipationPhase::fromCode(''));
    }

    /**
     * @return array<int, array<string, string>>
     */
    public static function publicParticipationPhaseCodeToKeyProvider(): array
    {
        return [
            [ProcedurePhaseKey::CONFIGURATION->value, PublicParticipationPhase::CONFIGURATION->getCode()],
            [ProcedurePhaseKey::EARLY_PARTICIPATION->value, PublicParticipationPhase::EARLY_PARTICIPATION->getCode()],
            [ProcedurePhaseKey::PARTICIPATION->value, PublicParticipationPhase::PARTICIPATION->getCode()],
            [ProcedurePhaseKey::EVALUATING->value, PublicParticipationPhase::EVALUATING->getCode()],
            [ProcedurePhaseKey::CLOSED->value, PublicParticipationPhase::CLOSED->getCode()],
        ];
    }

    /**
     * @return array<int, array<string, string>>
     */
    public static function institutionParticipationPhaseCodeToKeyProvider(): array
    {
        return [
            [ProcedurePhaseKey::CONFIGURATION->value, InstitutionParticipationPhase::CONFIGURATION->getCode()],
            [ProcedurePhaseKey::EARLY_PARTICIPATION->value, InstitutionParticipationPhase::EARLY_PARTICIPATION->getCode()],
            [ProcedurePhaseKey::PARTICIPATION->value, InstitutionParticipationPhase::PARTICIPATION->getCode()],
            [ProcedurePhaseKey::EVALUATING->value, InstitutionParticipationPhase::EVALUATING->getCode()],
            [ProcedurePhaseKey::CLOSED->value, InstitutionParticipationPhase::CLOSED->getCode()],
        ];
    }

    /**
     * @return array<array{string, PublicParticipationPhase}>
     */
    public static function publicKeyToParticipationPhaseProvider(): array
    {
        return [
            [ProcedurePhaseKey::CONFIGURATION->value, PublicParticipationPhase::CONFIGURATION],
            [ProcedurePhaseKey::EARLY_PARTICIPATION->value, PublicParticipationPhase::EARLY_PARTICIPATION],
            [ProcedurePhaseKey::PARTICIPATION->value, PublicParticipationPhase::PARTICIPATION],
            [ProcedurePhaseKey::ANOTHER_PARTICIPATION->value, PublicParticipationPhase::ANOTHER_PARTICIPATION],
            [ProcedurePhaseKey::EVALUATING->value, PublicParticipationPhase::EVALUATING],
            [ProcedurePhaseKey::CLOSED->value, PublicParticipationPhase::CLOSED],
        ];
    }

    /**
     * @return array<array{string, InstitutionParticipationPhase}>
     */
    public static function institutionKeyToParticipationPhaseProvider(): array
    {
        return [
            [ProcedurePhaseKey::CONFIGURATION->value, InstitutionParticipationPhase::CONFIGURATION],
            [ProcedurePhaseKey::EARLY_PARTICIPATION->value, InstitutionParticipationPhase::EARLY_PARTICIPATION],
            [ProcedurePhaseKey::PARTICIPATION->value, InstitutionParticipationPhase::PARTICIPATION],
            [ProcedurePhaseKey::ANOTHER_PARTICIPATION->value, InstitutionParticipationPhase::ANOTHER_PARTICIPATION],
            [ProcedurePhaseKey::EVALUATING->value, InstitutionParticipationPhase::EVALUATING],
            [ProcedurePhaseKey::CLOSED->value, InstitutionParticipationPhase::CLOSED],
        ];
    }
}
