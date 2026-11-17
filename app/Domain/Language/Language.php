<?php

declare(strict_types=1);

namespace App\Domain\Language;

use App\Domain\Language\Values\LanguageCreatedAt;
use App\Domain\Language\Values\LanguageDeletedAt;
use App\Domain\Language\Values\LanguageDescription;
use App\Domain\Language\Values\LanguageId;
use App\Domain\Language\Values\LanguageItemId;
use App\Domain\Language\Values\LanguageName;
use App\Domain\Language\Values\LanguageSettings;
use App\Domain\Language\Values\LanguageUpdatedAt;

final class Language
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
        ?LanguageId $id,
        LanguageItemId $itemId,
        LanguageName $name,
        LanguageDescription $description,
        LanguageSettings $settings,
        ?LanguageCreatedAt $createdAt = null,
        ?LanguageUpdatedAt $updatedAt = null,
        ?LanguageDeletedAt $deletedAt = null
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

    public function id(): ?LanguageId
    {
        return $this->id;
    }

    public function itemId(): LanguageItemId
    {
        return $this->itemId;
    }

    public function name(): LanguageName
    {
        return $this->name;
    }

    public function description(): LanguageDescription
    {
        return $this->description;
    }

    public function settings(): LanguageSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?LanguageCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?LanguageUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?LanguageDeletedAt
    {
        return $this->deletedAt;
    }
}
