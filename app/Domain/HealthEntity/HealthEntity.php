<?php

declare(strict_types=1);

namespace App\Domain\HealthEntity;

use App\Domain\HealthEntity\Values\HealthEntityCountryId;
use App\Domain\HealthEntity\Values\HealthEntityCreatedAt;
use App\Domain\HealthEntity\Values\HealthEntityDeletedAt;
use App\Domain\HealthEntity\Values\HealthEntityDescription;
use App\Domain\HealthEntity\Values\HealthEntityHealthEntityTypeId;
use App\Domain\HealthEntity\Values\HealthEntityId;
use App\Domain\HealthEntity\Values\HealthEntityItemId;
use App\Domain\HealthEntity\Values\HealthEntityName;
use App\Domain\HealthEntity\Values\HealthEntityRelationships;
use App\Domain\HealthEntity\Values\HealthEntitySettings;
use App\Domain\HealthEntity\Values\HealthEntityUpdatedAt;

final class HealthEntity
{
    private $id;
    private $healthEntityTypeId;
    private $itemId;
    private $name;
    private $description;
    private $settings;
    private $countryId;
    private $createdAt;
    private $updatedAt;
    private $deletedAt;
    private $relationships;

    public function __construct(
        ?HealthEntityId $id,
        HealthEntityHealthEntityTypeId $healthEntityTypeId,
        HealthEntityItemId $itemId,
        HealthEntityName $name,
        HealthEntityDescription $description,
        HealthEntitySettings $settings,
        ?HealthEntityCountryId $countryId = null,
        ?HealthEntityCreatedAt $createdAt = null,
        ?HealthEntityUpdatedAt $updatedAt = null,
        ?HealthEntityDeletedAt $deletedAt = null,
        ?HealthEntityRelationships $relationships = null
    )
    {
        $this->id = $id;
        $this->healthEntityTypeId = $healthEntityTypeId;
        $this->itemId = $itemId;
        $this->name = $name;
        $this->description = $description;
        $this->settings = $settings;
        $this->countryId = $countryId;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
        $this->deletedAt = $deletedAt;
        $this->relationships = $relationships;
    }

    public function id(): ?HealthEntityId
    {
        return $this->id;
    }

    public function healthEntityTypeId(): HealthEntityHealthEntityTypeId
    {
        return $this->healthEntityTypeId;
    }

    public function itemId(): HealthEntityItemId
    {
        return $this->itemId;
    }

    public function name(): HealthEntityName
    {
        return $this->name;
    }

    public function description(): HealthEntityDescription
    {
        return $this->description;
    }

    public function settings(): HealthEntitySettings
    {
        return $this->settings;
    }

    public function countryId(): ?HealthEntityCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?HealthEntityCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?HealthEntityUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?HealthEntityDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?HealthEntityRelationships
    {
        return $this->relationships;
    }
}
