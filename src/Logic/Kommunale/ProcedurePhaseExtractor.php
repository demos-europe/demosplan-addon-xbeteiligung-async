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


use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use Psr\Log\LoggerInterface;

class ProcedurePhaseExtractor
{
    private const CONFIGURATION_PHASE = 'configuration';
    public function __construct(
        private readonly LoggerInterface            $logger)
    {
    }

    public function extract(
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $beteiligungType
    ): ProcedurePhaseData {
        $verfahrensschrittType = $this->getSpecificVerfahrensschrittType($beteiligungType);

        //General Phase which is used as fallback for the public participation (citizens)
        $codeVerfahrensschrittType = $verfahrensschrittType?->getCode();

        $beteiligungOeffentlichkeit = $beteiligungType->getBeteiligungOeffentlichkeit();

        //Phase for public participation
        $codeOeffentlichkeitVerfahrensschritt = $this->getCodeOeffentlichkeitVerfahrensschritt($beteiligungOeffentlichkeit);

        //Sub Phase for the public (citizens)
        $codeOeffentlichkeitVerfahrensteilschritt = $this->getCodeOeffentlichkeitVerfahrensteilschritt($beteiligungOeffentlichkeit);

        $durchgangOeffentlichkeit = $beteiligungOeffentlichkeit?->getDurchgang() ?? 1;
        $zeitraumOeffentlichkeit = $beteiligungOeffentlichkeit?->getZeitraum();
        $beginnOeffentlichkeit = $zeitraumOeffentlichkeit?->getBeginn();
        $endeOeffentlichkeit = $zeitraumOeffentlichkeit?->getEnde();

        $beteiligungTOEB = $beteiligungType->getBeteiligungTOEB();

        //Phase for institutions
        $codeToebVerfahrensschritt = $this->getCodeBeteiligungTOEBVerfahrensschritt($beteiligungTOEB);

        //Sub Phase for institutions
        $codeToebVerfahrensteilschritt = $this->getCodeBeteiligungTOEBVerfahrensteilschritt($beteiligungTOEB);
        $durchgangTOEB = $beteiligungTOEB?->getDurchgang() ?? 1;
        $zeitraumTOEB = $beteiligungTOEB?->getZeitraum();
        $beginnTOEB = $zeitraumTOEB?->getBeginn();
        $endeTOEB = $zeitraumTOEB?->getEnde();

        $this->logWarningsForMissingCodes(
            $codeVerfahrensschrittType,
            $codeOeffentlichkeitVerfahrensschritt,
            $codeOeffentlichkeitVerfahrensteilschritt,
            $codeToebVerfahrensschritt,
            $codeToebVerfahrensteilschritt
        );

        return new ProcedurePhaseData(
            self::CONFIGURATION_PHASE,
            self::CONFIGURATION_PHASE,
            $codeVerfahrensschrittType,
            $codeOeffentlichkeitVerfahrensschritt,
            $codeOeffentlichkeitVerfahrensteilschritt,
            $codeToebVerfahrensschritt,
            $codeToebVerfahrensteilschritt,
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

    private function getCodeOeffentlichkeitVerfahrensschritt(
        BeteiligungKommunalOeffentlichkeitType|BeteiligungPlanfeststellungOeffentlichkeitType|null $beteiligungOeffentlichkeit
    ): ?string {
        if (null === $beteiligungOeffentlichkeit) {
            return null;
        }

        $codeVerfahrensschritt = null;
        if ($beteiligungOeffentlichkeit instanceof BeteiligungKommunalOeffentlichkeitType ) {
            /** @var BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getBeteiligungKommunalOeffentlichkeitArt()->getBeteiligungKommunalFormalOeffentlichkeit()->getCode();
        }
        if ($beteiligungOeffentlichkeit instanceof BeteiligungPlanfeststellungOeffentlichkeitType ) {
            /** @var BeteiligungPlanfeststellungOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getBeteiligungPlanfeststellungOeffentlichkeitArt()->getBeteiligungPlanfeststellungFormalOeffentlichkeit()->getCode();
        }


        return $codeVerfahrensschritt;
    }

    private function getCodeOeffentlichkeitVerfahrensteilschritt(
        BeteiligungKommunalOeffentlichkeitType|BeteiligungPlanfeststellungOeffentlichkeitType|null $beteiligungOeffentlichkeit
    ): ?string {
        if (null === $beteiligungOeffentlichkeit) {
            return null;
        }

        $verfahrensteilschrittCode = null;
        if ($beteiligungOeffentlichkeit instanceof BeteiligungKommunalOeffentlichkeitType ) {
            /** @var BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getVerfahrensteilschrittKommunal()?->getCode();
        }

        if ($beteiligungOeffentlichkeit instanceof BeteiligungPlanfeststellungOeffentlichkeitType ) {
            /** @var BeteiligungPlanfeststellungOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getVerfahrensteilschrittPlanfeststellung()?->getCode();
        }


        return $verfahrensteilschrittCode;
    }

    private function getCodeBeteiligungTOEBVerfahrensschritt(
        BeteiligungKommunalTOEBType|BeteiligungPlanfeststellungTOEBType|null $beteiligungTOEB
    ): ?string {
        if (null === $beteiligungTOEB) {
            return null;
        }

        $verfahrensschrittCode = null;
        if ($beteiligungTOEB instanceof BeteiligungKommunalTOEBType) {
            /** @var BeteiligungKommunalTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getBeteiligungKommunalTOEBArt()->getBeteiligungKommunalFormalTOEB()->getCode();
        }

        if ($beteiligungTOEB instanceof BeteiligungPlanfeststellungTOEBType ) {
            /** @var BeteiligungPlanfeststellungTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getBeteiligungPlanfeststellungTOEBArt()?->getBeteiligungPlanfeststellungFormalTOEB()?->getCode();
        }


        return $verfahrensschrittCode;
    }

    private function getCodeBeteiligungTOEBVerfahrensteilschritt(
        BeteiligungKommunalTOEBType|BeteiligungPlanfeststellungTOEBType|null $beteiligungTOEB
    ): ?string {
        if (null === $beteiligungTOEB) {
            return null;
        }
        $verfahrensteilschrittCode = null;
        if ($beteiligungTOEB instanceof BeteiligungKommunalTOEBType) {
            /** @var BeteiligungKommunalTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getVerfahrensteilschrittKommunal()?->getCode();
        }

        if ($beteiligungTOEB instanceof BeteiligungPlanfeststellungTOEBType ) {
            /** @var BeteiligungPlanfeststellungTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getVerfahrensteilschrittPlanfeststellung()?->getCode();
        }


        return $verfahrensteilschrittCode;
    }

    private function logWarningsForMissingCodes(
        ?string $codeVerfahrensschrittKommunal,
        ?string $codeOeffentlichkeitVerfahrensschritt,
        ?string $codeOeffentlichkeitVerfahrensteilschritt,
        ?string $codeToebVerfahrensschritt,
        ?string $codeToebVerfahrensteilschritt
    ): void {
        if (null === $codeVerfahrensschrittKommunal) {
            $this->logger->warning('Code Verfahrensschritt Kommunal is null');
        }
        if (null === $codeOeffentlichkeitVerfahrensschritt) {
            $this->logger->warning('Code Beteiligung OeffentlichkeitArt FormalOeffentlichkeit is null');
        }
        if (null === $codeOeffentlichkeitVerfahrensteilschritt) {
            $this->logger->warning('Code BeteiligungOeffentlichkeit Verfahrensteilschritt is null');
        }
        if (null === $codeToebVerfahrensschritt) {
            $this->logger->warning('Code Beteiligung TOEBArt FormalTOEB is null');
        }
        if (null === $codeToebVerfahrensteilschritt) {
            $this->logger->warning('Code Beteiligung TOEB Verfahrensteilschritt is null');
        }
    }
}
