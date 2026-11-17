<?php

declare(strict_types=1);

namespace App\Domain\TerminationType;

use App\Domain\TerminationType\Values\TerminationTypeCreatedAt;
use App\Domain\TerminationType\Values\TerminationTypeDeletedAt;
use App\Domain\TerminationType\Values\TerminationTypeDescription;
use App\Domain\TerminationType\Values\TerminationTypeId;
use App\Domain\TerminationType\Values\TerminationTypeItemId;
use App\Domain\TerminationType\Values\TerminationTypeName;
use App\Domain\TerminationType\Values\TerminationTypeRelationships;
use App\Domain\TerminationType\Values\TerminationTypeSettings;
use App\Domain\TerminationType\Values\TerminationTypeUpdatedAt;

final class TerminationType
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
        ?TerminationTypeId $id,
        TerminationTypeItemId $itemId,
        TerminationTypeName $name,
        TerminationTypeDescription $description,
        TerminationTypeSettings $settings,
        ?TerminationTypeCreatedAt $createdAt = null,
        ?TerminationTypeUpdatedAt $updatedAt = null,
        ?TerminationTypeDeletedAt $deletedAt = null,
        ?TerminationTypeRelationships $relationships = null
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

    public function id(): ?TerminationTypeId
    {
        return $this->id;
    }

    public function itemId(): TerminationTypeItemId
    {
        return $this->itemId;
    }

    public function name(): TerminationTypeName
    {
        return $this->name;
    }

    public function description(): TerminationTypeDescription
    {
        return $this->description;
    }

    public function settings(): TerminationTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?TerminationTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?TerminationTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?TerminationTypeDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?TerminationTypeRelationships
    {
        return $this->relationships;
    }
}
