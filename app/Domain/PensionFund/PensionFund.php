<?php

declare(strict_types=1);

namespace App\Domain\PensionFund;

use App\Domain\PensionFund\Values\PensionFundCountryId;
use App\Domain\PensionFund\Values\PensionFundCreatedAt;
use App\Domain\PensionFund\Values\PensionFundDeletedAt;
use App\Domain\PensionFund\Values\PensionFundDescription;
use App\Domain\PensionFund\Values\PensionFundId;
use App\Domain\PensionFund\Values\PensionFundItemId;
use App\Domain\PensionFund\Values\PensionFundName;
use App\Domain\PensionFund\Values\PensionFundRelationships;
use App\Domain\PensionFund\Values\PensionFundSettings;
use App\Domain\PensionFund\Values\PensionFundUpdatedAt;

final class PensionFund
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
        ?PensionFundId $id,
        PensionFundItemId $itemId,
        PensionFundName $name,
        PensionFundDescription $description,
        PensionFundSettings $settings,
        ?PensionFundCountryId $countryId = null,
        ?PensionFundCreatedAt $createdAt = null,
        ?PensionFundUpdatedAt $updatedAt = null,
        ?PensionFundDeletedAt $deletedAt = null,
        ?PensionFundRelationships $relationships = null
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

    public function id(): ?PensionFundId
    {
        return $this->id;
    }

    public function itemId(): PensionFundItemId
    {
        return $this->itemId;
    }

    public function name(): PensionFundName
    {
        return $this->name;
    }

    public function description(): PensionFundDescription
    {
        return $this->description;
    }

    public function settings(): PensionFundSettings
    {
        return $this->settings;
    }

    public function countryId(): ?PensionFundCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?PensionFundCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?PensionFundUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?PensionFundDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?PensionFundRelationships
    {
        return $this->relationships;
    }
}
