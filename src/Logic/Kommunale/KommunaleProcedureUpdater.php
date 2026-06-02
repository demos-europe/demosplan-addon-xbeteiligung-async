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
use Throwable;

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
        // Kept available for the catch blocks so a NOK can carry the resolved
        // procedure's id as beteiligungsID even when the subsequent update fails.
        $procedureId = null;
        try {
            $beteiligungKommunal = $this->validateAndExtractBeteiligung($message);
            $procedure = $this->findProcedureToUpdate($beteiligungKommunal);
            $procedureId = $procedure->getId();

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
            return $this->buildErrorResponse($e->getErrorTypes(), $message, $procedureId);
        } catch (Throwable $e) {
            // Catch both Exception and Error (including TypeError, ArgumentCountError, etc.)
            $this->logger->error(self::PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION, [
                'errorMessage' => $e->getMessage(),
                'errorClass' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->buildGenericErrorResponse($e->getMessage(), $message, $procedureId);
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
        $procedure = $this->resolveByBeteiligungsId($beteiligungKommunal)
            ?? $this->resolveByPlanId($beteiligungKommunal);

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

    /**
     * Resolves the procedure via the beteiligungsID carried by either participation
     * block. An empty string in one block must not block the lookup in the other,
     * which is why each id is validated before it reaches the service (a blank id
     * would otherwise trigger a Doctrine "identifier is missing" error).
     */
    private function resolveByBeteiligungsId(BeteiligungKommunalType $beteiligungKommunal): ?ProcedureInterface
    {
        $participants = [
            $beteiligungKommunal->getBeteiligungOeffentlichkeit(),
            $beteiligungKommunal->getBeteiligungTOEB(),
        ];

        foreach ($participants as $participant) {
            $beteiligungsId = $participant?->getBeteiligungsID();
            if (null === $beteiligungsId || '' === trim($beteiligungsId)) {
                continue;
            }

            $procedure = $this->procedureService->getProcedure(trim($beteiligungsId));
            if (null !== $procedure) {
                return $procedure;
            }
        }

        return null;
    }

    /**
     * Fallback used when the 0402 carries no beteiligungsID (XSD-allowed): the planID
     * is mandatory in the 0402 and lets us resolve the procedure created during the 0401.
     */
    private function resolveByPlanId(BeteiligungKommunalType $beteiligungKommunal): ?ProcedureInterface
    {
        $planId = $beteiligungKommunal->getPlanID();
        if (null === $planId || '' === trim($planId)) {
            return null;
        }
        $planId = trim($planId);

        // Primary: dedicated XBeteiligung cockpit mapping, written on 0401 ingress.
        $procedureId = $this->procedurePhaseCodeDetector->findProcedureIdByPlanId($planId);
        if (null !== $procedureId) {
            $procedure = $this->procedureService->getProcedure($procedureId);
            if (null !== $procedure) {
                return $procedure;
            }
        }

        // Fallback: planID persisted directly on the procedure (extern_id). Filled in the
        // same transaction as the 0401 procedure creation, so it survives even when the
        // subsequent cockpit-mapping write failed.
        return $this->entityManager
            ->getRepository(ProcedureInterface::class)
            ->findOneBy(['xtaPlanId' => $planId, 'deleted' => false]);
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
            $this->xbeteiligungAttachmentService->applyAnlagenToProcedure(
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

    private function buildErrorResponse(
        array $errorTypes,
        KommunalAktualisieren0402 $message,
        ?string $procedureId = null
    ): ResponseValue {
        return $this->kommunaleMessageFactory->buildProcedureUpdateErrorResponse422(
            $errorTypes,
            $message,
            $procedureId
        );
    }

    private function buildGenericErrorResponse(
        string $errorMessage,
        KommunalAktualisieren0402 $message,
        ?string $procedureId = null
    ): ResponseValue {
        $errorTypes = [$this->getErrorType(
            XBeteiligungService::GENERIC_ERROR_CODE,
            $errorMessage
        )];

        return $this->buildErrorResponse($errorTypes, $message, $procedureId);
    }
}
