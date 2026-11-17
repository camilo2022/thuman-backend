<?php

declare(strict_types=1);

namespace App\Domain\Bank;

use App\Domain\Bank\Values\BankCountryId;
use App\Domain\Bank\Values\BankCreatedAt;
use App\Domain\Bank\Values\BankDeletedAt;
use App\Domain\Bank\Values\BankDescription;
use App\Domain\Bank\Values\BankId;
use App\Domain\Bank\Values\BankItemId;
use App\Domain\Bank\Values\BankName;
use App\Domain\Bank\Values\BankRelationships;
use App\Domain\Bank\Values\BankSettings;
use App\Domain\Bank\Values\BankUpdatedAt;

final class Bank
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
        ?BankId $id,
        BankItemId $itemId,
        BankName $name,
        BankDescription $description,
        BankSettings $settings,
        ?BankCountryId $countryId = null,
        ?BankCreatedAt $createdAt = null,
        ?BankUpdatedAt $updatedAt = null,
        ?BankDeletedAt $deletedAt = null,
        ?BankRelationships $relationships = null
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

    public function id(): ?BankId
    {
        return $this->id;
    }

    public function itemId(): BankItemId
    {
        return $this->itemId;
    }

    public function name(): BankName
    {
        return $this->name;
    }

    public function description(): BankDescription
    {
        return $this->description;
    }

    public function settings(): BankSettings
    {
        return $this->settings;
    }

    public function countryId(): ?BankCountryId
    {
        return $this->countryId;
    }

    public function createdAt(): ?BankCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?BankUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?BankDeletedAt
    {
        return $this->deletedAt;
    }

    public function relationships(): ?BankRelationships
    {
        return $this->relationships;
    }
}
