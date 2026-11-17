<?php

declare(strict_types=1);

namespace App\Domain\PolicyType;

use App\Domain\PolicyType\Values\PolicyTypeCreatedAt;
use App\Domain\PolicyType\Values\PolicyTypeDeletedAt;
use App\Domain\PolicyType\Values\PolicyTypeDescription;
use App\Domain\PolicyType\Values\PolicyTypeId;
use App\Domain\PolicyType\Values\PolicyTypeItemId;
use App\Domain\PolicyType\Values\PolicyTypeName;
use App\Domain\PolicyType\Values\PolicyTypeSettings;
use App\Domain\PolicyType\Values\PolicyTypeUpdatedAt;

final class PolicyType
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
        ?PolicyTypeId $id,
        PolicyTypeItemId $itemId,
        PolicyTypeName $name,
        PolicyTypeDescription $description,
        PolicyTypeSettings $settings,
        ?PolicyTypeCreatedAt $createdAt = null,
        ?PolicyTypeUpdatedAt $updatedAt = null,
        ?PolicyTypeDeletedAt $deletedAt = null
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

    public function id(): ?PolicyTypeId
    {
        return $this->id;
    }

    public function itemId(): PolicyTypeItemId
    {
        return $this->itemId;
    }

    public function name(): PolicyTypeName
    {
        return $this->name;
    }

    public function description(): PolicyTypeDescription
    {
        return $this->description;
    }

    public function settings(): PolicyTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?PolicyTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PolicyTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?PolicyTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
