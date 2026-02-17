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
use DemosEurope\DemosplanAddon\Contracts\FileServiceInterface;
use DemosEurope\DemosplanAddon\Contracts\Handler\SingleDocumentHandlerInterface;
use DemosEurope\DemosplanAddon\Contracts\Services\ElementsServiceInterface;
use DemosEurope\DemosplanAddon\XBeteiligung\Entity\XBeteiligungFileMapping;
use DemosEurope\DemosplanAddon\XBeteiligung\Repository\XBeteiligungFileMappingRepository;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\AnlageValueObject;
use Exception;
use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use ReflectionException;
use RuntimeException;

/**
 * Service for handling XBeteiligung file attachments and document management.
 */
class XBeteiligungAttachmentService
{
    private const LOG_PREFIX = 'XBeteiligung Attachment Service: ';

    // Default values
    private const DEFAULT_CATEGORY_NAME = 'Nicht zugeordnet';

    // Element and file handling
    private const ELEMENT_CATEGORY_FILE = 'file';
    private const FILENAME_PREFIX = 'xbeteiligung';

    // Document actions
    private const ACTION_SINGLE_DOCUMENT_NEW = 'singledocumentnew';
    private const STATEMENT_ENABLED = '0';

    // Array keys for element data
    private const KEY_PROCEDURE_ID = 'pId';
    private const KEY_CATEGORY = 'category';
    private const KEY_TITLE = 'title';
    private const KEY_TEXT = 'text';
    private const KEY_ENABLED = 'enabled';

    // Array keys for document data
    private const KEY_ACTION = 'action';
    private const KEY_DOCUMENT_TITLE = 'r_title';
    private const KEY_DOCUMENT_TEXT = 'r_text';
    private const KEY_DOCUMENT_FILE = 'r_document';
    private const KEY_STATEMENT_ENABLED = 'r_statement_enabled';

    // Result keys
    private const KEY_IDENT = 'ident';

    public function __construct(
        private readonly FileServiceInterface $fileService,
        private readonly ElementsServiceInterface $elementsService,
        private readonly SingleDocumentHandlerInterface $singleDocumentHandler,
        private readonly LoggerInterface $logger,
        private readonly XBeteiligungFileMappingRepository $fileMappingRepository
    ) {
    }

    /**
     * Process and save all attachments (Anlagen) from XBeteiligung message to procedure.
     *
     * @param ProcedureInterface   $procedure           The target procedure
     * @param AnlageValueObject[] $anlagenValueObject Array of attachment value objects
     */
    public function saveAnlagenToProcedureCategories(
        ProcedureInterface $procedure,
        array $anlagenValueObject
    ): void {
        foreach ($anlagenValueObject as $anlage) {
            try {
                if (false === $this->isAttachmentValid($anlage, $procedure)) {
                    continue;
                }

                $this->processSingleAttachment($anlage, $procedure);
            } catch (Exception $e) {
                $this->logAttachmentProcessingError($e, $anlage, $procedure);
            }
        }
    }

    /**
     * Save or update attachments with file tracking for 402 message handling.
     *
     * This method extends saveAnlagenToProcedureCategories with file replacement logic:
     * - If dokumentId exists and file mapping found: replaces existing file
     * - If dokumentId exists but no mapping: creates new file and tracks it
     * - If no dokumentId: creates new file without tracking (backward compatibility)
     *
     * @param ProcedureInterface   $procedure          The target procedure
     * @param AnlageValueObject[] $anlagenValueObject Array of attachment value objects
     */
    public function saveOrUpdateAnlagenToProcedureCategories(
        ProcedureInterface $procedure,
        array $anlagenValueObject
    ): void {
        foreach ($anlagenValueObject as $anlage) {
            try {
                if (false === $this->isAttachmentValid($anlage, $procedure)) {
                    continue;
                }

                $dokumentId = $anlage->getDocumentId();

                // Check if dokumentId exists and if we have a mapping for it
                if (null !== $dokumentId && '' !== $dokumentId) {
                    $existingMapping = $this->fileMappingRepository->findByXmlFileIdAndProcedure(
                        $dokumentId,
                        $procedure->getId()
                    );

                    if (null !== $existingMapping) {
                        // Replace existing file
                        $this->replaceExistingAttachment($anlage, $procedure, $existingMapping);
                        continue;
                    }
                }

                // Create new file (either no dokumentId or no existing mapping)
                // This saves the attachment and creates the single document
                $element = $this->ensureDocumentCategory($procedure, $anlage->getDocumentCategoryName());
                $fileString = $this->saveAttachment($anlage, $procedure->getId());
                $singleDocumentId = $this->createSingleDocument($procedure, $element, $anlage->getFileName(), $fileString);

                $this->logger->info(self::LOG_PREFIX.'Successfully saved Anlage to procedure', [
                    'filename' => $anlage->getFileName(),
                    'procedureId' => $procedure->getId(),
                    'categoryName' => $element->getTitle(),
                    'singleDocumentId' => $singleDocumentId,
                ]);

                // Track mapping if dokumentId exists
                if (null !== $dokumentId && '' !== $dokumentId && null !== $singleDocumentId) {
                    $fileId = $this->extractFileIdFromFileString($fileString);
                    $this->trackFileMapping($dokumentId, $procedure->getId(), $fileId, $singleDocumentId);
                }
            } catch (Exception $e) {
                $this->logAttachmentProcessingError($e, $anlage, $procedure);
            }
        }
    }

