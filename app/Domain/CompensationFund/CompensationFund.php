<?php

declare(strict_types=1);

namespace App\Domain\CompensationFund;

use App\Domain\CompensationFund\Values\CompensationFundCountryId;
use App\Domain\CompensationFund\Values\CompensationFundCreatedAt;
use App\Domain\CompensationFund\Values\CompensationFundDeletedAt;
use App\Domain\CompensationFund\Values\CompensationFundDescription;
use App\Domain\CompensationFund\Values\CompensationFundId;
use App\Domain\CompensationFund\Values\CompensationFundItemId;
use App\Domain\CompensationFund\Values\CompensationFundName;
use App\Domain\CompensationFund\Values\CompensationFundRelationships;
use App\Domain\CompensationFund\Values\CompensationFundSettings;
use App\Domain\CompensationFund\Values\CompensationFundUpdatedAt;

final class CompensationFund
{
    private $id;
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
        ?CompensationFundId $id,
        CompensationFundItemId $itemId,
        CompensationFundName $name,
        CompensationFundDescription $description,
        CompensationFundSettings $settings,
        ?CompensationFundCountryId $countryId = null,
        ?CompensationFundCreatedAt $createdAt = null,
        ?CompensationFundUpdatedAt $updatedAt = null,
        ?CompensationFundDeletedAt $deletedAt = null,
        ?CompensationFundRelationships $relationships = null
    )
    {
        $this->id = $id;
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

    public function id(): ?CompensationFundId
    {
        return $this->id;
    }

    public function itemId(): CompensationFundItemId
    {
        return $this->itemId;
    }

    public function name(): CompensationFundName
    {
        return $this->name;
    }

    public function description(): CompensationFundDescription
    {
        return $this->description;
    }

    public function settings(): CompensationFundSettings
    {
        return $this->settings;
    }

    public function countryId(): ?CompensationFundCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?CompensationFundCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?CompensationFundUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?CompensationFundDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?CompensationFundRelationships
    {
        return $this->relationships;
    }
}
