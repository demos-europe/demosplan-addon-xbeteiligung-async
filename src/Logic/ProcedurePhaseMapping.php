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
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ProcedurePhaseKey;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\ProcedureMessageTyp;

/**
 * Historical XBeteiligung procedure phase mappings.
 *
 * These mappings were part of the enum-based system removed in v0.37 (DPLAN-16438).
 * Phase codes were last updated in v0.26 (DPLAN-16588).
 *
 * The mappings define how internal procedure phase keys map to XBeteiligung
 * Verfahrensschritt codes and human-readable names for different procedure types.
 *
 * Note: The XBeteiligung standard reuses some codes in different contexts
 * (e.g., '4000' is used for both Kommunal public and Raumordnung institution phases),
 * so we store code/name pairs directly rather than using a backed enum.
 */
class ProcedurePhaseMapping
{
    /**
     * Kommunal (Bauleitplanung) - Institution phases (TöB).
     *
     * Maps internal phase keys to XBeteiligung codes for agency/institution participation
     * in municipal planning procedures (Bauleitplanung).
     *
     * @var array<string, array{code: string, name: string}>
     */
    public const KOMMUNAL_INSTITUTION_PHASE_MAPPING = [
        ProcedurePhaseKey::CONFIGURATION->value => ['code' => '1000', 'name' => 'Einleitungsphase'],
        ProcedurePhaseKey::EARLY_PARTICIPATION->value => ['code' => '2000', 'name' => 'Frühzeitige Behördenbeteiligung'],
        ProcedurePhaseKey::PARTICIPATION->value => ['code' => '5000', 'name' => 'Beteiligung der Träger öffentlicher Belange'],
        ProcedurePhaseKey::ANOTHER_PARTICIPATION->value => ['code' => '5000', 'name' => 'Beteiligung der Träger öffentlicher Belange'],
        ProcedurePhaseKey::EVALUATING->value => ['code' => '7000', 'name' => 'Feststellungsverfahren'],
        ProcedurePhaseKey::CLOSED->value => ['code' => '8000', 'name' => 'Schlussphase'],
        ProcedurePhaseKey::POTENTIAL_ANALYSIS->value => ['code' => '2000', 'name' => 'Frühzeitige Behördenbeteiligung'],
        ProcedurePhaseKey::HEAT_PLANNING_DRAFT->value => ['code' => '5000', 'name' => 'Beteiligung der Träger öffentlicher Belange'],
    ];

    /**
     * Kommunal (Bauleitplanung) - Public phases (Öffentlichkeit).
     *
     * Maps internal phase keys to XBeteiligung codes for public participation
     * in municipal planning procedures (Bauleitplanung).
     *
     * @var array<string, array{code: string, name: string}>
     */
    public const KOMMUNAL_PUBLIC_PHASE_MAPPING = [
        ProcedurePhaseKey::CONFIGURATION->value => ['code' => '1000', 'name' => 'Einleitungsphase'],
        ProcedurePhaseKey::EARLY_PARTICIPATION->value => ['code' => '4000', 'name' => 'Frühzeitige Öffentlichkeitsbeteiligung'],
        ProcedurePhaseKey::PARTICIPATION->value => ['code' => '6000', 'name' => 'Digitale Veröffentlichung'],
        ProcedurePhaseKey::ANOTHER_PARTICIPATION->value => ['code' => '6000', 'name' => 'Digitale Veröffentlichung'],
        ProcedurePhaseKey::EVALUATING->value => ['code' => '7000', 'name' => 'Feststellungsverfahren'],
        ProcedurePhaseKey::CLOSED->value => ['code' => '8000', 'name' => 'Schlussphase'],
        ProcedurePhaseKey::POTENTIAL_ANALYSIS->value => ['code' => '4000', 'name' => 'Frühzeitige Öffentlichkeitsbeteiligung'],
        ProcedurePhaseKey::HEAT_PLANNING_DRAFT->value => ['code' => '6000', 'name' => 'Digitale Veröffentlichung'],
    ];

    /**
     * Raumordnung - Institution phases (TöB).
     *
     * Maps internal phase keys to XBeteiligung codes for agency/institution participation
     * in spatial planning procedures (Raumordnung).
     *
     * Updated in DPLAN-16588:
     * - participation code changed from 4300 to 4200
     * - earlyparticipation renamed to renewparticipation
     * - discussiondate phase added with code 4400
     *
     * @var array<string, array{code: string, name: string}>
     */
    public const RAUMORDNUNG_INSTITUTION_PHASE_MAPPING = [
        ProcedurePhaseKey::CONFIGURATION->value => ['code' => '4000', 'name' => 'Konfiguration TöB'],
        ProcedurePhaseKey::EARLY_PARTICIPATION->value => ['code' => '4100', 'name' => 'Frühzeitige Beteiligung TöB'],
        ProcedurePhaseKey::PARTICIPATION->value => ['code' => '4200', 'name' => 'Anhörung TöB'],
        ProcedurePhaseKey::RENEW_PARTICIPATION->value => ['code' => '4500', 'name' => 'Erneute Anhörung TöB (Durchlaufnummer)'],
        ProcedurePhaseKey::DISCUSSION_DATE->value => ['code' => '4400', 'name' => 'Erörterungstermin'],
        ProcedurePhaseKey::EVALUATING->value => ['code' => '4600', 'name' => 'Beschlussfassung TöB'],
        ProcedurePhaseKey::ANALYSIS->value => ['code' => '4600', 'name' => 'Beschlussfassung TöB'],
        ProcedurePhaseKey::CLOSED->value => ['code' => '4700', 'name' => 'Beschlussfassung TöB'],
    ];