    /**
     * Validate an attachment before processing.
     *
     * @param AnlageValueObject  $anlage    The attachment to validate
     * @param ProcedureInterface $procedure The procedure context for logging
     *
     * @return bool True if valid, false if should be skipped
     *
     * @throws InvalidArgumentException If validation fails
     */
    private function isAttachmentValid(
        AnlageValueObject $anlage,
        ProcedureInterface $procedure
    ): bool {
        if (!$anlage->isBase64Encoded()) {
            $this->logger->warning(self::LOG_PREFIX.'Skipping non-base64 encoded Anlage', [
                'filename' => $anlage->getFileName(),
                'procedureId' => $procedure->getId(),
            ]);

            return false;
        }

        $fileName = $anlage->getFileName();
        if (null === $fileName || '' === $fileName) {
            throw new InvalidArgumentException(
                'Filename cannot be null or empty'
            );
        }

        if (null === $anlage->getBase64Content() || '' === $anlage->getBase64Content()) {
            throw new InvalidArgumentException(
                'Empty file content for file: '.$fileName
            );
        }

        return true;
    }

    /**
     * Process a single validated attachment.
     *
     * @param AnlageValueObject  $anlage    The attachment to process
     * @param ProcedureInterface $procedure The target procedure
     *
     * @return string|null The created SingleDocument ID, or null if creation failed
     *
     * @throws ReflectionException
     */
    private function processSingleAttachment(
        AnlageValueObject $anlage,
        ProcedureInterface $procedure
    ): ?string {
        $element = $this->ensureDocumentCategory($procedure, $anlage->getDocumentCategoryName());
        $fileString = $this->saveAttachment($anlage, $procedure->getId());
        $singleDocumentId = $this->createSingleDocument($procedure, $element, $anlage->getFileName(), $fileString);

        $this->logger->info(self::LOG_PREFIX.'Successfully saved Anlage to procedure', [
            'filename' => $anlage->getFileName(),
            'procedureId' => $procedure->getId(),
            'categoryName' => $element->getTitle(),
            'singleDocumentId' => $singleDocumentId,
        ]);

        return $singleDocumentId;
    }

    /**
     * Log an error that occurred during attachment processing.
     *
     * @param Exception          $exception The exception that occurred
     * @param AnlageValueObject  $anlage    The attachment being processed
     * @param ProcedureInterface $procedure The procedure context
     */
    private function logAttachmentProcessingError(
        Exception $exception,
        AnlageValueObject $anlage,
        ProcedureInterface $procedure
    ): void {
        $this->logger->error(self::LOG_PREFIX.'Failed to process Anlage', [
            'filename' => $anlage->getFileName(),
            'procedureId' => $procedure->getId(),
            'error' => $exception->getMessage(),
            'exception' => $exception,
        ]);
    }

    /**
     * Ensure a document category element exists for the procedure.
     *
     * @param ProcedureInterface $procedure    The procedure
     * @param string|null        $categoryName The category name (defaults to 'Nicht zugeordnet')
     *
     * @return ElementsInterface The element representing the document category
     */
    private function ensureDocumentCategory(
        ProcedureInterface $procedure,
        ?string $categoryName
    ): ElementsInterface {
        if (null === $categoryName || '' === $categoryName) {
            $categoryName = self::DEFAULT_CATEGORY_NAME;
        }

        $element = $this->findDocumentCategory($procedure, $categoryName);

        return $element ?? $this->createDocumentCategory($procedure, $categoryName);
    }

