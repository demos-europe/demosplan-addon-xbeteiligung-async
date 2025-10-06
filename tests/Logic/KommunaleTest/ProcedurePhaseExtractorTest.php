<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS E-Partizipation GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\KommunaleTest;

use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\ProcedurePhaseExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType\BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalTOEBType\BeteiligungKommunalTOEBArtAnonymousPHPType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensschrittKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\ZeitraumType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\ProcedurePhaseData;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class ProcedurePhaseExtractorTest extends TestCase
{
    protected ProcedurePhaseExtractor $sut;

    protected function setUp(): void
    {
        parent::setUp();
        $logger = $this->createMock(LoggerInterface::class);
        $this->sut = new ProcedurePhaseExtractor($logger);
    }

    public function testExtract(): void
    {
        $beteiligungKommunal = $this->createMock(BeteiligungKommunalType::class);

        // Mocking the methods of BeteiligungKommunalType
        $beteiligungKommunal->method('getVerfahrensschrittKommunal')->willReturn($this->createMockVerfahrensschrittKommunal());
        $beteiligungKommunal->method('getBeteiligungOeffentlichkeit')->willReturn($this->createMockBeteiligungOeffentlichkeit());
        $beteiligungKommunal->method('getBeteiligungTOEB')->willReturn($this->createMockBeteiligungTOEB());

        // create expected procedure phase data object
        $expectedProcedurePhaseData = new ProcedurePhaseData(
            'configuration', // public participation phase is now hardcoded to 'configuration'
            'configuration', // institution participation phase is now hardcoded to 'configuration'
            new DateTime('2025-01-01'), // start date for public participation
            new DateTime('2025-01-31'), // end date for public participation
            new DateTime('2025-02-01'), // start date for institution participation
            new DateTime('2025-02-28'), // end date for institution participation
            1,
            1
        );


        $procedurePhaseData = $this->sut->extract($beteiligungKommunal);

        self::assertInstanceOf(ProcedurePhaseData::class, $procedurePhaseData);
        self::assertSame(
            $expectedProcedurePhaseData->getPublicParticipationPhaseKey(),
            $procedurePhaseData->getPublicParticipationPhaseKey()
        );
        self::assertSame(
            $expectedProcedurePhaseData->getInstitutionParticipationPhaseKey(),
            $procedurePhaseData->getInstitutionParticipationPhaseKey()
        );
        self::assertSame(
            $expectedProcedurePhaseData->getPublicParticipationStartDate()->format('Y-m-d'),
            $procedurePhaseData->getPublicParticipationStartDate()->format('Y-m-d')
        );
        self::assertSame(
            $expectedProcedurePhaseData->getPublicParticipationEndDate()->format('Y-m-d'),
            $procedurePhaseData->getPublicParticipationEndDate()->format('Y-m-d')
        );
        self::assertSame(
            $expectedProcedurePhaseData->getInstitutionParticipationStartDate()->format('Y-m-d'),
            $procedurePhaseData->getInstitutionParticipationStartDate()->format('Y-m-d')
        );
        self::assertSame(
            $expectedProcedurePhaseData->getInstitutionParticipationEndDate()->format('Y-m-d'),
            $procedurePhaseData->getInstitutionParticipationEndDate()->format('Y-m-d')
        );
        self::assertSame(
            $expectedProcedurePhaseData->getPublicParticipationIteration(),
            $procedurePhaseData->getPublicParticipationIteration()
        );
        self::assertSame(
            $expectedProcedurePhaseData->getInstitutionParticipationIteration(),
            $procedurePhaseData->getInstitutionParticipationIteration()
        );
    }

    private function createMockVerfahrensschrittKommunal()
    {
        $verfahrensschrittKommunal = $this->createMock(CodeVerfahrensschrittKommunalType::class);
        $verfahrensschrittKommunal->method('getCode')->willReturn('5300');
        return $verfahrensschrittKommunal;
    }

    private function createMockBeteiligungOeffentlichkeit()
    {
        $beteiligungOeffentlichkeit = $this->createMock(BeteiligungKommunalOeffentlichkeitType::class);
        $beteiligungOeffentlichkeit->method('getDurchgang')->willReturn(1);
        $beteiligungOeffentlichkeit->method('getZeitraum')->willReturn($this->createMockZeitraumPublicParticipation());
        $beteiligungOeffentlichkeit->method('getBeteiligungKommunalOeffentlichkeitArt')
            ->willReturn($this->createMockBeteiligungKommunalOeffentlichkeitArt());
        return $beteiligungOeffentlichkeit;
    }

    private function createMockBeteiligungTOEB()
    {
        $beteiligungTOEB = $this->createMock(BeteiligungKommunalTOEBType::class);
        $beteiligungTOEB->method('getDurchgang')->willReturn(1);
        $beteiligungTOEB->method('getZeitraum')->willReturn($this->createMockZeitraumTOEB());
        $beteiligungTOEB->method('getBeteiligungKommunalTOEBArt')
            ->willReturn($this->createMockBeteiligungKommunalTOEBArt());
        return $beteiligungTOEB;
    }

    private function createMockZeitraumPublicParticipation()
    {
        $zeitraum = $this->createMock(ZeitraumType::class);
        $zeitraum->method('getBeginn')->willReturn(new DateTime('2025-01-01'));
        $zeitraum->method('getEnde')->willReturn(new DateTime('2025-01-31'));
        return $zeitraum;
    }

    private function createMockZeitraumTOEB()
    {
        $zeitraum = $this->createMock(ZeitraumType::class);
        $zeitraum->method('getBeginn')->willReturn(new DateTime('2025-02-01'));
        $zeitraum->method('getEnde')->willReturn(new DateTime('2025-02-28'));
        return $zeitraum;
    }

    private function createMockBeteiligungKommunalOeffentlichkeitArt()
    {
        $beteiligungKommunalOeffentlichkeitArt = $this->createMock(
            BeteiligungKommunalOeffentlichkeitArtAnonymousPHPType::class
        );
        $beteiligungKommunalOeffentlichkeitArt->method('getBeteiligungKommunalFormalOeffentlichkeit')
            ->willReturn($this->createMockFormalOeffentlichkeit());

        return $beteiligungKommunalOeffentlichkeitArt;
    }

    private function createMockBeteiligungKommunalTOEBArt()
    {
        $beteiligungKommunalTOEBArt = $this->createMock(
            BeteiligungKommunalTOEBArtAnonymousPHPType::class
        );
        $beteiligungKommunalTOEBArt->method('getBeteiligungKommunalFormalTOEB')
            ->willReturn($this->createMockFormalTOEB());

        return $beteiligungKommunalTOEBArt;
    }

    private function createMockFormalOeffentlichkeit()
    {
        $formalOeffentlichkeit = $this->createMock(CodeVerfahrensschrittKommunalType::class);
        $formalOeffentlichkeit->method('getCode')->willReturn('5300');
        return $formalOeffentlichkeit;
    }

    private function createMockFormalTOEB()
    {
        $formalTOEB = $this->createMock(CodeVerfahrensschrittKommunalType::class);
        $formalTOEB->method('getCode')->willReturn('4300');
        return $formalTOEB;
    }
}
