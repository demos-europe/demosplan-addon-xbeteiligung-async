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
    public function updateProcedure(
        KommunalAktualisieren0402 $kommunalAktualisieren0402
    ): ResponseValue {
        try {
            $errorTypes = [];

            // Get BeteiligungKommunalType from the message
            $beteiligungKommunalType = $kommunalAktualisieren0402->getNachrichteninhalt()?->getBeteiligung();
            if (null === $beteiligungKommunalType) {
                $errorTypes[] = $this->getErrorType(
                    XBeteiligungService::GENERIC_ERROR_CODE,
                    self::MISSING_PARTICIPATION_INFO_ERROR_DESCRIPTION
                );
                return $this->kommunaleMessageFactory->buildProcedureUpdateErrorResponse422(
                    $errorTypes,
                    $kommunalAktualisieren0402
                );
            }
            // Get the procedure to update
            $procedureToUpdate = $this->getProcedure($beteiligungKommunalType);
            if (null === $procedureToUpdate) {
                $errorTypes[] = $this->getErrorType(
                    XBeteiligungService::GENERIC_ERROR_CODE,
                    self::PROCEDURE_NOT_FOUND_ERROR_DESCRIPTION
                );

                return $this->kommunaleMessageFactory->buildProcedureUpdateErrorResponse422(
                    $errorTypes,
                    $kommunalAktualisieren0402
                );
            }

            // Update procedure phases
            $procedurePhaseData = $this->procedurePhaseExtractor->extract($beteiligungKommunalType);
            $this->setProcedurePhase($procedureToUpdate, $procedurePhaseData);
            // Update procedure description and external description
            $description = $beteiligungKommunalType->getBeschreibungPlanungsanlass() ?? '';
            $procedureToUpdate->setDesc($description);
            $procedureToUpdate->setExternalDesc($description);
            // Update procedure documents will implemented later

            $connection = $this->entityManager->getConnection();
            $connection->beginTransaction();
            $procedureUpdated = $this->procedureService->updateProcedureObject($procedureToUpdate);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            $errorTypes[] = $this->getErrorType(
                XBeteiligungService::GENERIC_ERROR_CODE,
                self::PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION
            );
            $this->logger->error(self::PROCEDURE_UPDATE_FAILED_ERROR_DESCRIPTION,['errorMessage' => $e->getMessage()]);
            return $this->kommunaleMessageFactory->buildProcedureUpdateErrorResponse422(
                $errorTypes,
                $kommunalAktualisieren0402
            );
        }

        // create OK message for procedure update
        return $this->kommunaleMessageFactory->buildProcedureUpdateOKResponse412(
            $kommunalAktualisieren0402,
            $procedureUpdated
        );
    }

    private function getProcedure(
        BeteiligungKommunalType $beteiligungKommunalType,
    ): ?ProcedureInterface {
        $procedureToUpdate = $this->procedureService->getProcedure(
            $beteiligungKommunalType->getBeteiligungOeffentlichkeit()?->getBeteiligungsID()
        );
        if (null === $procedureToUpdate) {
            $procedureToUpdate = $this->procedureService->getProcedure(
                $beteiligungKommunalType->getBeteiligungTOEB()?->getBeteiligungsID()
            );
        }

        return $procedureToUpdate;
    }
}
