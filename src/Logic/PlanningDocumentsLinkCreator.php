<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;


use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\SingleDocumentInterface;
use DemosEurope\DemosplanAddon\Contracts\FileServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ParagraphServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\ValueObject\FileInfoInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeVerfahrensunterlagetypType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType;
use Symfony\Component\Routing\RouterInterface;

class PlanningDocumentsLinkCreator
{
    private ?SingleDocumentInterface $newSingleDocument = null;
    private ?SingleDocumentInterface $updatedSingleDocument = null;
    /** @var string[]|null $deletedSingleDocumentIds */
    private ?array $deletedSingleDocumentIds = null;

    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly RouterInterface $router,
        private readonly ParagraphServiceInterface $paragraphService
    )
    {
    }

    public function setNewSingleDocument(SingleDocumentInterface $newSingleDocument): void
    {
        $this->newSingleDocument = $newSingleDocument;
    }

    public function setUpdatedSingleDocument(SingleDocumentInterface $updatedSingleDocument): void
    {
        $this->updatedSingleDocument = $updatedSingleDocument;
    }

    public function addDeletedSingleDocument(string $deletedSingleDocumentId): void
    {
        if (null === $this->deletedSingleDocumentIds) {
            $this->deletedSingleDocumentIds = [$deletedSingleDocumentId];
            return;
        }

        $this->deletedSingleDocumentIds = array_merge($this->deletedSingleDocumentIds, [$deletedSingleDocumentId]);
    }

    /**
     * @return null|array<int, MetadatenAnlageType>
     */
    public function getPlanningDocuments(ProcedureInterface $procedure): ?array
    {
        $planningDocuments = [];
        $planningDocumentsFromFiles = [];
        $planningDocumentsFromParagraphs = [];
        $elements = $procedure->getElements();
        foreach ($elements as $element) {
            if (!$element->getEnabled()) {
                continue;
            }
            if ($element->getCategory() === ElementsInterface::ELEMENTS_CATEGORY_FILE) {
                $planningDocumentsFromFiles = $this->handleCategoryFile($element, $procedure->getId());
            }
            if ($element->getCategory() === ElementsInterface::ELEMENTS_CATEGORY_PARAGRAPH) {
                $planningDocumentsFromParagraphs = $this->handleCategoryParagraph($element, $procedure->getId());
            }
        }

        $planningDocuments = array_merge($planningDocumentsFromFiles, $planningDocumentsFromParagraphs);

        return 0 < count($planningDocuments) ? $planningDocuments : null;
    }

    private function handleCategoryFile(ElementsInterface $element, string $procedureId): array
    {
        $planningDocuments = [];
        // a new single doc was added
        if (null !== $this->newSingleDocument && '' !== $this->newSingleDocument->getDocument()) {
            $planningDocuments[] = $this->createLinkForSingleDoc(
                $element->getTitle(),
                $this->fileService->getFileInfoFromFileString($this->newSingleDocument->getDocument()),
                $procedureId
            );
        }
        if (0 === count($element->getDocuments())) {
            return $planningDocuments;
        }

        foreach($element->getDocuments() as $singleDocument) {
            // a single doc was updated
            if (null !== $this->updatedSingleDocument
                && $this->updatedSingleDocument->getVisible()
                && '' !== $this->updatedSingleDocument->getDocument()
                && $this->updatedSingleDocument->getId() === $singleDocument->getId()
            ) {
                $planningDocuments[] = $this->createLinkForSingleDoc(
                    $element->getTitle(),
                    $this->fileService->getFileInfoFromFileString($this->updatedSingleDocument->getDocument()),
                    $procedureId
                );
                continue;
            }

            // a single doc or more than one was deleted
            if (null !== $this->deletedSingleDocumentIds && $this->isDocumentScheduledForDeletion($singleDocument)) {
                continue;
            }

            // all not changed single docs
            if ('' === $singleDocument->getDocument() || !$singleDocument->getVisible()) {
                continue;
            }

            $planningDocuments[] = $this->createLinkForSingleDoc(
                $element->getTitle(),
                $this->fileService->getFileInfoFromFileString($singleDocument->getDocument()),
                $procedureId
            );
        }


        return $planningDocuments;
    }

    private function isDocumentScheduledForDeletion(SingleDocumentInterface $singleDocument): bool
    {
        foreach ($this->deletedSingleDocumentIds as $singleDocumentToDelete) {
            if ($singleDocumentToDelete === $singleDocument->getId()) {
                return true;
            }
        }

        return false;
    }

    private function handleCategoryParagraph(ElementsInterface $element, string $procedureId): array
    {
        $planningDocuments = [];

        if (0 < $this->paragraphService->getParaDocumentObjectList($procedureId, $element->getId())) {
            $planningDocuments[] = $this->createLinkToParaDoc(
                $element->getTitle(),
                $element->getTitle(),
                $procedureId,
                $element->getId()
            );
        }

        if ('' === $element->getFile()) {
            return $planningDocuments;
        }

        $planningDocuments[] = $this->createLinkForSingleDoc(
            $element->getTitle(),
            $this->fileService->getFileInfoFromFileString($element->getFile()),
            $procedureId
        );


        return $planningDocuments;
    }

    private function createLinkForSingleDoc(
        string $categoryName,
        FileInfoInterface $fileInfo,
        string $procedureId,
    ): MetadatenAnlageType {
        return $this->createMetadatenAnlageType(
            $categoryName,
            $fileInfo->getFileName(),
            $fileInfo->getContentType(),
            $this->router->generate(
                'core_file_procedure',
                ['procedureId' => $procedureId, 'hash' => $fileInfo->getHash()],
                RouterInterface::ABSOLUTE_URL
            )
        );
    }

    private function createLinkToParaDoc(
        string $categoryName,
        string $fileName,
        string $procedureId,
        string $elementId
    ): MetadatenAnlageType {
        return $this->createMetadatenAnlageType(
            $categoryName,
            $fileName,
            'text/html',
            $this->router->generate(
                'DemosPlan_public_plandocument_paragraph',
                ['procedure' => $procedureId, 'elementId' => $elementId],
                RouterInterface::ABSOLUTE_URL
            )
        );
    }

    private function createMetadatenAnlageType(
        string $categoryName,
        string $fileName,
        string $contentType,
        string $fileUrl
    ): MetadatenAnlageType {
        $codeXBauMimeContentType = new CodeXBauMimeTypeTypeType();
        $codeXBauMimeContentType->setCode($contentType);
        $codeXBauMimeContentType->setListURI('');
        $codeXBauMimeContentType->setListVersionID('');

        $link = new AnhangOderVerlinkungType();
        $link->setUriVerlinkung($fileUrl);

        $codeVerfahrensUnterlageType = new CodeVerfahrensunterlagetypType();
        $codeVerfahrensUnterlageType->setName($categoryName);
        $codeVerfahrensUnterlageType->setListVersionID('');
        $codeVerfahrensUnterlageType->setCode('');

        $metadatenAnlageType = new MetadatenAnlageType();
        $metadatenAnlageType->setBezeichnung($fileName);
        $metadatenAnlageType->setMimeType($codeXBauMimeContentType);
        $metadatenAnlageType->setAnhangOderVerlinkung($link);
        $metadatenAnlageType->setAnlageart($codeVerfahrensUnterlageType);

        return $metadatenAnlageType;
    }
}
