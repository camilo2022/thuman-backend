<?php

declare(strict_types=1);

namespace App\Domain\VacationType;

use App\Domain\VacationType\Values\VacationTypeCreatedAt;
use App\Domain\VacationType\Values\VacationTypeDeletedAt;
use App\Domain\VacationType\Values\VacationTypeDescription;
use App\Domain\VacationType\Values\VacationTypeId;
use App\Domain\VacationType\Values\VacationTypeItemId;
use App\Domain\VacationType\Values\VacationTypeName;
use App\Domain\VacationType\Values\VacationTypeRelationships;
use App\Domain\VacationType\Values\VacationTypeSettings;
use App\Domain\VacationType\Values\VacationTypeUpdatedAt;

final class VacationType
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
        ?VacationTypeId $id,
        VacationTypeItemId $itemId,
        VacationTypeName $name,
        VacationTypeDescription $description,
        VacationTypeSettings $settings,
        ?VacationTypeCreatedAt $createdAt = null,
        ?VacationTypeUpdatedAt $updatedAt = null,
        ?VacationTypeDeletedAt $deletedAt = null,
        ?VacationTypeRelationships $relationships = null
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

    public function id(): ?VacationTypeId
    {
        return $this->id;
    }

    public function itemId(): VacationTypeItemId
    {
        return $this->itemId;
    }

    public function name(): VacationTypeName
    {
        return $this->name;
    }

    public function description(): VacationTypeDescription
    {
        return $this->description;
    }

    public function settings(): VacationTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?VacationTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?VacationTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?VacationTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?VacationTypeRelationships
    {
        return $this->relationships;
    }
}
