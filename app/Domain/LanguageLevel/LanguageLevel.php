<?php

declare(strict_types=1);

namespace App\Domain\LanguageLevel;

use App\Domain\LanguageLevel\Values\LanguageLevelCreatedAt;
use App\Domain\LanguageLevel\Values\LanguageLevelDeletedAt;
use App\Domain\LanguageLevel\Values\LanguageLevelDescription;
use App\Domain\LanguageLevel\Values\LanguageLevelId;
use App\Domain\LanguageLevel\Values\LanguageLevelItemId;
use App\Domain\LanguageLevel\Values\LanguageLevelName;
use App\Domain\LanguageLevel\Values\LanguageLevelSettings;
use App\Domain\LanguageLevel\Values\LanguageLevelUpdatedAt;

final class LanguageLevel
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
        ?LanguageLevelId $id,
        LanguageLevelItemId $itemId,
        LanguageLevelName $name,
        LanguageLevelDescription $description,
        LanguageLevelSettings $settings,
        ?LanguageLevelCreatedAt $createdAt = null,
        ?LanguageLevelUpdatedAt $updatedAt = null,
        ?LanguageLevelDeletedAt $deletedAt = null
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

    public function id(): ?LanguageLevelId
    {
        return $this->id;
    }

    public function itemId(): LanguageLevelItemId
    {
        return $this->itemId;
    }

    public function name(): LanguageLevelName
    {
        return $this->name;
    }

    public function description(): LanguageLevelDescription
    {
        return $this->description;
    }

    public function settings(): LanguageLevelSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?LanguageLevelCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?LanguageLevelUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?LanguageLevelDeletedAt
    {
        return $this->deletedAt;
    }
}
