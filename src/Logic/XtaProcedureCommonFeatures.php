<?php

declare(strict_types=1);

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Contracts\Factory\BoilerplateCategoryFactoryInterface;
use DemosEurope\DemosplanAddon\Contracts\Factory\BoilerplateFactoryInterface;
use DemosEurope\DemosplanAddon\Contracts\FileServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Handler\ParagraphHandlerInterface;
use DemosEurope\DemosplanAddon\Contracts\Handler\SingleDocumentHandlerInterface;
use DemosEurope\DemosplanAddon\Contracts\PermissionsInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CurrentUserProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceStorageInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureTypeServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ServiceImporterInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\SingleDocumentServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\TransactionServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Translation\Translator;

abstract class XtaProcedureCommonFeatures
{
    public function __construct(
        protected readonly CurrentUserProviderInterface        $currentUserProvider,
        protected readonly FileServiceInterface                $fileService,
        protected readonly LoggerInterface                     $logger,
        protected readonly PermissionsInterface                $permissions,
        protected readonly ProcedureServiceInterface           $procedureService,
        protected readonly ProcedureServiceStorageInterface    $procedureServiceStorage,
        protected readonly ProcedureTypeServiceInterface       $procedureTypeService,
        protected readonly ServiceImporterInterface            $importService,
        protected readonly SingleDocumentHandlerInterface      $singleDocumentHandler,
        protected readonly SingleDocumentServiceInterface      $singleDocumentService,
        protected readonly TransactionServiceInterface         $transactionService,
        protected readonly UserHandlerInterface                $userHandler,
        protected readonly BoilerplateFactoryInterface         $boilerplateFactory,
        protected readonly BoilerplateCategoryFactoryInterface $boilerplateCategoryFactory,
        protected readonly ParagraphHandlerInterface           $paragraphHandler,
        protected readonly EntityManagerInterface              $entityManager,
        protected readonly XBeteiligungService                 $xBeteiligungService,
        protected readonly XBeteiligungResponseMessageFactory  $xtaBeteiligungMessageFactory,
        protected readonly Translator                          $translator,
    )
    {
    }
}