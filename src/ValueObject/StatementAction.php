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
use DemosEurope\DemosplanAddon\Contracts\Entities\ProcedureInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\StatementMetaInterface;
use DemosEurope\DemosplanAddon\Contracts\Entities\UserInterface;
use Doctrine\Common\Collections\Collection;

class StatementAction extends ValueObject
{
    protected const AUTHOR_ANONYMOUS = 'anonym';
    protected const AUTHOR_NAMED = 'namentlich';

    protected string $publicId;
    protected string $description;
    protected string $planId;
    protected string $procedureName;
    protected string $procedureId;
    protected ProcedureInterface $procedure;
    protected DateTime $createdAt;
    protected string $plannerDetailViewUrl;
    protected bool $publicUseName = false;
    protected string $priority;
    protected string $organizationName;
    protected string $phase;
    protected string $status;
    protected string $title;
    protected string $feedback;
    protected  string $file;
    protected ?string $votePla;
    protected array $tags;
    protected string $publicStatement = '';
    protected ?UserInterface $user;
    protected StatementMetaInterface $meta;

    public function __construct(?UserInterface $user, ProcedureInterface $procedure, StatementMetaInterface $meta)
    {
        $this->user = $user;
        $this->procedure = $procedure;
        $this->meta = $meta;
    }


    public function getPublicId(): string
    {
        return $this->publicId;
    }

    public function setPublicId(string $publicId): void
    {
        $this->publicId = $publicId;
    }

    // Replace HTML line breaks with spaces before stripping tags
    public function getDescription(): string
    {
        $withSpaces = str_replace([
            '<br>',        // Standard HTML line break tag
            '<br/>',       // XML/XHTML self-closing line break tag
            '<br />',      // Self-closing line break with space (HTML5/XHTML compatible)
            '</p>',        // Closing paragraph tag (ends a paragraph block)
            '</li>',       // Closing list item tag (ends a bullet point or numbered item)
            '&nbsp;'       // Non-breaking space HTML entity (prevents line wrapping)
        ], ' ', $this->description);

        $stripped = strip_tags($withSpaces);
        return trim($stripped);

    }



    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    public function getProcedureId(): string
    {
        return $this->procedureId;
    }

    public function setProcedureId(string $procedureId): void
    {
        $this->procedureId = $procedureId;
    }

    public function getProcedure(): ProcedureInterface
    {
        return $this->procedure;
    }

    public function getPlanId(): string
    {
        return $this->planId;
    }

    public function setPlanId(string $planId): void
    {
        $this->planId = $planId;
    }

    public function getPublicUseName(): string
    {
        return $this->publicUseName ? self::AUTHOR_ANONYMOUS : self::AUTHOR_NAMED;
    }

    public function setPublicUseName(bool $publicUseName): void
    {
        $this->publicUseName = $publicUseName;
    }

    public function setProcedureName(string $procedureName): void
    {
        $this->procedureName = $procedureName;
    }

    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function setPlannerDetailViewUrl(string $plannerDetailViewUrl): void
    {
        $this->plannerDetailViewUrl = $plannerDetailViewUrl;
    }

    public function setOrganizationName(string $organizationName): void
    {
        $this->organizationName = $organizationName;
    }

    public function getPriority(): string
    {
        return $this->priority;
    }

    public function setPriority(string $priority): void
    {
        $this->priority = $priority;
    }

    public function getPhase(): string
    {
        return $this->phase;
    }

    public function setPhase(string $phase): void
    {
        $this->phase = $phase;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getFeedback(): string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): void
    {
        $this->feedback = $feedback;
    }

    public function setFile(string $file): void
    {
        $this->file = $file;
    }

    public function getPublicStatement(): string
    {
        return $this->publicStatement;
    }

    public function setPublicStatement(string $publicStatement): void
    {
        $this->publicStatement = $publicStatement;
    }

    public function getVotePla(): ?string
    {
        return $this->votePla;
    }

    public function setVotePla(?string $votePla): void
    {
        $this->votePla = $votePla;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags($tags=[]): void
    {
        $this->tags = $tags;
    }

    public function getUser(): ?UserInterface
    {
        return $this->user;
    }

    public function getMeta(): StatementMetaInterface
    {
        return $this->meta;
    }



}
