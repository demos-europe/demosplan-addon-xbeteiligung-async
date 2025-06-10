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
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalAktualisieren0402;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;

class KommunaleProcedureUpdater extends ProcedureCommonFeatures
{
    /**
     * @throws SchemaException
     * @throws Exception
     */
    public function updateProcedure(KommunalAktualisieren0402 $kommunalAktualisieren0402): ResponseValue
    {
        $errorTypes = [];

        // Get BeteiligungKommunalType from the message
        $beteiligungKommunalType = $kommunalAktualisieren0402->getNachrichteninhalt()?->getBeteiligung();
        if (null === $beteiligungKommunalType) {
            return $this->kommunaleMessageFactory->buildProcedureUpdateErrorResponse422(
                $errorTypes,
                $kommunalAktualisieren0402
            );
        }
        // Get the procedure to update
        $procedureToUpdate = $this->getProcedure($beteiligungKommunalType);
        if (null === $procedureToUpdate) {
            return $this->kommunaleMessageFactory->buildProcedureUpdateErrorResponse422(
                $errorTypes,
                $kommunalAktualisieren0402
            );
        }

        // Update procedure with the data from BeteiligungKommunalType
        $procedureUpdated = $this->transactionService->executeAndFlushInTransaction(
            function () use ($beteiligungKommunalType, $procedureToUpdate) {
                // Update procedure phases
                $procedurePhaseData = $this->procedurePhaseExtractor->extract($beteiligungKommunalType);
                $this->setProcedurePhase($procedureToUpdate, $procedurePhaseData);
                // Update procedure description and external description
                $description = $beteiligungKommunalType->getBeschreibungPlanungsanlass() ?? '';
                $procedureToUpdate->setDesc($description);
                $procedureToUpdate->setExternalDesc($description);

                return $procedureToUpdate;
            }
        );


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
