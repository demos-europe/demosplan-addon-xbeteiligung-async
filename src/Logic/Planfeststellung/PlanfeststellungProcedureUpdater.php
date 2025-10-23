<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\Planfeststellung;

use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ProcedureCommonFeatures;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ResponseValue;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungAktualisieren0202;
use Exception;
use GoetasWebservices\XML\XSDReader\Schema\Exception\SchemaException;

class PlanfeststellungProcedureUpdater extends ProcedureCommonFeatures
{
    /**
     * @throws SchemaException
     * @throws Exception
     */
    public function updateProcedure(
        PlanfeststellungAktualisieren0202 $planfeststellungAktualisieren0202
    ): ResponseValue {
        $errorTypes = [];

        // Get BeteiligungPlanfeststellungType from the message
        $beteiligungPlanfeststellungType = $planfeststellungAktualisieren0202->getNachrichteninhalt()?->getBeteiligung();
        if (null === $beteiligungPlanfeststellungType) {
            return $this->planfeststellungMessageFactory->buildProcedureUpdateErrorResponse222(
                $errorTypes,
                $planfeststellungAktualisieren0202
            );
        }
        // Get the procedure to update
        $procedureToUpdate = $this->getProcedure($beteiligungPlanfeststellungType);
        if (null === $procedureToUpdate) {
            return $this->planfeststellungMessageFactory->buildProcedureUpdateErrorResponse222(
                $errorTypes,
                $planfeststellungAktualisieren0202
            );
        }

        // Update procedure phases
        $procedurePhaseData = $this->procedurePhaseExtractor->extract($beteiligungPlanfeststellungType);
        $this->setProcedurePhase($procedureToUpdate, $procedurePhaseData);

        // Update procedure description and external description
        $description = $beteiligungPlanfeststellungType->getBeschreibungPlanungsanlass() ?? '';
        $procedureToUpdate->setDesc($description);
        $procedureToUpdate->setExternalDesc($description);

        // Update map data (territory, bounding box, map extent)
        $geltungsbereich = $beteiligungPlanfeststellungType->getGeltungsbereich();
        $flaechenabgrenzungsUrl = $beteiligungPlanfeststellungType->getFlaechenabgrenzungUrl();
        $mapData = $this->xbeteiligungMapService->setMapData($geltungsbereich, $flaechenabgrenzungsUrl);

        $procedureToUpdate->getSettings()->setTerritory($mapData->getTerritory());
        $procedureToUpdate->getSettings()->setBoundingBox($mapData->getBbox());
        $procedureToUpdate->getSettings()->setMapExtent($mapData->getMapExtent());

        // Process flaechenabgrenzungsUrl for GIS layer update
        $this->gisLayerManager->processWmsUrl($flaechenabgrenzungsUrl, $procedureToUpdate);

        // Update procedure documents will implemented later

        $connection = $this->entityManager->getConnection();
        $procedureUpdated = null;
        try {
            $connection->beginTransaction();
            $procedureUpdated = $this->procedureService->updateProcedureObject($procedureToUpdate);
            $connection->commit();
        } catch (Exception $e) {
            $connection->rollBack();
            $this->logger->error('Procedure could not be updated.', ['errorMessage' => $e->getMessage()]);

            // Return error response when update fails
            return $this->planfeststellungMessageFactory->buildProcedureUpdateErrorResponse222(
                $errorTypes,
                $planfeststellungAktualisieren0202
            );
        }

        // create OK message for procedure update
        return $this->planfeststellungMessageFactory->buildProcedureUpdateOKResponse212(
            $planfeststellungAktualisieren0202,
            $procedureUpdated
        );
    }

    private function getProcedure(
        BeteiligungPlanfeststellungType $beteiligungPlanfeststellungType,
    ): ?ProcedureInterface {
        $procedureId = $beteiligungPlanfeststellungType->getBeteiligungOeffentlichkeit()?->getBeteiligungsID();
        $procedureToUpdate = $this->procedureService->getProcedure(
            $procedureId
        );
        if (null === $procedureToUpdate) {
            $procedureToUpdate = $this->procedureService->getProcedure(
                $beteiligungPlanfeststellungType->getBeteiligungTOEB()?->getBeteiligungsID()
            );
        }

        return $procedureToUpdate;
    }
}
