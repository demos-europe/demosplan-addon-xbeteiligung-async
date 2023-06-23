<?php

namespace DemosEurope\DemosplanAddon\XBeteiligung\ResourceTypes;

use DemosEurope\DemosplanAddon\Contracts\Config\GlobalConfigInterface;
use DemosEurope\DemosplanAddon\Contracts\CurrentContextProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\MessageBagInterface;
use DemosEurope\DemosplanAddon\Contracts\ResourceType\AddonResourceType;
use DemosEurope\DemosplanAddon\Contracts\ResourceType\ResourceTypeServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\ResourceType\UpdatableDqlResourceTypeInterface;
use DemosEurope\DemosplanAddon\Permission\PermissionEvaluatorInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\Permissions\Features;
use DemosEurope\DemosplanAddon\Logic\ResourceChange;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\ProcedureMessage;
use EDT\ConditionFactory\ConditionFactoryInterface;
use EDT\JsonApi\ResourceTypes\PropertyBuilder;
use EDT\PathBuilding\End;
use EDT\Querying\Contracts\PathsBasedInterface;
use EDT\Wrapping\Contracts\TypeProviderInterface;
use EDT\Wrapping\WrapperFactories\WrapperObjectFactory;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @property-read End $message
 * @property-read End $error
 * @property-read End $deleted
 * @property-read End $requestCount
 * @property-read End $createdAt
 * @property-read End $updateAt
 */
class ProcedureMessageResourceType extends AddonResourceType implements UpdatableDqlResourceTypeInterface
{
    public function __construct(
        PermissionEvaluatorInterface    $permissionEvaluator,
        TypeProviderInterface           $typeProvider,
        CurrentContextProviderInterface $currentContextProvider,
        GlobalConfigInterface           $globalConfig,
        LoggerInterface                 $logger,
        MessageBagInterface             $messageBag,
        ResourceTypeServiceInterface    $resourceTypeService,
        TranslatorInterface             $translator,
        ConditionFactoryInterface       $conditionFactory,
        WrapperObjectFactory            $wrapperFactory,
        ContainerInterface              $container
    ) {
        parent::__construct(
            $permissionEvaluator,
            $typeProvider,
            $currentContextProvider,
            $globalConfig,
            $logger,
            $messageBag,
            $resourceTypeService,
            $translator,
            $conditionFactory,
            $wrapperFactory,
            $container
        );
    }
    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProcedureMessage';
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return ProcedureMessage::class;
    }

    /**
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->permissionEvaluator->isPermissionEnabled(Features::feature_create_procedure_from_XBeteiligungMessage());
    }

    /**
     * @return bool
     */
    public function isDirectlyAccessible(): bool
    {
        return $this->isAvailable();
    }

    public function isExposedAsPrimaryResource(): bool
    {
        return $this->isAvailable();
    }

    /**
     * @return PathsBasedInterface
     */
    public function getAccessCondition(): PathsBasedInterface
    {
        return $this->conditionFactory->true();
    }

    /**
     * @return array|PropertyBuilder[]
     */
    protected function getProperties(): array
    {
        return [
            $this->createAttribute($this->id)->readable(true),
            $this->createAttribute($this->message)->readable()->filterable()->sortable(),
            $this->createAttribute($this->error)->filterable()->sortable(),
            $this->createAttribute($this->deleted)->filterable()->sortable(),
            $this->createAttribute($this->requestCount)->filterable()->sortable(),
            $this->createAttribute($this->createdAt)->readable()->filterable()->sortable(),
            $this->createAttribute($this->updateAt)->readable()->filterable()->sortable()
        ];
    }


    /**
     * @param object $object
     * @param array $properties
     * @return ResourceChange
     */
    public function updateObject(object $object, array $properties): ResourceChange
    {
        $resourceChange = new ResourceChange($object, $this, $properties);
        $resourceChange->addEntityToPersist($object);
        $this->resourceTypeService->updateObjectNaive($object, $properties);
        $this->resourceTypeService->validateObject($object);

        return $resourceChange;
    }

    /**
     * @param ProcedureMessage $updateTarget
     * @return array|null[]
     */
    public function getUpdatableProperties(object $updateTarget): array
    {
        return $this->toProperties(
            $this->deleted,
            $this->error
        );
    }

}
