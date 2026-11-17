<?php

declare(strict_types=1);

namespace App\Domain\LicenseType;

use App\Domain\LicenseType\Values\LicenseTypeCreatedAt;
use App\Domain\LicenseType\Values\LicenseTypeDeletedAt;
use App\Domain\LicenseType\Values\LicenseTypeDescription;
use App\Domain\LicenseType\Values\LicenseTypeId;
use App\Domain\LicenseType\Values\LicenseTypeItemId;
use App\Domain\LicenseType\Values\LicenseTypeName;
use App\Domain\LicenseType\Values\LicenseTypeRelationships;
use App\Domain\LicenseType\Values\LicenseTypeSettings;
use App\Domain\LicenseType\Values\LicenseTypeUpdatedAt;

final class LicenseType
{
    private $id;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?LicenseTypeId $id,
        LicenseTypeItemId $itemId,
        LicenseTypeName $name,
        LicenseTypeDescription $description,
        LicenseTypeSettings $settings,
        ?LicenseTypeCreatedAt $createdAt = null,
        ?LicenseTypeUpdatedAt $updatedAt = null,
        ?LicenseTypeDeletedAt $deletedAt = null,
        ?LicenseTypeRelationships $relationships = null
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
        $this->relationships = $relationships;
    }

    public function id(): ?LicenseTypeId
    {
        return $this->id;
    }

    public function itemId(): LicenseTypeItemId
    {
        return $this->itemId;
    }

    public function name(): LicenseTypeName
    {
        return $this->name;
    }

    public function description(): LicenseTypeDescription
    {
        return $this->description;
    }

    public function settings(): LicenseTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?LicenseTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?LicenseTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?LicenseTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?LicenseTypeRelationships
    {
        return $this->relationships;
    }
}
