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

enum InstitutionParticipationPhase
{
    case CONFIGURATION;
    case EARLY_PARTICIPATION;
    case PARTICIPATION;
    case ANOTHER_PARTICIPATION;
    case EVALUATING;
    case CLOSED;

    public static function fromKey(string $key): ?self {
        return match($key) {
            'configuration' => self::CONFIGURATION,
            'earlyparticipation' => self::EARLY_PARTICIPATION,
            'participation' => self::PARTICIPATION,
            'anotherparticipation' => self::ANOTHER_PARTICIPATION,
            'evaluating' => self::EVALUATING,
            'closed' => self::CLOSED,
            default => null,
        };
    }

    public function getCode(): string {
        return match($this) {
            self::CONFIGURATION => '1000',
            self::EARLY_PARTICIPATION => '2000',
            self::PARTICIPATION => '5000',
            self::ANOTHER_PARTICIPATION => '5000',
            self::EVALUATING => '7000',
            self::CLOSED => '8000',
        };
    }

    public function getName(): string {
        return match($this) {
            self::CONFIGURATION => 'Einleitungsphase',
            self::EARLY_PARTICIPATION => 'Frühzeitige Behördenbeteiligung',
            self::PARTICIPATION, self::ANOTHER_PARTICIPATION => 'Beteiligung der Träger öffentlicher Belange',
            self::EVALUATING => 'Feststellungsverfahren',
            self::CLOSED => 'Schlussphase',
        };
    }
}
