<?php

declare(strict_types=1);

namespace App\Domain\RiskManager;

use App\Domain\RiskManager\Values\RiskManagerCountryId;
use App\Domain\RiskManager\Values\RiskManagerCreatedAt;
use App\Domain\RiskManager\Values\RiskManagerDeletedAt;
use App\Domain\RiskManager\Values\RiskManagerDescription;
use App\Domain\RiskManager\Values\RiskManagerId;
use App\Domain\RiskManager\Values\RiskManagerItemId;
use App\Domain\RiskManager\Values\RiskManagerName;
use App\Domain\RiskManager\Values\RiskManagerRelationships;
use App\Domain\RiskManager\Values\RiskManagerSettings;
use App\Domain\RiskManager\Values\RiskManagerUpdatedAt;

final class RiskManager
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
        ?RiskManagerId $id,
        RiskManagerItemId $itemId,
        RiskManagerName $name,
        RiskManagerDescription $description,
        RiskManagerSettings $settings,
        ?RiskManagerCountryId $countryId = null,
        ?RiskManagerCreatedAt $createdAt = null,
        ?RiskManagerUpdatedAt $updatedAt = null,
        ?RiskManagerDeletedAt $deletedAt = null,
        ?RiskManagerRelationships $relationships = null
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

    public function id(): ?RiskManagerId
    {
        return $this->id;
    }

    public function itemId(): RiskManagerItemId
    {
        return $this->itemId;
    }

    public function name(): RiskManagerName
    {
        return $this->name;
    }

    public function description(): RiskManagerDescription
    {
        return $this->description;
    }

    public function settings(): RiskManagerSettings
    {
        return $this->settings;
    }

    public function countryId(): ?RiskManagerCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?RiskManagerCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?RiskManagerUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?RiskManagerDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?RiskManagerRelationships
    {
        return $this->relationships;
    }
}
