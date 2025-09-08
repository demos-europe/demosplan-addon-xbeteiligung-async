<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CurrentUserProviderInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CustomerServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\OrgaServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureServiceStorageInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ProcedureTypeServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\TransactionServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\UserHandlerInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\ProcedurePhaseExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\KommunaleMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\PlanfeststellungMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\MessageFactory\RaumordnungMessageFactory;
use DemosEurope\DemosplanAddon\XBeteiligung\Configuration\XBeteiligungConfiguration;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeFehlerartType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\FehlerType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

abstract class ProcedureCommonFeatures
{
    public function __construct(
        protected readonly CurrentUserProviderInterface       $currentUserProvider,
        protected readonly CustomerServiceInterface           $customerService,
        protected readonly EntityManagerInterface             $entityManager,
        protected readonly KommunaleMessageFactory            $kommunaleMessageFactory,
        protected readonly LoggerInterface                    $logger,
        protected readonly PlanfeststellungMessageFactory     $planfeststellungMessageFactory,
        protected readonly ProcedurePhaseExtractor            $procedurePhaseExtractor,
        protected readonly ProcedureServiceInterface          $procedureService,
        protected readonly ProcedureServiceStorageInterface   $procedureServiceStorage,
        protected readonly ProcedureTypeServiceInterface      $procedureTypeService,
        protected readonly RaumordnungMessageFactory          $raumordnungMessageFactory,
        protected readonly TransactionServiceInterface        $transactionService,
        protected readonly TranslatorInterface                $translator,
        protected readonly UserHandlerInterface               $userHandler,
        protected readonly OrgaServiceInterface               $orgaService,
        protected readonly XBeteiligungAgsService             $agsService,
        protected readonly XBeteiligungCustomerMappingService $customerMappingService,
        protected readonly XBeteiligungMapService             $xbeteiligungMapService,
        protected readonly XBeteiligungConfiguration          $xbeteiligungConfiguration,
    )
    {
    }

    protected function setProcedurePhase(
        ProcedureInterface $procedure,
        ProcedurePhaseData $procedurePhaseData,
    ): void {
        if (null !== $procedurePhaseData->getPublicParticipationPhase()) {
            $procedure->setPublicParticipationPhase($procedurePhaseData->getPublicParticipationPhase()->getKey());
        }
        if (null !== $procedurePhaseData->getInstitutionParticipationPhase()) {
            $procedure->setPhase($procedurePhaseData->getInstitutionParticipationPhase()->getKey());
        }
        if (null !== $procedurePhaseData->getPublicParticipationStartDate()) {
            $procedure->setPublicParticipationStartDate($procedurePhaseData->getPublicParticipationStartDate());
        }
        if (null !== $procedurePhaseData->getPublicParticipationEndDate()) {
            $procedure->setPublicParticipationEndDate($procedurePhaseData->getPublicParticipationEndDate());
        }
        if (null !== $procedurePhaseData->getInstitutionParticipationStartDate()) {
            $procedure->setStartDate($procedurePhaseData->getInstitutionParticipationStartDate());
        }
        if (null !== $procedurePhaseData->getInstitutionParticipationEndDate()) {
            $procedure->setEndDate($procedurePhaseData->getInstitutionParticipationEndDate());
        }
        if (null !== $procedurePhaseData->getPublicParticipationIteration()) {
            $procedure->getPublicParticipationPhaseObject()->setIteration(
                $procedurePhaseData->getPublicParticipationIteration()
            );
        }
        if (null !== $procedurePhaseData->getInstitutionParticipationIteration()) {
            $procedure->getPhaseObject()->setIteration(
                $procedurePhaseData->getInstitutionParticipationIteration()
            );
        }
    }

    protected function getErrorType(string $errorCode, string $errorDescription): FehlerType
    {
        $errorCodeType = new CodeFehlerartType();
        $errorCodeType->setCode($errorCode);
        $errorType = new FehlerType();
        $errorType->setBeschreibung($errorDescription);
        $errorType->setArt($errorCodeType);

        return $errorType;
    }
}
