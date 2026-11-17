<?php

declare(strict_types=1);

namespace App\Domain\HealthEntityType;

use App\Domain\HealthEntityType\Values\HealthEntityTypeCreatedAt;
use App\Domain\HealthEntityType\Values\HealthEntityTypeDeletedAt;
use App\Domain\HealthEntityType\Values\HealthEntityTypeDescription;
use App\Domain\HealthEntityType\Values\HealthEntityTypeId;
use App\Domain\HealthEntityType\Values\HealthEntityTypeItemId;
use App\Domain\HealthEntityType\Values\HealthEntityTypeName;
use App\Domain\HealthEntityType\Values\HealthEntityTypeSettings;
use App\Domain\HealthEntityType\Values\HealthEntityTypeUpdatedAt;

final class HealthEntityType
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
        ?HealthEntityTypeId $id,
        HealthEntityTypeItemId $itemId,
        HealthEntityTypeName $name,
        HealthEntityTypeDescription $description,
        HealthEntityTypeSettings $settings,
        ?HealthEntityTypeCreatedAt $createdAt = null,
        ?HealthEntityTypeUpdatedAt $updatedAt = null,
        ?HealthEntityTypeDeletedAt $deletedAt = null
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

    public function id(): ?HealthEntityTypeId
    {
        return $this->id;
    }

    public function itemId(): HealthEntityTypeItemId
    {
        return $this->itemId;
    }

    public function name(): HealthEntityTypeName
    {
        return $this->name;
    }

    public function description(): HealthEntityTypeDescription
    {
        return $this->description;
    }

    public function settings(): HealthEntityTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?HealthEntityTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?HealthEntityTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?HealthEntityTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
