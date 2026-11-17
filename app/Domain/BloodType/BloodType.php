<?php

declare(strict_types=1);

namespace App\Domain\BloodType;

use App\Domain\BloodType\Values\BloodTypeCreatedAt;
use App\Domain\BloodType\Values\BloodTypeDeletedAt;
use App\Domain\BloodType\Values\BloodTypeDescription;
use App\Domain\BloodType\Values\BloodTypeId;
use App\Domain\BloodType\Values\BloodTypeItemId;
use App\Domain\BloodType\Values\BloodTypeName;
use App\Domain\BloodType\Values\BloodTypeSettings;
use App\Domain\BloodType\Values\BloodTypeUpdatedAt;

final class BloodType
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
        ?BloodTypeId $id,
        BloodTypeItemId $itemId,
        BloodTypeName $name,
        BloodTypeDescription $description,
        BloodTypeSettings $settings,
        ?BloodTypeCreatedAt $createdAt = null,
        ?BloodTypeUpdatedAt $updatedAt = null,
        ?BloodTypeDeletedAt $deletedAt = null
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

    public function id(): ?BloodTypeId
    {
        return $this->id;
    }

    public function itemId(): BloodTypeItemId
    {
        return $this->itemId;
    }

    public function name(): BloodTypeName
    {
        return $this->name;
    }

    public function description(): BloodTypeDescription
    {
        return $this->description;
    }

    public function settings(): BloodTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?BloodTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?BloodTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?BloodTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
