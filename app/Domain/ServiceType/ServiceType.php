<?php

declare(strict_types=1);

namespace App\Domain\ServiceType;

use App\Domain\ServiceType\Values\ServiceTypeCreatedAt;
use App\Domain\ServiceType\Values\ServiceTypeDeletedAt;
use App\Domain\ServiceType\Values\ServiceTypeDescription;
use App\Domain\ServiceType\Values\ServiceTypeId;
use App\Domain\ServiceType\Values\ServiceTypeItemId;
use App\Domain\ServiceType\Values\ServiceTypeName;
use App\Domain\ServiceType\Values\ServiceTypeSettings;
use App\Domain\ServiceType\Values\ServiceTypeUpdatedAt;

final class ServiceType
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
        ?ServiceTypeId $id,
        ServiceTypeItemId $itemId,
        ServiceTypeName $name,
        ServiceTypeDescription $description,
        ServiceTypeSettings $settings,
        ?ServiceTypeCreatedAt $createdAt = null,
        ?ServiceTypeUpdatedAt $updatedAt = null,
        ?ServiceTypeDeletedAt $deletedAt = null
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

    public function id(): ?ServiceTypeId
    {
        return $this->id;
    }

    public function itemId(): ServiceTypeItemId
    {
        return $this->itemId;
    }

    public function name(): ServiceTypeName
    {
        return $this->name;
    }

    public function description(): ServiceTypeDescription
    {
        return $this->description;
    }

    public function settings(): ServiceTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?ServiceTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?ServiceTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?ServiceTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
