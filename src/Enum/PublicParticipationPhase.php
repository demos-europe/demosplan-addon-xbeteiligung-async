<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Enum;

enum PublicParticipationPhase
{
    case CONFIGURATION;
    case EARLY_PARTICIPATION;
    case PARTICIPATION;
    case ANOTHER_PARTICIPATION;
    case EVALUATING;
    case CLOSED;

    public static function fromKey(string $key): ?self {
        return match($key) {
            ProcedurePhaseKey::CONFIGURATION->value => self::CONFIGURATION,
            ProcedurePhaseKey::EARLY_PARTICIPATION->value => self::EARLY_PARTICIPATION,
            ProcedurePhaseKey::PARTICIPATION->value => self::PARTICIPATION,
            ProcedurePhaseKey::ANOTHER_PARTICIPATION->value => self::ANOTHER_PARTICIPATION,
            ProcedurePhaseKey::EVALUATING->value => self::EVALUATING,
            ProcedurePhaseKey::CLOSED->value => self::CLOSED,
            default => null,
        };
    }

    public static function fromCode(string $code): ?self {
        foreach (self::cases() as $case) {
            if ($case->getCode() === $code) {
                return match ($case) {
                    self::CONFIGURATION => self::CONFIGURATION,
                    self::EARLY_PARTICIPATION => self::EARLY_PARTICIPATION,
                    self::PARTICIPATION => self::PARTICIPATION,
                    self::ANOTHER_PARTICIPATION => self::ANOTHER_PARTICIPATION,
                    self::EVALUATING => self::EVALUATING,
                    self::CLOSED => self::CLOSED,
                };
            }
        }

        return null;
    }

    public function getCode(): string {
        return match($this) {
            self::CONFIGURATION => '1000',
            self::EARLY_PARTICIPATION => '4000',
            self::PARTICIPATION => '6000',
            self::ANOTHER_PARTICIPATION => '6000',
            self::EVALUATING => '7000',
            self::CLOSED => '8000',
        };
    }

    public function getName(): string {
        return match($this) {
            self::CONFIGURATION => 'Einleitungsphase',
            self::EARLY_PARTICIPATION => 'Frühzeitige Öffentlichkeitsbeteiligung',
            self::PARTICIPATION, self::ANOTHER_PARTICIPATION => 'Digitale Veröffentlichung',
            self::EVALUATING => 'Feststellungsverfahren',
            self::CLOSED => 'Schlussphase',
        };
    }

    public function getKey(): string {
        return match($this) {
            self::CONFIGURATION => ProcedurePhaseKey::CONFIGURATION->value,
            self::EARLY_PARTICIPATION => ProcedurePhaseKey::EARLY_PARTICIPATION->value,
            self::PARTICIPATION => ProcedurePhaseKey::PARTICIPATION->value,
            self::ANOTHER_PARTICIPATION => ProcedurePhaseKey::ANOTHER_PARTICIPATION->value,
            self::EVALUATING => ProcedurePhaseKey::EVALUATING->value,
            self::CLOSED => ProcedurePhaseKey::CLOSED->value,
        };
    }
}
