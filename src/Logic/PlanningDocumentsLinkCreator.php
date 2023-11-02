<?php
declare(strict_types=1);


namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic;


use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\FileServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ElementsServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\ValueObject\FileInfoInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\AnhangOderVerlinkungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\CodeXBauMimeTypeTypeType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\schema\MetadatenAnlageType;
use Symfony\Component\Routing\RouterInterface;

class PlanningDocumentsLinkCreator
{
    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly RouterInterface $router,
        private readonly ElementsServiceInterface $elementsService
    )
    {
    }

    /**
     * @return null|array<int, MetadatenAnlageType>
     */
    public function getPlanningDocuments(ProcedureInterface $procedure): ?array
    {
        $planningDocuments = [];
        $elements = $this->elementsService->getEnabledFileAndParagraphElements($procedure->getId(), null);
        if(count($elements) > 0) {
            foreach($elements as $element) {
                $this->handleCategoryFile($element, $procedure->getId(), $planningDocuments);

                $this->handleCategoryParagraph($element, $procedure->getId(), $planningDocuments);
            }
        }

        return 0 < count($planningDocuments) ? $planningDocuments : null;
    }

    private function handleCategoryFile(array $element, string $procedureId, array &$planningDocuments): void
    {
        if (0 === count($element['documents'])) {
            return;
        }

        foreach($element['documents'] as $singleDocument) {
            if ('' === $singleDocument['document']) {
                continue;
            }
            $planningDocuments[] = $this->createLinkForSingleDoc(
                $singleDocument['title'],
                $this->fileService->getFileInfoFromFileString($singleDocument['document']),
                $procedureId
            );
        }
    }

    private function handleCategoryParagraph(array $element, string $procedureId, array &$planningDocuments): void
    {
        if ('' === $element['file']) {
            return;
        }
        $planningDocuments[] = $this->createLinkForSingleDoc(
            $element['title'],
            $this->fileService->getFileInfoFromFileString($element['file']),
            $procedureId
        );
        if ($element['hasParagraphs']) {
            $planningDocuments[] = $this->createLinkToParaDoc(
                $element['title'],
                $procedureId,
                $element['id']
            );
        }
    }

    private function createLinkForSingleDoc(
        string $categoryName,
        FileInfoInterface $fileInfo,
        string $procedureId,
    ): MetadatenAnlageType {
        return $this->createMetadatenAnlageType(
            $categoryName,
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
        string $procedureId,
        string $elementId
    ): MetadatenAnlageType {
        return $this->createMetadatenAnlageType(
            $categoryName,
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
        string $contentType,
        string $fileUrl
    ): MetadatenAnlageType {
        $codeXBauMimeContentType = new CodeXBauMimeTypeTypeType();
        $codeXBauMimeContentType->setCode($contentType);
        $codeXBauMimeContentType->setListURI('');
        $codeXBauMimeContentType->setListVersionID('');

        $link = new AnhangOderVerlinkungType();
        $link->setUriVerlinkung($fileUrl);

        $metadatenAnlageType = new MetadatenAnlageType();
        $metadatenAnlageType->setBezeichnung($categoryName);
        $metadatenAnlageType->setMimeType($codeXBauMimeContentType);
        $metadatenAnlageType->setAnhangOderVerlinkung($link);

        return $metadatenAnlageType;
    }
}
