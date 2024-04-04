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
    case ExternalDesc = 'externalDesc';
    case StartDate = 'startDate';
    case EndDate = 'endDate';
    case PublicParticipationStartDate = 'publicParticipationStartDate';
    case PublicParticipationEndDate = 'publicParticipationEndDate';
    case PublicParticipationPhase = 'publicParticipationPhase';
    case Phase = 'phase';
    case BoundingBox = 'mapExtent'; // why mapExtend? see here: DPLAN-2012
    case Territory = 'territory'; // geoJson FG for Geltungsbereich
    case File = 'file';
    case CurrentSlug = 'currentSlug'; // public procedure url
    case Enabled = 'enabled'; // category (element) is enabled in procedure
    case NewSingleDocument = 'new_single_document';
    case DeleteSingleDocument = 'delete_single_document';
    case UpdateSingleDocument = 'update_single_document';
    //case NewParagraph = 'NewParagraph'; work in progress

    public static function propertyHasChanged(array $changeSet): bool
    {
        foreach ($changeSet as $propertyName => $propertyValue) {
            if (null !== self::tryFrom($propertyName)) {
                return true;
            }
        }

        return false;
    }
}
