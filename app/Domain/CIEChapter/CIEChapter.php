<?php

declare(strict_types=1);

namespace App\Domain\CIEChapter;

use App\Domain\CIEChapter\Values\CIEChapterCreatedAt;
use App\Domain\CIEChapter\Values\CIEChapterDeletedAt;
use App\Domain\CIEChapter\Values\CIEChapterDescription;
use App\Domain\CIEChapter\Values\CIEChapterId;
use App\Domain\CIEChapter\Values\CIEChapterItemId;
use App\Domain\CIEChapter\Values\CIEChapterName;
use App\Domain\CIEChapter\Values\CIEChapterSettings;
use App\Domain\CIEChapter\Values\CIEChapterUpdatedAt;

final class CIEChapter
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;

    public function __construct(
        ?CIEChapterId $id,
        CIEChapterItemId $itemId,
        CIEChapterName $name,
        CIEChapterDescription $description,
        CIEChapterSettings $settings,
        ?CIEChapterCreatedAt $createdAt = null,
        ?CIEChapterUpdatedAt $updatedAt = null,
        ?CIEChapterDeletedAt $deletedAt = null
    )
    {
        $this->id = $id;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
    }

    public function id(): ?CIEChapterId
    {
        return $this->id;
    }

    public function itemId(): CIEChapterItemId
    {
        return $this->itemId;
    }

    public function name(): CIEChapterName
    {
        return $this->name;
    }

    public function description(): CIEChapterDescription
    {
        return $this->description;
    }

    public function settings(): CIEChapterSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?CIEChapterCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CIEChapterUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CIEChapterDeletedAt
    {
        return $this->deletedAt;
    }
}
