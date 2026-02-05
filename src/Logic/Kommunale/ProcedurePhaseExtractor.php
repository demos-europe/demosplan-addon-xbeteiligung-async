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


use DemosEurope\DemosplanAddon\XBeteiligung\Logic\ExternalMapper\PhaseCodeMapper;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType;
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
        private readonly LoggerInterface $logger,
        private readonly PhaseCodeMapper $phaseCodeMapper)
    {
    }

    public function extract(
        BeteiligungKommunalType|BeteiligungPlanfeststellungType $beteiligungType
    ): ProcedurePhaseData {
        $verfahrensschrittType = $this->getSpecificVerfahrensschrittType($beteiligungType);
        $codeVerfahrensschrittType = $verfahrensschrittType?->getCode();

        //Phases for the public (citizens)
        $beteiligungOeffentlichkeit = $beteiligungType->getBeteiligungOeffentlichkeit();

        //Phases for the public (citizens)
        $oeffentlichkeitVerfahrensschrittCode = $this->getOeffentlichkeitVerfahrensschrittCode($beteiligungOeffentlichkeit);
        $oeffentlichkeitVerfahrensteilschrittCode = $this->getOeffentlichkeitVerfahrensteilschrittCode($beteiligungOeffentlichkeit);
        $durchgangOeffentlichkeit = $beteiligungOeffentlichkeit?->getDurchgang() ?? 1;
        $zeitraumOeffentlichkeit = $beteiligungOeffentlichkeit?->getZeitraum();
        $beginnOeffentlichkeit = $zeitraumOeffentlichkeit?->getBeginn();
        $endeOeffentlichkeit = $zeitraumOeffentlichkeit?->getEnde();
        $beteiligungOeffentlichkeitArt = $this->getBeteiligungOeffentlichkeitArt($beteiligungOeffentlichkeit, $beteiligungType);
        $beteiligungFormalOeffentlichkeit = $this->getBeteiligungFormalOeffentlichkeit($beteiligungOeffentlichkeitArt, $beteiligungType);
        $codeBeteiligungOeffentlichkeit = $beteiligungFormalOeffentlichkeit?->getCode();

        //Phases for institutions
        $beteiligungTOEB = $beteiligungType->getBeteiligungTOEB();

        //Get procedure step code
        $toebVerfahrensschrittCode = $this->getBeteiligungTOEBVerfahrensschrittCode($beteiligungTOEB);
        $toebVerfahrensteilschrittCode = $this->getBeteiligungTOEBVerfahrensteilschrittCode($beteiligungTOEB);
        $durchgangTOEB = $beteiligungTOEB?->getDurchgang() ?? 1;
        $zeitraumTOEB = $beteiligungTOEB?->getZeitraum();
        $beginnTOEB = $zeitraumTOEB?->getBeginn();
        $endeTOEB = $zeitraumTOEB?->getEnde();
        $beteiligungTOEBArt = $this->getBeteiligungTOEBArt($beteiligungTOEB, $beteiligungType);
        $beteiligungFormalTOEB = $this->getBeteiligungFormalTOEB($beteiligungTOEBArt, $beteiligungType);
        $codeBeteiligungTOEB = $beteiligungFormalTOEB?->getCode();

        $this->phaseCodeMapper->storeExternalProcedurePhaseCodes(
            $beteiligungType->getPlanID(),
            $codeBeteiligungOeffentlichkeit,
            $codeBeteiligungTOEB
          );


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

    private function getOeffentlichkeitVerfahrensschrittCode($beteiligungOeffentlichkeit) {
        $verfahrensteilschrittCode = null;
        if ($beteiligungOeffentlichkeit instanceof BeteiligungKommunalOeffentlichkeitType ) {
            /** @var BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getBeteiligungKommunalOeffentlichkeitArt()->getBeteiligungKommunalFormalOeffentlichkeit()->getCode();
        }
        if ($beteiligungOeffentlichkeit instanceof BeteiligungPlanfeststellungOeffentlichkeitType ) {
            /** @var BeteiligungPlanfeststellungOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getBeteiligungPlanfeststellungOeffentlichkeitArt()->getBeteiligungPlanfeststellungFormalOeffentlichkeit()->getCode();
        }

        return $verfahrensteilschrittCode;

    }

    private function getOeffentlichkeitVerfahrensteilschrittCode($beteiligungOeffentlichkeit) {
        $verfahrensteilschrittCode = null;
        if ($beteiligungOeffentlichkeit instanceof BeteiligungKommunalOeffentlichkeitType ) {
            /** @var BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getVerfahrensteilschrittKommunal()->getCode();
        }

        if ($beteiligungOeffentlichkeit instanceof BeteiligungPlanfeststellungOeffentlichkeitType ) {
            /** @var BeteiligungPlanfeststellungOeffentlichkeitType $beteiligungOeffentlichkeit */
            return $beteiligungOeffentlichkeit->getVerfahrensteilschrittPlanfeststellung()->getCode();
        }

        return $verfahrensteilschrittCode;


    }

    private function getBeteiligungTOEBVerfahrensschrittCode($beteiligungTOEB) {
        $verfahrensteilschrittCode = null;
        if ($beteiligungTOEB instanceof BeteiligungKommunalTOEBType) {
            /** @var BeteiligungKommunalTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getBeteiligungKommunalTOEBArt()->getBeteiligungKommunalFormalTOEB()->getCode();
        }

        if ($beteiligungTOEB instanceof BeteiligungPlanfeststellungTOEBType ) {
            /** @var BeteiligungPlanfeststellungTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getBeteiligungPlanfeststellungTOEBArt()->getBeteiligungPlanfeststellungFormalTOEB()->getCode();
        }

        return $verfahrensteilschrittCode;


    }

    private function getBeteiligungTOEBVerfahrensteilschrittCode($beteiligungTOEB) {
        $verfahrensteilschrittCode = null;
        if ($beteiligungTOEB instanceof BeteiligungKommunalTOEBType) {
            /** @var BeteiligungKommunalTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getVerfahrensteilschrittKommunal()->getCode();
        }

        if ($beteiligungTOEB instanceof BeteiligungPlanfeststellungTOEBType ) {
            /** @var BeteiligungPlanfeststellungTOEBType $beteiligungOeffentlichkeit */
            return $beteiligungTOEB->getVerfahrensteilschrittPlanfeststellung()->getCode();
        }

        return $verfahrensteilschrittCode;


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
            /** @var  BeteiligungKommunalOeffentlichkeitType $beteiligungOeffentlichkeit*/
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
            /** @var BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType $beteiligungOeffentlichkeitArt */
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
            /** @var BeteiligungKommunalTOEBType  $beteiligungKommunalFormalTOEB */
            $beteiligungKommunalFormalTOEB = $beteiligungTOEBArt->getBeteiligungKommunalFormalTOEB();
            return $beteiligungKommunalFormalTOEB;
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
