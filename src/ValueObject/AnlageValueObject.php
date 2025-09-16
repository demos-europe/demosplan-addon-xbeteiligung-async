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
    private ?string $anlageart;
    private ?string $mimeType;
    private ?string $dokumentId;
    private ?string $dateiname;
    private ?string $url;
    private ?string $dokument;
    private bool $isAnhang;
    private bool $isVerlinkung;
    private bool $isBase64;

    public function __construct(
        ?string $bezeichnung,
        ?string $versionsnummer,
        ?DateTime $datum,
        ?string $anlageart,
        ?string $mimeType,
        ?string $dokumentId,
        ?string $dateiname,
        ?string $url,
        ?string $dokument,
        bool $isAnhang,
        bool $isVerlinkung,
        bool $isBase64
    ) {
        $this->bezeichnung = $bezeichnung;
        $this->versionsnummer = $versionsnummer;
        $this->datum = $datum;
        $this->anlageart = $anlageart;
        $this->mimeType = $mimeType;
        $this->dokumentId = $dokumentId;
        $this->dateiname = $dateiname;
        $this->url = $url;
        $this->dokument = $dokument;
        $this->isAnhang = $isAnhang;
        $this->isVerlinkung = $isVerlinkung;
        $this->isBase64 = $isBase64;
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

    public function getDocumentType(): ?string
    {
        return $this->anlageart;
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
        return $this->dokument !== null && $this->dokument !== '';
    }

    public function hasUrl(): bool
    {
        return $this->url !== null && $this->url !== '';
    }
}
