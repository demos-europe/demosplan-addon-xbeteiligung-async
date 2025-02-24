<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Contracts\Services\CurrentUserProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceStorageInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureTypeServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\TransactionServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class ProcedureCommonFeatures
{
    public function __construct(
        protected readonly CurrentUserProviderInterface       $currentUserProvider,
        protected readonly LoggerInterface                    $logger,
        protected readonly ProcedureServiceInterface          $procedureService,
        protected readonly ProcedureServiceStorageInterface   $procedureServiceStorage,
        protected readonly ProcedureTypeServiceInterface      $procedureTypeService,
        protected readonly UserHandlerInterface               $userHandler,
        protected readonly EntityManagerInterface             $entityManager,
        protected readonly XBeteiligungResponseMessageFactory $xBeteiligungMessageFactory,
        protected readonly TranslatorInterface                $translator,
        protected readonly TransactionServiceInterface        $transactionService,
    )
    {
    }
}