    /**
     * Find an existing document category element in the procedure.
     *
     * @param ProcedureInterface $procedure    The procedure
     * @param string             $categoryName The category name
     *
     * @return ElementsInterface|null The element or null if not found
     */
    private function findDocumentCategory(
        ProcedureInterface $procedure,
        string $categoryName
    ): ?ElementsInterface {
        /** @var ElementsInterface[] $existingElements */
        $existingElements = $this->elementsService->getElementsAdminList($procedure->getId());

        foreach ($existingElements as $element) {
            if (self::ELEMENT_CATEGORY_FILE === $element->getCategory()
                && $categoryName === $element->getTitle()
                && false === $element->getDeleted()) {
                return $element;
            }
        }

        return null;
    }

    /**
     * Create a new document category element for the procedure.
     *
     * @param ProcedureInterface $procedure    The procedure
     * @param string             $categoryName The category name
     *
     * @return ElementsInterface The created element
     */
    private function createDocumentCategory(
        ProcedureInterface $procedure,
        string $categoryName
    ): ElementsInterface {
        $elementData = [
            self::KEY_PROCEDURE_ID => $procedure->getId(),
            self::KEY_CATEGORY => self::ELEMENT_CATEGORY_FILE,
            self::KEY_TITLE => $categoryName,
            self::KEY_TEXT => '',
            self::KEY_ENABLED => false,
        ];

        $result = $this->elementsService->addElement($elementData);

        return $this->elementsService->getElementObject($result[self::KEY_IDENT]);
    }

    /**
     * Save an XBeteiligung attachment to file storage.
     *
     * @param AnlageValueObject $anlage      The attachment value object
     * @param string            $procedureId The procedure ID to associate the file with
     *
     * @return string The file string in format: filename:file_id:size:mimetype
     *
     * @throws RuntimeException If the file string is empty
     */
    private function saveAttachment(AnlageValueObject $anlage, string $procedureId): string
    {
        $this->logger->info(self::LOG_PREFIX.'Saving attachment', [
            'filename' => $anlage->getFileName(),
            'content_length' => strlen($anlage->getBase64Content()),
            'documentCategoryName' => $anlage->getDocumentCategoryName(),
            'procedureId' => $procedureId,
        ]);

        // Save file using FileService with binary content
        // SOAP library automatically decodes base64 content from XML
        $file = $this->fileService->saveBinaryFileContent(
            $anlage->getFileName(),
            $anlage->getBase64Content(),
            self::FILENAME_PREFIX,
            null,
            $procedureId
        );

        // Get file string in format: filename:file_id:size:mimetype
        $fileString = $file->getFileString();
        if ('' === $fileString) {
            throw new RuntimeException(
                'FileService returned empty file string for file: '.$anlage->getFileName()
            );
        }

        $this->logger->info(self::LOG_PREFIX.'Successfully saved attachment', [
            'filename' => $anlage->getFileName(),
            'procedureId' => $procedureId,
            'fileId' => $file->getId(),
        ]);

        return $fileString;
    }

    /**
     * Create a SingleDocument entry for a file in the procedure.
     *
     * @param ProcedureInterface $procedure The procedure
     * @param ElementsInterface  $element   The document category element
     * @param string             $filename  The filename
     * @param string             $fileString The file string (filename:file_id:size:mimetype)
     *
     * @return string|null The created SingleDocument ID, or null if not found in result
     *
     * @throws ReflectionException
     */
    private function createSingleDocument(
        ProcedureInterface $procedure,
        ElementsInterface $element,
        string $filename,
        string $fileString
    ): ?string {
        // Use SingleDocumentHandler which has a contract interface
        $data = [
            self::KEY_ACTION => self::ACTION_SINGLE_DOCUMENT_NEW,
            self::KEY_DOCUMENT_TITLE => $filename,
            self::KEY_DOCUMENT_TEXT => '',
            self::KEY_DOCUMENT_FILE => $fileString, // Format: filename:file_id:size:mimetype
            self::KEY_STATEMENT_ENABLED => self::STATEMENT_ENABLED, // Required field
        ];

        $result = $this->singleDocumentHandler->administrationDocumentNewHandler(
            $procedure->getId(),
            $element->getId(),
            $element->getId(),
            $data
        );

        // Extract document ID from result
        return $result[self::KEY_IDENT] ?? null;
    }

