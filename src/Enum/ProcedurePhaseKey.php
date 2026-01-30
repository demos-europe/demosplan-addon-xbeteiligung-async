<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Enum;

/**
 * Internal procedure phase keys used in DemosPlan.
 *
 * These keys correspond to the phase keys defined in config/procedure/procedurephases.yml.
 */
enum ProcedurePhaseKey: string
{
    case CONFIGURATION = 'configuration';
    case EARLY_PARTICIPATION = 'earlyparticipation';
    case PARTICIPATION = 'participation';
    case ANOTHER_PARTICIPATION = 'anotherparticipation';
    case RENEW_PARTICIPATION = 'renewparticipation';
    case DISCUSSION_DATE = 'discussiondate';
    case EVALUATING = 'evaluating';
    case ANALYSIS = 'analysis';
    case CLOSED = 'closed';
    case AFFECTED_MUNICIPALITIES = 'affectedmunicipalities';
    case CONSULTATION = 'consultation';
    case DISCUSSION_MEETING = 'discussionmeeting';
    case RECONSULTATION = 'reconsultation';
    case REPLAY_EVALUATING = 'replayevaluating';
    case POTENTIAL_ANALYSIS = 'potentialanalysis';
    case HEAT_PLANNING_DRAFT = 'heatplanningdraft';
    case EARLY = 'early';
    case OTHER = 'other';
}
