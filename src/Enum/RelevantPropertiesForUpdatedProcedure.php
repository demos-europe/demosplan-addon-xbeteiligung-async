<?php
/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Enum;

enum RelevantPropertiesForUpdatedProcedure: string
{
    case Name = 'name';
    case Orga = 'orga';
    case Desc = 'desc';
    case StartDate = 'startDate';
    case EndDate = 'endDate';
    case BoundingBox = 'boundingBox';
}
