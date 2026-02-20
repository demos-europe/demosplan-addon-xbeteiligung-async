<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Tests\Logic;

use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\FileInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\FileServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Handler\SingleDocumentHandlerInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ElementsServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungFileMapping;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\XBeteiligungAttachmentService;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungFileMappingRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\AnlageValueObject;
use PHPUnit\Framework\Attributes\AllowMockObjectsWithoutExpectations;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use RuntimeException;

#[AllowMockObjectsWithoutExpectations]
class XBeteiligungAttachmentServiceTest extends TestCase
{
    private MockObject $fileService;
    private MockObject $elementsService;
    private MockObject $singleDocumentHandler;
    private MockObject $logger;
    private MockObject $fileMappingRepository;
    private XBeteiligungAttachmentService $sut;

    private const PROCEDURE_ID = 'proc-uuid-1234';
    private const ELEMENT_ID = 'element-uuid-5678';
    private const DOC_ID = 'doc-uuid-9012';
    private const FILE_ID_OLD = 'file-uuid-old';
    private const FILE_ID_NEW = 'file-uuid-new';
    private const XML_FILE_ID = 'xml-doc-id-abc';
    private const FILE_STRING_NEW = 'test.pdf:file-uuid-new:12345:application/pdf';
    private const CATEGORY_NAME = 'Test Category';

