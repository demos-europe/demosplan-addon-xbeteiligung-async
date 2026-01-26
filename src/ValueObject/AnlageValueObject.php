<?php

declare(strict_types=1);

/**
 * This file is part of the package demosplan.
 *
 * (c) 2010-present DEMOS plan GmbH, for more information see the license file.
 *
 * All rights reserved
 */

namespace DemosEurope\DemosplanAddon\XBeteiligung\ValueObject;

use DateTime;

/**
 * Value Object representing an attachment/document (Anlage) from XBeteiligung messages.
 * This extracts relevant data from MetadatenAnlageType for procedure creation.
 */
class AnlageValueObject
{
    private ?string $bezeichnung;
    private ?string $versionsnummer;
    private ?DateTime $datum;
    private ?string $anlageArtCode;
    private ?string $anlageArtName;
    private ?string $mimeType;
    private ?string $dokumentId = null;
    private ?string $dateiname = null;
    private ?string $url = null;
    private ?string $dokument;
    private bool $isAnhang = false;
    private bool $isVerlinkung = false;
    private bool $isBase64 = false;

    public function __construct(
        ?string $bezeichnung,
        ?string $versionsnummer,
        ?DateTime $datum,
        ?string $anlageArtCode,
        ?string $anlageArtName,
        ?string $mimeType,
        ?string $dokument
    ) {
        $this->bezeichnung = $bezeichnung;
        $this->versionsnummer = $versionsnummer;
        $this->datum = $datum;
        $this->anlageArtCode = $anlageArtCode;
        $this->anlageArtName = $anlageArtName;
        $this->mimeType = $mimeType;
        $this->dokument = $dokument;
    }

    public function getFileName(): ?string
    {
        return $this->dateiname;
    }

    public function getTitle(): ?string
    {
        return $this->bezeichnung;
    }

    public function getVersion(): ?string
    {
        return $this->versionsnummer;
    }

    public function getDate(): ?DateTime
    {
        return $this->datum;
    }

    public function getDocumentCategoryCode(): ?string
    {
        return $this->anlageArtCode;
    }

    public function getDocumentCategoryName(): ? string
    {
        return $this->anlageArtName;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function getDocumentId(): ?string
    {
        return $this->dokumentId;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function getBase64Content(): ?string
    {
        return $this->dokument;
    }

    public function isAttachment(): bool
    {
        return $this->isAnhang;
    }

    public function isLink(): bool
    {
        return $this->isVerlinkung;
    }

    public function isBase64Encoded(): bool
    {
        return $this->isBase64;
    }

    public function hasContent(): bool
    {
        return null !== $this->dokument && '' !== $this->dokument;
    }

    public function hasUrl(): bool
    {
        return null !== $this->url && '' !== $this->url;
    }

    public function setDokumentId(?string $dokumentId): void
    {
        $this->dokumentId = $dokumentId;
    }

    public function setDateiname(?string $dateiname): void
    {
        $this->dateiname = $dateiname;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function setIsAnhang(bool $isAnhang): void
    {
        $this->isAnhang = $isAnhang;
    }

    public function setIsVerlinkung(bool $isVerlinkung): void
    {
        $this->isVerlinkung = $isVerlinkung;
    }

    public function setIsBase64(bool $isBase64): void
    {
        $this->isBase64 = $isBase64;
    }
}
