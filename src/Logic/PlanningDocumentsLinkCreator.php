<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */


namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;


use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\SingleDocumentInterface;
use DemosEurope\DemosplanAddon\Contracts\FileServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ParagraphServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\ValueObject\FileInfoInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\Kernmodul\CodeXBauMimeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AnhangOderVerlinkungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AnlagenType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\CodeVerfahrensunterlagetypType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;

class PlanningDocumentsLinkCreator
{
    /** @var array<string, SingleDocumentInterface> $newSingleDocuments */
    private array $newSingleDocuments = [];
    /** @var array<string, SingleDocumentInterface> $updatedSingleDocuments */
    private array $updatedSingleDocuments = [];
    /** @var array<string, string<int, string>> $deletedSingleDocumentIds */
    private array $deletedSingleDocumentIds = [];

    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly RouterInterface $router,
        private readonly ParagraphServiceInterface $paragraphService
    )
    {
    }

    public function addNewSingleDocument(string $procedureId, SingleDocumentInterface $newSingleDocument): void
    {
        $this->newSingleDocuments[$procedureId] = $newSingleDocument;
    }

    public function addUpdatedSingleDocument(string $procedureId, SingleDocumentInterface $updatedSingleDocument): void
    {
        $this->updatedSingleDocuments[$procedureId] = $updatedSingleDocument;
    }

    public function addDeletedSingleDocument(string $procedureId, string $deletedSingleDocumentId): void
    {
        if (!array_key_exists($procedureId, $this->deletedSingleDocumentIds)) {
            $this->deletedSingleDocumentIds[$procedureId] = [];
        }
        $this->deletedSingleDocumentIds[$procedureId] =
            array_merge($this->deletedSingleDocumentIds[$procedureId], [$deletedSingleDocumentId]);
    }

    /**
     * @return null|array<int, AnlagenType>
     */
    public function getPlanningDocuments(ProcedureInterface $procedure): ?array
    {
        $planningDocumentsFromFiles = [];
        $planningDocumentsFromParagraphs = [];
        $elements = $procedure->getElements();
        foreach ($elements as $element) {
            if (!$element->getEnabled()) {
                continue;
            }
            if ($element->getCategory() === ElementsInterface::ELEMENT_CATEGORIES['file']) {
                $planningDocumentsFromFiles = $this->handleCategoryFile($element, $procedure->getId());
            }
            if ($element->getCategory() === ElementsInterface::ELEMENT_CATEGORIES['paragraph']) {
                $planningDocumentsFromParagraphs = $this->handleCategoryParagraph($element, $procedure->getId());
            }
        }

        $planningDocuments = array_merge($planningDocumentsFromFiles, $planningDocumentsFromParagraphs);

        if (0 === count($planningDocuments)) {
            return null;
        }

        $anlagenWrapper = new AnlagenType();
        $anlagenWrapper->setAnlage($planningDocuments);

        return [$anlagenWrapper];
    }

    private function handleCategoryFile(ElementsInterface $element, string $procedureId): array
    {
        $planningDocuments = [];
        // a new single doc was added
        if (array_key_exists($procedureId, $this->newSingleDocuments)
            && '' !== $this->newSingleDocuments[$procedureId]->getDocument()) {
            $planningDocuments[] = $this->createLinkForSingleDoc(
                $element->getTitle(),
                $this->fileService->getFileInfoFromFileString($this->newSingleDocuments[$procedureId]->getDocument()),
                $procedureId
            );
        }
        if (0 === count($element->getDocuments())) {
            return $planningDocuments;
        }

        foreach($element->getDocuments() as $singleDocument) {
            // a single doc was updated
            if (array_key_exists($procedureId, $this->updatedSingleDocuments)
                && $this->updatedSingleDocuments[$procedureId]->getVisible()
                && '' !== $this->updatedSingleDocuments[$procedureId]->getDocument()
                && $this->updatedSingleDocuments[$procedureId]->getId() === $singleDocument->getId()
            ) {
                $planningDocuments[] = $this->createLinkForSingleDoc(
                    $element->getTitle(),
                    $this->fileService->getFileInfoFromFileString($this->updatedSingleDocuments[$procedureId]->getDocument()),
                    $procedureId
                );
                continue;
            }

            // a single doc or more than one was deleted
            if ([] !== $this->deletedSingleDocumentIds
                && $this->isDocumentScheduledForDeletion($singleDocument, $procedureId)) {
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

    private function isDocumentScheduledForDeletion(SingleDocumentInterface $singleDocument, string $procedureId): bool
    {
        foreach ( $this->deletedSingleDocumentIds[$procedureId] as $singleDocumentToDeleteId) {
            if ($singleDocumentToDeleteId === $singleDocument->getId()) {
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
                UrlGeneratorInterface::ABSOLUTE_URL
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
                UrlGeneratorInterface::ABSOLUTE_URL
            )
        );
    }

    private function createMetadatenAnlageType(
        string $categoryName,
        string $fileName,
        string $contentType,
        string $fileUrl
    ): MetadatenAnlageType {
        $codeXBauMimeContentType = new CodeXBauMimeTypeType();
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