    protected function setUp(): void
    {
        $this->fileService = $this->createMock(FileServiceInterface::class);
        $this->elementsService = $this->createMock(ElementsServiceInterface::class);
        $this->singleDocumentHandler = $this->createMock(SingleDocumentHandlerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fileMappingRepository = $this->createMock(XBeteiligungFileMappingRepository::class);

        $this->sut = new XBeteiligungAttachmentService(
            $this->fileService,
            $this->elementsService,
            $this->singleDocumentHandler,
            $this->logger,
            $this->fileMappingRepository
        );
    }

    public function testNewAttachmentWithoutDokumentIdIsAddedWithoutMapping(): void
    {
        $anlage = $this->createAnlage(null);
        $procedure = $this->createProcedureMock();

        $this->setupExistingCategory();
        $this->fileService->method('saveBinaryFileContent')
            ->willReturn($this->createFileMock(self::FILE_STRING_NEW, self::FILE_ID_NEW));
        $this->singleDocumentHandler->method('administrationDocumentNewHandler')
            ->willReturn(['ident' => self::DOC_ID]);

        $this->fileMappingRepository->expects($this->never())->method('save');

        $this->sut->applyAnlagenToProcedure($procedure, [$anlage]);
    }

    public function testNewAttachmentWithDokumentIdCreatesFileMapping(): void
    {
        $anlage = $this->createAnlage(self::XML_FILE_ID);
        $procedure = $this->createProcedureMock();

        $this->setupExistingCategory();
        $this->fileService->method('saveBinaryFileContent')
            ->willReturn($this->createFileMock(self::FILE_STRING_NEW, self::FILE_ID_NEW));
        $this->singleDocumentHandler->method('administrationDocumentNewHandler')
            ->willReturn(['ident' => self::DOC_ID]);

        // findByXmlFileIdAndProcedure is called twice: once in applyAnlagenToProcedure (outer
        // check) and once inside trackFileMapping - both return null for a new attachment
        $this->fileMappingRepository->method('findByXmlFileIdAndProcedure')->willReturn(null);

        $this->fileMappingRepository
            ->expects($this->once())
            ->method('save')
            ->with(self::callback(fn (XBeteiligungFileMapping $mapping) =>
                $mapping->getXmlFileId() === self::XML_FILE_ID
                && $mapping->getProcedureId() === self::PROCEDURE_ID
                && $mapping->getFileId() === self::FILE_ID_NEW
                && $mapping->getSingleDocumentId() === self::DOC_ID
            ))
            ->willReturnCallback(fn (XBeteiligungFileMapping $m) => $m);

        $this->sut->applyAnlagenToProcedure($procedure, [$anlage]);
    }

    public function testExistingAttachmentIsReplacedAndOldFileIsDeleted(): void
    {
        $anlage = $this->createAnlage(self::XML_FILE_ID);
        $procedure = $this->createProcedureMock();

        $existingMapping = (new XBeteiligungFileMapping())
            ->setXmlFileId(self::XML_FILE_ID)
            ->setProcedureId(self::PROCEDURE_ID)
            ->setFileId(self::FILE_ID_OLD)
            ->setSingleDocumentId(self::DOC_ID);

        $this->fileMappingRepository->method('findByXmlFileIdAndProcedure')
            ->with(self::XML_FILE_ID, self::PROCEDURE_ID)
            ->willReturn($existingMapping);

        $this->fileService->method('saveBinaryFileContent')
            ->willReturn($this->createFileMock(self::FILE_STRING_NEW, self::FILE_ID_NEW));
        $this->singleDocumentHandler->method('administrationDocumentEditHandler')
            ->willReturn(['ident' => self::DOC_ID]);

        $this->fileService->expects($this->once())->method('deleteFile')->with(self::FILE_ID_OLD);
        $this->fileMappingRepository->expects($this->once())->method('save')
            ->willReturnCallback(fn (XBeteiligungFileMapping $m) => $m);

        $this->sut->applyAnlagenToProcedure($procedure, [$anlage]);

        self::assertSame(self::FILE_ID_NEW, $existingMapping->getFileId());
    }

    public function testNonBase64AttachmentIsSkipped(): void
    {
        $anlage = new AnlageValueObject('Title', '1.0', null, 'code', self::CATEGORY_NAME, 'application/pdf', 'data');
        $anlage->setDateiname('test.pdf');
        // isBase64 defaults to false

        $this->fileService->expects($this->never())->method('saveBinaryFileContent');
        $this->fileMappingRepository->expects($this->never())->method('save');

        $this->sut->applyAnlagenToProcedure($this->createProcedureMock(), [$anlage]);
    }

    public function testAttachmentProcessingExceptionIsCaughtAndLogged(): void
    {
        $anlage = $this->createAnlage(null);
        $procedure = $this->createProcedureMock();

        $this->setupExistingCategory();
        $this->fileService->method('saveBinaryFileContent')
            ->willThrowException(new RuntimeException('Storage failure'));

        $this->logger->expects($this->once())->method('error');

        $this->sut->applyAnlagenToProcedure($procedure, [$anlage]);
    }

    private function createAnlage(?string $dokumentId, string $filename = 'test.pdf'): AnlageValueObject
    {
        $anlage = new AnlageValueObject(
            'Test Document', '1.0', null, 'code', self::CATEGORY_NAME, 'application/pdf',
            base64_encode('file content')
        );
        $anlage->setDateiname($filename);
        $anlage->setIsBase64(true);
        $anlage->setDokumentId($dokumentId);

        return $anlage;
    }

    private function createProcedureMock(): MockObject
    {
        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn(self::PROCEDURE_ID);

        return $procedure;
    }

    private function createFileMock(string $fileString, string $fileId): MockObject
    {
        $file = $this->createMock(FileInterface::class);
        $file->method('getFileString')->willReturn($fileString);
        $file->method('getId')->willReturn($fileId);

        return $file;
    }

    private function setupExistingCategory(): void
    {
        $element = $this->createMock(ElementsInterface::class);
        $element->method('getId')->willReturn(self::ELEMENT_ID);
        $element->method('getTitle')->willReturn(self::CATEGORY_NAME);
        $element->method('getCategory')->willReturn('file');
        $element->method('getDeleted')->willReturn(false);

        $this->elementsService->method('getElementsAdminList')->willReturn([$element]);
        $this->elementsService->method('addElement')->willReturn(['ident' => self::ELEMENT_ID]);
        $this->elementsService->method('getElementObject')->willReturn($element);
    }
}
