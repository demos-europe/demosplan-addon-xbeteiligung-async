<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;

use DemosEurope\DemosplanAddon\XBeteiligung\Exeption\FormatException;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\AnlagenExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\ProcedurePhaseExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\KommunalInitiieren0401;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\Procedure\ProcedureDataValueObject;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\PlanfeststellungInitiieren0201;
use Psr\Log\LoggerInterface;

/**
 * Service to extract and map procedure data from XML objects to value objects.
 * Handles the conversion from XBeteiligung XML schema objects to internal data structures.
 */
class ProcedureDataExtractor
{
    public function __construct(
        private readonly AnlagenExtractor $anlagenExtractor,
        private readonly LoggerInterface $logger,
        private readonly ProcedurePhaseExtractor $procedurePhaseExtractor,
        private readonly XBeteiligungMapService $xbeteiligungMapService,
    ) {
    }

    /**
     * @throws FormatException
     */
    public function extract(KommunalInitiieren0401|PlanfeststellungInitiieren0201 $xmlObject): ProcedureDataValueObject
    {
        $messageContent = $this->getMessageContent($xmlObject);

        $planId = $messageContent->getPlanID();
        $planName = $messageContent->getPlanname();
        $planDescription = $messageContent->getBeschreibungPlanungsanlass();
        $orgaName = $messageContent->getAkteurVorhaben()->getVeranlasser()?->getName()?->getName();
        $geltungsbereich = $messageContent->getGeltungsbereich();

        $mapData = $this->xbeteiligungMapService->setMapData($geltungsbereich);
        $procedurePhaseData = $this->procedurePhaseExtractor->extract($messageContent);
        $anlagen = $this->anlagenExtractor->extract($messageContent);

        $procedureData = new ProcedureDataValueObject();

        $procedureData->setPlanId($planId);
        $procedureData->setPlanName($planName);
        $procedureData->setPlanDescription($planDescription);
        $procedureData->setContactOrganization($orgaName);

        $procedureData->setMapData($mapData);
        $procedureData->setProcedurePhaseData($procedurePhaseData);
        $procedureData->setAnlagen($anlagen);

        return $procedureData;
    }

    private function getMessageContent(
        KommunalInitiieren0401|PlanfeststellungInitiieren0201 $xmlObject
    ): BeteiligungKommunalType|BeteiligungPlanfeststellungType {
        $messageContent = $xmlObject->getNachrichteninhalt()?->getBeteiligung();
        if(null === $messageContent) {
            $this->logger->error(
                'Message content is missing',
                ['xmlObject' => var_export($xmlObject, true)]
            );

            throw new FormatException('Message content is missing');
        }

        return $messageContent;
    }
}
