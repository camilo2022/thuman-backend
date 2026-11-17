<?php

declare(strict_types=1);

namespace App\Domain\BankAccountType;

use App\Domain\BankAccountType\Values\BankAccountTypeCreatedAt;
use App\Domain\BankAccountType\Values\BankAccountTypeDeletedAt;
use App\Domain\BankAccountType\Values\BankAccountTypeDescription;
use App\Domain\BankAccountType\Values\BankAccountTypeId;
use App\Domain\BankAccountType\Values\BankAccountTypeItemId;
use App\Domain\BankAccountType\Values\BankAccountTypeName;
use App\Domain\BankAccountType\Values\BankAccountTypeSettings;
use App\Domain\BankAccountType\Values\BankAccountTypeUpdatedAt;

final class BankAccountType
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
        ?BankAccountTypeId $id,
        BankAccountTypeItemId $itemId,
        BankAccountTypeName $name,
        BankAccountTypeDescription $description,
        BankAccountTypeSettings $settings,
        ?BankAccountTypeCreatedAt $createdAt = null,
        ?BankAccountTypeUpdatedAt $updatedAt = null,
        ?BankAccountTypeDeletedAt $deletedAt = null
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

    public function id(): ?BankAccountTypeId
    {
        return $this->id;
    }

    public function itemId(): BankAccountTypeItemId
    {
        return $this->itemId;
    }

    public function name(): BankAccountTypeName
    {
        return $this->name;
    }

    public function description(): BankAccountTypeDescription
    {
        return $this->description;
    }

    public function settings(): BankAccountTypeSettings
    {
        return $this->settings;
    }

    public function createdAt(): ?BankAccountTypeCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ?BankAccountTypeUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): ?BankAccountTypeDeletedAt
    {
        return $this->deletedAt;
    }
}
