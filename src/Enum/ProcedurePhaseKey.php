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

enum ProcedurePhaseKey : string
{
    case CONFIGURATION = 'configuration';
    case EARLY_PARTICIPATION = 'earlyparticipation';
    case PARTICIPATION = 'participation';
    case ANOTHER_PARTICIPATION = 'anotherparticipation';
    case EVALUATING = 'evaluating';
    case CLOSED = 'closed';
}