    /**
     * Raumordnung - Public phases (Öffentlichkeit).
     *
     * Maps internal phase keys to XBeteiligung codes for public participation
     * in spatial planning procedures (Raumordnung).
     *
     * Updated in DPLAN-16588:
     * - participation code changed from 5300 to 5200
     * - discussiondate phase added with code 5400
     *
     * @var array<string, array{code: string, name: string}>
     */
    public const RAUMORDNUNG_PUBLIC_PHASE_MAPPING = [
        ProcedurePhaseKey::CONFIGURATION->value => ['code' => '5000', 'name' => 'Konfiguration betroffene Öffentlichkeit'],
        ProcedurePhaseKey::EARLY_PARTICIPATION->value => ['code' => '5100', 'name' => 'Ermittlung und Information Betroffener (durch Gemeinden)'],
        ProcedurePhaseKey::PARTICIPATION->value => ['code' => '5200', 'name' => 'Anhörung Betroffener (Öffentlichkeit)'],
        ProcedurePhaseKey::DISCUSSION_DATE->value => ['code' => '5400', 'name' => 'Erörterungstermin'],
        ProcedurePhaseKey::ANOTHER_PARTICIPATION->value => ['code' => '5500', 'name' => 'Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer)'],
        ProcedurePhaseKey::RENEW_PARTICIPATION->value => ['code' => '5500', 'name' => 'Erneute Anhörung Betroffener (Öffentlichkeit) (Durchlaufnummer)'],
        ProcedurePhaseKey::EVALUATING->value => ['code' => '5600', 'name' => 'Beschlussfassung betroffene Öffentlichkeit'],
        ProcedurePhaseKey::ANALYSIS->value => ['code' => '5600', 'name' => 'Beschlussfassung betroffene Öffentlichkeit'],
        ProcedurePhaseKey::CLOSED->value => ['code' => '5700', 'name' => 'Beschlussfassung betroffene Öffentlichkeit'],
    ];

    /**
     * Planfeststellung - Procedure phases.
     *
     * Maps internal phase keys to XBeteiligung codes for planning approval procedures
     * (Planfeststellung).
     *
     * Note: All phases currently map to code 9998 ("kein VS" - no procedure step defined)
     * as the XBeteiligung standard for Planfeststellung is not yet fully defined.
     * This is a temporary placeholder until proper codes are established.
     *
     * Planfeststellung does not distinguish between institution and public phases
     * in the current mapping.
     *
     * @var array<string, array{code: string, name: string}>
     */
    public const PLANFESTSTELLUNG_PHASE_MAPPING = [
        ProcedurePhaseKey::CONFIGURATION->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::EARLY->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::AFFECTED_MUNICIPALITIES->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::CONSULTATION->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::DISCUSSION_MEETING->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::RECONSULTATION->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::REPLAY_EVALUATING->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::EVALUATING->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::CLOSED->value => ['code' => '9998', 'name' => 'kein VS'],
        ProcedurePhaseKey::OTHER->value => ['code' => '9998', 'name' => 'kein VS'],
    ];

    /**
     * Get phase code for a given procedure type, participation type, and phase key.
     *
     * @param ProcedureMessageTyp $procedureType     Procedure type (kommunal, raumordnung, planfeststellung)
     * @param ParticipationType   $participationType Participation type (institution, public)
     * @param ProcedurePhaseKey   $phaseKey          Internal phase key
     *
     * @return string|null Phase code or null if not found
     */
    public static function getPhaseCode(
        ProcedureMessageTyp $procedureType,
        ParticipationType $participationType,
        ProcedurePhaseKey $phaseKey
    ): ?string {
        $mapping = self::getMapping($procedureType, $participationType);
        $phaseData = $mapping[$phaseKey->value] ?? null;

        return $phaseData['code'] ?? null;
    }

    /**
     * Get phase name for a given procedure type, participation type, and phase key.
     *
     * @param ProcedureMessageTyp $procedureType     Procedure type (kommunal, raumordnung, planfeststellung)
     * @param ParticipationType   $participationType Participation type (institution, public)
     * @param ProcedurePhaseKey   $phaseKey          Internal phase key
     *
     * @return string|null Phase name or null if not found
     */
    public static function getPhaseName(
        ProcedureMessageTyp $procedureType,
        ParticipationType $participationType,
        ProcedurePhaseKey $phaseKey
    ): ?string {
        $mapping = self::getMapping($procedureType, $participationType);
        $phaseData = $mapping[$phaseKey->value] ?? null;

        return $phaseData['name'] ?? null;
    }

    /**
     * Get the complete mapping for a given procedure type and participation type.
     *
     * @param ProcedureMessageTyp $procedureType     Procedure type (kommunal, raumordnung, planfeststellung)
     * @param ParticipationType   $participationType Participation type (institution, public)
     *
     * @return array<string, array{code: string, name: string}> Phase mapping array
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
