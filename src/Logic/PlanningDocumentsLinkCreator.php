<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;


use DemosEurope\DemosplanAddon\Contracts\Entities\ElementsInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
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
    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly RouterInterface $router,
        private readonly ParagraphServiceInterface $paragraphService
    )
    {
    }

    /**
     * @return null|array<int, MetadatenAnlageType>
     */
    public function getPlanningDocuments(ProcedureInterface $procedure): ?array
    {
        $planningDocuments = [];
        $elements = $procedure->getElements();
        foreach ($elements as $element) {
            if (!$element->getEnabled()) {
                continue;
            }
            if ($element->getCategory() === ElementsInterface::ELEMENTS_CATEGORY_FILE) {
                $this->handleCategoryFile($element, $procedure->getId(), $planningDocuments);
            }
            if ($element->getCategory() === ElementsInterface::ELEMENTS_CATEGORY_PARAGRAPH) {
                $this->handleCategoryParagraph($element, $procedure->getId(), $planningDocuments);
            }
        }

        return 0 < count($planningDocuments) ? $planningDocuments : null;
    }

    private function handleCategoryFile(ElementsInterface $element, string $procedureId, array &$planningDocuments): void
    {
        if (0 === count($element->getDocuments())) {
            return;
        }

        foreach($element->getDocuments() as $singleDocument) {
            if ('' === $singleDocument->getDocument()) {
                continue;
            }

            $planningDocuments[] = $this->createLinkForSingleDoc(
                $element->getTitle(),
                $this->fileService->getFileInfoFromFileString($singleDocument->getDocument()),
                $procedureId
            );
        }
    }

    private function handleCategoryParagraph(ElementsInterface $element, string $procedureId, array &$planningDocuments): void
    {
        if (0 < $this->paragraphService->getParaDocumentObjectList($procedureId, $element->getId())) {
            $planningDocuments[] = $this->createLinkToParaDoc(
                $element->getTitle(),
                $element->getTitle(),
                $procedureId,
                $element->getId()
            );
        }

        if ('' === $element->getFile()) {
            return;
        }

        $planningDocuments[] = $this->createLinkForSingleDoc(
            $element->getTitle(),
            $this->fileService->getFileInfoFromFileString($element->getFile()),
            $procedureId
        );
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
