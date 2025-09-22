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

    private const PUBLIC_TITLE = 'Public Document';
    private const PUBLIC_VERSION = '1.0';
    private const PUBLIC_DATE = '2024-01-01';
    private const PUBLIC_FILENAME = 'public_doc.pdf';
    private const PUBLIC_DOCUMENT_ID = 'doc123';

    private const TOEB_TITLE = 'TOEB Document';
    private const TOEB_VERSION = '2.0';
    private const TOEB_DATE = '2024-02-01';
    private const TOEB_URL = 'https://example.com/toeb.pdf';

    private const PLANFEST_TITLE = 'Planfeststellung Document';
    private const PLANFEST_VERSION = '3.0';
    private const PLANFEST_DATE = '2024-03-01';
    private const PLANFEST_CONTENT = 'base64encodedcontent';

    private const DOCUMENT_TYPE_CODE = '0100';
    private const MIME_TYPE_PDF = 'application/pdf';

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
        self::assertSame(self::PUBLIC_TITLE, $publicAttachment->getTitle());
        self::assertSame(self::PUBLIC_VERSION, $publicAttachment->getVersion());
        self::assertSame(self::PUBLIC_DATE, $publicAttachment->getDate()->format('Y-m-d'));
        self::assertSame(self::DOCUMENT_TYPE_CODE, $publicAttachment->getDocumentType());
        self::assertSame(self::MIME_TYPE_PDF, $publicAttachment->getMimeType());
        self::assertSame(self::PUBLIC_FILENAME, $publicAttachment->getFileName());
        self::assertSame(self::PUBLIC_DOCUMENT_ID, $publicAttachment->getDocumentId());
        self::assertFalse($publicAttachment->hasUrl());
        self::assertNull($publicAttachment->getBase64Content());
        self::assertTrue($publicAttachment->isAttachment());
        self::assertFalse($publicAttachment->isLink());
        self::assertFalse($publicAttachment->isBase64Encoded());
        self::assertFalse($publicAttachment->hasContent());

        // Check second attachment (TOEB)
        $toebAttachment = $result[1];
        self::assertSame(self::TOEB_TITLE, $toebAttachment->getTitle());
        self::assertSame(self::TOEB_VERSION, $toebAttachment->getVersion());
        self::assertSame(self::TOEB_DATE, $toebAttachment->getDate()->format('Y-m-d'));
        self::assertNull($toebAttachment->getDocumentType());
        self::assertSame(self::MIME_TYPE_PDF, $toebAttachment->getMimeType());
        self::assertNull($toebAttachment->getDocumentId());
        self::assertSame(self::TOEB_URL, $toebAttachment->getUrl());
        self::assertNull($toebAttachment->getBase64Content());
        self::assertFalse($toebAttachment->isAttachment());
        self::assertTrue($toebAttachment->isLink());
        self::assertFalse($toebAttachment->isBase64Encoded());
        self::assertFalse($toebAttachment->hasContent());
        self::assertTrue($toebAttachment->hasUrl());
    }

    public function testExtractFromBeteiligungPlanfeststellungTypeWithPublicAttachments(): void
    {
        $beteiligungPlanfeststellung = $this->createBeteiligungPlanfeststellungWithAttachments();

        $result = $this->sut->extract($beteiligungPlanfeststellung);

        self::assertCount(1, $result);
        self::assertContainsOnlyInstancesOf(AnlageValueObject::class, $result);

        // Check attachment
        $attachment = $result[0];
        self::assertSame(self::PLANFEST_TITLE, $attachment->getTitle());
        self::assertSame(self::PLANFEST_VERSION, $attachment->getVersion());
        self::assertSame(self::PLANFEST_DATE, $attachment->getDate()->format('Y-m-d'));
        self::assertNull($attachment->getDocumentType());
        self::assertSame(self::MIME_TYPE_PDF, $attachment->getMimeType());
        self::assertNull($attachment->getDocumentId());
        self::assertFalse($attachment->hasUrl());
        self::assertSame(self::PLANFEST_CONTENT, $attachment->getBase64Content());
        self::assertFalse($attachment->isAttachment());
        self::assertFalse($attachment->isLink());
        self::assertTrue($attachment->isBase64Encoded());
        self::assertTrue($attachment->hasContent());
    }

    public function testExtractWithNoAttachments(): void
    {
        $beteiligungKommunal = new BeteiligungKommunalType();
        $beteiligungKommunal->setBeteiligungOeffentlichkeit(null);
        $beteiligungKommunal->setBeteiligungTOEB(null);

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
        $beteiligungKommunal = new BeteiligungKommunalType();

        // Public participation with attachment
        $publicParticipation = new BeteiligungKommunalOeffentlichkeitType();
        $publicAttachment = $this->createAttachmentWithAnhang(self::PUBLIC_TITLE, self::PUBLIC_FILENAME, self::PUBLIC_DOCUMENT_ID);
        $publicParticipation->setAnlagen([$publicAttachment]);

        // TOEB with link
        $toebParticipation = new BeteiligungKommunalTOEBType();
        $toebAttachment = $this->createAttachmentWithVerlinkung(self::TOEB_TITLE, self::TOEB_URL);
        $toebParticipation->setAnlagen([$toebAttachment]);

        $beteiligungKommunal->setBeteiligungOeffentlichkeit($publicParticipation);
        $beteiligungKommunal->setBeteiligungTOEB($toebParticipation);

        return $beteiligungKommunal;
    }

    private function createBeteiligungPlanfeststellungWithAttachments(): BeteiligungPlanfeststellungType
    {
        $beteiligungPlanfeststellung = new BeteiligungPlanfeststellungType();

        // Public participation with base64 content
        $publicParticipation = new BeteiligungPlanfeststellungOeffentlichkeitType();
        $attachment = $this->createAttachmentWithBase64Content(self::PLANFEST_TITLE, self::PLANFEST_CONTENT);
        $publicParticipation->setAnlagen([$attachment]);

        $beteiligungPlanfeststellung->setBeteiligungOeffentlichkeit($publicParticipation);
        $beteiligungPlanfeststellung->setBeteiligungTOEB(null);

        return $beteiligungPlanfeststellung;
    }

    private function createBeteiligungKommunalWithInvalidAttachment(): BeteiligungKommunalType
    {
        $beteiligungKommunal = new BeteiligungKommunalType();
        $publicParticipation = new BeteiligungKommunalOeffentlichkeitType();

        // Create an attachment that will cause issues during processing
        // Use a mock only for the part that needs to throw an exception
        $invalidAttachment = $this->createMock(MetadatenAnlageType::class);
        $invalidAttachment->method('getBezeichnung')->willReturn('Invalid Attachment');
        $invalidAttachment->method('getVersionsnummer')->willReturn('1.0');
        $invalidAttachment->method('getDatum')->willReturn(new DateTime('2024-01-01'));
        $invalidAttachment->method('getAnlageart')->willReturn(null);
        $invalidAttachment->method('getMimeType')->willReturn(null);
        $invalidAttachment->method('getDokument')->willReturn(null);

        // This will cause an exception when trying to create the AnlageValueObject
        $invalidAttachment->method('getAnhangOderVerlinkung')->willThrowException(new \Exception('Test exception'));

        $publicParticipation->setAnlagen([$invalidAttachment]);
        $beteiligungKommunal->setBeteiligungOeffentlichkeit($publicParticipation);
        $beteiligungKommunal->setBeteiligungTOEB(null);

        return $beteiligungKommunal;
    }

    private function createAttachmentWithAnhang(string $title, string $filename, string $documentId): MetadatenAnlageType
    {
        $attachment = new MetadatenAnlageType();
        $attachment->setBezeichnung($title);
        $attachment->setVersionsnummer(self::PUBLIC_VERSION);
        $attachment->setDatum(new DateTime(self::PUBLIC_DATE));

        $anlageart = new CodeVerfahrensunterlagetypType();
        $anlageart->setCode(self::DOCUMENT_TYPE_CODE);
        $attachment->setAnlageart($anlageart);

        $mimeType = new CodeXBauMimeTypeType();
        $mimeType->setCode(self::MIME_TYPE_PDF);
        $attachment->setMimeType($mimeType);

        $attachment->setDokument(null);

        // Create AnhangOderVerlinkung with Anhang
        $anhangOderVerlinkung = new AnhangOderVerlinkungType();

        $anhang = new MetadatenAnhangType();
        $anhang->setDokumentid($documentId);
        $anhang->setDateiname($filename);

        $anhangOderVerlinkung->setAnhang($anhang);
        $anhangOderVerlinkung->setUriVerlinkung(null);

        $attachment->setAnhangOderVerlinkung($anhangOderVerlinkung);

        return $attachment;
    }

    private function createAttachmentWithVerlinkung(string $title, string $url): MetadatenAnlageType
    {
        $attachment = new MetadatenAnlageType();
        $attachment->setBezeichnung($title);
        $attachment->setVersionsnummer(self::TOEB_VERSION);
        $attachment->setDatum(new DateTime(self::TOEB_DATE));
        $attachment->setAnlageart(null);

        $mimeType = new CodeXBauMimeTypeType();
        $mimeType->setCode(self::MIME_TYPE_PDF);
        $attachment->setMimeType($mimeType);

        $attachment->setDokument(null);

        // Create AnhangOderVerlinkung with Verlinkung
        $anhangOderVerlinkung = new AnhangOderVerlinkungType();
        $anhangOderVerlinkung->setAnhang(null);
        $anhangOderVerlinkung->setUriVerlinkung($url);

        $attachment->setAnhangOderVerlinkung($anhangOderVerlinkung);

        return $attachment;
    }

    private function createAttachmentWithBase64Content(string $title, string $base64Content): MetadatenAnlageType
    {
        $attachment = new MetadatenAnlageType();
        $attachment->setBezeichnung($title);
        $attachment->setVersionsnummer(self::PLANFEST_VERSION);
        $attachment->setDatum(new DateTime(self::PLANFEST_DATE));
        $attachment->setAnlageart(null);

        $mimeType = new CodeXBauMimeTypeType();
        $mimeType->setCode(self::MIME_TYPE_PDF);
        $attachment->setMimeType($mimeType);

        $attachment->setDokument($base64Content);
        $attachment->setAnhangOderVerlinkung(null);

        return $attachment;
    }
}
