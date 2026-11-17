<?php

declare(strict_types=1);

namespace App\Domain\ProfileCategory;

use App\Domain\ProfileCategory\Values\ProfileCategoryCreatedAt;
use App\Domain\ProfileCategory\Values\ProfileCategoryDeletedAt;
use App\Domain\ProfileCategory\Values\ProfileCategoryDescription;
use App\Domain\ProfileCategory\Values\ProfileCategoryId;
use App\Domain\ProfileCategory\Values\ProfileCategoryItemId;
use App\Domain\ProfileCategory\Values\ProfileCategoryName;
use App\Domain\ProfileCategory\Values\ProfileCategorySettings;
use App\Domain\ProfileCategory\Values\ProfileCategoryUpdatedAt;

final class ProfileCategory
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
        ?ProfileCategoryId $id,
        ProfileCategoryItemId $itemId,
        ProfileCategoryName $name,
        ProfileCategoryDescription $description,
        ProfileCategorySettings $settings,
        ?ProfileCategoryCreatedAt $createdAt = null,
        ?ProfileCategoryUpdatedAt $updatedAt = null,
        ?ProfileCategoryDeletedAt $deletedAt = null
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

    public function id(): ?ProfileCategoryId
    {
        return $this->id;
    }

    public function itemId(): ProfileCategoryItemId
    {
        return $this->itemId;
    }

    public function name(): ProfileCategoryName
    {
        return $this->name;
    }

    public function description(): ProfileCategoryDescription
    {
        return $this->description;
    }

    public function settings(): ProfileCategorySettings
    {
        return $this->settings;
    }

    public function createdAt(): ?ProfileCategoryCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ProfileCategoryUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ProfileCategoryDeletedAt
    {
        return $this->deletedAt;
    }
}
