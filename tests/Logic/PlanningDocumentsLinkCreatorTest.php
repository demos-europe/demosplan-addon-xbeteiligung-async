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

use DemosEurope\DemosplanAddon\Contracts\Entities\CustomerInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\SingleDocumentInterface;
use DemosEurope\DemosplanAddon\Contracts\FileServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\CustomerUrlGeneratorInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ParagraphServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\ValueObject\FileInfoInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Logic\PlanningDocumentsLinkCreator;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PlanningDocumentsLinkCreatorTest extends TestCase
{
    private CustomerUrlGeneratorInterface|MockObject $customerUrlGenerator;
    private ParagraphServiceInterface|MockObject $paragraphService;
    private CustomerInterface|MockObject $customer;

    protected function setUp(): void
    {
        $this->customerUrlGenerator = $this->createMock(CustomerUrlGeneratorInterface::class);
        $this->paragraphService = $this->createMock(ParagraphServiceInterface::class);
        $this->customer = $this->createMock(CustomerInterface::class);
    }

    public function testFileLinkIsGeneratedForCustomerOfProcedure(): void
    {
        $fileInfo = $this->createMock(FileInfoWithGetters::class);
        $fileInfo->method('getHash')->willReturn('hash-1');
        $fileInfo->method('getFileName')->willReturn('plan.pdf');
        $fileInfo->method('getContentType')->willReturn('application/pdf');
        $fileService = $this->createMock(FileServiceWithFileInfo::class);
        $fileService->method('getFileInfoFromFileString')->willReturn($fileInfo);

        $singleDocument = $this->createMock(SingleDocumentInterface::class);
        $singleDocument->method('getDocument')->willReturn('plan.pdf:file-id');
        $singleDocument->method('getVisible')->willReturn(true);
        $element = $this->createElement(ElementsInterface::ELEMENT_CATEGORIES['file']);
        $element->method('getDocuments')->willReturn(new ArrayCollection([$singleDocument]));

        $this->customerUrlGenerator->expects(self::once())
            ->method('generate')
            ->with($this->customer, 'core_file_procedure', ['procedureId' => 'proc-1', 'hash' => 'hash-1'])
            ->willReturn('https://customer.example/file');

        $documents = $this->createSut($fileService)->getPlanningDocuments($this->createProcedure($element));

        self::assertCount(1, $documents);
        self::assertSame(
            'https://customer.example/file',
            $documents[0]->getAnhangOderVerlinkung()->getUriVerlinkung()
        );
    }

    public function testParagraphLinkIsGeneratedForCustomerOfProcedure(): void
    {
        $element = $this->createElement(ElementsInterface::ELEMENT_CATEGORIES['paragraph']);
        $element->method('getId')->willReturn('element-1');
        $element->method('getFile')->willReturn('');

        $this->customerUrlGenerator->expects(self::once())
            ->method('generate')
            ->with(
                $this->customer,
                'DemosPlan_public_plandocument_paragraph',
                ['procedure' => 'proc-1', 'elementId' => 'element-1']
            )
            ->willReturn('https://customer.example/paragraph');

        $documents = $this->createSut($this->createMock(FileServiceInterface::class))
            ->getPlanningDocuments($this->createProcedure($element));

        self::assertCount(1, $documents);
        self::assertSame(
            'https://customer.example/paragraph',
            $documents[0]->getAnhangOderVerlinkung()->getUriVerlinkung()
        );
    }

    private function createSut(FileServiceInterface $fileService): PlanningDocumentsLinkCreator
    {
        return new PlanningDocumentsLinkCreator($fileService, $this->customerUrlGenerator, $this->paragraphService);
    }

    private function createElement(string $category): ElementsInterface|MockObject
    {
        $element = $this->createMock(ElementsInterface::class);
        $element->method('getEnabled')->willReturn(true);
        $element->method('getCategory')->willReturn($category);
        $element->method('getTitle')->willReturn('Title');

        return $element;
    }

    private function createProcedure(ElementsInterface $element): ProcedureInterface|MockObject
    {
        $procedure = $this->createMock(ProcedureInterface::class);
        $procedure->method('getId')->willReturn('proc-1');
        $procedure->method('getCustomer')->willReturn($this->customer);
        $procedure->method('getElements')->willReturn(new ArrayCollection([$element]));

        return $procedure;
    }
}

/**
 * The SDK only documents these methods with @method tags, which PHPUnit cannot configure.
 */
interface FileServiceWithFileInfo extends FileServiceInterface
{
    public function getFileInfoFromFileString(string $fileString): FileInfoInterface;
}

interface FileInfoWithGetters extends FileInfoInterface
{
    public function getHash(): string;

    public function getFileName(): string;

    public function getContentType(): string;
}
