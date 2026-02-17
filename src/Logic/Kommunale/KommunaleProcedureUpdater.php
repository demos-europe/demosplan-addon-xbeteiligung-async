<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
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
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
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

            $procedureDataValueObject = $this->procedureDataExtractor->extract($message);

            $this->updateProcedureData($procedure, $procedureDataValueObject);
            $updatedProcedure = $this->saveProcedureWithTransaction($procedure);
            $this->procedurePhaseCodeDetector->storeExternalProcedurePhaseCodes(
                $updatedProcedure->getId(),
                $procedureDataValueObject);
            return $this->kommunaleMessageFactory->buildProcedureUpdateOKResponse412(
                $message,
                $updatedProcedure
            );
        } catch (XBeteiligungProcedureException $e) {
            $this->logger->error(self::PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION, [
                'message' => $e->getMessage()
            ]);
            return $this->buildErrorResponse($e->getErrorTypes(), $message);
        } catch (Exception $e) {
            $this->logger->error(self::PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION, [
                'errorMessage' => $e->getMessage()
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
        ProcedureDataValueObject $procedureDataValueObject
    ): void {

        $this->setProcedurePhase($procedure, $procedureDataValueObject->getProcedurePhaseData());

        // Update procedure name and external name
        $planName = $procedureDataValueObject->getPlanName();
        if (null !== $planName) {
            $procedure->setName($planName);
            $procedure->setExternalName($planName);
        }

        // Update procedure description and external description
        $description = $procedureDataValueObject->getPlanDescription() ?? '';
        $procedure->setDesc($description);
        $procedure->setExternalDesc($description);

        // Update map data (territory, bounding box, map extent)
        $mapData = $procedureDataValueObject->getMapData();
        if (null !== $mapData->getTerritory()) {
            $procedure->getSettings()->setTerritory($mapData->getTerritory());
        }
        if (null !== $mapData->getBbox()) {
            $procedure->getSettings()->setBoundingBox($mapData->getBbox());
        }
        if (null !== $mapData->getMapExtent()) {
            $procedure->getSettings()->setMapExtent($mapData->getMapExtent());
        }

        // Update GIS layers
        $flaechenabgrenzungsUrl = $mapData->getFlaechenabgrenzungsUrl();
        if (null !== $flaechenabgrenzungsUrl) {
            $this->gisLayerManager->processUrl($flaechenabgrenzungsUrl, $procedure);
        }

        // Process attachment updates (replaces existing files based on dokumentId)
        $anlagen = $procedureDataValueObject->getAnlagen();
        if (!empty($anlagen)) {
            $this->xbeteiligungAttachmentService->saveOrUpdateAnlagenToProcedureCategories(
                $procedure,
                $anlagen
            );
        }
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
