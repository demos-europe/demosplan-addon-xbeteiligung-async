<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\Logic\Kommunale;

use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\AnhangOderVerlinkungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungKommunalType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\BeteiligungPlanfeststellungType;
use DemosEurope\DemosplanAddon\XBeteiligung\Soap\Schema\XBeteiligung\MetadatenAnlageType;
use DemosEurope\DemosplanAddon\XBeteiligung\ValueObject\AnlageValueObject;
use Exception;
use Psr\Log\LoggerInterface;

/**
 * Extracts attachment data (Anlagen) from XBeteiligung messages.
 * Handles both Kommunal and Planfeststellung participation types.
 * Supports both public participation (Öffentlichkeitsbeteiligung) and TOEB attachments.
 */
class AnlagenExtractor
{
    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    /**
     * Extracts all attachments from either BeteiligungKommunalType or BeteiligungPlanfeststellungType.
     * Returns an array of AnlageValueObject instances from both public and TOEB participation.
     *
     * @return AnlageValueObject[]
     */
    public function extract(BeteiligungKommunalType|BeteiligungPlanfeststellungType $beteiligung): array
    {
        $anlagen = [];

        $anlagen = array_merge(
            $anlagen,
            $this->extractPublicParticipationAttachments($beteiligung)
        );

        $anlagen = array_merge(
            $anlagen,
            $this->extractToebAttachments($beteiligung)
        );

        $this->logger->info('Extracted attachments from XBeteiligung message', [
            'total_attachments' => count($anlagen),
        ]);

        return $anlagen;
    }

    /**
     * Extracts attachments from public participation (Öffentlichkeitsbeteiligung).
     *
     * @return AnlageValueObject[]
     */
    private function extractPublicParticipationAttachments(BeteiligungKommunalType|BeteiligungPlanfeststellungType $beteiligung): array
    {
        $beteiligungOeffentlichkeit = $beteiligung->getBeteiligungOeffentlichkeit();
        if (null === $beteiligungOeffentlichkeit) {
            return [];
        }

        $oeffentlichkeitsAnlagen = $beteiligungOeffentlichkeit->getAnlagen();
        return $this->processAttachmentArray($oeffentlichkeitsAnlagen);
    }

    /**
     * Extracts attachments from TOEB participation.
     *
     * @return AnlageValueObject[]
     */
    private function extractToebAttachments(BeteiligungKommunalType|BeteiligungPlanfeststellungType $beteiligung): array
    {
        $beteiligungTOEB = $beteiligung->getBeteiligungTOEB();
        if (null === $beteiligungTOEB) {
            return [];
        }

        $toebAnlagen = $beteiligungTOEB->getAnlagen();
        return $this->processAttachmentArray($toebAnlagen);
    }

    /**
     * Processes an array of attachments and converts them to AnlageValueObject instances.
     *
     * @param MetadatenAnlageType[]|null $anlagenArray
     * @return AnlageValueObject[]
     */
    private function processAttachmentArray(?array $anlagenArray): array
    {
        if (null === $anlagenArray || !is_array($anlagenArray)) {
            return [];
        }

        $anlagen = [];
        foreach ($anlagenArray as $anlage) {
            try {
                $anlagen[] = $this->createAnlageValueObject($anlage);
            } catch (Exception $e) {
                $this->logger->warning('Failed to extract attachment', [
                    'error' => $e->getMessage(),
                    'attachment_title' => $anlage?->getBezeichnung(),
                ]);
            }
        }

        return $anlagen;
    }

    /**
     * Creates an AnlageValueObject from a MetadatenAnlageType.
     */
    private function createAnlageValueObject(MetadatenAnlageType $metadatenAnlage): AnlageValueObject
    {
        $anlageArtCode = $metadatenAnlage->getAnlageart()?->getCode();
        $anlageArtName = $metadatenAnlage->getAnlageart()?->getName();
        $mimeType = $metadatenAnlage->getMimeType()?->getCode();

        $anlage = new AnlageValueObject(
            bezeichnung: $metadatenAnlage->getBezeichnung(),
            versionsnummer: $metadatenAnlage->getVersionsnummer(),
            datum: $metadatenAnlage->getDatum(),
            anlageArtCode: $anlageArtCode,
            anlageArtName: $anlageArtName,
            mimeType: $mimeType,
            dokument: $metadatenAnlage->getDokument()
        );

        $anhangOderVerlinkung = $metadatenAnlage->getAnhangOderVerlinkung();
        if (null !== $anhangOderVerlinkung) {
            $this->processAnhangData($anhangOderVerlinkung, $anlage);
            $this->processVerlinkungData($anhangOderVerlinkung, $anlage);
        }

        $this->setBase64Flag($anlage);

        return $anlage;
    }

    /**
     * Sets the Base64 flag if document content is provided.
     */
    private function setBase64Flag(AnlageValueObject $anlage): void
    {
        if ($anlage->hasContent()) {
            $anlage->setIsBase64(true);
        }
    }

    /**
     * Processes attachment (Anhang) data.
     */
    private function processAnhangData(AnhangOderVerlinkungType $anhangOderVerlinkung, AnlageValueObject $anlage): void
    {
        $anhang = $anhangOderVerlinkung->getAnhang();
        if (null !== $anhang) {
            $anlage->setIsAnhang(true);
            $anlage->setDokumentId($anhang->getDokumentid());
            $anlage->setDateiname($anhang->getDateiname());
        }
    }

    /**
     * Processes link (Verlinkung) data.
     */
    private function processVerlinkungData(AnhangOderVerlinkungType $anhangOderVerlinkung, AnlageValueObject $anlage): void
    {
        $uriVerlinkung = $anhangOderVerlinkung->getUriVerlinkung();
        if (null !== $uriVerlinkung && '' !== $uriVerlinkung) {
            $anlage->setIsVerlinkung(true);
            $anlage->setUrl($uriVerlinkung);
        }
    }
}
