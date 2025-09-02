<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Exception\XBeteiligungProcedureException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ProcedureCommonFeatures;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungService;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;

class KommunaleProcedureUpdater extends ProcedureCommonFeatures
{
    private const MISSING_PARTICIPATION_INFO_ERROR_DESCRIPTION = 'Missing participation information in the message';
    private const PROCEDURE_NOT_FOUND_ERROR_DESCRIPTION = 'Procedure could not be found';
    private const PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION = 'Failed to update the procedure';
    /**
     * @throws SchemaException
     * @throws Exception
     */
    public function updateProcedure(KommunalAktualisieren0402 $message): ResponseValue
    {
        try {
            $beteiligungKommunal = $this->validateAndExtractBeteiligung($message);
            $procedure = $this->findProcedureToUpdate($beteiligungKommunal);
            $this->updateProcedureData($procedure, $beteiligungKommunal);
            $updatedProcedure = $this->saveProcedureWithTransaction($procedure);

            return $this->kommunaleMessageFactory->buildProcedureUpdateOKResponse412(
                $message,
                $updatedProcedure
            );
        } catch (XBeteiligungProcedureException $e) {
            $this->logger->error('XBeteiligung procedure update failed', [
                'message' => $e->getMessage(),
                'errorCount' => count($e->getErrorTypes())
            ]);
            return $this->buildErrorResponse($e->getErrorTypes(), $message);
        } catch (Exception $e) {
            $this->logger->error('Unexpected error during procedure update', [
                'errorMessage' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->buildGenericErrorResponse($e->getMessage(), $message);
        }
    }

    private function validateAndExtractBeteiligung(KommunalAktualisieren0402 $message): BeteiligungKommunalType
    {
        $beteiligungKommunal = $message->getNachrichteninhalt()?->getBeteiligung();
        
        if (null === $beteiligungKommunal) {
            throw new XBeteiligungProcedureException(
                [$this->getErrorType(
                    XBeteiligungService::GENERIC_ERROR_CODE,
                    self::MISSING_PARTICIPATION_INFO_ERROR_DESCRIPTION
                )],
                self::MISSING_PARTICIPATION_INFO_ERROR_DESCRIPTION
            );
        }

        return $beteiligungKommunal;
    }

    private function findProcedureToUpdate(BeteiligungKommunalType $beteiligungKommunal): ProcedureInterface
    {
        $procedure = $this->procedureService->getProcedure(
            $beteiligungKommunal->getBeteiligungOeffentlichkeit()?->getBeteiligungsID()
        );
        
        if (null === $procedure) {
            $procedure = $this->procedureService->getProcedure(
                $beteiligungKommunal->getBeteiligungTOEB()?->getBeteiligungsID()
            );
        }

        if (null === $procedure) {
            throw new XBeteiligungProcedureException(
                [$this->getErrorType(
                    XBeteiligungService::GENERIC_ERROR_CODE,
                    self::PROCEDURE_NOT_FOUND_ERROR_DESCRIPTION
                )],
                self::PROCEDURE_NOT_FOUND_ERROR_DESCRIPTION
            );
        }

        return $procedure;
    }

    private function updateProcedureData(
        ProcedureInterface $procedure,
        BeteiligungKommunalType $beteiligungKommunal
    ): void {
        // Update procedure phases
        $procedurePhaseData = $this->procedurePhaseExtractor->extract($beteiligungKommunal);
        $this->setProcedurePhase($procedure, $procedurePhaseData);
        
        // Update procedure description and external description
        $description = $beteiligungKommunal->getBeschreibungPlanungsanlass() ?? '';
        $procedure->setDesc($description);
        $procedure->setExternalDesc($description);
        
        // Update procedure documents will implemented later
    }

    private function saveProcedureWithTransaction(ProcedureInterface $procedure): ProcedureInterface
    {
        $connection = $this->entityManager->getConnection();
        
        try {
            $connection->beginTransaction();
            $updatedProcedure = $this->procedureService->updateProcedureObject($procedure);
            $connection->commit();
            
            return $updatedProcedure;
        } catch (Exception $e) {
            $connection->rollBack();
            
            throw new XBeteiligungProcedureException(
                [$this->getErrorType(
                    XBeteiligungService::GENERIC_ERROR_CODE,
                    self::PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION
                )],
                self::PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION,
                0,
                $e
            );
        }
    }

    private function buildErrorResponse(array $errorTypes, KommunalAktualisieren0402 $message): ResponseValue
    {
        return $this->kommunaleMessageFactory->buildProcedureUpdateErrorResponse422(
            $errorTypes,
            $message
        );
    }

    private function buildGenericErrorResponse(string $errorMessage, KommunalAktualisieren0402 $message): ResponseValue
    {
        $errorTypes = [$this->getErrorType(
            XBeteiligungService::GENERIC_ERROR_CODE,
            $errorMessage
        )];
        
        return $this->buildErrorResponse($errorTypes, $message);
    }
}
