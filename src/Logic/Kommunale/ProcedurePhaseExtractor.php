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


use DemosEurope\DemosplanAddon\XBeteiligung\Enum\InstitutionParticipationPhase;
use DemosEurope\DemosplanAddon\XBeteiligung\Enum\PublicParticipationPhase;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use Psr\Log\LoggerInterface;

class ProcedurePhaseExtractor
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public function extract(
        BeteiligungKommunalType $beteiligungKommunal
    ): ProcedurePhaseData {
        $verfahrensschrittKommunal = $beteiligungKommunal->getVerfahrensschrittKommunal();
        $codeVerfahrensschrittKommunal = $verfahrensschrittKommunal?->getCode();

        $beteiligungOeffentlichkeit = $beteiligungKommunal->getBeteiligungOeffentlichkeit();
        $durchgangOeffentlichkeit = $beteiligungOeffentlichkeit?->getDurchgang() ?? 1;
        $zeitraumOeffentlichkeit = $beteiligungOeffentlichkeit?->getZeitraum();
        $beginnOeffentlichkeit = $zeitraumOeffentlichkeit?->getBeginn();
        $endeOeffentlichkeit = $zeitraumOeffentlichkeit?->getEnde();
        $beteiligungKommunalOeffentlichkeitArt = $beteiligungOeffentlichkeit
            ?->getBeteiligungKommunalOeffentlichkeitArt();
        $beteiligungKommunalFormalOeffentlichkeit = $beteiligungKommunalOeffentlichkeitArt
            ?->getBeteiligungKommunalFormalOeffentlichkeit();
        $codeBeteiligungOeffentlichkeit = $beteiligungKommunalFormalOeffentlichkeit?->getCode();

        $beteiligungKommunalTOEB = $beteiligungKommunal->getBeteiligungTOEB();
        $durchgangTOEB = $beteiligungKommunalTOEB?->getDurchgang() ?? 1;;
        $zeitraumTOEB = $beteiligungKommunalTOEB?->getZeitraum();
        $beginnTOEB = $zeitraumTOEB?->getBeginn();
        $endeTOEB = $zeitraumTOEB?->getEnde();
        $beteiligungKommunalTOEBArt = $beteiligungKommunalTOEB?->getBeteiligungKommunalTOEBArt();
        $beteiligungKommunalTOEBFormal = $beteiligungKommunalTOEBArt?->getBeteiligungKommunalFormalTOEB();
        $codeBeteiligungTOEB = $beteiligungKommunalTOEBFormal?->getCode();


        $publicParticipationPhase = null !== $codeVerfahrensschrittKommunal
            ? PublicParticipationPhase::fromCode($codeVerfahrensschrittKommunal)
            : null;
        $publicParticipationPhase = null !== $codeBeteiligungOeffentlichkeit
            ? PublicParticipationPhase::fromCode($codeBeteiligungOeffentlichkeit)
            : $publicParticipationPhase;

        $institutionParticipationPhase = null !== $codeVerfahrensschrittKommunal
            ? InstitutionParticipationPhase::fromCode($codeVerfahrensschrittKommunal)
            : null;
        $institutionParticipationPhase = null !== $codeBeteiligungTOEB
            ? InstitutionParticipationPhase::fromCode($codeBeteiligungTOEB)
            : $institutionParticipationPhase;

        $this->logWarningsForMissingCodes(
            $codeVerfahrensschrittKommunal,
            $codeBeteiligungOeffentlichkeit,
            $codeBeteiligungTOEB
        );

        return new ProcedurePhaseData(
            $publicParticipationPhase,
            $institutionParticipationPhase,
            $beginnOeffentlichkeit,
            $endeOeffentlichkeit,
            $beginnTOEB,
            $endeTOEB,
            $durchgangOeffentlichkeit,
            $durchgangTOEB
        );
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