    /**
     * Replace an existing attachment with new content from 402 update message.
     *
     * @param AnlageValueObject        $anlage          The new attachment data
     * @param ProcedureInterface       $procedure       The procedure
     * @param XBeteiligungFileMapping $existingMapping The existing file mapping
     *
     * @throws ReflectionException
     */
    private function replaceExistingAttachment(
        AnlageValueObject $anlage,
        ProcedureInterface $procedure,
        XBeteiligungFileMapping $existingMapping
    ): void {
        // Save new file version
        $fileString = $this->saveAttachment($anlage, $procedure->getId());
        $newFileId = $this->extractFileIdFromFileString($fileString);

        // Update the SingleDocument with new file
        $updateData = [
            'action' => 'singledocumentedit',
            self::KEY_DOCUMENT_TITLE => $anlage->getFileName(),
            self::KEY_DOCUMENT_FILE => $fileString,
        ];

        $result = $this->singleDocumentHandler->administrationDocumentEditHandler(
            $existingMapping->getSingleDocumentId(),
            $updateData
        );

        $newSingleDocumentId = $result[self::KEY_IDENT] ?? $existingMapping->getSingleDocumentId();

        // Update mapping with new file IDs
        $existingMapping->setFileId($newFileId);
        $existingMapping->setSingleDocumentId($newSingleDocumentId);
        $this->fileMappingRepository->save($existingMapping);

        $this->logger->info(self::LOG_PREFIX.'Successfully replaced existing attachment', [
            'filename' => $anlage->getFileName(),
            'procedureId' => $procedure->getId(),
            'xmlFileId' => $anlage->getDocumentId(),
            'oldFileId' => $existingMapping->getFileId(),
            'newFileId' => $newFileId,
            'singleDocumentId' => $newSingleDocumentId,
        ]);
    }

    /**
     * Track or update file mapping between XML dokumentId and demosplan file entities.
     *
     * @param string $xmlFileId        The dokumentId from XML
     * @param string $procedureId      The procedure ID
     * @param string $fileId           The File entity ID (_f_ident)
     * @param string $singleDocumentId The SingleDocument ID (_sd_id)
     */
    private function trackFileMapping(
        string $xmlFileId,
        string $procedureId,
        string $fileId,
        string $singleDocumentId
    ): void {
        try {
            $mapping = $this->fileMappingRepository->findByXmlFileIdAndProcedure($xmlFileId, $procedureId);

            if (null === $mapping) {
                // Create new mapping
                $mapping = new XBeteiligungFileMapping();
                $mapping->setXmlFileId($xmlFileId);
                $mapping->setProcedureId($procedureId);
                $mapping->setFileId($fileId);
                $mapping->setSingleDocumentId($singleDocumentId);

                $this->logger->info(self::LOG_PREFIX.'Created file mapping', [
                    'xmlFileId' => $xmlFileId,
                    'procedureId' => $procedureId,
                    'fileId' => $fileId,
                    'singleDocumentId' => $singleDocumentId,
                ]);
            } else {
                // Update existing mapping
                $mapping->setFileId($fileId);
                $mapping->setSingleDocumentId($singleDocumentId);

                $this->logger->info(self::LOG_PREFIX.'Updated file mapping', [
                    'xmlFileId' => $xmlFileId,
                    'procedureId' => $procedureId,
                    'fileId' => $fileId,
                    'singleDocumentId' => $singleDocumentId,
                ]);
            }

            $this->fileMappingRepository->save($mapping);
        } catch (Exception $e) {
            $this->logger->error(self::LOG_PREFIX.'Failed to track file mapping', [
                'xmlFileId' => $xmlFileId,
                'procedureId' => $procedureId,
                'fileId' => $fileId,
                'singleDocumentId' => $singleDocumentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Extract file ID from fileString format: filename:file_id:size:mimetype.
     *
     * @param string $fileString The file string from FileService
     *
     * @return string The extracted file ID
     *
     * @throws RuntimeException If fileString format is invalid
     */
    private function extractFileIdFromFileString(string $fileString): string
    {
        $parts = explode(':', $fileString);

        if (count($parts) < 2) {
            throw new RuntimeException(
                'Invalid fileString format. Expected "filename:file_id:size:mimetype", got: '.$fileString
            );
        }

        return $parts[1];
    }
}
