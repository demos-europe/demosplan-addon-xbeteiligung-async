<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Api\DcatApPluStandardCode;


use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use DemosEurope\DemosplanAddon\XBeteiligung\Api\DcatApPluStandardCode\DcatApPluStandardCodeProvider;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungDcatApPluStandardCode;

#[ApiResource(
    shortName: 'DcatApPluStandardCode',
    operations: [
        new GetCollection(uriTemplate: '/DcatApPluStandardCode', paginationEnabled: false),
        new Get(uriTemplate: '/DcatApPluStandardCode/{id}'),
    ],
    formats: ['jsonapi'],
    routePrefix: '/3.0',
    provider: DcatApPluStandardCodeProvider::class,
)]
class DcatApPluStandardCodeResource
{
    #[ApiProperty(readable: false, identifier: true)]
    public string $id = '';

    #[ApiProperty(readable: true, writable: false)]
    public string $code = '';

    #[ApiProperty(readable: true, writable: false)]
    public string $description = '';

    #[ApiProperty(readable: true, writable: false)]
    public int $sortOrder = 0;

    public static function fromEntity(XBeteiligungDcatApPluStandardCode $entity): self
    {
        $resource = new self();
        $resource->id = $entity->getId();
        $resource->code = $entity->getCode();
        $resource->description = $entity->getDescription();
        $resource->sortOrder = $entity->getSortOrder();

        return $resource;
    }
}
