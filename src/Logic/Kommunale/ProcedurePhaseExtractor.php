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


use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use Psr\Log\LoggerInterface;

class ProcedurePhaseExtractor
{
    private const CONFIGURATION_PHASE = 'configuration';
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function extract(
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $beteiligungType
    ): ProcedurePhaseData {
        $verfahrensschrittType = $this->getSpecificVerfahrensschrittType($beteiligungType);
        $codeVerfahrensschrittType = $verfahrensschrittType?->getCode();

        $beteiligungOeffentlichkeit = $beteiligungType->getBeteiligungOeffentlichkeit();
        $durchgangOeffentlichkeit = $beteiligungOeffentlichkeit?->getDurchgang() ?? 1;
        $zeitraumOeffentlichkeit = $beteiligungOeffentlichkeit?->getZeitraum();
        $beginnOeffentlichkeit = $zeitraumOeffentlichkeit?->getBeginn();
        $endeOeffentlichkeit = $zeitraumOeffentlichkeit?->getEnde();
        $beteiligungKommunalOeffentlichkeitArt = $beteiligungOeffentlichkeit
            ?->getBeteiligungKommunalOeffentlichkeitArt();
        $beteiligungKommunalFormalOeffentlichkeit = $beteiligungKommunalOeffentlichkeitArt
            ?->getBeteiligungKommunalFormalOeffentlichkeit();
        $codeBeteiligungOeffentlichkeit = $beteiligungKommunalFormalOeffentlichkeit?->getCode();

        $beteiligungKommunalTOEB = $beteiligungType->getBeteiligungTOEB();
        $durchgangTOEB = $beteiligungKommunalTOEB?->getDurchgang() ?? 1;
        $zeitraumTOEB = $beteiligungKommunalTOEB?->getZeitraum();
        $beginnTOEB = $zeitraumTOEB?->getBeginn();
        $endeTOEB = $zeitraumTOEB?->getEnde();
        $beteiligungKommunalTOEBArt = $beteiligungKommunalTOEB?->getBeteiligungKommunalTOEBArt();
        $beteiligungKommunalTOEBFormal = $beteiligungKommunalTOEBArt?->getBeteiligungKommunalFormalTOEB();
        $codeBeteiligungTOEB = $beteiligungKommunalTOEBFormal?->getCode();

        $this->logWarningsForMissingCodes(
            $codeVerfahrensschrittType,
            $codeBeteiligungOeffentlichkeit,
            $codeBeteiligungTOEB
        );

        return new ProcedurePhaseData(
            self::CONFIGURATION_PHASE,
            self::CONFIGURATION_PHASE,
            $beginnOeffentlichkeit,
            $endeOeffentlichkeit,
            $beginnTOEB,
            $endeTOEB,
            $durchgangOeffentlichkeit,
            $durchgangTOEB
        );
    }

    private function getSpecificVerfahrensschrittType(
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $beteiligungType
    ): null| CodeVerfahrensschrittKommunalType|CodeVerfahrensschrittPlanfeststellungType {
        $verfahrensschrittType = null;
        if ($beteiligungType instanceof BeteiligungKommunalType) {
            $verfahrensschrittType = $beteiligungType->getVerfahrensschrittKommunal();
        }
        if ($beteiligungType instanceof BeteiligungPlanfeststellungType) {
            $verfahrensschrittType = $beteiligungType->getVerfahrensschrittPlanfeststellung();
        }


        return $verfahrensschrittType;
    }

    private function logWarningsForMissingCodes(
        ?string $codeVerfahrensschrittKommunal,
        ?string $codeBeteiligungOeffentlichkeit,
        ?string $codeBeteiligungTOEB
    ): void {
        if (null === $codeVerfahrensschrittKommunal) {
            $this->logger->warning('Code Verfahrensschritt Kommunal is null');
        }
        if (null === $codeBeteiligungOeffentlichkeit) {
            $this->logger->warning('Code Beteiligung Oeffentlichkeit is null');
        }
        if (null === $codeBeteiligungTOEB) {
            $this->logger->warning('Code Beteiligung TOEB is null');
        }
    }
}
