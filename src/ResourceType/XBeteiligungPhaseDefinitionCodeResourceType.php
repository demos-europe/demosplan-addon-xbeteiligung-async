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

use DemosEurope\DemosplanAddon\Contracts\CurrentContextProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\ResourceType\AddonResourceType;
use DemosEurope\DemosplanAddon\Contracts\ResourceType\ProcedurePhaseDefinitionResourceTypeInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungPhaseDefinitionCode;
use EDT\ConditionFactory\ConditionFactoryInterface;
use EDT\DqlQuerying\Contracts\ClauseFunctionInterface;
use EDT\DqlQuerying\Contracts\OrderBySortMethodInterface;
use EDT\JsonApi\ResourceConfig\Builder\ResourceConfigBuilderInterface;
use EDT\Wrapping\EntityDataInterface;
use EDT\Wrapping\PropertyBehavior\FixedSetBehavior;
use Webmozart\Assert\Assert;

/**
 * @template-extends AddonResourceType<XBeteiligungPhaseDefinitionCode>
 */
class XBeteiligungPhaseDefinitionCodeResourceType extends AddonResourceType
{
    /**
     * @param ConditionFactoryInterface<ClauseFunctionInterface<bool>> $conditionFactory
     */
    public function __construct(
        private readonly PermissionEvaluatorInterface $permissionEvaluator,
        private readonly ConditionFactoryInterface $conditionFactory,
        private readonly CurrentContextProviderInterface $currentContextProvider,
        private readonly ProcedurePhaseDefinitionResourceTypeInterface $phaseDefinitionResourceType,
    ) {
    }

    public function getTypeName(): string
    {
        return 'XBeteiligungPhaseDefinitionCode';
    }

    public function getEntityClass(): string
    {
        return XBeteiligungPhaseDefinitionCode::class;
    }

    public function isAvailable(): bool
    {
        return $this->permissionEvaluator->isPermissionEnabled('area_customer_procedure_phase_definitions');
    }

    protected function getAccessConditions(): array
    {
        $customerId = $this->currentContextProvider->getCurrentCustomer()->getId();
        Assert::stringNotEmpty($customerId);

        return [
            $this->conditionFactory->propertyHasValue($customerId, ['phaseDefinition', 'customer', 'id']),
        ];
    }

    /**
     * @return ResourceConfigBuilderInterface<ClauseFunctionInterface<bool>, OrderBySortMethodInterface, XBeteiligungPhaseDefinitionCode>
     */
    protected function getProperties(): ResourceConfigBuilderInterface
    {
        $configBuilder = new XBeteiligungPhaseDefinitionCodeResourceConfigBuilder(
            $this->getEntityClass(),
            $this->getPropertyBuilderFactory()
        );

        $configBuilder->id
            ->setReadableByPath()
            ->setFilterable();

        $configBuilder->code
            ->setReadableByPath()
            ->setFilterable()
            ->addPathUpdateBehavior()
            ->addPathCreationBehavior();

        $configBuilder->phaseDefinition
            ->setRelationshipType($this->phaseDefinitionResourceType)
            ->setReadableByPath()
            ->setFilterable()
            ->addPathCreationBehavior();

        $configBuilder->addPostConstructorBehavior(
            new FixedSetBehavior(function (XBeteiligungPhaseDefinitionCode $entity, EntityDataInterface $entityData): array {
                $this->getEntityManager()->persist($entity);

                return [];
            })
        );

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
        return $this->isAvailable();
    }

    public function isUpdateAllowed(): bool
    {
        return $this->isAvailable();
    }

    public function isDeleteAllowed(): bool
    {
        return $this->isAvailable();
    }
}
