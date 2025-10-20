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
        $beteiligungOeffentlichkeitArt = $this->getBeteiligungOeffentlichkeitArt($beteiligungOeffentlichkeit, $beteiligungType);
        $beteiligungFormalOeffentlichkeit = $this->getBeteiligungFormalOeffentlichkeit($beteiligungOeffentlichkeitArt, $beteiligungType);
        $codeBeteiligungOeffentlichkeit = $beteiligungFormalOeffentlichkeit?->getCode();

        $beteiligungTOEB = $beteiligungType->getBeteiligungTOEB();
        $durchgangTOEB = $beteiligungTOEB?->getDurchgang() ?? 1;
        $zeitraumTOEB = $beteiligungTOEB?->getZeitraum();
        $beginnTOEB = $zeitraumTOEB?->getBeginn();
        $endeTOEB = $zeitraumTOEB?->getEnde();
        $beteiligungTOEBArt = $this->getBeteiligungTOEBArt($beteiligungTOEB, $beteiligungType);
        $beteiligungFormalTOEB = $this->getBeteiligungFormalTOEB($beteiligungTOEBArt, $beteiligungType);
        $codeBeteiligungTOEB = $beteiligungFormalTOEB?->getCode();

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

    private function getBeteiligungOeffentlichkeitArt($beteiligungOeffentlichkeit, $beteiligungType) {
        if (null === $beteiligungOeffentlichkeit) {
            return null;
        }

        if ($beteiligungType instanceof BeteiligungKommunalType) {
            return $beteiligungOeffentlichkeit->getBeteiligungKommunalOeffentlichkeitArt();
        }

        if ($beteiligungType instanceof BeteiligungPlanfeststellungType) {
            return $beteiligungOeffentlichkeit->getBeteiligungPlanfeststellungOeffentlichkeitArt();
        }

        return null;
    }

    private function getBeteiligungFormalOeffentlichkeit($beteiligungOeffentlichkeitArt, $beteiligungType) {
        if (null === $beteiligungOeffentlichkeitArt) {
            return null;
        }

        if ($beteiligungType instanceof BeteiligungKommunalType) {
            return $beteiligungOeffentlichkeitArt->getBeteiligungKommunalFormalOeffentlichkeit();
        }

        if ($beteiligungType instanceof BeteiligungPlanfeststellungType) {
            return $beteiligungOeffentlichkeitArt->getBeteiligungPlanfeststellungFormalOeffentlichkeit();
        }

        return null;
    }

    private function getBeteiligungTOEBArt($beteiligungTOEB, $beteiligungType) {
        if (null === $beteiligungTOEB) {
            return null;
        }

        if ($beteiligungType instanceof BeteiligungKommunalType) {
            return $beteiligungTOEB->getBeteiligungKommunalTOEBArt();
        }

        if ($beteiligungType instanceof BeteiligungPlanfeststellungType) {
            return $beteiligungTOEB->getBeteiligungPlanfeststellungTOEBArt();
        }

        return null;
    }

    private function getBeteiligungFormalTOEB($beteiligungTOEBArt, $beteiligungType) {
        if (null === $beteiligungTOEBArt) {
            return null;
        }

        if ($beteiligungType instanceof BeteiligungKommunalType) {
            return $beteiligungTOEBArt->getBeteiligungKommunalFormalTOEB();
        }

        if ($beteiligungType instanceof BeteiligungPlanfeststellungType) {
            return $beteiligungTOEBArt->getBeteiligungPlanfeststellungFormalTOEB();
        }

        return null;
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
