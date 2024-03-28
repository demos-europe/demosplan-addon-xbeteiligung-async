<?php
/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Enum;

enum ProcedureMessageTyp: string
{
    case KOMMUNAL = 'Kommunal';
    case RAUMORDNUNG = 'Raumordnung';
    case PLANFESTSTELLUNG = 'Planfeststellung';
}
