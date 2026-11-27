<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Api\ProcedurePlanId;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Lets planners link a manually created procedure to an existing DiPlanCockpit procedure by its planID.
 *
 * The planID is stored in the existing core property {@see ProcedureInterface::getXtaPlanId()}, which
 * the XBeteiligung messages already send as `planID`.
 */
#[ApiResource(
    shortName: 'XBeteiligungProcedurePlanId',
    operations: [
        new Get(uriTemplate: self::ITEM_URI_TEMPLATE),
        new Patch(uriTemplate: self::ITEM_URI_TEMPLATE, processor: ProcedurePlanIdProcessor::class),
    ],
    formats: ['jsonapi'],
    routePrefix: '/3.0', // Same prefix as core's ApiPlatformConstants::ROUTE_PREFIX_V3
    provider: ProcedurePlanIdProvider::class,
)]
class ProcedurePlanIdResource
{
    private const ITEM_URI_TEMPLATE = '/XBeteiligungProcedurePlanId/{id}';

    /**
     * The id of the procedure this planID belongs to.
     */
    #[ApiProperty(readable: false, identifier: true)]
    public string $id = '';

    /**
     * An empty string removes the link to a Cockpit procedure.
     */
    #[ApiProperty(readable: true, writable: true)]
    #[Assert\NotNull(message: 'The planID may not be null.')]
    #[Assert\Length(max: 255, maxMessage: 'The planID may not exceed {{ limit }} characters.')]
    public ?string $xtaPlanId = null;

    public static function fromProcedure(ProcedureInterface $procedure): self
    {
        $resource = new self();
        $resource->id = $procedure->getId();
        $resource->xtaPlanId = $procedure->getXtaPlanId();

        return $resource;
    }
}
