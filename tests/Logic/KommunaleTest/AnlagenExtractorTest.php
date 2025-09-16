<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic\KommunaleTest;

use DateTime;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale\AnlagenExtractor;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\MetadatenAnhangType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AnhangOderVerlinkungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalTOEBType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungOeffentlichkeitType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensunterlagetypType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeXBauMimeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\AnlageValueObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class AnlagenExtractorTest extends TestCase
{
    protected AnlagenExtractor $sut;
    protected LoggerInterface $logger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->sut = new AnlagenExtractor($this->logger);
    }

    public function testExtractFromBeteiligungKommunalTypeWithPublicAndToebAttachments(): void
    {
        $beteiligungKommunal = $this->createBeteiligungKommunalWithAttachments();

        $result = $this->sut->extract($beteiligungKommunal);

        self::assertCount(2, $result);
        self::assertContainsOnlyInstancesOf(AnlageValueObject::class, $result);

        // Check first attachment (public participation)
        $publicAttachment = $result[0];
        self::assertSame('Public Document', $publicAttachment->getTitle());
        self::assertSame('public_doc.pdf', $publicAttachment->getFileName());
        self::assertSame('doc123', $publicAttachment->getDocumentId());
        self::assertTrue($publicAttachment->isAttachment());
        self::assertFalse($publicAttachment->isLink());

        // Check second attachment (TOEB)
        $toebAttachment = $result[1];
        self::assertSame('TOEB Document', $toebAttachment->getTitle());
        self::assertSame('https://example.com/toeb.pdf', $toebAttachment->getUrl());
        self::assertFalse($toebAttachment->isAttachment());
        self::assertTrue($toebAttachment->isLink());
    }

    public function testExtractFromBeteiligungPlanfeststellungTypeWithPublicAttachments(): void
    {
        $beteiligungPlanfeststellung = $this->createBeteiligungPlanfeststellungWithAttachments();

        $result = $this->sut->extract($beteiligungPlanfeststellung);

        self::assertCount(1, $result);
        self::assertContainsOnlyInstancesOf(AnlageValueObject::class, $result);

        // Check attachment
        $attachment = $result[0];
        self::assertSame('Planfeststellung Document', $attachment->getTitle());
        self::assertTrue($attachment->isBase64Encoded());
        self::assertTrue($attachment->hasContent());
    }

    public function testExtractWithNoAttachments(): void
    {
        $beteiligungKommunal = self::createMock(BeteiligungKommunalType::class);
        $beteiligungKommunal->method('getBeteiligungOeffentlichkeit')->willReturn(null);
        $beteiligungKommunal->method('getBeteiligungTOEB')->willReturn(null);

        $result = $this->sut->extract($beteiligungKommunal);

        self::assertEmpty($result);
    }

    public function testExtractWithExceptionInProcessing(): void
    {
        $beteiligungKommunal = $this->createBeteiligungKommunalWithInvalidAttachment();

        $this->logger->expects(self::once())
            ->method('warning')
            ->with(
                'Failed to extract attachment',
                self::arrayHasKey('error')
            );

        $result = $this->sut->extract($beteiligungKommunal);

        self::assertEmpty($result);
    }

    private function createBeteiligungKommunalWithAttachments(): BeteiligungKommunalType
    {
        $beteiligungKommunal = $this->createMock(BeteiligungKommunalType::class);

        // Public participation with attachment
        $publicParticipation = $this->createMock(BeteiligungKommunalOeffentlichkeitType::class);
        $publicAttachment = $this->createAttachmentWithAnhang('Public Document', 'public_doc.pdf', 'doc123');
        $publicParticipation->method('getAnlagen')->willReturn([$publicAttachment]);

        // TOEB with link
        $toebParticipation = $this->createMock(BeteiligungKommunalTOEBType::class);
        $toebAttachment = $this->createAttachmentWithVerlinkung('TOEB Document', 'https://example.com/toeb.pdf');
        $toebParticipation->method('getAnlagen')->willReturn([$toebAttachment]);

        $beteiligungKommunal->method('getBeteiligungOeffentlichkeit')->willReturn($publicParticipation);
        $beteiligungKommunal->method('getBeteiligungTOEB')->willReturn($toebParticipation);

        return $beteiligungKommunal;
    }

    private function createBeteiligungPlanfeststellungWithAttachments(): BeteiligungPlanfeststellungType
    {
        $beteiligungPlanfeststellung = $this->createMock(BeteiligungPlanfeststellungType::class);

        // Public participation with base64 content
        $publicParticipation = $this->createMock(BeteiligungPlanfeststellungOeffentlichkeitType::class);
        $attachment = $this->createAttachmentWithBase64Content('Planfeststellung Document', 'base64encodedcontent');
        $publicParticipation->method('getAnlagen')->willReturn([$attachment]);

        $beteiligungPlanfeststellung->method('getBeteiligungOeffentlichkeit')->willReturn($publicParticipation);
        $beteiligungPlanfeststellung->method('getBeteiligungTOEB')->willReturn(null);

        return $beteiligungPlanfeststellung;
    }

    private function createBeteiligungKommunalWithInvalidAttachment(): BeteiligungKommunalType
    {
        $beteiligungKommunal = $this->createMock(BeteiligungKommunalType::class);
        $publicParticipation = $this->createMock(BeteiligungKommunalOeffentlichkeitType::class);

        // Create an invalid attachment that will throw an exception during processing
        $invalidAttachment = $this->createMock(MetadatenAnlageType::class);
        $invalidAttachment->method('getBezeichnung')->willReturn('Invalid Attachment');
        $invalidAttachment->method('getVersionsnummer')->willReturn('1.0');
        $invalidAttachment->method('getDatum')->willReturn(new DateTime('2024-01-01'));
        $invalidAttachment->method('getAnlageart')->willReturn(null);
        $invalidAttachment->method('getMimeType')->willReturn(null);
        $invalidAttachment->method('getDokument')->willReturn(null);

        // This will cause an exception when trying to create the AnlageValueObject
        $invalidAttachment->method('getAnhangOderVerlinkung')->willThrowException(new \Exception('Test exception'));

        $publicParticipation->method('getAnlagen')->willReturn([$invalidAttachment]);
        $beteiligungKommunal->method('getBeteiligungOeffentlichkeit')->willReturn($publicParticipation);
        $beteiligungKommunal->method('getBeteiligungTOEB')->willReturn(null);

        return $beteiligungKommunal;
    }

    private function createAttachmentWithAnhang(string $title, string $filename, string $documentId): MetadatenAnlageType
    {
        $attachment = $this->createMock(MetadatenAnlageType::class);
        $attachment->method('getBezeichnung')->willReturn($title);
        $attachment->method('getVersionsnummer')->willReturn('1.0');
        $attachment->method('getDatum')->willReturn(new DateTime('2024-01-01'));

        $anlageart = $this->createMock(CodeVerfahrensunterlagetypType::class);
        $anlageart->method('getCode')->willReturn('0100');
        $attachment->method('getAnlageart')->willReturn($anlageart);

        $mimeType = $this->createMock(CodeXBauMimeTypeType::class);
        $mimeType->method('getCode')->willReturn('application/pdf');
        $attachment->method('getMimeType')->willReturn($mimeType);

        $attachment->method('getDokument')->willReturn(null);

        // Create AnhangOderVerlinkung with Anhang
        $anhangOderVerlinkung = $this->createMock(AnhangOderVerlinkungType::class);

        $anhang = $this->createMock(MetadatenAnhangType::class);
        $anhang->method('getDokumentid')->willReturn($documentId);
        $anhang->method('getDateiname')->willReturn($filename);

        $anhangOderVerlinkung->method('getAnhang')->willReturn($anhang);
        $anhangOderVerlinkung->method('getUriVerlinkung')->willReturn(null);

        $attachment->method('getAnhangOderVerlinkung')->willReturn($anhangOderVerlinkung);

        return $attachment;
    }

    private function createAttachmentWithVerlinkung(string $title, string $url): MetadatenAnlageType
    {
        $attachment = $this->createMock(MetadatenAnlageType::class);
        $attachment->method('getBezeichnung')->willReturn($title);
        $attachment->method('getVersionsnummer')->willReturn('2.0');
        $attachment->method('getDatum')->willReturn(new DateTime('2024-02-01'));
        $attachment->method('getAnlageart')->willReturn(null);
        $attachment->method('getMimeType')->willReturn(null);
        $attachment->method('getDokument')->willReturn(null);

        // Create AnhangOderVerlinkung with Verlinkung
        $anhangOderVerlinkung = $this->createMock(AnhangOderVerlinkungType::class);
        $anhangOderVerlinkung->method('getAnhang')->willReturn(null);
        $anhangOderVerlinkung->method('getUriVerlinkung')->willReturn($url);

        $attachment->method('getAnhangOderVerlinkung')->willReturn($anhangOderVerlinkung);

        return $attachment;
    }

    private function createAttachmentWithBase64Content(string $title, string $base64Content): MetadatenAnlageType
    {
        $attachment = $this->createMock(MetadatenAnlageType::class);
        $attachment->method('getBezeichnung')->willReturn($title);
        $attachment->method('getVersionsnummer')->willReturn('3.0');
        $attachment->method('getDatum')->willReturn(new DateTime('2024-03-01'));
        $attachment->method('getAnlageart')->willReturn(null);
        $attachment->method('getMimeType')->willReturn(null);
        $attachment->method('getDokument')->willReturn($base64Content);
        $attachment->method('getAnhangOderVerlinkung')->willReturn(null);

        return $attachment;
    }
}
