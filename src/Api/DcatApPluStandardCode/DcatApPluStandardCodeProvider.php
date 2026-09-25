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


use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungDcatApPluStandardCode;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungDcatApPluStandardCodeRepository;
use EDT\DqlQuerying\Contracts\OrderBySortMethodInterface;
use EDT\DqlQuerying\SortMethodFactories\SortMethodFactory;
use EDT\Querying\Contracts\PathException;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Webmozart\Assert\Assert;

class DcatApPluStandardCodeProvider implements ProviderInterface
{
    public function __construct(
        private readonly SortMethodFactory $sortMethodFactory,
        private readonly DcatApPluStandardCodeAccessChecker $accessChecker,
        private readonly XBeteiligungDcatApPluStandardCodeRepository $repository,
    ) {
    }

    /**
     * @throws PathException
     */
    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        Assert::same($operation->getClass(), DcatApPluStandardCodeResource::class);

        if (!$this->accessChecker->isAvailable()) {
            throw new AccessDeniedHttpException(
                sprintf(
                    'Access denied: insufficient permissions to access %s',
                    $operation->getShortName()
                )
            );
        }

        if ($operation instanceof CollectionOperationInterface) {
            return $this->provideCollection(
                [
                    $this->sortMethodFactory->propertyAscending('sortOrder')
                ]
            );
        }

        if (isset($uriVariables['id'])) {
            return $this->provideSingle($uriVariables['id']);
        }

        return null;
    }

    private function provideSingle(string $id): ?DcatApPluStandardCodeResource
    {
        try {
            $dcatCode = $this->repository->getEntityByIdentifier(
                $id,
                $this->accessChecker->getAccessConditions(),
                ['id']
            );
        } catch (InvalidArgumentException) {
            return null;
        }

        return DcatApPluStandardCodeResource::fromEntity($dcatCode);
    }

    /**
     * @param list<OrderBySortMethodInterface> $sortMethods
     *
     * @return list<DcatApPluStandardCodeResource>
     */
    private function provideCollection(array $sortMethods): array
    {
        $dcatCode = $this->repository->getEntities(
            $this->accessChecker->getAccessConditions(),
            $sortMethods,
        );

        return array_map(
            static fn (XBeteiligungDcatApPluStandardCode $dcatCode): DcatApPluStandardCodeResource
            => DcatApPluStandardCodeResource::fromEntity($dcatCode),
            $dcatCode
        );
    }
}
