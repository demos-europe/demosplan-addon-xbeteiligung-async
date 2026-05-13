<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ParticipationType;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ProcedureMessageTyp;

/**
 * Hardcoded XBeteiligung procedure phase mappings.
 *
 * Maps DemoPlan procedure phase definition names to XBeteiligung Verfahrensschritt codes.
 *
 * The XBeteiligung standard reuses some codes in different contexts (e.g., '4000'
 * is used for both Kommunal public and Raumordnung institution phases), which is
 * why the mappings are split by procedure type and participation audience.
 *
 * Phases without a historical code mapping receive the placeholder code '0815'
 * (matching XBeteiligungService::PLACEHOLDER_PROCEDURE_PHASE_CODE).
 */
class ProcedurePhaseMapping
{
    /**
     * Kommunal (Bauleitplanung) - Institution phases (TöB).
     *
     * @var array<string, string>
     */
    public const KOMMUNAL_INSTITUTION_PHASE_MAPPING = [
        'Konfiguration TöB'                            => '1000',
        'Frühzeitige Beteiligung TöB - § 4 (1) BauGB'  => '2000',
        'Bestands- und Potentialanalyse'               => '2000',
        'Beteiligung TöB - § 4 (2) BauGB'              => '5000',
        'Erneute Beteiligung TöB - § 4a (3) BauGB'     => '5000',
        'Entwurf Wärmeplan'                            => '5000',
        'Auswertung TöB'                               => '7000',
        'Beschlussfassung TöB'                         => '8000',
    ];

    /**
     * Kommunal (Bauleitplanung) - Public phases (Öffentlichkeit).
     *
     * @var array<string, string>
     */
    public const KOMMUNAL_PUBLIC_PHASE_MAPPING = [
        'Konfiguration Öffentlichkeit'                            => '1000',
        'Frühzeitige Beteiligung Öffentlichkeit - § 3 (1) BauGB'  => '4000',
        'Bestands- und Potentialanalyse'                          => '4000',
        'Beteiligung Öffentlichkeit'                              => '6000',
        'Erneute Beteiligung Öffentlichkeit - § 4a (3) BauGB'     => '6000',
        'Entwurf Wärmeplan'                                       => '6000',
        'Auswertung Öffentlichkeit'                               => '7000',
        'Beschlussfassung Öffentlichkeit'                         => '8000',
    ];

    /**
     * Raumordnung - Institution phases (TöB).
     *
     * @var array<string, string>
     */
    public const RAUMORDNUNG_INSTITUTION_PHASE_MAPPING = [
        'Konfiguration'                                      => '4000',
        'Einsichtnahme'                                      => '0815',
        'Unterrichtung der öffentlichen Stellen'             => '4100',
        'Scoping bzw. Screening im Rahmen der Umweltprüfung' => '0815',
        'Beteiligung öffentliche Stellen'                    => '4200',
        'Erörterungstermin'                                  => '4400',
        'Erneute Beteiligung öffentliche Stellen'            => '4500',
        'Auswertung'                                         => '4600',
        'Abgeschlossen'                                      => '4700',
    ];

    /**
     * Raumordnung - Public phases (Öffentlichkeit).
     *
     * @var array<string, string>
     */
    public const RAUMORDNUNG_PUBLIC_PHASE_MAPPING = [
        'Konfiguration'                            => '5000',
        'Einsichtnahme'                            => '0815',
        'Unterrichtung der Öffentlichkeit'         => '5100',
        'Beteiligung Öffentlichkeit'               => '5200',
        'Erörterungstermin'                        => '5400',
        'Erneute Beteiligung Öffentlichkeit'       => '5500',
        'Auswertung'                               => '5600',
        'Abgeschlossen'                            => '5700',
    ];

    /**
     * Planfeststellung - All phases (institution and public combined).
     *
     * All phases currently map to code 9998 ("kein VS") as the XBeteiligung standard
     * for Planfeststellung is not yet fully defined.
     *
     * @var array<string, string>
     */
    public const PLANFESTSTELLUNG_PHASE_MAPPING = [
        'Konfiguration TöB'                                                => '9998',
        'Frühzeitige Beteiligung (TÖB)'                                    => '9998',
        'Ermittlung und Information Behörden und berührte Gemeinden'       => '9998',
        'Anhörung TöB'                                                     => '9998',
        'Erwiderung/Planänderung bzw. Auswertung'                          => '9998',
        'Erörterungstermin'                                                => '9998',
        'Erneute Anhörung TöB'                                             => '9998',
        'Auswertung TöB'                                                   => '9998',
        'Beschlussfassung TöB'                                             => '9998',
        'Sonstige Beteiligung (TÖB)'                                       => '9998',
        'Konfiguration betroffene Öffentlichkeit'                          => '9998',
        'Frühzeitige Beteiligung (Öffentlichkeit)'                         => '9998',
        'Ermittlung und Information Betroffene (durch Gemeinden)'          => '9998',
        'Anhörung Betroffener (Öffentlichkeit)'                            => '9998',
        'Erneute Anhörung Betroffener (Öffentlichkeit)'                    => '9998',
        'Auswertung betroffene Öffentlichkeit'                             => '9998',
        'Beschlussfassung betroffene Öffentlichkeit'                       => '9998',
        'Sonstige Beteiligung (Öffentlichkeit)'                            => '9998',
    ];

    /**
     * Get phase code for a given procedure type, participation type, and phase name.
     *
     * @param ProcedureMessageTyp $procedureType     Procedure type (kommunal, raumordnung, planfeststellung)
     * @param ParticipationType   $participationType Participation type (institution, public)
     * @param string|null         $phaseName         DemoPlan phase definition name (Klarname)
     *
     * @return string|null Phase code or null if not found
     */
    public static function getPhaseCode(
        ProcedureMessageTyp $procedureType,
        ParticipationType $participationType,
        ?string $phaseName
    ): ?string {
        if (null === $phaseName) {
            return null;
        }

        return self::getMapping($procedureType, $participationType)[$phaseName] ?? null;
    }

    /**
     * Get the complete mapping for a given procedure type and participation type.
     *
     * @return array<string, string>
     */
    private static function getMapping(ProcedureMessageTyp $procedureType, ParticipationType $participationType): array
    {
        return match ($procedureType) {
            ProcedureMessageTyp::KOMMUNAL => match ($participationType) {
                ParticipationType::INSTITUTION => self::KOMMUNAL_INSTITUTION_PHASE_MAPPING,
                ParticipationType::PUBLIC => self::KOMMUNAL_PUBLIC_PHASE_MAPPING,
            },
            ProcedureMessageTyp::RAUMORDNUNG => match ($participationType) {
                ParticipationType::INSTITUTION => self::RAUMORDNUNG_INSTITUTION_PHASE_MAPPING,
                ParticipationType::PUBLIC => self::RAUMORDNUNG_PUBLIC_PHASE_MAPPING,
            },
            ProcedureMessageTyp::PLANFESTSTELLUNG => self::PLANFESTSTELLUNG_PHASE_MAPPING,
        };
    }
}
