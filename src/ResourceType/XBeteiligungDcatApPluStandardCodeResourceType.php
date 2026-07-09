<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\ResourceType;

use DemosEurope\DemosplanAddon\Contracts\ResourceType\AddonResourceType;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungDcatApPluStandardCode;
use EDT\DqlQuerying\Contracts\ClauseFunctionInterface;
use EDT\DqlQuerying\Contracts\OrderBySortMethodInterface;
use EDT\JsonApi\ResourceConfig\Builder\ResourceConfigBuilderInterface;

/**
 * Read-only: the DCAT-AP-PLU codelist is fixed and seeded via migration, not admin-editable.
 *
 * @template-extends AddonResourceType<XBeteiligungDcatApPluStandardCode>
 */
class XBeteiligungDcatApPluStandardCodeResourceType extends AddonResourceType
{
    public function __construct(
        private readonly PermissionEvaluatorInterface $permissionEvaluator,
    ) {
    }

    public function getTypeName(): string
    {
        return 'XBeteiligungDcatApPluStandardCode';
    }

    public function getEntityClass(): string
    {
        return XBeteiligungDcatApPluStandardCode::class;
    }

    public function isAvailable(): bool
    {
        return $this->permissionEvaluator->isPermissionEnabled('area_customer_procedure_phase_definitions');
    }

    protected function getAccessConditions(): array
    {
        return [];
    }

    /**
     * @return ResourceConfigBuilderInterface<ClauseFunctionInterface<bool>, OrderBySortMethodInterface, XBeteiligungDcatApPluStandardCode>
     */
    protected function getProperties(): ResourceConfigBuilderInterface
    {
        $configBuilder = new XBeteiligungDcatApPluStandardCodeResourceConfigBuilder(
            $this->getEntityClass(),
            $this->getPropertyBuilderFactory()
        );

        $configBuilder->id
            ->setReadableByPath()
            ->setFilterable();

        $configBuilder->code
            ->setReadableByPath()
            ->setFilterable();

        $configBuilder->description
            ->setReadableByPath();

        return $configBuilder;
    }

    public function isGetAllowed(): bool
    {
        return $this->isAvailable();
    }

    public function isListAllowed(): bool
    {
        return $this->isAvailable();
    }

    public function isCreateAllowed(): bool
    {
        return false;
    }

    public function isUpdateAllowed(): bool
    {
        return false;
    }

    public function isDeleteAllowed(): bool
    {
        return false;
    }
}
